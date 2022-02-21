<?php

namespace app\modules\admin\controllers\industry;

use app\models\Category;
use app\models\gp\Gp;
use app\models\industry\DepartmentChecking;
use app\models\industry\DepartmentElectro;
use app\models\industry\DepartmentForming;
use app\models\industry\DepartmentGp;
use app\models\industry\DepartmentMechanical;
use app\models\industry\DepartmentPaiting;
use app\models\industry\DepartmentPlastic;
use app\models\industry\DepartmentPrinting;
use app\models\industry\DepartmentRegulator;
use app\models\industry\DepartmentSizing;
use app\models\industry\DepartmentStamping;
use app\models\industry\DepartmentTest;
use app\models\industry\handbook\BDeffect;
use app\models\industry\handbook\BDepartment;
use app\models\industry\handbook\BLine;
use app\models\industry\handbook\Detail;
use app\models\Notification;
use app\models\user\User;
use phpDocumentor\Reflection\Types\Collection;
use stdClass;
use Yii;
use app\models\industry\AllDeffect;
use app\models\industry\AllDeffectSearch;
use yii\base\BaseObject;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Request;

class AllDeffectController extends Controller
{

    public $user;



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

    public function actionAdd($id)
    {
        $oldModel = AllDeffect::findOne($id);
        $model = new AllDeffect();

        $amounts = $model->count_deffect;

        $department =(object)[];

        if($oldModel->department_id == 1 && $oldModel){
            $department  = DepartmentStamping::find()->where(['id'=>$oldModel->dep_id])->one();

        }else if($oldModel->department_id == 2 && $oldModel){
            $department  = DepartmentPaiting::find()->where(['id'=>$oldModel->dep_id])->one();
        }else if($oldModel->department_id == 3 && $oldModel){
            $department  = DepartmentMechanical::find()->where(['id'=>$oldModel->dep_id])->one();
        }else if($oldModel->department_id == 4 && $oldModel){
            $department  = DepartmentTest::find()->where(['id'=>$oldModel->dep_id])->one();
        }else if($oldModel->department_id == 5 && $oldModel){
            $department  = DepartmentSizing::find()->where(['id'=>$oldModel->dep_id])->one();
        }else if($oldModel->department_id == 6 && $oldModel){
            $department  = DepartmentElectro::find()->where(['id'=>$oldModel->dep_id])->one();
        }else if($oldModel->department_id == 7 && $oldModel){
            $department  = DepartmentGp::find()->where(['id'=>$oldModel->dep_id])->one();
        }else if($oldModel->department_id == 8 && $oldModel){
            $department  = DepartmentPlastic::find()->where(['id'=>$oldModel->dep_id])->one();
        }else if($oldModel->department_id == 9&& $oldModel){
            $department = DepartmentRegulator::find()->where(['id'=>$oldModel->dep_id])->one();
        }else if($oldModel->department_id == 10&& $oldModel){
            $department = DepartmentPrinting::find()->where(['id'=>$oldModel->dep_id])->one();
        }else if($oldModel->department_id == 11&& $oldModel){
            $department = DepartmentForming::find()->where(['id'=>$oldModel->dep_id])->one();
        }else if($oldModel->department_id == 12&& $oldModel){
            $department = DepartmentChecking::find()->where(['id'=>$oldModel->dep_id])->one();
        }

        if ($model->load(Yii::$app->request->post())) {

            if($department->amount  < $model->count_deffect){
                Yii::$app->session->setFlash('error_saved', 'Деффектной продукции не может быть больше чем едениц в поддоне');
                return $this->redirect(Yii::$app->request->referrer);
            }else{
                $department->amount  += $amounts - $model->count_deffect;
                $department->is_defect = 1;
                $department->status = 0;
                $model->status = 1;
                if($model->count_deffect == 0){
                    $department->is_defect = 0;
                    $model->status = 0;
                    $model->save();

                }
                $model->save();
                $department->save();
            }

            Yii::$app->session->setFlash('alldefect_saved', 'Дефект успешно сохранен');
            return $this->redirect(['/admin/industry/all-deffect/view', 'id'=>$model->id]);
        }

        if($department_id || $model) {
            $select_deffect = '';
            if ($dep_name == 1) {
                $select_deffect = DepartmentStamping::find()->where(['id' => $department_id])->one();
            } else if ($dep_name == 2) {
                $select_deffect = DepartmentPaiting::find()->where(['id' => $department_id])->one();
            } else if ($dep_name == 3) {
                $select_deffect = DepartmentMechanical::find()->where(['id' => $department_id])->one();
            } else if ($dep_name == 4) {
                $select_deffect = DepartmentTest::find()->where(['id' => $department_id])->one();
            } else if ($dep_name == 5) {
                $select_deffect = DepartmentSizing::find()->where(['id' => $department_id])->one();
            } else if ($dep_name == 6) {
                $select_deffect = DepartmentElectro::find()->where(['id' => $department_id])->one();
            } else if ($dep_name == 7) {
                $select_deffect = DepartmentGp::find()->where(['id' => $department_id])->one();
            } else if ($dep_name == 8) {
                $select_deffect = DepartmentPlastic::find()->where(['id' => $department_id])->one();
            } else if ($dep_name == 9) {
                $select_deffect = DepartmentRegulator::find()->where(['id' => $department_id])->one();
            } else if ($dep_name == 10) {
                $select_deffect = DepartmentPrinting::find()->where(['id' => $department_id])->one();
            } else if ($dep_name == 11) {
                $select_deffect = DepartmentForming::find()->where(['id' => $department_id])->one();
            } else if ($dep_name == 12) {
                $select_deffect = DepartmentChecking::find()->where(['id' => $department_id])->one();
            }
        }

            $departments = ArrayHelper::map(BDepartment::find()->where(['status'=>1])->all(), 'id', 'name_ru');
            $defects = BDeffect::find()->where(['status'=>1])->andWhere(['department_id' => $oldModel->department_id ? $oldModel->department_id : $model->department_id])->all();

            $full_deffects = [];
            if (!empty($defects)) {
                foreach ($defects as $defect) {
                    $full_deffects[$defect->id] = $defect->name_ru;
                }
            }

            $details = ArrayHelper::map(Detail::find()->where(['status'=>1])->all(), 'id', 'name_ru');
            $lines = ArrayHelper::map(BLine::find()->where(['status'=>1])->all(), 'id', 'name_ru');
            $units = ArrayHelper::map(Category::find()->where(['type'=>'unit'])->all(), 'id', 'name_ru');

            return $this->render('add', [
                'departments' => $departments,
                'units' => $units,
                'defects' => $full_deffects,
                'select_deffect' => $select_deffect,
                'department' => $department,
                'details' => $details,
                'lines' => $lines,
                'model' => $model,
                'oldModel' => $oldModel,

            ]);

    }

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

