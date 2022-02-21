<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

use app\models\user\User;
?>

<?php if (($user->role == User::ROLE_ADMIN) || ($user->role == User::ROLE_MODERATOR)) {?>
    <div class="box box-info color-palette-box">
        <div class="box-body">
            <div class="text-center">
                <a href="<?=Yii::$app->urlManager->createUrl(['/admin/report/'])?>">
                    <img class="" src="/assets_files/img/report.png" width="50%">
                </a>
            </div>
            <ul class="company-left-menu">
                <li><a href="<?=Yii::$app->urlManager->createUrl(['/admin/report/detail-income'])?>" class="btn btn-<?=($action == 'detail-income') ? 'danger' : 'primary';?> width-full">Поступившие детали на склад</a></li>
                <li><a href="<?=Yii::$app->urlManager->createUrl(['/admin/report/detail-defect'])?>" class="btn btn-<?=($action == 'detail-defect') ? 'danger' : 'primary';?> width-full">Дефектные детали</a></li>
                <li><a href="<?=Yii::$app->urlManager->createUrl(['/admin/report/detail-shop'])?>" class="btn btn-<?=($action == 'detail-shop') ? 'danger' : 'primary';?> width-full">Отданные детали в магазин</a></li>
                <li><a href="<?=Yii::$app->urlManager->createUrl(['/admin/report/detail-line'])?>" class="btn btn-<?=($action == 'detail-line') ? 'danger' : 'primary';?> width-full">Отданные детали с магазина</a></li>
                <li><a href="<?=Yii::$app->urlManager->createUrl(['/admin/report/detail-gp-get'])?>" class="btn btn-<?=($action == 'detail-gp-get') ? 'danger' : 'primary';?> width-full">Принятая продукция в ГП</a></li>
                <li><a href="<?=Yii::$app->urlManager->createUrl(['/admin/report/detail-gp-send'])?>" class="btn btn-<?=($action == 'detail-gp-send') ? 'danger' : 'primary';?> width-full">Отданная продукция с ГП</a></li>
            </ul>
        </div>
    </div>
<?php }?>