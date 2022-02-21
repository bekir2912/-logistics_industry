<?php

namespace app\modules\admin\controllers\industry;

use app\models\industry\BufferZone;
use app\models\industry\DepartmentChecking;
use app\models\industry\DepartmentMechanical;
use app\models\industry\DepartmentPaiting;
use app\models\industry\DepartmentStamping;
use app\models\industry\DepartmentTest;
use app\models\industry\handbook\BLine;
use app\models\industry\handbook\ProductModel;
use app\models\user\User;
use DateTime;
use Yii;
use app\models\industry\DepartmentSizing;
use app\models\industry\DepartmentSizingSearch;
use yii\base\BaseObject;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class DepartmentSizingController extends Controller
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

            if (!in_array('department-sizing', $accesses)) {
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
        $searchModel = new DepartmentSizingSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);


        // Для  фильтрации моделей
        $models =  \app\models\industry\DepartmentSizing::find()->select('model_id')->orderBy('model_id')->groupBy('model_id')->all();
        $models_filter = [];

        if(!empty($models)) {
            foreach ($models as $model) {
                $models_filter[$model->model->id] = $model->model->name_ru;
            }
        }

        $departments_filter = [];

        $departments =  DepartmentSizing::find()->select('previous_department_id')->orderBy('previous_department_id')->groupBy('previous_department_id')->all();
        if(!empty($departments)) {
            foreach ($departments as $dep) {
                $departments_filter[$dep->previous->id] = $dep->previous->name_ru;
            }
        }


        $dataProvider->setSort([
            'defaultOrder' => [
                'id' => 'desc'
            ]
        ]);



        return $this->render('index', [
            'searchModel' => $searchModel,
            'models_filter' => $models_filter,
            'departments_filter' => $departments_filter,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCheck($id) {
        $model = DepartmentSizing::findOne($id);

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
        DepartmentSizing::deleteAll(['id' => $arrays]);
        Yii::$app->session->setFlash('sizing_removed', 'Поддоны успешно удалены');
        return $this->redirect(Yii::$app->request->referrer);
    }
    public function actionView($id)
    {
        $model = DepartmentSizing::findOne($id);
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


    public function actionReport(){

        $department_id = 5;
        $department_name = 'b_department_sizing';


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



    public function actionCreate($id = null) {
        $model = new DepartmentSizing();


        $department_id = yii::$app->request->get('department_id');
        $buffer_id = yii::$app->request->get('buffer_id');


        $department = DepartmentTest::findOne($department_id);
        $buffer = BufferZone::findOne($buffer_id);

        if ($id) {
            $model = DepartmentSizing::find()->where(['id'=>$id])->one();
        }

        if( $department->department_id == 4){
            Yii::$app->session->setFlash('buffer_alert_time', 'Данный поддон после сохранения будет находится 4 часа в ожидании, после можно будет выбрать линию');
        }elseif ($model->department_id == 5){
            $now = date('d.m.Y H:i');
            $date_format_expire = date('d.m.Y H:i', $model->time_expire);

            $date_now = new DateTime($now);
            $time_expire = new DateTime($date_format_expire);

            $interval= $time_expire->diff($date_now);
            if($model->time_expire >= time() )
                Yii::$app->session->setFlash('buffer_alert_time', "Данный поддон будет находится в ожидании еще " .$interval->h. " часов " .$interval->i . " минут" );
            elseif ($model->time_expire < time())
                Yii::$app->session->setFlash('buffer_alert_time', "Данный поддон будет находится в буфферной зоне 0 часов 0 минут" );
        }


        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if($buffer){
                $buffer->status = 1;
                $buffer->save();
            }

            if($department->department_id == 4) {
//                $time_expire_to_unix = time() + (4 * 60 * 60);
                if ($model->time_expire &&  $model->time_expire < $model->created_at) {
                    $model->removeObject();
                    Yii::$app->session->setFlash('error_save', 'Дата приготовления не может быть меньше даты добавления');
                    return $this->redirect(Yii::$app->request->referrer);
                } else {
                    //                $model->time_expire = $model->created_at + (4* 60 * 60);
                    $model->time_expire = $model->created_at + (180);
                    $model->save();
 //
                    Yii::$app->session->setFlash('buffer_saved', 'Продукт успешно сохранен');
                    return $this->redirect(['/admin/industry/department-sizing/view',
                        'id' => $model->id,
                    ]);
                }
            }



            Yii::$app->session->setFlash('sizing_saved', 'Операция успешно сохранена');
            return $this->redirect(['/admin/industry/department-sizing/view', 'id'=>$model->id]);
        }


        $lines = ArrayHelper::map(BLine::find()->where(['status'=>1])->andWhere(['department_id' =>$model->department_id ?$model->department_id: $department->department_id+1])->all(), 'id', 'name_ru');
        $models = ArrayHelper::map(ProductModel::find()->where(['status'=>1])->andWhere(['department_id' =>5])->all(), 'id', 'name_ru');

        return $this->render('create', [
            'department' => $department,
            'model' => $model,
            'lines' => $lines,
            'models' => $models,
        ]);
    }

    public function actionRemove($id) {
        $model = DepartmentSizing::find()->where(['id'=>$id])->one();

        if (Yii::$app->user->identity->role != User::ROLE_INDUSTRY_USER  && $model) {
            $model->removeObject();
            Yii::$app->session->setFlash('sizing_removed', 'Операция успешно удалена');
        }

        return $this->redirect(['/admin/industry/department-sizing']);
    }


    protected function findModel($id)
    {
        if (($model = DepartmentSizing::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
