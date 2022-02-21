<?php

namespace app\modules\admin\controllers\industry;

use app\models\industry\BPlansDates;
use app\models\industry\DepartmentStamping;
use app\models\industry\handbook\BDepartment;
use app\models\industry\handbook\ProductModel;
use app\models\user\User;
use Cassandra\Date;
use DateInterval;
use DatePeriod;
use DateTime;
use phpDocumentor\Reflection\Types\Self_;
use PHPUnit\Util\Blacklist;
use Yii;
use app\models\industry\BPlan;
use app\models\industry\BPlanSearch;
use yii\base\BaseObject;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * BPlanController implements the CRUD actions for BPlan model.
 */
class BPlanController extends Controller
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

            if (!in_array('b-plan', $accesses)) {
                throw new HttpException(200, 'В доступе отказано');
            }
        }

        return parent::beforeAction($action);
    }


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

    /**
     * Lists all BPlan models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BPlanSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);


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

    /**
     * Displays a single BPlan model.
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

    public function actionMulti($id=null)
    {
        $arrays = Yii::$app->request->post()['selection'];
        $array = BPlan::find()->where(['id' => $arrays])->all();
        foreach ($array as $k => $a){
            foreach ($a->plansDates as $d => $plans){
                $plans->removeObject();
            }
        }
        BPlan::deleteAll(['id' => $arrays]);
        Yii::$app->session->setFlash('plan_removed', 'Поддоны успешно удалены');
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionCreate($id = null) {
        $start = yii::$app->request->get('start')? yii::$app->request->get('start'): ''; //id операции
        $end = yii::$app->request->get('end')? yii::$app->request->get('end') : ''; // id отдела откуда пришел


        $model = new BPlan();
        $departments = ArrayHelper::map(BDepartment::find()->where(['status'=>1])->all(), 'id', 'name_ru');

        if ($id) {
            $model = BPlan::find()->where(['id'=>$id])->one();
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->saveObject()) {
                Yii::$app->session->setFlash('plan_saved', 'План успешно сохранен');
                return $this->redirect(['/admin/industry/b-plan/view', 'id' => $model->id]);
            }
        }

        if ($start && $end) {


            $date_interval =  $this->getDatesFromRange($start,$end);


//            $stacks = Brand::find()->where(['category_id'=>$data['id']])->orderBy('name')->all();
//            Yii::$app->response->format = Response::FORMAT_JSON;
//            return ['data'=>$stacks];
        }



        return $this->render('create', [
            'model' => $model,
            'start' => $start,
            'end' => $end,
            'departments' => $departments,
            'date_interval' => $date_interval,
        ]);
    }


//    public function actionGetDates() {
//        if (Yii::$app->request->isAjax && ($data = Yii::$app->request->post())) {
//
//            $start = new DateTime($data['start']);
//            $end = new DateTime($data['end']);
//
//            $date_interval =  $this->getDatesFromRange($data['start'],$data['end'] );
////            debug($date_interval);
//
//            Yii::$app->response->format = Response::FORMAT_JSON;
//            return ['data'=>$date_interval];
//
////            $stacks = Brand::find()->where(['category_id'=>$data['id']])->orderBy('name')->all();
////            Yii::$app->response->format = Response::FORMAT_JSON;
////            return ['data'=>$stacks];
//        }
//    }



    // Функция для возврата массива дат между двумя датами
    function getDatesFromRange($start, $end, $format = 'Y-m-d') {
        $array = array();
        $interval = new DateInterval('P1D');

        $realEnd = new DateTime($end);
        $realEnd->add($interval);

        $period = new DatePeriod(new DateTime($start), $interval, $realEnd);

        foreach($period as $date) {
            $array[] = $date->format($format);
        }

        return $array;
    }




    protected function findModel($id)
    {
        if (($model = BPlan::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException(Yii::t('app', 'Поиск не дал результата'));
    }


    public function actionRemove($id) {
        $model = BPlan::find()->where(['id'=>$id])->one();

        if (Yii::$app->user->identity->role != User::ROLE_INDUSTRY_USER  && $model) {

            $model->removeObject();
            Yii::$app->session->setFlash('plan_removed', 'План успешно удален');
        }

        return $this->redirect(['/admin/industry/b-plan']);
    }
}
