<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

use xj\qrcode\QRcode;
use xj\qrcode\widgets\Text;

use app\models\user\User;
?>

<?php if (($user->role == User::ROLE_ADMIN) || ($user->role == User::ROLE_MODERATOR)) {?>
    <div class="box box-info color-palette-box">
        <div class="box-body">
            <div class="text-center">
                <a href="<?=Yii::$app->urlManager->createUrl(['/admin/product/view', 'id'=>$model->id])?>">
                    <img class="profile-user-img img-responsive" src="<?=$model->getPhoto();?>" width="70%">
                </a>
            </div>
            <ul class="company-left-menu">
                <li><a href="<?=Yii::$app->urlManager->createUrl(['/admin/product/create', 'id'=>$model->id])?>" class="btn btn-primary width-full">Редактировать</a></li>
                <li><a href="<?=Yii::$app->urlManager->createUrl(['/admin/product/remove', 'id'=>$model->id])?>" class="btn btn-danger width-full remove-object">Удалить</a></li>
            </ul>
        </div>
    </div>
<?php }?>

<?php if ($user->role == User::ROLE_WORKER) {?>
    <div class="box box-info color-palette-box">
        <div class="box-body">
            <div class="text-center">
                <a href="<?=Yii::$app->urlManager->createUrl(['/worker/product/view', 'id'=>$model->id])?>">
                    <img class="profile-user-img img-responsive" src="<?=$model->getPhoto();?>" width="70%">
                </a>
            </div>
            <!-- <ul class="company-left-menu">
                <li><a href="<?=Yii::$app->urlManager->createUrl(['/worker/product/create', 'id'=>$model->id])?>" class="btn btn-primary width-full">Редактировать</a></li>
                <li><a href="<?=Yii::$app->urlManager->createUrl(['/worker/product/remove', 'id'=>$model->id])?>" class="btn btn-danger width-full remove-object">Удалить</a></li>
            </ul> -->
        </div>
    </div>
<?php }?>

<script>
    function CallPrint(strid){
        var prtContent = document.getElementById(strid);
        var WinPrint = window.open('','','left=50,top=50,width=800,height=640,toolbar=0,scrollbars=1,status=0');
        var map = document.getElementById('qr-print');
        WinPrint.document.write(map.innerHTML);
        WinPrint.document.close();
        WinPrint.focus();
        WinPrint.print();
        WinPrint.close();
        prtContent.innerHTML=strOldOne;
    }
</script>