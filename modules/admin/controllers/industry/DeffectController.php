<?php

namespace app\modules\admin\controllers\industry;

use app\models\industry\DepartmentStamping;
use app\models\industry\handbook\BDepartment;
use app\models\user\User;
use Yii;
use app\models\industry\handbook\BDeffect;
use app\models\industry\handbook\BDeffectSearch;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class DeffectController extends Controller
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

            if (!in_array('deffect', $accesses)) {
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
        $searchModel = new BDeffectSearch();
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



    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate($id = null) {
        $model = new BDeffect();

        if ($id) {
            $model = BDeffect::find()->where(['id'=>$id])->one();
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            Yii::$app->session->setFlash('deffect_saved', 'Деффект успешно сохранен');
                return $this->redirect(['/admin/industry/deffect/view', 'id'=>$model->id]);
        }

        $departments = ArrayHelper::map(BDepartment::find()->where(['status'=>1])->all(), 'id', 'name_ru');

        return $this->render('create', [
            'model' => $model,
            'departments' => $departments,

        ]);
    }


    public function actionMulti($id=null)
    {
        $arrays = Yii::$app->request->post()['selection'];
        BDeffect::deleteAll(['id' => $arrays]);
        Yii::$app->session->setFlash('deffect_removed', 'Успешно удалено');
        return $this->redirect(Yii::$app->request->referrer);
    }

    protected function findModel($id)
    {
        if (($model = BDeffect::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException(Yii::t('app', 'Поиск не дал результата'));
    }


    public function actionRemove($id) {
        $model = BDeffect::find()->where(['id'=>$id])->one();

        if (Yii::$app->user->identity->role != User::ROLE_INDUSTRY_USER  && $model) {
            $model->removeObject();
            Yii::$app->session->setFlash('deffect_removed', 'Дефект успешно удален');
        }

        return $this->redirect(['/admin/industry/deffect']);
    }
}
