<?php
namespace app\modules\admin\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\ArrayHelper;
use yii\web\HttpException;
use yii\web\UploadedFile;
use yii\web\Response;

use app\models\user\User;
use app\models\shop\Shop;
use app\models\shop\ShopStack;
use app\models\shop\ShopStackSearch;
use app\models\shop\ShopStackShelving;
use app\models\shop\ShopProduct;
use app\models\shop\ShopProductSearch;
use app\models\shipment\Shipment;
use app\models\shipment\ShipmentSearch;
use app\models\Settings;

class ShopController extends Controller{
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

            if (!in_array('shop', $accesses)) {
                throw new HttpException(403, 'В доступе отказано');
            }
        }

        return parent::beforeAction($action);
    }

    public function actionIndex() {
        $model = Shop::findOne(1);

        if (!$model) {
            return $this->redirect(['/admin/shop/create']);
        }

        return $this->render('index', [
            'model' => $model
        ]);
    }

    public function actionStacks() {
        $model = Shop::findOne(1);

        if (!$model) {
            return $this->redirect(['/admin/shop/create']);
        }

        $searchModel = new ShopStackSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->with('shopStackShelvings');

        $dataProvider->setSort([
            'defaultOrder' => [
                'id' => 'desc'
            ]
        ]);

        $stacks = ShopStack::find()->all();

        return $this->render('stack/index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'stacks' => $stacks
        ]);
    }

    public function actionCreate() {
        $model = Shop::findOne(1);

        if (!$model) {
            $model = new Shop;
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->save()) {
                Yii::$app->session->setFlash('shop_saved', 'Магазин успешно сохранен');
                return $this->redirect(['/admin/shop']);
            }
        }

        return $this->render('create', [
            'model' => $model
        ]);
    }

    public function actionStackCreate($stack_id = null) {
        $model = new ShopStack;

        if ($stack_id) {
            $model = ShopStack::find()->with('shopStackShelvings')->where(['id'=>$stack_id])->one();
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->shop_id = 1;
            if ($model->saveObject()) {
                Yii::$app->session->setFlash('shop_stack_saved', 'Стелаж успешно сохранен');
                return $this->redirect(['/admin/shop/stack-view', 'stack_id'=>$model->id]);
            }
        }

        return $this->render('stack/create', [
            'model' => $model
        ]);
    }

    public function actionStackView($stack_id) {
        $searchModel = new ShopProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andWhere(['shop_stack_id' => $stack_id]);

        $model = ShopStack::find()->with('shopStackShelvings')->where(['id'=>$stack_id])->one();

        return $this->render('stack/view', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionStackRemove($stack_id) {
        $model = Shop::findOne(1);

        if ($this->user && ($this->user->role != User::ROLE_USER) && $model && $model->delete()) {
            Yii::$app->session->setFlash('shop_stack_removed', 'Стелаж успешно удален');
        }
        return $this->redirect(['/admin/shop/stacks', 'id'=>$id]);
    }

    public function actionProducts() {
        $searchModel = new ShopProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $shop = Shop::findOne(1);

        $dataProvider->setSort([
            'defaultOrder' => [
                'id' => 'desc'
            ]
        ]);

        $total_amount = 0;

        $products = ShopProduct::find()->where(['shop_id'=>1])->all();
        $currency = Settings::findOne(['type'=>'currency']);

        if ($products) {
            foreach ($products as $product) {
                $total_amount += $product->amount;
            }
        }

        return $this->render('products', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'shop' => $shop,
            'total_amount' => $total_amount
        ]);
    }

    public function actionGetShelvings() {
        if (Yii::$app->request->isAjax && ($data = Yii::$app->request->post())) {
            $shelfs = ShopStackShelving::find()->where(['shop_stack_id'=>$data['id']])->all();

            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['data'=>$shelfs];
        }
    }

    public function actionShipment() {
        $searchModel = new ShipmentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        // $dataProvider->query->andWhere(['status'=>$model->id]);

        $dataProvider->setSort([
            'defaultOrder' => [
                'status' => 'desc'
            ]
        ]);

        return $this->render('shipment/index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $model
        ]);
    }
}