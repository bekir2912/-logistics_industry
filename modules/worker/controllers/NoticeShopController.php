<?php
namespace app\modules\worker\controllers;

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

    public function actionView($id) {
        $model = NoticeShopStock::find()->where(['id'=>$id])->one();

        $searchModel = new NoticeShopStockProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->with('product')->andWhere(['notice_shop_stock_id' => $id]);

        $dataProvider->setSort([
            'defaultOrder' => [
                'id' => 'desc'
            ]
        ]);

        $notification = Notification::findOne(['object_id'=>$id, 'type'=>'shop_stock']);
        if ($notification) {
            $notification->status = 1;
            $notification->save(false);
        }

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
    
    public function actionAccept($id) {
        $model = NoticeShopStock::findOne($id);

        if ($model) {
            $model->status = 1;
            if ($model->save(false)) {
                Yii::$app->session->setFlash('notice_shop_accepted', 'Заявка успешно подтверждена');
            }
        }

        return $this->redirect(Yii::$app->request->referrer);
    }
}