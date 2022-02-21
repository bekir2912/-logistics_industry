<?php

namespace app\modules\admin\controllers\industry;

use app\models\Category;
use app\models\industry\handbook\BDepartment;
use app\models\industry\handbook\BLine;
use app\models\industry\handbook\DetailGroupSearch;
use app\models\product\ProductSearch;
use app\models\shipment\ShipmentSearch;
use app\models\user\User;
use Yii;
use app\models\industry\handbook\Detail;
use app\models\industry\handbook\DetailSearch;
use yii\base\BaseObject;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class DetailController extends Controller
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

            if (!in_array('detail', $accesses)) {
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
        //Баланс Деталей
        $searchModel = new DetailSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);




        //Сумма деталей
        $searchSumDetailModel = new DetailGroupSearch();
        $dataSumDetailProvider = $searchSumDetailModel->search(Yii::$app->request->queryParams);

        //Все заявки
        $searchShipmentModel = new ShipmentSearch();
        $dataShipmentProvider = $searchShipmentModel->search(Yii::$app->request->queryParams);


        $connection = Yii::$app->getDb();


//        $command2 = $connection->createCommand("SELECT d.product_id, d.department_id, d.unit_id,d.name_ru, d.created_at, sum(count) as `amount`
//                                            FROM `b_details d`
//                                            INNER JOIN product p ON d.product_id = p.id
//                                            INNER JOIN  b_departments b ON d.department_id = b.id
//                                            WHERE `d.status` = 1
//                                            GROUP BY d.product_id, d.department_id, d.unit_id,d.name_ru, d.created_at
//                                          ");
//        $models = $command2->queryAll();


        $dataProvider->setSort([
            'defaultOrder' => [
                'id' => 'desc'
            ]
        ]);

        return $this->render('index', [
            'searchShipmentModel' => $searchShipmentModel,
            'dataSumDetailProvider' => $dataSumDetailProvider,


            'searchSumDetailModel' => $searchSumDetailModel,
            'dataShipmentProvider' => $dataShipmentProvider,




            'models' => $models,
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
        $model = new Detail();

        if ($id) {
            $model = Detail::find()->where(['id'=>$id])->one();
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            Yii::$app->session->setFlash('detail_saved', 'Деталь успешно сохранена');
            return $this->redirect(['/admin/industry/detail/view', 'id'=>$model->id]);
        }

        $departments = ArrayHelper::map(BDepartment::find()->where(['status'=>1])->all(), 'id', 'name_ru');
        $units = ArrayHelper::map(Category::find()->where(['type'=>'unit'])->all(), 'id', 'name_ru');

        return $this->render('create', [
            'departments' => $departments,
            'model' => $model,
            'units' => $units,
        ]);
    }


    protected function findModel($id)
    {
        if (($model = Detail::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException(Yii::t('app', 'Запрос не вернул результата'));
    }

    public function actionRemove($id) {
        $model = Detail::find()->where(['id'=>$id])->one();

        if (Yii::$app->user->identity->role != User::ROLE_INDUSTRY_USER  && $model) {
            $model->removeObject();
            Yii::$app->session->setFlash('detail_removed', 'Деталь успешно удалена');
        }

        return $this->redirect(['/admin/industry/detail']);
    }
}
