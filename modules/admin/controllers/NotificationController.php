<?php
namespace app\modules\admin\controllers;

use Yii;
use yii\web\Controller;

use yii\web\Response;
use yii\data\Pagination;
use yii\filters\auth\HttpBearerAuth;
use yii\web\HttpException;

use app\models\user\User;
use app\models\Notification;

class NotificationController extends Controller{
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

            if (!in_array('notification', $accesses)) {
                throw new HttpException(403, 'В доступе отказано');
            }
        }

        return parent::beforeAction($action);
    }

	public function actionIndex(){
	    $model = new Notification;

	    $alerts = $model->find()->where(['status_admin'=>0]);

	    $count = $alerts->count();
	    $pagination = new Pagination([
            'defaultPageSize' => 20,
            'totalCount' => $count
        ]);

        $alerts = $alerts->orderBy('id desc')->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

	    return $this->render('index', [
	        'model'=>$model,
	        'alerts'=>$alerts,
	        'count'=>$count,
	        'pagination'=>$pagination
	    ]);
	}

	public function actionRemove($id){
		$model = Notification::findOne($id);

		if (!$model) {
			throw new HttpException(422, 'Переданны не все параметры');
		}
		
		if ($model->delete()) {
			Yii::$app->session->setFlash('alert_deleted', 'Уведомление успешно удалено');
		}

		return $this->redirect(Yii::$app->request->referrer);
	}

	public function actionGetAlert(){
	    if ($this->user && Yii::$app->request->isAjax && ($data = Yii::$app->request->post())) {
	        $info = Notification::find()->count();
	        
	        if ($info == 0) {
	        	$info = '';
	        }

	        Yii::$app->response->format = Response::FORMAT_JSON;
	        return ['info'=>$info];
	    }
	}
}