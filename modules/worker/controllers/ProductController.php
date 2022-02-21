<?php
namespace app\modules\worker\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\ArrayHelper;
use yii\web\HttpException;
use yii\web\UploadedFile;
use yii\web\Response;

use app\models\user\User;
use app\models\stock\Stock;
use app\models\stack\Stack;
use app\models\stack\StackShelving;
use app\models\product\Product;
use app\models\product\ProductSearch;
use app\models\Category;

class ProductController extends Controller{
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

            if (!in_array('product', $accesses)) {
                throw new HttpException(403, 'В доступе отказано');
            }
        }

        return parent::beforeAction($action);
    }

    public function actionIndex($type = null) {
        $searchModel = new ProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if ($type == 'defect') {
            $dataProvider->query->with('image')->andWhere(['status'=>2]);
        } else {
            $dataProvider->query->with('image')->andWhere(['status'=>1]);
        }

        $dataProvider->setSort([
            'defaultOrder' => [
                'id' => 'desc'
            ]
        ]);

        $model = new Product;

        if ($model->load(Yii::$app->request->post()) && ($model->file = UploadedFile::getInstance($model, 'file'))) {
            if ($model->upload()) {
                Yii::$app->session->setFlash('uploaded', 'Медикаменты успешно загружены');
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
        $model = new Product;
        
        if ($id) {
            $model = Product::find()->with('image')->where(['id'=>$id])->one();
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->saveObject()) {
                Yii::$app->session->setFlash('product_saved', 'Товар успешно сохранен');
                return $this->redirect(['/worker/product/view', 'id'=>$model->id]);
            }
        }

        $regions = ArrayHelper::map(Category::find()->where(['type'=>'region'])->all(), 'id', 'name_ru');
        $units = ArrayHelper::map(Category::find()->where(['type'=>'unit'])->all(), 'id', 'name_ru');
        $manufacturers = ArrayHelper::map(Category::find()->where(['type'=>'manufacturer'])->all(), 'id', 'name_ru');
        $stocks = ArrayHelper::map(Stock::find()->all(), 'id', 'name_ru');
        $categories = ArrayHelper::map(Category::find()->where(['type'=>'category'])->all(), 'id', 'name_ru');

        return $this->render('create', [
            'model' => $model,
            'regions' => $regions,
            'units' => $units,
            'manufacturers' => $manufacturers,
            'stocks' => $stocks,
            'categories' => $categories
        ]);
    }

    public function actionView($id) {
        $model = Product::find()->with('region', 'unit', 'manufacturer')->where(['id'=>$id])->one();

        return $this->render('view', [
            'model' => $model
        ]);
    }

    public function actionRemove($id) {
        $model = Product::findOne($id);

        if ($this->user && ($this->user->role != User::ROLE_USER) && $model && $model->removeObject()) {
            Yii::$app->session->setFlash('product_removed', 'Товар успешно удален');
        }
        return $this->redirect(['/worker/product']);
    }

    public function actionSetDefect($id) {
        $model = Product::findOne($id);

        if ($model) {
            $model->status = 2;
            if ($model->save(false)) {
                Yii::$app->session->setFlash('defect_setted', 'Товар усешно перемещен в раздел дефектной продукции');
            }
        }

        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionUnsetDefect($id) {
        $model = Product::findOne($id);

        if ($model) {
            $model->status = 1;
            if ($model->save(false)) {
                Yii::$app->session->setFlash('defect_unsetted', 'Товар усешно возвращен из раздела дефектной продукции');
            }
        }

        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionGetStacks() {
        if (Yii::$app->request->isAjax && ($data = Yii::$app->request->post())) {
            $stacks = Stack::find()->where(['stock_id'=>$data['id']])->all();

            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['data'=>$stacks];
        }
    }

    public function actionGetShelvings() {
        if (Yii::$app->request->isAjax && ($data = Yii::$app->request->post())) {
            $shelfs = StackShelving::find()->where(['stack_id'=>$data['id']])->all();

            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['data'=>$shelfs];
        }
    }
}