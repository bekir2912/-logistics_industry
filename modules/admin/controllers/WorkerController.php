<?php
namespace app\modules\admin\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\ArrayHelper;

use app\models\user\User;
use app\models\user\UserSearch;
use app\models\Category;

class WorkerController extends Controller{
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
        $dataProvider->query->with('image')->andWhere(['role'=>User::ROLE_WORKER]);

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
                $msg = 'Сотрудник успешно заблокирован';
            }else{
                $model->status = 0;
                $msg = 'Сотрудник успешно разблокирован';
            }
            if ($model->save(false)) {
                Yii::$app->session->setFlash('locked', $msg);
            }
            return $this->redirect(Yii::$app->request->referrer);
        }
    }

    public function actionCreate() {
        $model = new User;
        $model->scenario = User::SIGNUP_WORKER;

        if ($id = Yii::$app->request->get('id')) {
            if ($model = User::find()->with('image')->where(['id'=>$id])->one()) {
                $model->scenario = User::UPDATE_WORKER;
                $current_password = $model->password;
            } else {
                $model = new User;
                $model->scenario = User::SIGNUP_WORKER;
            }
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->password = !$model->password ? $current_password : $model->generatePassword($model->password);
            if ($model->saveUser(User::ROLE_WORKER, 1)) {
                Yii::$app->session->setFlash('worker_save', 'Сотрудник успешно сохранен');
            }
            return $this->redirect(['/admin/worker/view', 'id'=>$model->id]);
        }

        return $this->render('create', [
            'model' => $model
        ]);
    }

    public function actionView($id) {
        $model = User::find()->with('image')->where(['id'=>$id])->one();

        return $this->render('view', [
            'model' => $model
        ]);
    }

    public function actionRemove($id) {
        $model = User::find()->with('image')->where(['id'=>$id])->one();

        if ($this->user && ($this->user->role != User::ROLE_USER) && $model && $model->removeUser()) {
            Yii::$app->session->setFlash('worker_removed', 'Сотрудник успешно удален');
        }

        return $this->redirect(['/admin/worker']);
    }
}