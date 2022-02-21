<?php
namespace app\modules\admin\controllers;

use app\models\industry\DepartmentGp;
use app\models\industry\handbook\BDepartment;
use app\models\Notification;
use Yii;
use yii\base\BaseObject;
use yii\web\Controller;
use yii\helpers\ArrayHelper;
use yii\web\HttpException;

use app\models\user\User;
use app\models\stock\Stock;
use app\models\product\Product;
use app\models\shipment\Shipment;
use app\models\shipment\ShipmentSearch;
use app\models\shipment\ShipmentProductSearch;
use app\models\Category;

use app\models\agent\Agent;

class ShipmentController extends Controller{
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

            if (!in_array('shipment', $accesses)) {
                throw new HttpException(403, 'В доступе отказано');
            }
        }

        return parent::beforeAction($action);
    }

    public function actionIndex() {
        $searchModel = new ShipmentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $dataProvider->setSort([
            'defaultOrder' => [
                'id' => 'desc'
            ]
        ]);

        $model = new Shipment;

        if ($model->load(Yii::$app->request->post()) && ($model->file = UploadedFile::getInstance($model, 'file'))) {
            if ($model->upload()) {
                Yii::$app->session->setFlash('uploaded', 'Данные успешно загружены');
                return $this->redirect(Yii::$app->request->referrer);
            }
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $model
        ]);
    }

    public function actionCreate($id = null) {
        $model = new Shipment;

        if ($id) {
            $model = Shipment::find()->with('shipmentProducts')->where(['id'=>$id])->one();
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            
            if ($model->saveObject()) {

                $notification = new Notification();
                $notification->object_id = $model->id;
                $notification->message = "Новая заявка от раздела: " . $model->department->name_ru;
                $notification->department_id = $model->department_id;
                $notification->status_admin = 0;
                $notification->type = 'shipment';
                $notification->status = 0;
                $notification->save();



                Yii::$app->session->setFlash('shipment_saved', 'Заявка успешно отправлена');
                return $this->redirect(['/admin/industry/detail', 'id'=>$model->id]);
            }
        }



        $departments = ArrayHelper::map(BDepartment::find()->all(), 'id', 'name_ru');
        $products = ArrayHelper::map(Product::find()->where(['status'=>1])->all(), 'id', 'name_ru');
        $articles = ArrayHelper::map(Product::find()->where(['status'=>1])->all(), 'id', 'article');

        return $this->render('create', [
            'model' => $model,
            'products' => $products,
            'articles' => $articles,
            'departments' => $departments
        ]);
    }

    public function actionView($id) {
        $model = Shipment::findOne($id);

        $searchModel = new ShipmentProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andWhere(['shipment_id'=>$model->id]);

        $dataProvider->setSort([
            'defaultOrder' => [
                'id' => 'desc'
            ]
        ]);

        return $this->render('view', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionRemove($id) {
        $model = Shipment::findOne($id);

        if ($this->user && ($this->user->role != User::ROLE_USER) && $model && $model->delete()) {
            Yii::$app->session->setFlash('shipment_removed', 'Отгрузка успешно удалена');
        }
        return $this->redirect(['/admin/industry/detail']);
    }

    public function actionConfirmation($id, $type) {
        $model = Shipment::findOne($id);

        if ($model) {
            $model->status = $type;
            if ($model->save(false)) {
                if ($model->status == 1) {
                    Yii::$app->session->setFlash('shipment_accepted', 'Отгрузка успешно одобрена');
                }
                if ($model->status == 2) {
                    Yii::$app->session->setFlash('shipment_accepted', 'Отгрузка успешно отклонена');
                }
            }
        }

        return $this->redirect(Yii::$app->request->referrer);
    }
}