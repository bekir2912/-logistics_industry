<?php

namespace app\modules\admin\controllers\industry;

use app\models\Category;
use app\models\industry\AllDeffect;
use app\models\industry\DepartmentChecking;
use app\models\industry\DepartmentElectro;
use app\models\industry\DepartmentGp;
use app\models\industry\DepartmentMechanical;
use app\models\industry\DepartmentPaiting;
use app\models\industry\DepartmentPlastic;
use app\models\industry\DepartmentPrinting;
use app\models\industry\DepartmentSizing;
use app\models\industry\DepartmentStamping;
use app\models\industry\DepartmentTest;
use app\models\industry\handbook\BDeffect;
use app\models\industry\handbook\BDepartment;
use app\models\industry\handbook\BLine;
use app\models\industry\handbook\Detail;
use app\models\industry\handbook\ProductModel;
use app\models\user\User;
use DateTime;
use Yii;
use app\models\industry\BufferZone;
use app\models\industry\BufferZoneSearch;
use yii\base\BaseObject;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Request;

class BufferZoneController extends Controller
{

    public $user;

    public function beforeAction($action) {
        $this->enableCsrfValidation = false;
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/admin']);
        }
        $this->user = User::find()->with('moderatorAccess', 'moderatorAccess.moderator')->where(['id'=>Yii::$app->user->identity->id])->one();

        if (($this->user->role == User::ROLE_MODERATOR)) {
            $accesses = array();

            if ($this->user && $this->user->moderatorAccess) {
                foreach ($this->user->moderatorAccess as $v) {
                    if ($v && $v->moderator) {
                        $accesses[] = $v->moderator->url;
                    }
                }
            }

            if (!in_array('buffer-zone', $accesses)) {
                throw new HttpException(200, 'В доступе отказано');
            }
        }

