<?php

namespace app\modules\admin\controllers\industry;

use app\models\industry\BufferZone;
use app\models\industry\DepartmentChecking;
use app\models\industry\DepartmentGp;
use app\models\industry\DepartmentMechanical;
use app\models\industry\DepartmentPaiting;
use app\models\industry\DepartmentPrinting;
use app\models\industry\DepartmentSizing;
use app\models\industry\DepartmentStamping;
use app\models\industry\handbook\BLine;
use app\models\industry\handbook\ProductModel;
use app\models\user\User;
use DateTime;
use Yii;
use app\models\industry\DepartmentElectro;
use app\models\industry\DepartmentElectroSearch;
use yii\base\BaseObject;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class DepartmentElectroController extends Controller
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

            if (!in_array('department-electro', $accesses)) {
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

    public function actionCheck($id) {
        $model = DepartmentElectro::findOne($id);

        if ($model) {
            $model->is_ckeck = 1;
            if ($model->save(false)) {
                Yii::$app->session->setFlash('ckeck_status', 'Статус проверки успешно изменен');
            }
        }

        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionMulti($id=null)
    {
        $arrays = Yii::$app->request->post()['selection'];
        DepartmentElectro::deleteAll(['id' => $arrays]);
        Yii::$app->session->setFlash('electro_removed', 'Поддоны успешно удалены');
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionIndex()
    {
        $searchModel = new DepartmentElectroSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $departments_filter = [];

        $departments =  DepartmentElectro::find()->select('previous_department_id')->orderBy('previous_department_id')->groupBy('previous_department_id')->all();

        if(!empty($departments)) {
            foreach ($departments as $dep) {
                $departments_filter[$dep->previous->id] = $dep->previous->name_ru;
            }
        }

        // Для  фильтрации моделей
        $models =  \app\models\industry\DepartmentElectro::find()->select('model_id')->orderBy('model_id')->groupBy('model_id')->all();
        $models_filter = [];

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

        $modelTable = ProductModel::tableName();
        $gpTable = DepartmentElectro::tableName();
        $grouping = DepartmentElectro::find()
            ->select(['SUM(amount) as amount', 'model_id', $modelTable .'.name_ru'])
            ->innerJoin($modelTable, "$modelTable.id = $gpTable.model_id")
            ->where(['previous_department_id' => 6])
            ->groupBy('model_id')
            ->all();
        $array_data = [];
        $electro = new DepartmentElectro();
        foreach ($grouping as $key=>$model){
            $array_data[$key][] = $electro->getModelName($model->model_id) ;
            $array_data[$key][] = (int)$model['amount'];
        }

        $result = array_merge([['Week', 'Кол-во']], $array_data);



        return $this->render('index', [
            'searchModel' => $searchModel,
            'models_filter' => $models_filter,
            'result' => $result,
            'departments_filter' => $departments_filter,
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate($id = null) {
        $model = new DepartmentElectro();

        $department_id = yii::$app->request->get('department_id'); //id операции
        $dep_name = yii::$app->request->get('dep_name'); // id отдела откуда пришел

        $from_department = yii::$app->request->get('from_department');
        $buffer_id = yii::$app->request->get('buffer_id');
//        debug($from_department);

        $department = '';
        if($from_department == 10 || $dep_name == 10){
            $department = DepartmentPrinting::findOne($department_id);

        }elseif ($from_department == 5){
            $department = DepartmentSizing::findOne($department_id);
        }

//        $department = DepartmentSizing::findOne($department_id);
        $buffer = BufferZone::findOne($buffer_id);

        if ($id) {
            $model = DepartmentElectro::find()->where(['id'=>$id])->one();
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            if($department){
                $department->status = 1;
                $department->save();
            }
            if($buffer){
                $buffer->status = 1;
                $buffer->save();
            }
            Yii::$app->session->setFlash('electro_saved', 'Операция успешно сохранена');
            return $this->redirect(['/admin/industry/department-electro/view', 'id'=>$model->id]);
        }

        $models = "";
        if($from_department == 10 || $dep_name == 10){
            $models = ArrayHelper::map(ProductModel::find()->where(['status'=>1])->andWhere(['department_id' =>10])->all(), 'id', 'name_ru');
        }else{
            $models = ArrayHelper::map(ProductModel::find()->where(['status'=>1])->andWhere(['department_id' =>6])->all(), 'id', 'name_ru');
        }


        $lines = ArrayHelper::map(BLine::find()->where(['status'=>1])->andWhere(['department_id' => 6])->all(), 'id', 'name_ru');
        return $this->render('create', [
            'department' => $department,
            'lines' => $lines,
            'model' => $model,
            'models' => $models,
        ]);
    }


    public function actionReport(){

        $department_id = 6;
        $department_name = 'b_department_electro';


        $now_date = strftime("%Y-%m-%d", time());
        $tomorrow_date = date('Y-m-d',strtotime($now_date . "+1 days"));
        $tomorrow_date = $tomorrow_date .' 08:00:00';
        $now_date = $now_date .' 08:00:00';
        $connection = Yii::$app->getDb();


        //Общее число готовой(факт) выпущенной продукции за сегодня день
        $command2 = $connection->createCommand("SELECT s.dates, sum(s.amount) as `amount`
                                            FROM $department_name s
                                            Where s.status = 1 AND s.previous_department_id = 6  AND  s.department_id = 6 AND (s.dates IS NOT NULL) AND  (s.dates BETWEEN '$now_date' AND  '$tomorrow_date')
                                            GROUP BY s.dates");
        $total_now = $command2->queryAll();


        $d = new DateTime('first day of this month');
        $month = $d->format('Y-m-d');
        $month = $month .' 08:00:00';

        //получение последнего дня месяца
        $d2 = new DateTime('last day of this month');
        $last = $d2->format('Y-m-d');
        $tomorrow = date('Y-m-d',strtotime($last . "+1 days"));
        $last = $tomorrow .' 08:00:00';

        //Общее число запланированной(план) продукции на сегодняшний день
        $command3 = $connection->createCommand("SELECT MONTH(p.date), sum(p.value) as `amount`
                                            FROM b_plans_dates p
                                            Where p.status = 1 AND department_id = $department_id AND (p.date IS NOT NULL) AND p.date BETWEEN '$month' AND  '$tomorrow_date'
                                            GROUP BY MONTH(p.date)");
        $total_plan_between = $command3->queryAll();
        //На сегодня выпущенно
        $command4 = $connection->createCommand("SELECT MONTH(p.dates), sum(p.amount) as `amount`
                                            FROM $department_name p
                                            Where p.status = 1 AND p.previous_department_id = 6  AND p.department_id = 6 AND (p.dates IS NOT NULL) AND p.dates BETWEEN '$month' AND  '$tomorrow_date'
                                            GROUP BY MONTH(p.dates)");
        $total_ready_between = $command4->queryAll();
        //Общее число бракованной продукции на сегодняшний день
        $command5 = $connection->createCommand("SELECT MONTH(p.dates), sum(p.count_deffect) as `amount`
                                            FROM b_deffects p
                                            Where p.status = 1 AND department_id = $department_id AND (p.dates IS NOT NULL) AND p.dates BETWEEN '$month' AND  '$tomorrow_date'
                                            GROUP BY MONTH(p.dates)");
        $defect_between = $command5->queryAll();
        //Общее число плана за весь месяц
        $command6 = $connection->createCommand("SELECT MONTH(p.date), sum(p.value) as `amount`
                                            FROM b_plans_dates p
                                            Where p.status = 1 AND department_id = $department_id AND (p.date IS NOT NULL) AND p.date BETWEEN '$month' AND  '$last'
                                            GROUP BY MONTH(p.date)");
        $total_plans = $command6->queryAll();


        $start = yii::$app->request->get('start');
        $end = yii::$app->request->get('end');

        if($start && $end){
            $start =  $start . ' 08:00:00';
            $end_next_day = date('Y-m-d',strtotime($end . "+1 days"));

            $end =  $end_next_day . ' 08:00:00';
        }


        $plans= '';
        $department_fact= '';
        if($start && $end){
            //Кол-во  продукции в отделе штампока план

            //Кол-во  продукции в отделе штампока план
            $command = $connection->createCommand("SELECT DATE_FORMAT(date,'%Y-%m-%d') as 'dates', sum(value) as 'amount'
                                            FROM b_plans_dates 
                                            Where status = 1 AND date IS NOT NULL
                                            AND department_id = 6 AND date BETWEEN  '$start' AND '$end'
                                            GROUP BY DATE_FORMAT(date,'%Y-%m-%d')");
            $plans = $command->queryAll();

            //Кол-во выпущенной продукции в отделе штампока на кажды день
            $command = $connection->createCommand("SELECT DATE_FORMAT(dates,'%Y-%m-%d') as 'dates', sum(amount) as 'amount'
                                            FROM $department_name
                                            Where status = 1 AND dates IS NOT NULL AND department_id = 6
                                            AND previous_department_id = 6 
                                            AND dates BETWEEN   '$start' AND '$end'
                                            GROUP BY DATE_FORMAT(dates,'%Y-%m-%d')");
            $department_fact = $command->queryAll();

        }else{
            //Кол-во  продукции в отделе штампока план
            $command = $connection->createCommand("SELECT DATE_FORMAT(date,'%Y-%m-%d') as 'dates', sum(value) as 'amount'
                                            FROM b_plans_dates 
                                            Where status = 1 AND date IS NOT NULL 
                                            AND department_id = $department_id AND date BETWEEN  NOW() - INTERVAL 30 DAY AND NOW() + INTERVAL 30 DAY
                                            GROUP BY DATE_FORMAT(date,'%Y-%m-%d')");
            $plans = $command->queryAll();

            //Кол-во выпущенной продукции в отделе штампока на кажды день
            $command = $connection->createCommand("SELECT DATE_FORMAT(dates,'%Y-%m-%d') as 'dates', sum(amount) as 'amount'
                                            FROM $department_name
                                            Where status = 1 AND dates IS NOT NULL AND department_id = 6
                                            AND previous_department_id = 6 
                                            AND dates BETWEEN  NOW() - INTERVAL 30 DAY AND NOW() + INTERVAL 30 DAY
                                            GROUP BY DATE_FORMAT(dates,'%Y-%m-%d')");
            $department_fact = $command->queryAll();
        }


        //Преаращения в читабельный массив для Pie Chart План
        $plan_array = [];
        //Преаращения в читабельный массив для Pie Chart Факт
        $plan_array2 = [];


        if(!empty($plans)) {
            foreach ($plans as $plan) {
                $plan_array[] = array($plan['dates'], $plan['amount']);
            }
        }

        if(!empty($department_fact)) {
            foreach ($department_fact as $res) {
                $plan_array2[] = array($res['dates'], $res['amount']);
            }
        }

//        debug($plan_array);
//        debug($plan_array2);


        return $this->render('report', [
            'plan_array' => $plan_array,
            'plan_array2' => $plan_array2,
            'total_now' => $total_now,
            'total_plan_between' => $total_plan_between,
            'month' => $month,
            'now_date' => $now_date,
            'total_ready_between' => $total_ready_between,
            'defect_between' => $defect_between,
            'total_plans' => $total_plans,
            'tomorrow_date' => $tomorrow_date,
        ]);
    }





    public function actionRemove($id) {
        $model = DepartmentElectro::find()->where(['id'=>$id])->one();

        if (Yii::$app->user->identity->role != User::ROLE_INDUSTRY_USER  && $model) {
            $model->removeObject();
            Yii::$app->session->setFlash('electro_removed', 'Операция успешно удалена');
        }

        return $this->redirect(['/admin/industry/department-electro ']);
    }


    protected function findModel($id)
    {
        if (($model = DepartmentElectro::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
