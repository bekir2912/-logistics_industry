<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\widgets\LinkPager;
use yii\bootstrap\ActiveForm;

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
        <?php if (Yii::$app->session->hasFlash('shipment_removed')) {?>
            <div class="alert alert-success text-center">
                <?=Yii::$app->session->getFlash('shipment_removed');?>
            </div>
        <?php }?>
        <div class="box">
            <div class="box-header">
                <div class="box-title">
                    <a href="<?=Yii::$app->urlManager->createUrl(['/worker/shipment/create'])?>" class="btn btn-primary">
                        <i class="fa fa-plus"></i>
                        Добавить отгрузку
                    </a>
                </div>
                <div id="action-links" style="display:none">
                    <a href="javascript:;" class="btn btn-danger" data-value="remove"><i class="fa fa-trash"></i> Удалить</a>
                    <a href="javascript:;" class="btn btn-warning" data-value="disable"><i class="fa fa-lock"></i> Заблокировать</a>
                    <a href="javascript:;" class="btn btn-success" data-value="enable"><i class="fa fa-unlock"></i> Разблокировать</a>
                </div>
            </div>
            <div class="box-body" id="item-block">
                
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
                            [
                                'attribute'=>'id',
                                'label'=>'<i class="fa fa-sort"></i> ID',
                                'encodeLabel' => false,
                                'contentOptions' => [
                                    'style' => 'width:70px'
                                ],
                            ],
                            [
                                'attribute'=>'agent_id',
                                'label'=>'<i class="fa fa-sort"></i> Агент',
                                'encodeLabel' => false,
                                'format' => 'html',
                                'value' => function ($model, $key, $index, $column) {
                                    return $model->agent ? $model->agent->name : '-';
                                }
                            ],
                            [
                                'attribute'=>'type_id',
                                'label'=>'<i class="fa fa-sort"></i> Тип отгрузки',
                                'encodeLabel' => false,
                                'format' => 'html',
                                'value' => function ($model, $key, $index, $column) {
                                    return $model->shipmentType ? $model->shipmentType->name_ru : '-';
                                }
                            ],
                            [
                                'attribute'=>'fio',
                                'label'=>'<i class="fa fa-sort"></i> Ф.И.О',
                                'encodeLabel' => false,
                            ],
                            [
                                'attribute'=>'date_shipment',
                                'label'=>'<i class="fa fa-sort"></i> Дата отгрузки',
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
                                        return '<small class="label label-warning">В ожидании</small>';
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
                                                    <li><a href="'.Yii::$app->urlManager->createUrl(['/worker/shipment/view', 'id'=>$model->id]).'" class="dropdown-item">Посмотреть</a></li>
                                                    <li><a href="'.Yii::$app->urlManager->createUrl(['/worker/shipment/create', 'id'=>$model->id]).'" class="dropdown-item">Редактировать</a></li>
                                                    <li><a href="'.Yii::$app->urlManager->createUrl(['/worker/shipment/remove', 'id'=>$model->id]).'" class="dropdown-item" class="remove-object">Удалить</a></li>
                                                </ul>';
                                    }
                                ],
                            ]
                        ],
                    ]); ?>
                
            </div>
        </div>
    </section>
</div>  