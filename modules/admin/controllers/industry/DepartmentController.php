<?php

namespace app\modules\admin\controllers\industry;

use app\models\gp\Gp;
use app\models\user\User;
use Yii;
use app\models\industry\handbook\BDepartment;
use app\models\industry\handbook\BDepartmentsSearch;
use yii\base\BaseObject;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DepartmentController implements the CRUD actions for BDepartment model.
 */
class DepartmentController extends Controller
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

            if (!in_array('department', $accesses)) {
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
        $searchModel = new BDepartmentsSearch();
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




    public function actionCreate($id = null)
    {

        $model = new BDepartment();

        if ($id) {
            $model = BDepartment::find()->where(['id'=>$id])->one();
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
                Yii::$app->session->setFlash('department_saved', 'Отдел успешно сохранен');
            return $this->redirect(['view', 'id' => $model->id]);
        }


        return $this->render('create', [
            'model' => $model,
        ]);
    }




    protected function findModel($id)
    {
        if (($model = BDepartment::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
