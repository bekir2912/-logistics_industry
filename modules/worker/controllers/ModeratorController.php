<?php
namespace app\modules\worker\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\ArrayHelper;

use app\models\user\User;
use app\models\user\UserSearch;
use app\models\moderator\ModeratorUrl;
use app\models\moderator\ModeratorAccess;
use app\models\Category;
use app\models\log\Log;

class ModeratorController extends Controller{
    public $user;

    public function beforeAction($action) {
        if (!Yii::$app->user->isGuest) {
            $this->user = Yii::$app->user->identity;
            if ($this->user->role == User::ROLE_USER) {
                return $this->redirect(['/']);
            }
        } else {
            return $this->redirect(['/']);
        }
        return parent::beforeAction($action);
    }

    public function actionIndex() {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->with('image')->andWhere(['role'=>User::ROLE_MODERATOR]);

        $dataProvider->setSort([
            'defaultOrder' => [
                'id' => 'desc'
            ]
        ]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionLock($id) {
        if ($model = User::findOne($id)) {
            if($model->status == 0){
                $model->status = 2;
                $msg = 'Модератор успешно заблокирован';
            }else{
                $model->status = 0;
                $msg = 'Модератор успешно разблокирован';
            }
            if ($model->save(false)) {
                Yii::$app->session->setFlash('locked', $msg);
            }
            return $this->redirect(Yii::$app->request->referrer);
        }
    }

    public function actionCreate() {
        $model = new User;
        $model->scenario = User::SIGNUP_MODERATOR;

        if ($id = Yii::$app->request->get('id')) {
            if ($model = User::find()->with('image')->where(['id'=>$id])->one()) {
                $model->scenario = User::UPDATE_MODERATOR;
                $current_password = $model->password;
            } else {
                $model = new User;
                $model->scenario = User::SIGNUP_MODERATOR;
            }
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->password = !$model->password ? $current_password : $model->generatePassword($model->password);
            if ($model->saveUser(User::ROLE_MODERATOR, 1)) {
                Yii::$app->session->setFlash('moderator_save', 'Модератор успешно сохранен');
            }
            return $this->redirect(['/worker/moderator/view', 'id'=>$model->id]);
        }

        $urls = ModeratorUrl::find()->with('moderatorAccessUser')->all();

        $regions = ArrayHelper::map(Category::find()->where(['type'=>'region'])->all(), 'id', 'name_ru');

        return $this->render('create', [
            'model' => $model,
            'urls' => $urls,
            'regions' => $regions
        ]);
    }

    public function actionView($id) {
        $model = User::find()->with('image')->where(['id'=>$id])->one();

        $urls = ModeratorAccess::find()->with('moderator')->where(['user_id'=>$id])->all();

        return $this->render('view', [
            'model' => $model,
            'urls' => $urls
        ]);
    }

    public function actionRemove($id) {
        $model = User::find()->with('image')->where(['id'=>$id])->one();
        if ($this->user && ($this->user->role != User::ROLE_USER) && $model && $model->removeUser()) {
            Yii::$app->session->setFlash('moderator_removed', 'Модератор успешно удален');
        }

        return $this->redirect(['/worker/moderator']);
    }
}