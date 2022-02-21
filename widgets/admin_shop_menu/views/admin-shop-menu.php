<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

use app\models\user\User;
?>

<?php if (($user->role == User::ROLE_ADMIN) || ($user->role == User::ROLE_MODERATOR)) {?>
    <div class="box box-info color-palette-box">
        <div class="box-body">
            <div class="text-center">
                <a href="<?=Yii::$app->urlManager->createUrl(['/admin/shop/'])?>">
                    <img class="" src="/admin_files/img/store.png" width="50%">
                </a>
            </div>
            <ul class="company-left-menu">
                <li><a href="<?=Yii::$app->urlManager->createUrl(['/admin/shop/'])?>" class="btn btn-primary width-full">Магазин</a></li>
                <li><a href="<?=Yii::$app->urlManager->createUrl(['/admin/shop/shipment'])?>" class="btn btn-primary width-full">Заявки <?php if ($shipments > 0) {?><span class="label label-danger"><?=$shipments;?></span><?php }?></a></li>
                <li><a href="<?=Yii::$app->urlManager->createUrl(['/admin/shop/stacks'])?>" class="btn btn-primary width-full">Стелажи</a></li>
                <li><a href="<?=Yii::$app->urlManager->createUrl(['/admin/shop/products'])?>" class="btn btn-primary width-full">Продукция</a></li>
                <li><a href="<?=Yii::$app->urlManager->createUrl(['/admin/shop/create'])?>" class="btn btn-primary width-full">Редактировать</a></li>
            </ul>
        </div>
    </div>
<?php }?>

<?php if ($user->role == User::ROLE_WORKER) {?>
    <div class="box box-info color-palette-box">
        <div class="box-body">
            <div class="text-center">
                <a href="<?=Yii::$app->urlManager->createUrl(['/worker/shop/'])?>">
                    <img class="" src="/admin_files/img/store.png" width="50%">
                </a>
            </div>
            <ul class="company-left-menu">
                <li><a href="<?=Yii::$app->urlManager->createUrl(['/worker/shop/stacks'])?>" class="btn btn-primary width-full">Стелажи</a></li>
                <li><a href="<?=Yii::$app->urlManager->createUrl(['/worker/shop/products'])?>" class="btn btn-primary width-full">Товары</a></li>
            </ul>
        </div>
    </div>
<?php }?>

<?php if ($user->role == User::ROLE_WORKER_SHOP) {?>
    <div class="box box-info color-palette-box">
        <div class="box-body">
            <div class="text-center">
                <a href="<?=Yii::$app->urlManager->createUrl(['/worker_shop/shop/'])?>">
                    <img class="" src="/admin_files/img/store.png" width="50%">
                </a>
            </div>
            <ul class="company-left-menu">
                <li><a href="<?=Yii::$app->urlManager->createUrl(['/worker_shop/shop/stacks'])?>" class="btn btn-primary width-full">Стелажи</a></li>
                <li><a href="<?=Yii::$app->urlManager->createUrl(['/worker_shop/shop/products'])?>" class="btn btn-primary width-full">Товары</a></li>
            </ul>
        </div>
    </div>
<?php }?>