<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

use app\models\user\User;
?>

<?php if (($user->role == User::ROLE_ADMIN) || ($user->role == User::ROLE_MODERATOR)) {?>
    <div class="box box-info color-palette-box">
        <div class="box-body">
            <div class="text-center">
                <a href="<?=Yii::$app->urlManager->createUrl(['/admin/shipment/view', 'id'=>$model->id])?>">
                    <img class="profile-user-img img-responsive" src="/assets_files/img/delivery-truck.png" width="">
                </a>
            </div>
            <ul class="company-left-menu">
                <li><a href="<?=Yii::$app->urlManager->createUrl(['/admin/shipment/create', 'id'=>$model->id])?>" class="btn btn-primary width-full">Редактировать</a></li>
                <li><a href="<?=Yii::$app->urlManager->createUrl(['/admin/shipment/remove', 'id'=>$model->id])?>" class="btn btn-danger width-full remove-object">Удалить</a></li>
            </ul>
        </div>
    </div>
<?php }?>
