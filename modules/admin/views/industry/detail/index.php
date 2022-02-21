<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;


$this->title = 'Баланс Деталей';
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
        <?php if (Yii::$app->session->hasFlash('detail_removed')) {?>
            <div class="callout callout-success text-center">
                <?=Yii::$app->session->getFlash('detail_removed');?>
            </div>
        <?php }?>
        <?php if (Yii::$app->session->hasFlash('shipment_saved')) {?>
            <div class="callout callout-success text-center">
                <?=Yii::$app->session->getFlash('shipment_saved');?>
            </div>
        <?php }?>
        <div class="box box-info color-palette-box">

            <div class="row">
                <div class="col-md-12">

                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#ru" aria-controls="ru" role="tab" data-toggle="tab">Баланс деталей</a></li>
                        <li role="presentation"  ><a href="#uz" aria-controls="uz" role="tab" data-toggle="tab">Сумма деталей</a></li>
                        <li role="presentation"  ><a href="#en" aria-controls="en" role="tab" data-toggle="tab">Все заявки</a></li>
                    </ul>

                </div>
            </div>



            <div class="box-header with-border">
                <div class="box-title pull-right" style="font-size: 14px">
<!--                    --><?php //if (!Yii::$app->request->get('type')) {?>
<!--                        <a href="--><?//=Yii::$app->urlManager->createUrl(['/admin/industry/detail/create'])?><!--" class="btn btn-primary">-->
<!--                            <i class="fa fa-pencil"></i>-->
<!--                            Добавить деталь-->
<!--                        </a>-->
<!--                    --><?php //}?>


                    <?php if (!Yii::$app->request->get('type')) {?>
                        <a href="<?=Yii::$app->urlManager->createUrl(['/admin/shipment/create'])?>" class="btn btn-success">
                            <i class="fa fa-plus"></i>
                            Создать заявку
                        </a>
                    <?php }?>
                </div>
                <div id="action-links" style="display:none">
                    <a href="javascript:;" class="btn btn-danger" data-value="remove"><i class="fa fa-trash"></i> Удалить</a>
                    <a href="javascript:;" class="btn btn-warning" data-value="disable"><i class="fa fa-lock"></i> Заблокировать</a>
                    <a href="javascript:;" class="btn btn-success" data-value="enable"><i class="fa fa-unlock"></i> Разблокировать</a>
                </div>
            </div>
            <div class="box-body" id="item-block">


                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="ru">

                        <div style="overflow-x: auto; width: 100%;">
                            <?= GridView::widget([
                                'dataProvider' => $dataProvider,
                                'filterModel' => $searchModel,
                                'summary' => "Страница {begin} - {end} из {totalCount} деталей<br/><br/>",
                                'emptyText' => 'Деталей нет',
                                'rowOptions'   => function ($model, $index, $widget, $grid) {
                                    return [
                                        'id' => $model['id'],
                                        'onclick' => 'location.href="'
                                            . Yii::$app->urlManager->createUrl('admin/industry/detail/view')
                                            . '?id="+(this.id);'
                                    ];
                                },
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
                                        'attribute'=>'department_id',
                                        'label'=>'<i class="fa fa-sort"></i> Отдел',
                                        'encodeLabel' => false,
                                        'contentOptions' => [
                                            'style' => 'width:100px'
                                        ],
                                        'value' => function ($model, $key, $index, $column) {
                                            return $model->department ? $model->department->name_ru : '-';
                                        },
                                    ],
                                    [
                                        'attribute'=>'product_id',
                                        'label'=>'<i class="fa fa-sort"></i> Продукт',
                                        'encodeLabel' => false,
                                        'contentOptions' => [
                                            'style' => 'width:100px'
                                        ],
                                        'value' => function ($model, $key, $index, $column) {
                                            return $model->product ? $model->product->name_ru : '-';
                                        },
                                    ],
                                    [
                                        'attribute'=>'name_ru',
                                        'label'=>'<i class="fa fa-sort"></i> Название',
                                        'encodeLabel' => false,

                                    ],
                                    [
                                        'attribute'=>'count',
                                        'label'=>'<i class="fa fa-sort"></i> Кол-во',
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
                                        'attribute'=>'created_at',
                                        'label'=>'<i class="fa fa-sort"></i> Дата создания',
                                        'encodeLabel' => false,
                                        'value' => function ($model, $key, $index, $column) {
                                            return date('d.m.Y H:i',$model->created_at);
                                        },
                                    ],



                                    [
                                        'class' => 'yii\grid\ActionColumn',
                                        'template' => '{update} {delete}',
                                        'contentOptions' => [
                                            'style' => 'width:150px'
                                        ],
                                        'buttons' => [
                                            'update' => function ($url, $model) use ($words) {
                                                return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Yii::$app->urlManager->createUrl(['admin/industry/detail/create', 'id'=>$model->id]), ['class'=>'btn btn-primary']);
                                            },
                                            'delete' => function ($url, $model) use ($words) {
                                                return Html::a('<span class="glyphicon glyphicon-trash"></span>', Yii::$app->urlManager->createUrl(['admin/industry/detail/remove', 'id'=>$model->id]), ['class'=>'btn btn-danger remove-object']);
                                            }
                                        ],
                                    ]
                                ],
                            ]); ?>

                        </div>

                    </div>
                    <div role="tabpanel" class="tab-pane " id="uz">

                        <div style="overflow-x: auto; width: 100%;">
                            <?= GridView::widget([
                                'dataProvider' => $dataSumDetailProvider,
                                'filterModel' => $searchSumDetailModel,
                                'summary' => "Страница {begin} - {end} из {totalCount} деталей<br/><br/>",
                                'emptyText' => 'Деталей нет',
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
                                        'attribute'=>'department_id',
                                        'label'=>'<i class="fa fa-sort"></i> Отдел',
                                        'encodeLabel' => false,
                                        'contentOptions' => [
                                            'style' => 'width:100px'
                                        ],
                                        'value' => function ($model, $key, $index, $column) {
                                            return $model->department ? $model->department->name_ru : '-';
                                        },
                                    ],
                                    [
                                        'attribute'=>'product_id',
                                        'label'=>'<i class="fa fa-sort"></i> Продукт',
                                        'encodeLabel' => false,
                                        'contentOptions' => [
                                            'style' => 'width:100px'
                                        ],
                                        'value' => function ($model, $key, $index, $column) {
                                            return $model->product ? $model->product->name_ru : '-';
                                        },
                                    ],
                                    [
                                        'attribute'=>'name_ru',
                                        'label'=>'<i class="fa fa-sort"></i> Название',
                                        'encodeLabel' => false,

                                    ],
                                    [
                                        'attribute'=>'count',
                                        'label'=>'<i class="fa fa-sort"></i> Кол-во',
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
                                                return '<small class="label label-warning">Ожидание</small>';
                                            }
                                        },
                                    ],

                                    [
                                        'attribute'=>'created_at',
                                        'label'=>'<i class="fa fa-sort"></i> Дата создания',
                                        'encodeLabel' => false,
                                        'value' => function ($model, $key, $index, $column) {
                                            return date('d.m.Y H:i',$model->created_at);
                                        },
                                    ],



