<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

use app\models\user\User;
?>

<?php if (($user->role == User::ROLE_ADMIN) || ($user->role == User::ROLE_MODERATOR)) {?>
    <div class="box box-info color-palette-box">
        <div class="box-body">
            <div class="text-center">
                <a href="<?=Yii::$app->urlManager->createUrl(['/admin/stock/view', 'id'=>$model->id])?>">
                    <img class="" src="/admin_files/img/storage.png" width="50%">
                </a>
            </div>
            <ul class="company-left-menu">
                <li><a href="<?=Yii::$app->urlManager->createUrl(['/admin/stock/stacks', 'id'=>$model->id])?>" class="btn btn-primary width-full">Этажи</a></li>
                <li><a href="<?=Yii::$app->urlManager->createUrl(['/admin/stock/products', 'id'=>$model->id])?>" class="btn btn-primary width-full">Продукция</a></li>
                <li><a href="<?=Yii::$app->urlManager->createUrl(['/admin/stock/create', 'id'=>$model->id])?>" class="btn btn-primary width-full">Редактировать</a></li>
                <li><a href="<?=Yii::$app->urlManager->createUrl(['/admin/stock/remove', 'id'=>$model->id])?>" class="btn btn-danger width-full remove-object">Удалить</a></li>
            </ul>
        </div>
    </div>
<?php }?>

<?php if ($user->role == User::ROLE_WORKER) {?>
    <div class="box box-info color-palette-box">
        <div class="box-body">
            <div class="text-center">
                <a href="<?=Yii::$app->urlManager->createUrl(['/worker/stock/view', 'id'=>$model->id])?>">
                    <img class="" src="/admin_files/img/storage.png" width="50%">
                </a>
            </div>
            <ul class="company-left-menu">
                <li><a href="<?=Yii::$app->urlManager->createUrl(['/worker/stock/stacks', 'id'=>$model->id])?>" class="btn btn-primary width-full">Стелажи</a></li>
                <li><a href="<?=Yii::$app->urlManager->createUrl(['/worker/stock/products', 'id'=>$model->id])?>" class="btn btn-primary width-full">Товары</a></li>
            </ul>
        </div>
    </div>
<?php }?>