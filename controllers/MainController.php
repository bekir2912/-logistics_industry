<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;

class MainController extends Controller {
    public function actionIndex() {
        return $this->redirect(['/admin']);
    }

    public function actionLogOut() {
    	Yii::$app->user->logout();
        return $this->goHome();
    }
}