//                                    [
//                                        'class' => 'yii\grid\ActionColumn',
//                                        'template' => '{view}',
//                                        'buttons' => [
//                                            'view' => function ($url, $model) {
//                                                return '<div class="btn-group"><button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
//                                                    <span class="fa fa-cog"></span>
//                                                </button>
//                                                <ul class="dropdown-menu pull-left">
//                                                    <li><a href="'.Yii::$app->urlManager->createUrl(['/admin/industry/detail/view', 'id'=>$model->id]).'" class="dropdown-item">Посмотреть</a></li>
//                                                    <li><a href="'.Yii::$app->urlManager->createUrl(['/admin/industry/detail/create', 'id'=>$model->id]).'" class="dropdown-item">Редактировать</a></li>
//                                                    <li><a href="'.Yii::$app->urlManager->createUrl(['/admin/industry/detail/remove', 'id'=>$model->id]).'" class="dropdown-item" class="remove-object">Удалить</a></li>
//                                                </ul>';
//                                            }
//                                        ],
//                                    ]
                                ],
                            ]); ?>

                        </div>


                        <br>
                    </div>

                    <div role="tabpanel" class="tab-pane" id="en">

                        <div style="overflow-x: auto; width: 100%;">
                            <?= GridView::widget([
                                'dataProvider' => $dataShipmentProvider,
                                'filterModel' => $searchShipmentModel,
                                'summary' => "Страница {begin} - {end} из {totalCount} деталей<br/><br/>",
                                'emptyText' => 'Деталей нет',
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
                                'rowOptions'   => function ($model, $index, $widget, $grid) {
                                    return [
                                        'id' => $model['id'],
                                        'onclick' => 'location.href="'
                                            . Yii::$app->urlManager->createUrl('admin/shipment/view')
                                            . '?id="+(this.id);'
                                    ];
                                },
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
                                        'attribute'=>'department_id',
                                        'label'=>'<i class="fa fa-sort"></i> Отдел',
                                        'encodeLabel' => false,
                                        'contentOptions' => [
                                            'style' => 'width:100px'
                                        ],
                                        'value' => function ($model, $key, $index, $column) {
                                            return $model->department ? $model->department->name_ru : '-';
                                        },
                                    ],

                                    [
                                        'attribute'=>'fio',
                                        'label'=>'<i class="fa fa-sort"></i> ФИО',
                                        'encodeLabel' => false,
                                    ],
                                    [
                                        'attribute'=>'product_id',
                                        'label'=>'<i class="fa fa-sort"></i> Продукт',
                                        'encodeLabel' => false,
                                        'contentOptions' => [
                                            'style' => 'width:200px'
                                        ],
                                        'value' => function ($model, $key, $index, $column) {
                                            $products = '';
                                            foreach ($model->shipmentProducts as $product){
                                                $products = $products . " " . $product->product->name_ru . ' ';
                                            }
                                            return $model->shipmentProducts ? $products : '-';
                                        },
                                    ],
                                    [
                                        'attribute'=>'count',
                                        'label'=>'<i class="fa fa-sort"></i> Кол-во',
                                        'encodeLabel' => false,
                                         'value' => function ($model, $key, $index, $column) {
                                            $sum =  '' ;
                                            foreach ($model->shipmentProducts as $counts){
                                                $sum = $sum . ' ' . $counts->amount;
                                            }
                                            return $sum ? $sum : '-';
                                        },
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
                                            if ($model->status == 0) {
                                                return '<small class="label label-warning">Не подтвержден</small>';
                                            }if ($model->status == 1) {
                                                return '<small class="label label-success">Подтвержден</small>';
                                            }else{
                                                return '<small class="label label-danger">Отменен</small>';
                                            }
                                        },
                                    ],

                                    [
                                        'attribute'=>'date',
                                        'label'=>'<i class="fa fa-sort"></i> Дата',
                                        'encodeLabel' => false,
                                        'contentOptions' => [
                                            'style' => 'width:200px'
                                        ],
                                    ],



                                    [
                                        'class' => 'yii\grid\ActionColumn',
                                        'template' => '{update} {delete}',
                                        'contentOptions' => [
                                            'style' => 'width:150px'
                                        ],
                                        'buttons' => [
                                            'update' => function ($url, $model) use ($words) {
                                                return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Yii::$app->urlManager->createUrl(['admin/shipment/create', 'id'=>$model->id]), ['class'=>'btn btn-primary']);
                                            },
                                            'delete' => function ($url, $model) use ($words) {
                                                return Html::a('<span class="glyphicon glyphicon-trash"></span>', Yii::$app->urlManager->createUrl(['admin/shipment/remove', 'id'=>$model->id]), ['class'=>'btn btn-danger remove-object']);
                                            }
                                        ],
                                    ]
                                ],
                            ]); ?>

                        </div>

                    </div>



                </div>



            </div>
        </div>
    </section>
</div>
