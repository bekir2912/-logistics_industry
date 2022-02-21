<?php
namespace app\modules\worker_shop\controllers;

use Yii;
use yii\web\Controller;
use yii\data\Pagination;

use app\models\user\User;
use app\models\Words;

class TranslateController extends Controller{
	public $user;
    
    public function beforeAction($action) {
        $this->enableCsrfValidation = false;
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/worker_shop/default']);
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

            if (!in_array('words', $accesses)) {
                throw new HttpException(403, 'В доступе отказано');
            }
        }

        return parent::beforeAction($action);
    }

    public function actionIndex() {
        $model = new Words;

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->saveWord('update')) {
                Yii::$app->session->setFlash('word_saved', 'Перевод успешно сохранен');
            }
            return $this->redirect(Yii::$app->request->referrer);
        }

        $words = $model->find()->all();

        return $this->render('index', [
            'model' => $model,
            'words' => $words
        ]);
    }

    public function actionCreate() {
        $model = new Words;

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->saveWord()) {
                Yii::$app->session->setFlash('word_saved', 'Перевод успешно сохранен');
                return $this->redirect(['/worker_shop/translate']);
            }
        }

        return $this->render('create', [
            'model' => $model
        ]);
    }

    public function actionRemove($id) {
        $model = Words::findOne($id);

        if ($this->user && ($this->user->role != User::ROLE_USER) && $model && $model->delete()) {
            Yii::$app->session->setFlash('word_removed', 'Перевод успешно удален');
        }

        return $this->redirect(Yii::$app->request->referrer);
    }
}