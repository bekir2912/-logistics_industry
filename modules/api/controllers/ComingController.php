<?php
namespace app\modules\api\controllers;

use Yii;
use yii\web\Response;
use yii\rest\Controller;
use yii\web\HttpException;
use yii\data\ActiveDataProvider;
use yii\filters\auth\HttpBearerAuth;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

use app\models\Category;
use app\models\coming\Coming;
use app\models\coming\ComingProduct;

class ComingController extends Controller {
    public $user;

    public function beforeAction($action) {
        $this->enableCsrfValidation = false;

        if (Yii::$app->request->headers->has('OPTIONS')) {
            throw new HttpException(200, 'OK');
        }

        if (!Yii::$app->user->isGuest) {
            $this->user = Yii::$app->user->identity;
        }

        return parent::beforeAction($action);
    }

    public function behaviors() {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::className(),
            'optional' => ['*']
        ];
        return $behaviors;
    }

    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'data',
    ];

    public function actionIndex($stock_id = null) {
        $query = Coming::find();

        if ($stock_id) {
            $query->where(['stock_id'=>$stock_id]);
        }

        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
            'sort' => ['defaultOrder' => ['id'=>'desc']]
        ]);
    }

    public function actionView($id) {
        $model = Coming::findOne($id);
        return ['data'=>$model];
    }

    public function actionHistory($stock_id) {
        $comings = ArrayHelper::map(Coming::find()->where(['stock_id'=>$stock_id])->all(), 'id', 'id');

        $query = ComingProduct::find()->where(['in', 'coming_id', $comings]);

        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
            'sort' => ['defaultOrder' => ['id'=>'desc']]
        ]);
    }

    public function actionCreate() {
        $post = Yii::$app->request->post();

        $model = array_key_exists('coming_id', $post) ? Coming::findOne($post['coming_id']) : new Coming;
        $model->setAttributes($post);

        if (!$model->validate()) {
            Yii::$app->response->statusCode = 422;
            return ['errors'=>$model->errors];
        }

        if ($model->saveObject()) {
            return ['data' => $model];
        }
    }
}
?>