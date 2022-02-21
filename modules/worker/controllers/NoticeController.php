<?php
namespace app\modules\worker\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\ArrayHelper;
use yii\web\HttpException;
use yii\web\UploadedFile;

use app\models\user\User;
use app\models\product\Product;
use app\models\Category;
use app\models\Notification;
use app\models\notice\waybill\NoticeWaybill;
use app\models\notice\waybill\NoticeWaybillSearch;
use app\models\notice\waybill\NoticeWaybillProduct;
use app\models\notice\waybill\NoticeWaybillProductSearch;
use app\models\notice\truck\NoticeTruck;
use app\models\notice\truck\NoticeTruckSearch;
use app\models\notice\truck\NoticeTruckProduct;
use app\models\notice\truck\NoticeTruckProductSearch;
use app\models\notice\control\NoticeControl;
use app\models\notice\control\NoticeControlSearch;
use app\models\notice\control\NoticeControlProduct;
use app\models\notice\control\NoticeControlProductSearch;
use app\models\notice\act\NoticeAct;
use app\models\notice\act\NoticeActSearch;
use app\models\notice\act\NoticeActProduct;
use app\models\notice\act\NoticeActProductSearch;
use app\models\stock\Stock;
use app\models\stack\Stack;
use app\models\stack\StackShelving;

class NoticeController extends Controller{
	public $user;

    public function beforeAction($action) {
        $this->enableCsrfValidation = false;
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/worker']);
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

            if (!in_array('notice', $accesses)) {
                throw new HttpException(403, 'В доступе отказано');
            }
        }

