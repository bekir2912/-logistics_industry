<?php

namespace app\modules\admin\controllers\industry;

use app\models\industry\BPlan;
use app\models\industry\handbook\BDepartment;
use app\models\industry\handbook\BLine;
use app\models\user\User;
use Yii;
use app\models\industry\BPlansDates;
use app\models\industry\BPlansDatesSearch;
use yii\base\BaseObject;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PlansDatesController implements the CRUD actions for BPlansDates model.
 */
class PlansDatesController extends Controller
{

    public  $user;

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

            if (!in_array('plans-dates', $accesses)) {
                throw new HttpException(403, 'В доступе отказано');
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
     * Lists all BPlansDates models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BPlansDatesSearch();
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
     * Displays a single BPlansDates model.
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

    /**
     * Creates a new BPlansDates model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id = null) {

        $model = new BPlansDates();
        $departments = ArrayHelper::map(BDepartment::find()->where(['status'=>1])->all(), 'id', 'name_ru');

        if ($id) {
            $model = BPlansDates::find()->where(['id'=>$id])->one();
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            Yii::$app->session->setFlash('dates_saved', 'План успешно сохранен');
            return $this->redirect(['/admin/industry/plans-dates/view', 'id'=>$model->id]);
        }




        return $this->render('create', [
            'model' => $model,
            'departments' => $departments,
        ]);
    }



    public function actionRemove($id) {
        $model =  BPlansDates::find()->where(['id'=>$id])->one();

        if (Yii::$app->user->identity->role != User::ROLE_INDUSTRY_USER  && $model) {
            $model->removeObject();
            Yii::$app->session->setFlash('dates_removed', 'План успешно удален');
        }

        return $this->redirect(['/admin/industry/plans-dates']);
    }

    protected function findModel($id)
    {
        if (($model = BPlansDates::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
