<?php
namespace app\modules\worker\controllers;

use Yii;
use yii\web\Controller;
use yii\web\HttpException;
use yii\helpers\ArrayHelper;
use yii\web\Response;

use app\models\user\User;
use app\models\user\UserSearch;
use app\models\Category;
use app\models\Images;

class UserController extends Controller {
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

            if (!in_array('user', $accesses)) {
                return $this->redirect(['/worker/default/profile']);
            }
        }

        return parent::beforeAction($action);
    }

    public function actionIndex() {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->with('image')->andWhere(['role'=>User::ROLE_USER])->andWhere(['!=', 'status', 3]);

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
        $model = User::findOne($id);

        if (!$model) {
            throw new HttpException(404, 'Page not found');
        }

        if ($model->status == 0) {
            $model->status = 2;
            $model->token = '';
            $msg = 'Пользователь успешно заблокирован';
        } else {
            $model->status = 0;
            $msg = 'Пользователь успешно разблокирован';
        }

        if ($model->save(false)) {
            Yii::$app->session->setFlash('locked', $msg);
        }

        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionCreate() {
        $model = new User;
        $model->scenario = User::SIGNUP_ADMIN_USER;

        if ($id = Yii::$app->request->get('id')) {
            if ($model = User::find()->with('image')->where(['id'=>$id])->one()) {
                $model->scenario = User::UPDATE_ADMIN_USER;
                $current_password = $model->password;
            } else {
                $model = new User;
                $model->scenario = User::SIGNUP_ADMIN_USER;
            }
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->password = !$model->password ? $current_password : $model->generatePassword($model->password);
            if ($model->saveUser(User::ROLE_USER, 1)) {
                Yii::$app->session->setFlash('user_saved', 'Пользователь успешно сохранен');
            }
            return $this->redirect(['/worker/user/view', 'id'=>$model->id]);
        }
        
        return $this->render('create', [
            'model'=>$model
        ]);
    }

    public function actionView($id) {
        $model = User::find()->with('image', 'photos')->where(['id'=>$id])->one();

        return $this->render('view', [
            'model'=>$model
        ]);
    }

    public function actionRemove($id) {
        $model = User::find()->with('image', 'photos')->where(['id'=>$id])->one();
        
        if ($this->user && ($this->user->role != User::ROLE_USER) && $model && $model->removeUser()) {
            Yii::$app->session->setFlash('user_removed', 'Пользователь успешно удален');
        }

        return $this->redirect(['/worker/user']);
    }

    public function actionSetStyle() {
        if (Yii::$app->request->isAjax && ($data = Yii::$app->request->post())) {
            $user = User::findOne($this->user->id);
            $user->admin_style = $data['val'];
            $user->save(false);

            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['user'=>$user];
        }
    }
}