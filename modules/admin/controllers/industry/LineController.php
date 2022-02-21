<?php

namespace app\modules\admin\controllers\industry;

use app\models\industry\DepartmentStamping;
use app\models\industry\handbook\BDepartment;
use app\models\user\User;
use Yii;
use app\models\industry\handbook\BLine;
use app\models\industry\handbook\BLineSearch;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;


class LineController extends Controller
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

            if (!in_array('line', $accesses)) {
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
        $searchModel = new BLineSearch();
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

    public function actionMulti($id=null)
    {
        $arrays = Yii::$app->request->post()['selection'];
        BLine::deleteAll(['id' => $arrays]);
        Yii::$app->session->setFlash('line_removed', 'Успешно удалено');
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }



    public function actionCreate($id = null) {
        $model = new BLine();

        if ($id) {
            $model = BLine::find()->where(['id'=>$id])->one();
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            Yii::$app->session->setFlash('line_saved', 'Линия успешно сохранена');
            return $this->redirect(['/admin/industry/line/view', 'id'=>$model->id]);
        }

        $departments = ArrayHelper::map(BDepartment::find()->where(['status'=>1])->all(), 'id', 'name_ru');

        return $this->render('create', [
            'departments' => $departments,
            'model' => $model,
        ]);
    }


    protected function findModel($id)
    {
        if (($model = BLine::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException(Yii::t('app', 'Запрос не вернул результата'));
    }

    public function actionRemove($id) {
        $model = BLine::find()->where(['id'=>$id])->one();

        if (Yii::$app->user->identity->role != User::ROLE_INDUSTRY_USER  && $model) {
            $model->removeObject();
            Yii::$app->session->setFlash('line_removed', 'Линия успешно удалена');
        }

        return $this->redirect(['/admin/industry/line']);
    }
}
