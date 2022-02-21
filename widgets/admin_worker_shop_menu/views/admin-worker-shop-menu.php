<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

use app\models\user\User;
?>

<div class="box box-info color-palette-box">
    <div class="box-body">
    	<img src="<?=$model->getPhoto('250x250');?>" width="100%" class="img-thumbnail"/>
        <ul class="company-left-menu">
            <li><a href="<?=Yii::$app->urlManager->createUrl(['/admin/worker-shop/create', 'id'=>$model->id])?>" class="btn btn-primary width-full">Редактировать</a></li>
            <li><a href="<?=Yii::$app->urlManager->createUrl(['/admin/worker-shop/remove', 'id'=>$model->id])?>" class="btn btn-danger width-full remove-object">Удалить</a></li>
        </ul>
    </div>
</div>