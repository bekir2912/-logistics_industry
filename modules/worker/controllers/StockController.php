<?php
namespace app\modules\worker\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\ArrayHelper;
use yii\web\HttpException;

use app\models\user\User;
use app\models\stock\Stock;
use app\models\stock\StockSearch;
use app\models\stack\Stack;
use app\models\stack\StackSearch;
use app\models\product\Product;
use app\models\product\ProductSearch;
use app\models\Category;
use app\models\Settings;

class StockController extends Controller{
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

            if (!in_array('stock', $accesses)) {
                throw new HttpException(403, 'В доступе отказано');
            }
        }

        return parent::beforeAction($action);
    }

    public function actionIndex() {
        $searchModel = new StockSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->with('user');

        $dataProvider->setSort([
            'defaultOrder' => [
                'id' => 'desc'
            ]
        ]);

        $stocks = Stock::find()->with('products')->all();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'stocks' => $stocks
        ]);
    }

    public function actionCreate($id = null) {
        $model = new Stock;
        $model->scenario = Stock::SIGN_UP;

        if ($id) {
            $model = Stock::find()->with('user')->where(['id'=>$id])->one();
            $model->scenario = Stock::UPDATE;
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $user = new User;
            if ($model->user) {
                $user = User::findOne($model->user_id);
            }
            $user->status = 1;
            $user->role = User::ROLE_STOCK;
            $user->login = $model->login;
            if ($model->password) {
                $user->password = Yii::$app->security->generatePasswordHash($model->password);
            }

            if ($user->save()) {
                $model->status = 1;
                $model->user_id = $user->id;
                if ($model->save()) {
                    Yii::$app->session->setFlash('stock_saved', 'Склад успешно сохранен');
                    return $this->redirect(['/worker/stock/view', 'id'=>$model->id]);
                }
            }
        }

        return $this->render('create', [
            'model' => $model
        ]);
    }

    public function actionView($id) {
        $model = Stock::findOne($id);

        return $this->render('view', [
            'model' => $model
        ]);
    }

    public function actionRemove($id) {
        $model = Stock::findOne($id);

        if ($this->user && ($this->user->role != User::ROLE_USER) && $model && $model->delete()) {
            Yii::$app->session->setFlash('stock_removed', 'Склад успешно удален');
        }
        return $this->redirect(['/worker/stock']);
    }

    // stacks
    public function actionStacks($id) {
        $searchModel = new StackSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andWhere(['stock_id'=>$id]);

        $dataProvider->setSort([
            'defaultOrder' => [
                'id' => 'desc'
            ]
        ]);

        $stacks = Stack::find()->where(['stock_id'=>$id])->all();

        return $this->render('stack/index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'stacks' => $stacks
        ]);
    }

    public function actionStackCreate($id, $stack_id = null) {
        $model = new Stack;

        if ($stack_id) {
            $model = Stack::find()->with('stackShelvings')->where(['stock_id'=>$id, 'id'=>$stack_id])->one();
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->stock_id = $id;
            if ($model->saveObject()) {
                Yii::$app->session->setFlash('stack_saved', 'Стелаж успешно сохранен');
                return $this->redirect(['/worker/stock/stack-view', 'id'=>$model->stock_id, 'stack_id'=>$model->id]);
            }
        }

        return $this->render('stack/create', [
            'model' => $model
        ]);
    }

    public function actionStackView($id, $stack_id) {
        $searchModel = new ProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andWhere(['stack_id' => $stack_id]);

        $model = Stack::find()->with('stackShelvings')->where(['stock_id'=>$id, 'id'=>$stack_id])->one();

        return $this->render('stack/view', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionStackRemove($id, $stack_id) {
        $model = Stack::find()->where(['id'=>$stack_id, 'stock_id'=>$id])->one();

        if ($this->user && ($this->user->role != User::ROLE_USER) && $model && $model->delete()) {
            Yii::$app->session->setFlash('stack_removed', 'Стелаж успешно удален');
        }
        return $this->redirect(['/stock/stock/stacks', 'id'=>$id]);
    }

    public function actionProducts($id = null) {
        $searchModel = new ProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andWhere(['stock_id' => $id]);

        $stock = null;

        if ($id) {
            $stock = Stock::findOne($id);
        }

        $dataProvider->setSort([
            'defaultOrder' => [
                'id' => 'desc'
            ]
        ]);

        $total_price_usd = 0;
        $total_price_uzs = 0;
        $total_amount = 0;

        $products = Product::find()->where(['stock_id'=>$id])->all();
        $currency = Settings::findOne(['type'=>'currency']);

        if ($products) {
            foreach ($products as $product) {
                $total_price_usd += $product->price_sale * $product->amount;
                $total_price_uzs += ($product->price_sale * $product->amount) * (int)$currency->content;
                $total_amount += $product->amount;
            }
        }

        return $this->render('products', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'stock' => $stock,
            'total_price_usd' => $total_price_usd,
            'total_price_uzs' => $total_price_uzs,
            'total_amount' => $total_amount
        ]);
    }
}