<?php

namespace app\modules\admin\controllers\industry;

use app\models\Category;
use app\models\industry\BufferZone;
use app\models\industry\DepartmentElectro;
use app\models\industry\DepartmentMechanical;
use app\models\industry\DepartmentPaiting;
use app\models\industry\DepartmentPlastic;
use app\models\industry\handbook\ProductModel;
use app\models\Notification;
use app\models\user\User;
use DateTime;
use Yii;
use app\models\industry\DepartmentGp;
use app\models\industry\DepartmentGpSearch;
use yii\base\BaseObject;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DepartmentGpController implements the CRUD actions for DepartmentGp model.
 */
class DepartmentGpController extends Controller
{

    public  $user;
    /**
     * {@inheritdoc}
     */
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

            if (!in_array('department-gp', $accesses)) {
                throw new HttpException(403, 'В доступе отказано');
            }
        }

        return parent::beforeAction($action);
    }


    /**
     * Lists all DepartmentGp models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DepartmentGpSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);


        // Для  фильтрации моделей
        $models =  \app\models\industry\DepartmentGp::find()->select('model_id')->orderBy('model_id')->groupBy('model_id')->all();
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
        $gpTable = DepartmentGp::tableName();

        $grouping = DepartmentGp::find()
            ->select(['SUM(amount) as amount', 'model_id', $modelTable .'.name_ru'])
            ->innerJoin($modelTable, "$modelTable.id = $gpTable.model_id")
            ->groupBy('model_id')
            ->all();


        $array_data = [];
        foreach ($grouping as $key=>$model){

            $array_data[$key][] = $model['name_ru'];
            $array_data[$key][] = (int)$model['amount'];
        }
        $result = array_merge([['Week', 'Кол-во']], $array_data);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'result' => $result,
            'models_filter' => $models_filter,
        ]);

    }



    /**
     * Displays a single DepartmentGp model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionStock($id = null){
        $obj = DepartmentGp::findOne($id);

        $notification = new Notification();
        $notification->object_id = $obj->id;
        $notification->model_id = $obj->model_id;
        $notification->message = "Новая заявка от раздела: " . $obj->department->name_ru;
        $notification->department_id = $obj->department_id;
        $notification->status_admin = 0;
        $notification->type = 'b_department_gp';
        $notification->status = 0;
        $notification->save();

        $obj->status = 3;
        $obj->save();
        return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
    }


    public function actionCheck($id) {
        $model = DepartmentGp::findOne($id);

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
        DepartmentGp::deleteAll(['id' => $arrays]);
        Yii::$app->session->setFlash('gp_removed', 'Поддоны успешно удалены');
        return $this->redirect(Yii::$app->request->referrer);
    }


    /**
     * Creates a new DepartmentGp model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id = null) {
        $model = new DepartmentGp();

        $department_id = yii::$app->request->get('department_id');
        $buffer_id = yii::$app->request->get('buffer_id');

        $department = DepartmentElectro::findOne($department_id);
        $buffer = BufferZone::findOne($buffer_id);


        if ($id) {
            $model = DepartmentGp::find()->where(['id'=>$id])->one();
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {



            if($buffer){
                $buffer->status = 1;
                $buffer->save();
            }
            Yii::$app->session->setFlash('gp_saved', 'Операция успешно сохранена');
            return $this->redirect(['/admin/industry/department-gp/view', 'id'=>$model->id]);
        }

        $models = ArrayHelper::map(ProductModel::find()->where(['status'=>1])->andWhere(['department_id' =>7])->all(), 'id', 'name_ru');
        $units = ArrayHelper::map(Category::find()->where(['type'=>'unit'])->all(), 'id', 'name_ru');

        return $this->render('create', [
            'department' => $department,
            'model' => $model,
            'models' => $models,
            'units' => $units,
        ]);
    }

    public function actionReport(){

        $department_id = 7;
        $department_name = 'b_department_gp';


        $now_date = strftime("%Y-%m-%d", time());
        $tomorrow_date = date('Y-m-d',strtotime($now_date . "+1 days"));
        $tomorrow_date = $tomorrow_date .' 08:00:00';
        $now_date = $now_date .' 08:00:00';
        $connection = Yii::$app->getDb();


        //Общее число готовой(факт) выпущенной продукции за сегодня день
        $command2 = $connection->createCommand("SELECT s.dates, sum(s.amount) as `amount`
                                            FROM $department_name s
                                            Where s.status = 1 AND (s.dates IS NOT NULL) AND  (s.dates BETWEEN '$now_date' AND  '$tomorrow_date')
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
                                            Where p.status = 1 AND (p.dates IS NOT NULL) AND p.dates BETWEEN '$month' AND  '$tomorrow_date'
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
                                            AND department_id = $department_id AND date BETWEEN  '$start' AND '$end'
                                            GROUP BY DATE_FORMAT(date,'%Y-%m-%d')");
            $plans = $command->queryAll();

            //Кол-во выпущенной продукции в отделе штампока на кажды день
            $command = $connection->createCommand("SELECT DATE_FORMAT(dates,'%Y-%m-%d') as 'dates', sum(amount) as 'amount'
                                            FROM $department_name
                                            Where status = 1 AND dates IS NOT NULL
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
                                            Where status = 1 AND dates IS NOT NULL
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


    /**
     * Updates an existing DepartmentGp model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionRemove($id) {
        $model = DepartmentGp::find()->where(['id'=>$id])->one();

        if (Yii::$app->user->identity->role != User::ROLE_INDUSTRY_USER  && $model) {
            $model->removeObject();
            Yii::$app->session->setFlash('gp_removed', 'Операция успешно удалена');
        }

        return $this->redirect(['/admin/industry/department-gp']);
    }



    /**
     * Finds the DepartmentGp model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DepartmentGp the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DepartmentGp::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('Запрос не дал результата');

    }
}