            if (!in_array('all-deffect', $accesses)) {
                throw new HttpException(403, 'В доступе отказано');
            }
        }

        return parent::beforeAction($action);
    }

    public function actionStock($id){
        $model = AllDeffect::findOne($id);

        $notification = new Notification();
        $notification->object_id = $model->id;
        $notification->message = "Дефектная продукция: " . $model->deffect->name_ru;
        $notification->department_id = $model->department_id;
        $notification->status_admin = 0;
        $notification->type = AllDeffect::tableName();
        $notification->status = 0;
        $notification->save();


        $model->in_stock = 1;
        $model->save();
        Yii::$app->session->setFlash('close_deffect', 'Деффект отправлен на склад');

        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);

    }


    public function actionIndex()
    {
        $searchModel = new AllDeffectSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $department_id = yii::$app->request->get('department_id');
        $is_save = yii::$app->request->get('is_save');
        $status = yii::$app->request->get('status');
        $current_operation = yii::$app->request->get('current_operation');

        // Для  фильтрации моделей
        $models =  \app\models\industry\AllDeffect::find()->select('model_id')->orderBy('model_id')->groupBy('model_id')->all();
        $models_filter = [];
        if(!empty($models)) {
            foreach ($models as $model) {
                $models_filter[$model->model->id] = $model->model->name_ru;
            }
        }

        if($department_id || $current_operation){
            $searchModel = new AllDeffectSearch();
            $array['AllDeffectSearch']['department_id'] = $department_id;
            $array['AllDeffectSearch']['is_save'] = $is_save;
            $array['AllDeffectSearch']['status'] = $status;
            $array['AllDeffectSearch']['current_operation'] = $current_operation;
            $dataProvider = $searchModel->search($array);




            $dataProvider->setSort([
                'defaultOrder' => [
                    'id' => 'desc'
                ]
            ]);

            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }
        // Для  фильтрации отделов
        $departments =  \app\models\industry\AllDeffect::find()->select('department_id')->orderBy('department_id')->groupBy('department_id')->all();
        $departmnets_filter = [];
        if(!empty($departments)) {
            foreach ($departments as $dep) {
                $departmnets_filter[$dep->department->id] = $dep->department->name_ru;
            }
        }

        // Для вывода сортировки по убыванию
        $dataProvider->setSort([
            'defaultOrder' => [
                'id' => 'desc'
            ]
        ]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'models_filter' => $models_filter,
            'departmnets_filter' => $departmnets_filter,
        ]);
    }


    public function actionView($id = NULL)
    {

        if(empty($id)){
            Yii::$app->session->setFlash('empty_deffect', 'Деффектной продукции не обнаружено');
            return $this->redirect(['industry/all-deffect']);
        }



        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate($id = null) {
        $model = new AllDeffect();
        $department_id = yii::$app->request->get('id_department');
        $dep_name = yii::$app->request->get('dep_name');

        if ($id) {
            $model = AllDeffect::find()->where(['id'=>$id])->one();
        }
        $amounts = $model->count_deffect;

        $department = '';
        if($dep_name == 1 && $department_id){
            $department  = DepartmentStamping::find()->where(['id'=>$department_id])->one();

        }else if($dep_name == 2 && $department_id){
            $department  = DepartmentPaiting::find()->where(['id'=>$department_id])->one();
        }else if($dep_name == 3 && $department_id){
            $department  = DepartmentMechanical::find()->where(['id'=>$department_id])->one();
        }else if($dep_name == 4 && $department_id){
            $department  = DepartmentTest::find()->where(['id'=>$department_id])->one();
        }else if($dep_name == 5 && $department_id){
            $department  = DepartmentSizing::find()->where(['id'=>$department_id])->one();
        }else if($dep_name == 6 && $department_id){
            $department  = DepartmentElectro::find()->where(['id'=>$department_id])->one();
        }else if($dep_name == 7 && $department_id){
            $department  = DepartmentGp::find()->where(['id'=>$department_id])->one();
        }else if($dep_name == 8 && $department_id){
            $department  = DepartmentPlastic::find()->where(['id'=>$department_id])->one();
        }else if($dep_name == 9){
            $department = DepartmentRegulator::find()->where(['id'=>$department_id])->one();
        }else if($dep_name == 10){
            $department = DepartmentPrinting::find()->where(['id'=>$department_id])->one();
        }else if($dep_name == 11){
            $department = DepartmentForming::find()->where(['id'=>$department_id])->one();
        }else if($dep_name == 12){
            $department = DepartmentChecking::find()->where(['id'=>$department_id])->one();
        }
        if ($model->load(Yii::$app->request->post())) {

            if($department->amount < $model->count_deffect){
                Yii::$app->session->setFlash('error_saved', 'Деффектной продукции не может быть больше чем едениц в поддоне');
                return $this->redirect(Yii::$app->request->referrer);
            }else{
                if($model->is_save == 0){
                    $department->amount  += $amounts - $model->count_deffect;
                }
                $department->is_defect = 1;
                $department->status = 0;
                $model->status = 1;
                if($model->count_deffect == 0){
                    $department->is_defect = 0;
                    $model->status = 0;
                    $model->save();

                }
                $model->save();
                $department->save();
            }

            Yii::$app->session->setFlash('alldefect_saved', 'Дефект успешно сохранен');
            return $this->redirect(['/admin/industry/all-deffect/view', 'id'=>$model->id]);
        }

        if($department_id || $model){
            $select_deffect = '';
            if($dep_name == 1){
                $select_deffect = DepartmentStamping::find()->where(['id'=>$department_id])->one();
            }else if($dep_name == 2){
                $select_deffect = DepartmentPaiting::find()->where(['id'=>$department_id])->one();
            }else if($dep_name == 3){
                $select_deffect = DepartmentMechanical::find()->where(['id'=>$department_id])->one();
            }else if($dep_name == 4){
                $select_deffect = DepartmentTest::find()->where(['id'=>$department_id])->one();
            }else if($dep_name == 5){
                $select_deffect = DepartmentSizing::find()->where(['id'=>$department_id])->one();
            }else if($dep_name == 6){
                $select_deffect = DepartmentElectro::find()->where(['id'=>$department_id])->one();
            }else if($dep_name == 7){
                $select_deffect = DepartmentGp::find()->where(['id'=>$department_id])->one();
            }else if($dep_name == 8){
                $select_deffect = DepartmentPlastic::find()->where(['id'=>$department_id])->one();
            }else if($dep_name == 9){
                $select_deffect = DepartmentRegulator::find()->where(['id'=>$department_id])->one();
            }else if($dep_name == 10){
                $select_deffect = DepartmentPrinting::find()->where(['id'=>$department_id])->one();
            }else if($dep_name == 11){
                $select_deffect = DepartmentForming::find()->where(['id'=>$department_id])->one();
            }else if($dep_name == 12){
                $select_deffect = DepartmentChecking::find()->where(['id'=>$department_id])->one();
            }

        $departments = ArrayHelper::map(BDepartment::find()->where(['status'=>1])->all(), 'id', 'name_ru');
        $defects = BDeffect::find()->where(['status'=>1])->andWhere(['department_id' => $department->department_id ? $department->department_id : $model->department_id])->all();

        $full_deffects = [];
        if (!empty($defects)) {
            foreach ($defects as $defect) {
                $full_deffects[$defect->id] = $defect->name_ru;
            }
        }

        $details = ArrayHelper::map(Detail::find()->where(['status'=>1])->all(), 'id', 'name_ru');
        $lines = ArrayHelper::map(BLine::find()->where(['status'=>1])->all(), 'id', 'name_ru');
        $units = ArrayHelper::map(Category::find()->where(['type'=>'unit'])->all(), 'id', 'name_ru');

        return $this->render('create', [
            'departments' => $departments,
            'units' => $units,
            'defects' => $full_deffects,
            'department' => $department,
            'details' => $details,
            'lines' => $lines,
            'model' => $model,
            'select_deffect' => $select_deffect,

        ]);
        }
    }

    public function actionRemove($id) {
        $model = AllDeffect::find()->where(['id'=>$id])->andWhere(['or', ['status' => 0], ['is_save' => 0]])->one();

        if (Yii::$app->user->identity->role != User::ROLE_INDUSTRY_USER  && $model) {
            $model->removeObject();
            Yii::$app->session->setFlash('alldefect_removed', 'Дефект успешно удален');
            return $this->redirect(['/admin/industry/all-deffect']);
        }
        Yii::$app->session->setFlash('alldefect_warning', 'Дефект не может быть удален пока статус активен');
        return $this->redirect(['/admin/industry/all-deffect']);

    }



    protected function findModel($id)
    {
        if (($model = AllDeffect::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'Запрос не вернул результата'));
    }
}
