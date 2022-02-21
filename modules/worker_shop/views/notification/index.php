<?php
use yii\helpers\Html;
use yii\widgets\LinkPager;

$this->title = 'Уведомления';
$this->params['breadcrumbs'][] = $this->title;
?>

<div id="title-breadcrumb-option-demo" class="page-title-breadcrumb">
    <div class="page-header pull-left">
        <div class="page-title"><?=$this->title;?> <strong><?=$count?></strong></div>
    </div>
    <ol class="breadcrumb page-breadcrumb pull-right">
        <li><i class="fa fa-home"></i> <a href="<?=Yii::$app->urlManager->createUrl(['/account'])?>">Личный кабинет</a> <i class="fa fa-angle-right"></i></li>
        <li class="hidden"><a href="#"><?=$this->title?></a> <i class="fa fa-angle-right"></i></li>
        <li class="active"><?=$this->title?></li>
    </ol>
    <div class="clearfix"></div>
</div>

<div class="page-content">
    <div class="row">
        <div class="col-lg-12">
            <?php if(Yii::$app->session->hasFlash('alert_deleted')){?>
                <div class="alert alert-success text-center alert-bottom"><?=Yii::$app->session->getFlash('alert_deleted');?></div>
            <?php }?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <?php echo Html::encode($this->title);?> <?php if($count && ($count > 0)){?><strong><?php echo $count?></strong><?php }?>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-xs-12">
                            <?php if ($alerts) {?>
                                <?php foreach ($alerts as $key => $alert) {?>
                                    <div class="alert alert-warning" style="margin-bottom:10px">
                                        <a href="<?=Yii::$app->urlManager->createUrl(['/admin/notification/remove', 'id'=>$alert->id])?>" class="pull-right remove-object"><i class="glyphicon glyphicon-trash"></i> Удалить</a>
                                        <?php if ($alert->type == 'order') {?>
                                            <strong>Новый заказ</strong>
                                            <br/>
                                            <a href="<?=Yii::$app->urlManager->createUrl(['/admin/pharmacy/view', 'id'=>$alert->order->pharmacy->id])?>"><strong>Аптека: <?=$alert->order->pharmacy->name ? $alert->order->pharmacy->name : '';?></strong></a><br/>
                                            Имя: <?=$alert->order->name ? $alert->order->name : '';?><br/>
                                            Телефон: <?=$alert->order->contacts ? $alert->order->contacts : '';?>
                                            <hr/>
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <span style="font-size:12px"><i class="glyphicon glyphicon-time"></i> <?=$alert->date;?></span>
                                                </div>
                                                <div class="col-sm-6 text-right">
                                                    <a href="<?=Yii::$app->urlManager->createUrl(['/admin/pharmacy/order-view', 'id'=>$alert->order->pharmacy_id, 'order_id'=>$alert->order->id])?>" class="btn btn-info pull-right">Посмотреть заказ</a>
                                                </div>
                                            </div>
                                        <?php }?>
                                    </div>
                                <?php }?>
                                <?php if ($pagination) {?>
                                    <?=LinkPager::widget(['pagination'=>$pagination]);?>
                                <?php }?>
                            <?php } else {?>
                                <div class="alert alert-warning text-center">Уведомлений нет</div>
                            <?php }?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>