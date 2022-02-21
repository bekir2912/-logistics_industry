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

use app\models\noticeto\NoticetoSms;

class SmsController extends Controller {
    public $secret_key = '602217796e4bd';
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

    public function actionIndex($key) {
        if ($key != $this->secret_key) {
            throw new HttpException(403, 'В доступе отказано');
        }

        $xml = simplexml_load_string(file_get_contents("php://input"));

        $msisdn = (string)$xml->message->attributes()->msisdn;
        $content = (string)$xml->message->content;

        $model = NoticetoSms::findOne(['phone'=>$msisdn, 'type'=>2, 'answer'=>null]);
        if (!$model) {
            $model = NoticetoSms::findOne(['phone'=>$msisdn, 'type'=>3, 'answer'=>null]);
        }

        if ($model) {
            $model->answer = $content;
            $model->save(false);
        }

        throw new HttpException(200, 'OK');
    }
}
?>