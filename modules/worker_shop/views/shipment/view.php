<?php
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\widgets\LinkPager;

use xj\qrcode\QRcode;
use xj\qrcode\widgets\Text;

use app\widgets\admin_language_tab\AdminLanguageTab;
use app\widgets\admin_shipment_menu\AdminShipmentMenu;

$this->title = 'Отгрузка';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="content-wrapper">
    <section class="content-header">
        <h1><?=$this->title;?></h1>
        
        <ol class="breadcrumb">
            <li><a href="<?=Yii::$app->urlManager->createUrl(['/worker/'])?>"><i class="fa fa-dashboard"></i> Главная</a></li>
            <li class="active"><?=$this->title;?></li>
        </ol>
    </section>
    <section class="content">
        <?php if (Yii::$app->session->hasFlash('shipment_saved')) {?>
            <div class="alert alert-success text-center">
                <?=Yii::$app->session->getFlash('shipment_saved');?>
            </div>
        <?php }?>
        <?php if (Yii::$app->session->hasFlash('shipment_accepted')) {?>
            <div class="alert alert-success text-center">
                <?=Yii::$app->session->getFlash('shipment_accepted');?>
            </div>
        <?php }?>
        <div class="row">
            <div class="col-sm-3">
                <?=AdminShipmentMenu::widget();?>
            </div>
            <div class="col-sm-9">
                <?php if ($model) {?>
                    <div class="box">
                        <div class="box-header">
                            <?php if ($model->status == 0) {?>
                                <div class="pull-right">
                                    <a href="<?=Yii::$app->urlManager->createUrl(['/worker/shipment/confirmation', 'id'=>$model->id, 'type'=>'1']);?>" class="btn btn-success"><i class="fas fa-check"></i> Принять</a>
                                    <a href="<?=Yii::$app->urlManager->createUrl(['/worker/shipment/confirmation', 'id'=>$model->id, 'type'=>'2']);?>" class="btn btn-danger"><i class="fas fa-minus-circle"></i> Отклонить</a>
                                </div>
                            <?php }?>
                            <button type="button" class="btn btn-warning dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-expanded="true">
                                <span class="fas fa-cog"></span>
                            </button>
                            <div class="dropdown-menu" x-placement="bottom-start">
                                <a href="<?=Yii::$app->urlManager->createUrl(['/worker/shipment/create']);?>" class="dropdown-item">Добавить списание</a>
                                <a href="<?=Yii::$app->urlManager->createUrl(['/worker/shipment/create', 'id'=>$model->id]);?>" class="dropdown-item">Редактировать</a>
                                <a href="<?=Yii::$app->urlManager->createUrl(['/worker/shipment/remove', 'id'=>$model->id]);?>" class="dropdown-item" class="remove-object">Удалить</a>
                            </div>
                        </div>
                        <div class="box-body">
                            <table class="table table-striped">
                                <tr>
                                    <td>ID:</td>
                                    <td><?=$model->id ? $model->id : '-';?></td>
                                </tr>
                                <tr>
                                    <td>Контрагент:</td>
                                    <td><?=$model->agent ? $model->agent->name : '-';?></td>
                                </tr>
                                <tr>
                                    <td>Тип отгрузки:</td>
                                    <td><?=$model->shipmentType ? $model->shipmentType->name_ru : '-';?></td>
                                </tr>
                                <tr>
                                    <td>Ф.И.О:</td>
                                    <td><?=$model->fio ? $model->fio : '-';?></td>
                                </tr>
                                <tr>
                                    <td>Статус:</td>
                                    <td>
                                        <?php
                                            if ($model->status == 1) {
                                                echo '<small class="label label-success">Активный</small>';
                                            } else {
                                                echo '<small class="label label-warning">В ожидании</small>';
                                            }
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Дата отгрузки:</td>
                                    <td><?=$model->date_shipment ? $model->date_shipment : '-';?></td>
                                </tr>
                                <tr>
                                    <td>Примечание:</td>
                                    <td><?=$model->comment ? $model->comment : '-';?></td>
                                </tr>
                            </table>
                            <br/>
                        </div>
                    </div>
                    <div class="box">
                        <div class="box-header">
                            Товары
                        </div>
                        <div class="box-body">
                            
                                <?= GridView::widget([
                                    'dataProvider' => $dataProvider,
                                    'filterModel' => $searchModel,
                                    'summary' => "Страница {begin} - {end} из {totalCount} данных<br/><br/>",
                                    'emptyText' => 'Данных нет',
                                    'pager' => [
                                        'options'=>['class'=>'pagination'],
                                        'pageCssClass' => 'page-item',
                                        'prevPageLabel' => 'Назад',
                                        'nextPageLabel' => 'Вперед',
                                        'maxButtonCount'=>10,
                                        'linkOptions' => [
                                            'class' => 'page-link'
                                        ]
                                     ],
                                    'tableOptions' => [
                                        'class'=>'table table-striped'
                                    ],
                                    'columns' => [
                                        ['class' => 'yii\grid\SerialColumn'],
                                        [
                                            'class' => 'yii\grid\CheckboxColumn'
                                        ],
                                        // [
                                        //     'attribute'=>'qr',
                                        //     'label'=>'<i class="fa fa-sort"></i> QR',
                                        //     'encodeLabel' => false,
                                        //     'format' => 'html',
                                        //     'value' => function ($model, $key, $index, $column) {
                                        //         return Text::widget([
                                        //             'outputDir' => '@webroot/upload/qrcode',
                                        //             'outputDirWeb' => '@web/upload/qrcode',
                                        //             'ecLevel' => QRcode::QR_ECLEVEL_L,
                                        //             'text' => $model->qr,
                                        //             'size' => 2,
                                        //         ]);
                                        //     },
                                        // ],
                                        [
                                            'attribute'=>'id',
                                            'label'=>'<i class="fa fa-sort"></i> ID',
                                            'encodeLabel' => false,
                                            'contentOptions' => [
                                                'style' => 'width:70px'
                                            ],
                                        ],
                                        [
                                            'attribute'=>'product_id',
                                            'label'=>'<i class="fa fa-sort"></i> Товар',
                                            'encodeLabel' => false,
                                            'format' => 'html',
                                            'value' => function ($model, $key, $index, $column) {
                                                return $model->product ? '<a href="'.Yii::$app->urlManager->createUrl(['/worker/product/view', 'id'=>$model->product->id]).'">'.$model->product->name_ru.'</a>' : '-';
                                            },
                                        ],
                                        [
                                            'attribute'=>'amount',
                                            'label'=>'<i class="fa fa-sort"></i> Кол-во',
                                            'encodeLabel' => false,
                                        ],
                                        [
                                            'attribute'=>'article',
                                            'label'=>'<i class="fa fa-sort"></i> Артикул',
                                            'encodeLabel' => false,
                                        ],
                                        [
                                            'attribute'=>'status',
                                            'label'=>'<i class="fa fa-sort"></i> Статус',
                                            'encodeLabel' => false,
                                            'format' => 'html',
                                            'contentOptions' => [
                                                'style' => 'width:100px'
                                            ],
                                            'value' => function ($model, $key, $index, $column) {
                                                if ($model->status == 1) {
                                                    return '<small class="label label-success">Активный</small>';
                                                } else {
                                                    return '<small class="label label-danger">Заблокирован</small>';
                                                }
                                            },
                                        ],
                                        [
                                            'class' => 'yii\grid\ActionColumn',
                                            'template' => '{view}',
                                            'buttons' => [
                                                'view' => function ($url, $model) {
                                                    return '<div class="btn-group"><button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                                                                <span class="fa fa-cog"></span>
                                                            </button>
                                                            <ul class="dropdown-menu pull-left">
                                                                <li><a href="'.Yii::$app->urlManager->createUrl(['/worker/shipment/remove', 'id'=>$model->id]).'" class="dropdown-item" class="remove-object">Удалить</a></li>
                                                            </ul>';
                                                }
                                            ],
                                        ]
                                    ],
                                ]); ?>
                            
                        </div>
                    </div>
                <?php } else {?>
                    <div class="alert alert-warning text-center">Данных нет</div>
                <?php }?>
            </div>
        </div>
    </section>
</div>