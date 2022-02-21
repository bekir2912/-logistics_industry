<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\widgets\LinkPager;
use yii\bootstrap\ActiveForm;

use app\widgets\admin_report_menu\AdminReportMenu;

$this->title = 'Отчетность';
$this->params['breadcrumbs'][] = $this->title;

?>


<div class="content-wrapper">
    <section class="content-header">
        <h1><?=$this->title;?></h1>
        
        <ol class="breadcrumb">
            <li><a href="<?=Yii::$app->urlManager->createUrl(['/admin/'])?>"><i class="fa fa-dashboard"></i> Главная</a></li>
            <li class="active"><?=$this->title;?></li>
        </ol>
    </section>
    <section class="content">

        <?php if (Yii::$app->session->hasFlash('report_removed')) {?>
            <div class="alert alert-warning text-center">
                <?=Yii::$app->session->getFlash('report_removed');?>
            </div>
        <?php }?>
<!--        <div class="row">-->
<!--            <div class="col-sm-3">-->
<!--                --><?//=AdminReportMenu::widget();?>
<!--            </div>-->
<!--            <div class="col-sm-9">-->
<!--                <div class="box">-->
<!--                    <div class="box-body" id="item-block">-->
<!--                        <div class="alert alert-info text-center">Выберите тип отчетности</div>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
    </section>
</div>  