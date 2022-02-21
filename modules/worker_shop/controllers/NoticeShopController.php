<?php
namespace app\modules\worker_shop\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\ArrayHelper;
use yii\web\HttpException;
use yii\web\UploadedFile;

use app\models\notice_shop_stock\NoticeShopStock;
use app\models\notice_shop_stock\NoticeShopStockSearch;
use app\models\notice_shop_stock\NoticeShopStockProduct;
use app\models\notice_shop_stock\NoticeShopStockProductSearch;

use app\models\user\User;
use app\models\product\Product;
use app\models\Category;
use app\models\Notification;

class NoticeShopController extends Controller{
	public $user;

    public function beforeAction($action) {
        $this->enableCsrfValidation = false;
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/worker_shop']);
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

            if (!in_array('notice-shop', $accesses)) {
                throw new HttpException(403, 'В доступе отказано');
            }
        }

        return parent::beforeAction($action);
    }

    public function actionIndex() {
        $searchModel = new NoticeShopStockSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $dataProvider->setSort([
            'defaultOrder' => [
                'id' => 'desc'
            ]
        ]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionCreate($id = null) {
        $model = new NoticeShopStock;

        if ($id) {
            $model = NoticeShopStock::find()->with('noticeShopStockProducts')->where(['id'=>$id])->one();
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->saveObject()) {
                Yii::$app->session->setFlash('notice_shop_saved', 'Заявка успешно отправлена');
                return $this->redirect(['/worker_shop/notice-shop/view', 'id'=>$model->id]);
            }
        }

        $products = ArrayHelper::map(Product::find()->all(), 'id', 'name_ru');
        $articles = ArrayHelper::map(Product::find()->all(), 'id', 'article');
        $units = ArrayHelper::map(Category::find()->where(['type'=>'unit'])->all(), 'id', 'name_ru');

        return $this->render('create', [
            'model' => $model,
            'products' => $products,
            'articles' => $articles,
            'units' => $units
        ]);
    }

    public function actionView($id) {
        $model = NoticeShopStock::find()->where(['id'=>$id, 'user_id'=>$this->user->id])->one();

        $searchModel = new NoticeShopStockProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->with('product')->andWhere(['notice_shop_stock_id' => $id]);

        $dataProvider->setSort([
            'defaultOrder' => [
                'id' => 'desc'
            ]
        ]);

        return $this->render('view', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionRemove($id) {
        $model = NoticeShopStock::findOne($id);

        if ($this->user && ($this->user->role != User::ROLE_USER) && $model && $model->delete()) {
            Yii::$app->session->setFlash('notice_shop_removed', 'Заявка успешно удалена');
        }
        return $this->redirect(['/worker_shop/notice-shop']);
    }
}