<?php

namespace app\modules\admin\controllers\industry;

use app\models\industry\BufferZone;
use app\models\industry\DepartmentChecking;
use app\models\industry\DepartmentStamping;
use app\models\industry\handbook\BDepartment;
use app\models\industry\handbook\BLine;
use app\models\user\User;
use Yii;
use app\models\industry\handbook\ProductModel;
use app\models\industry\handbook\ProductModelSearch;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class ProductModelController extends Controller
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

            if (!in_array('product-model', $accesses)) {
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
        $searchModel = new ProductModelSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);


        $departments =  ProductModel::find()->select('department_id')->orderBy('department_id')->groupBy('department_id')->all();
        $department_filter = [];

        if(!empty($departments)) {
            foreach ($departments as $dep) {
                $department_filter[$dep->department->id] = $dep->department->name_ru;
            }
        }


        $dataProvider->setSort([
            'defaultOrder' => [
                'id' => 'desc'
            ]
        ]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'department_filter' => $department_filter,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionMulti($id=null)
    {
        $arrays = Yii::$app->request->post()['selection'];
        ProductModel::deleteAll(['id' => $arrays]);
        Yii::$app->session->setFlash('model_removed', 'Успешно удалено');
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }


    public function actionCreate($id = null)
    {

        $model = new ProductModel();

        if ($id) {
            $model = ProductModel::find()->where(['id'=>$id])->one();
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('model_saved', 'Модель успешно сохранена');
            return $this->redirect(['view', 'id' => $model->id]);
        }

        $department = ArrayHelper::map(BDepartment::find()->where(['status'=>1])->all(), 'id', 'name_ru');



        return $this->render('create', [
            'model' => $model,
            'department' => $department,
        ]);
    }

    public function actionRemove($id) {
        $model = ProductModel::find()->where(['id'=>$id])->one();

        if (Yii::$app->user->identity->role != User::ROLE_INDUSTRY_USER  && $model) {
            $model->removeObject();
            Yii::$app->session->setFlash('model_removed', 'Модель успешно удалена');
        }

        return $this->redirect(['/admin/industry/product-model']);
    }

    protected function findModel($id)
    {
        if (($model = ProductModel::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'Запрос не дал результат'));
    }
}