        return parent::beforeAction($action);
    }

    // waybill
    public function actionWaybill() {
        $searchModel = new NoticeWaybillSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $dataProvider->setSort([
            'defaultOrder' => [
                'id' => 'desc'
            ]
        ]);

        return $this->render('waybill/index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionWaybillCreate($id = null) {
        $model = new NoticeWaybill;

        if ($id) {
            $model = NoticeWaybill::find()->with('noticeWaybillProducts')->where(['id'=>$id])->one();
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->saveObject()) {
                Yii::$app->session->setFlash('waybill_saved', 'Накладная успешно сохранена');
                return $this->redirect(['/worker/notice/waybill-view', 'id'=>$model->id]);
            }
        }

        $providers = ArrayHelper::map(Category::find()->where(['type'=>'provider'])->all(), 'id', 'name_ru');
        $products = ArrayHelper::map(Product::find()->all(), 'id', 'name_ru');
        $articles = ArrayHelper::map(Product::find()->all(), 'id', 'article');
        $units = ArrayHelper::map(Category::find()->where(['type'=>'unit'])->all(), 'id', 'name_ru');

        return $this->render('waybill/create', [
            'model' => $model,
            'providers' => $providers,
            'products' => $products,
            'articles' => $articles,
            'units' => $units
        ]);
    }

    public function actionWaybillView($id) {
        $model = NoticeWaybill::find()->with('provider')->where(['id'=>$id, 'user_id'=>$this->user->id])->one();

        $searchModel = new NoticeWaybillProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->with('product', 'unit')->andWhere(['notice_waybill_id' => $id]);

        $dataProvider->setSort([
            'defaultOrder' => [
                'id' => 'desc'
            ]
        ]);

        return $this->render('waybill/view', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    // truck
    public function actionTruck() {
        $searchModel = new NoticeTruckSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->with('noticeWaybill', 'noticeWaybill.provider');

        $dataProvider->setSort([
            'defaultOrder' => [
                'id' => 'desc'
            ]
        ]);

        return $this->render('truck/index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionTruckCreate($id = null) {
        $model = new NoticeTruck;

        if ($id) {
            $model = NoticeTruck::find()->with('noticeTruckProducts')->where(['id'=>$id])->one();
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->saveObject()) {
                Yii::$app->session->setFlash('truck_saved', 'Приём грузовика успешно сохранен');
                return $this->redirect(Yii::$app->request->referrer);
            }
        }

        return $this->render('truck/create', [
            'model' => $model
        ]);
    }

    public function actionTruckView($id) {
        $model = NoticeTruck::find()->with('noticeWaybill', 'noticeWaybill.provider')->where(['id'=>$id])->one();

        $searchModel = new NoticeTruckProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->with('product', 'unit')->andWhere(['notice_truck_id' => $id]);

        $dataProvider->setSort([
            'defaultOrder' => [
                'id' => 'desc'
            ]
        ]);

        $products = NoticeTruckProduct::find()->with('product', 'unit')->where(['notice_truck_id'=>$id])->all();

        $truck = NoticeTruck::findOne($id);

        if ($truck->load(Yii::$app->request->post()) && $truck->validate()) {
            if ($truck->saveObject()) {
                Yii::$app->session->setFlash('truck_notice_saved', 'Заявка успешно подтверждена');
                return $this->redirect(Yii::$app->request->referrer);
            }
        }

        $notification = Notification::findOne(['object_id'=>$id, 'type'=>'truck']);
        if ($notification) {
            $notification->status = 1;
            $notification->save(false);
        }

        return $this->render('truck/view', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'truck' => $truck,
            'products' => $products
        ]);
    }

    // control
    public function actionControl() {
        $searchModel = new NoticeControlSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->with('noticeTruck', 'noticeTruck.noticeWaybill', 'noticeTruck.noticeWaybill.provider');

        $dataProvider->setSort([
            'defaultOrder' => [
                'id' => 'desc'
            ]
        ]);

        return $this->render('control/index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionControlCreate($id = null) {
        $model = new NoticeControl;

        if ($id) {
            $model = NoticeControl::find()->with('noticeControlProducts')->where(['id'=>$id])->one();
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->saveObject()) {
                Yii::$app->session->setFlash('control_saved', 'Входной контроль успешно сохранен');
                return $this->redirect(Yii::$app->request->referrer);
            }
        }

        return $this->render('control/create', [
            'model' => $model
        ]);
    }

    public function actionControlView($id) {
        $model = NoticeControl::find()->with('noticeTruck', 'noticeTruck.noticeWaybill', 'noticeTruck.noticeWaybill.provider')->where(['id'=>$id])->one();

        $searchModel = new NoticeControlProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->with('product', 'unit')->andWhere(['notice_control_id' => $id]);

        $dataProvider->setSort([
            'defaultOrder' => [
                'id' => 'desc'
            ]
        ]);

        $products = NoticeControlProduct::find()->with('product')->where(['notice_control_id'=>$id])->all();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->saveObject()) {
                Yii::$app->session->setFlash('control_notice_accepted', 'Данные успешно сохранены');
                return $this->redirect(Yii::$app->request->referrer);
            }
        }

        $notification = Notification::findOne(['object_id'=>$id, 'type'=>'control']);
        if ($notification) {
            $notification->status = 1;
            $notification->save(false);
        }

        return $this->render('control/view', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'products' => $products
        ]);
    }

    public function actionControlAccept($id) {
        $model = NoticeControl::findOne($id);
        if ($model) {
            if ($model->saveObject()) {
                Yii::$app->session->setFlash('control_notice_accepted', 'Контроль качества подтвержден');
            }
        }

        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionControlAcceptProduct($id) {
        $model = NoticeControlProduct::findOne($id);
        if ($model) {
            $model->status = 1;
            if ($model->save(false)) {
                Yii::$app->session->setFlash('control_notice_product_accepted', 'Контроль качества подтвержден');
            }
        }

        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionControlDeclineProduct($id) {
        $model = NoticeControlProduct::findOne($id);
        if ($model) {
            $model->status = 0;
            if ($model->save(false)) {
                Yii::$app->session->setFlash('control_notice_product_declined', 'Контроль качества продукта отклонен');
            }
        }

        return $this->redirect(Yii::$app->request->referrer);
    }

    // act
    public function actionAct() {
        $searchModel = new NoticeActSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->with('noticeControl', 'noticeControl.noticeTruck', 'noticeControl.noticeTruck.noticeWaybill', 'noticeControl.noticeTruck.noticeWaybill.provider');

        $dataProvider->setSort([
            'defaultOrder' => [
                'id' => 'desc'
            ]
        ]);

        return $this->render('act/index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionActCreate($id = null) {
        $model = new NoticeAct;

        if ($id) {
            $model = NoticeAct::find()->with('noticeActProducts')->where(['id'=>$id])->one();
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->saveObject()) {
                Yii::$app->session->setFlash('act_saved', 'Акт приемки успешно сохранен');
                return $this->redirect(Yii::$app->request->referrer);
            }
        }

        return $this->render('act/create', [
            'model' => $model
        ]);
    }

    public function actionActView($id) {
        $model = NoticeAct::find()->with('noticeControl', 'noticeControl.noticeTruck', 'noticeControl.noticeTruck.noticeWaybill', 'noticeControl.noticeTruck.noticeWaybill.provider')->where(['id'=>$id])->one();

        $searchModel = new NoticeActProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->with('product', 'unit')->andWhere(['notice_act_id' => $id])->andWhere(['status'=>1]);

        $dataProvider->setSort([
            'defaultOrder' => [
                'id' => 'desc'
            ]
        ]);

        $notification = Notification::findOne(['object_id'=>$id, 'type'=>'act']);
        if ($notification) {
            $notification->status = 1;
            $notification->save(false);
        }

        return $this->render('act/view', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionActAccept($id) {
        $model = NoticeAct::find()->with('noticeActProducts')->where(['id'=>$id])->one();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->saveObject()) {
                Yii::$app->session->setFlash('act_saved', 'Акт приемки успешно сохранен');
                return $this->redirect(['/worker/notice/act-view', 'id'=>$model->id]);
            }
        }

        $providers = ArrayHelper::map(Category::find()->where(['type'=>'provider'])->all(), 'id', 'name_ru');
        $products = ArrayHelper::map(Product::find()->all(), 'id', 'name_ru');
        $articles = ArrayHelper::map(Product::find()->all(), 'id', 'article');
        $stocks = ArrayHelper::map(Stock::find()->all(), 'id', 'name_ru');
        $stacks = ArrayHelper::map(Stack::find()->all(), 'id', 'stack_number');
        $shelvings = ArrayHelper::map(StackShelving::find()->all(), 'id', 'shelf_number');
        $units = ArrayHelper::map(Category::find()->where(['type'=>'unit'])->all(), 'id', 'name_ru');

        return $this->render('act/create', [
            'model' => $model,
            'providers' => $providers,
            'products' => $products,
            'articles' => $articles,
            'stocks' => $stocks,
            'stacks' => $stacks,
            'shelvings' => $shelvings,
            'units' => $units
        ]);
    }
}