        return parent::beforeAction($action);
    }


    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }




    public function actionIndex()
    {
        $searchModel = new BufferZoneSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);



	// Для  фильтрации моделей
        $models =  \app\models\industry\BufferZone::find()->select('model_id')->orderBy('model_id')->groupBy('model_id')->all();
        $models_filter = [];

        $department_from =  BufferZone::find()->select('from_department_id')->orderBy('from_department_id')->groupBy('from_department_id')->all();
        $department_to =  BufferZone::find()->select('to_department_id')->orderBy('to_department_id')->groupBy('to_department_id')->all();
        $from_filter = [];
        $to_filter = [];


        if(!empty($department_from)) {
            foreach ($department_from as $dep) {
                $from_filter[$dep->fromDepartment->id] = $dep->fromDepartment->name_ru;
            }
        }
        if(!empty($department_to)) {
            foreach ($department_to as $dep) {
                $to_filter[$dep->toDepartment->id] = $dep->toDepartment->name_ru;
            }
        }



        if(!empty($models)) {
            foreach ($models as $model) {
                $models_filter[$model->model->id] = $model->model->name_ru;
            }
        }
        $dataProvider->setSort([
            'defaultOrder' => [
                'id' => 'desc'
            ]
        ]);




        $count_department = (int)BDepartment::find()->count();
        $table_name = BDepartment::find()->orderBy(['id' => SORT_ASC])->all();

        array_unshift($table_name, NULL);
        unset($table_name[0]);

        $not_fix = [];
        $fix = [];
        $success = [];
        $connection = Yii::$app->getDb();

        //Не иссправная деф продукция
        for ($i = 1; $i <= 12; $i++){
            if($i == 7)continue;
            $command1 = $connection->createCommand("SELECT  SUM(count_deffect) AS `amount`, department_id 
                                                    FROM `b_deffects` WHERE `is_save`=0 
                                                    and `department_id` = $i and  count_deffect >  0
                                                    GROUP BY `department_id`");
            $not_fix[$i] = $command1->queryAll();
        }

        //Иссправная деф продукция
        for ($i = 1; $i <= 12; $i++){
            if($i == 7)continue;

            $command2 = $connection->createCommand("SELECT SUM(count_deffect)  AS `amount`, department_id 
                                                    FROM `b_deffects` WHERE `is_save`=1
                                                    and `department_id` = $i and  count_deffect >  0
                                                    GROUP BY `department_id`");
        $fix[$i] = $command2->queryAll();
        }
        //Без деф продукции

        for ($i = 1; $i <= 12; $i++){
            if($i == 7)continue;

        $name = (string)$table_name[$i]->table_name;

            $command3 = $connection->createCommand("SELECT SUM(amount) AS `amount`, department_id 
                                                    FROM `$name`
                                                    WHERE `status`= 1
                                                    AND `department_id` = $i
                                                    GROUP BY `department_id`");
        $success[$i] = $command3->queryAll();

        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'models_filter' => $models_filter,
            'dataProvider' => $dataProvider,
            'fix' => $fix,
            'not_fix' => $not_fix,
            'success' => $success,
            'count_department' => $count_department,
            'from_filter' => $from_filter,
            'to_filter' => $to_filter,
        ]);
    }

    public function actionMulti($id=null)
    {
        $arrays = Yii::$app->request->post()['selection'];
        BufferZone::deleteAll(['id' => $arrays]);
        Yii::$app->session->setFlash('buffer_removed', 'Поддоны успешно удалены');
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionView($id)
    {
        $model = BufferZone::findOne($id);
        if($model->time_expire){

            $now = date('d.m.Y H:i');
            $date_format_expire = date('d.m.Y H:i', $model->time_expire);

            $date_now = new DateTime($now);
            $time_expire = new DateTime($date_format_expire);

            $interval= $time_expire->diff($date_now);
        }


        return $this->render('view', [
            'model' => $model,
            'time_expire'=>$time_expire,
            'interval'=>$interval
        ]);
    }

    public function actionCreate($id = null) {
        $model = new BufferZone();
        $department_id = yii::$app->request->get('department_id');
        $dep_name = yii::$app->request->get('dep_name');



        $department  = DepartmentStamping::find()->where(['id'=>$department_id])->one();
        if ($id) {
            $model = BufferZone::find()->where(['id'=>$id])->one();
        }


        $department ='';

        if($dep_name == 1 && $department_id){
            $department  = DepartmentStamping::find()->where(['id'=>$department_id])->one();
        } else if ($dep_name == 2 && $department_id){
            $department  = DepartmentPaiting::find()->where(['id'=>$department_id])->one();
        } else if ($dep_name == 3 && $department_id){
            $department  = DepartmentMechanical::find()->where(['id'=>$department_id])->one();
        } else if ($dep_name == 4 && $department_id){
            $department  = DepartmentTest::find()->where(['id'=>$department_id])->one();
        } else if ($dep_name == 5 && $department_id){
            $department  = DepartmentSizing::find()->where(['id'=>$department_id])->one();
        } else if ($dep_name == 6 && $department_id){
            $department  = DepartmentElectro::find()->where(['id'=>$department_id])->one();
        } else if ($dep_name == 7 && $department_id){
            $department  = DepartmentGp::find()->where(['id'=>$department_id])->one();
        } else if ($dep_name == 8 && $department_id){
            $department  = DepartmentPlastic::find()->where(['id'=>$department_id])->one();
        }else if ($dep_name == 12 && $department_id){
            $department  = DepartmentChecking::find()->where(['id'=>$department_id])->one();
        }else if ($dep_name == 10 && $department_id){
            $department  = DepartmentPrinting::find()->where(['id'=>$department_id])->one();
        }
        if( $department->department_id == 3){
            Yii::$app->session->setFlash('buffer_alert_time_expire', 'Данный поддон после сохранения будет находится в буфферной зоне 6 часов');
        }elseif ($model->from_department_id == 3){
            $now = date('d.m.Y H:i');
            $date_format_expire = date('d.m.Y H:i', $model->time_expire);

            $date_now = new DateTime($now);
            $time_expire = new DateTime($date_format_expire);

            $interval= $time_expire->diff($date_now);
            if($model->time_expire >= time() ){
                Yii::$app->session->setFlash('buffer_alert_time', "Данный поддон будет находится в буфферной зоне " .$interval->h. " часов " .$interval->i . " минут" );
            }
            elseif ($model->time_expire < time()){
                Yii::$app->session->setFlash('buffer_alert_time', "Данный поддон будет находится в буфферной зоне 0 часов 0 минут" );
            }
        }



        if ($model->load(Yii::$app->request->post()) && $model->save()) {



            if($department){
                $department->status = 1;
                $department->model_id = $model->model_id;
                $department->save();
            }

            if($department->department_id == 3) {
                $time_expire_to_unix = time() + (180);

                if ($model->time_expire && $time_expire_to_unix < $model->created_at) {
                    $model->removeObject();
                    Yii::$app->session->setFlash('error_save', 'Дата приготовления не может быть меньше даты добавления');
                    return $this->redirect(Yii::$app->request->referrer);
                } else {
                    //                $model->time_expire = $model->created_at + (4* 60 * 60);
                    $model->time_expire = $model->created_at + (180);
                    $model->save();

                    Yii::$app->session->setFlash('buffer_saved', 'Продукт успешно сохранен');
                    return $this->redirect(['/admin/industry/buffer-zone/view',
                        'id' => $model->id,
                    ]);
                }
            }

            Yii::$app->session->setFlash('buffer_saved', 'Продукт успешно сохранен');
            return $this->redirect(['/admin/industry/buffer-zone/view',
                'id' => $model->id,
            ]);


        }

        if($department_id || $model){

            if ($department->department_id == 5){
                $models = ArrayHelper::map(ProductModel::find()->where(['status'=>1])->andWhere(['department_id' => 5])->all(), 'id', 'name_ru');
            }else{
                $models = ArrayHelper::map(ProductModel::find()->where(['status'=>1])->all(), 'id', 'name_ru');
            }
            $departments = ArrayHelper::map(BDepartment::find()->where(['status'=>1])->all(), 'id', 'name_ru');
//        $details = ArrayHelper::map(Detail::find()->where(['status'=>1])->all(), 'id', 'name_ru');
//        $lines = ArrayHelper::map(BLine::find()->where(['status'=>1])->all(), 'id', 'name_ru');
//        $units = ArrayHelper::map(Category::find()->where(['type'=>'unit'])->all(), 'id', 'name_ru');

        return $this->render('create', [
//            'departments' => $departments,
//            'units' => $units,
            'departments' => $departments,
            'department' => $department,
            'models' => $models ,
            'model' => $model,
        ]);
        }
    }
    public function actionRemove($id) {
        $model = BufferZone::find()->where(['id'=>$id])->one();

        $department_id = yii::$app->request->get('department_id');
        $dep_name = yii::$app->request->get('dep_name');

        $department ='';

        if($dep_name == 1 && $department_id){
            $department  = DepartmentStamping::find()->where(['id'=>$department_id])->one();
        } else if ($dep_name == 2 && $department_id){
            $department  = DepartmentPaiting::find()->where(['id'=>$department_id])->one();
        } else if ($dep_name == 3 && $department_id){
            $department  = DepartmentMechanical::find()->where(['id'=>$department_id])->one();
        } else if ($dep_name == 4 && $department_id){
            $department  = DepartmentTest::find()->where(['id'=>$department_id])->one();
        } else if ($dep_name == 5 && $department_id){
            $department  = DepartmentSizing::find()->where(['id'=>$department_id])->one();
        } else if ($dep_name == 6 && $department_id){
            $department  = DepartmentElectro::find()->where(['id'=>$department_id])->one();
        } else if ($dep_name == 7 && $department_id){
            $department  = DepartmentGp::find()->where(['id'=>$department_id])->one();
        }else if ($dep_name == 8 && $department_id){
            $department  = DepartmentPlastic::find()->where(['id'=>$department_id])->one();
        }else if ($dep_name == 12 && $department_id){
            $department  = DepartmentChecking::find()->where(['id'=>$department_id])->one();
        }else if ($dep_name == 10 && $department_id){
            $department  = DepartmentPrinting::find()->where(['id'=>$department_id])->one();
        }


        $department->status = 0;
        $department->save();

        if (Yii::$app->user->identity->role != User::ROLE_INDUSTRY_USER  && $model) {
            $model->removeObject();
            Yii::$app->session->setFlash('buffer_removed', 'Операция успешно удалена');
        }

        return $this->redirect(['/admin/industry/buffer-zone']);
    }


    protected function findModel($id)
    {
        if (($model = BufferZone::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'Запрос не вернул результата'));
    }
}
