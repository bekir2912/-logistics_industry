<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;



$this->title = 'Дефектная продукция';
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

        <?php if (Yii::$app->session->hasFlash('alldefect_removed')) {?>
            <div class="alert alert-success text-center"><?=Yii::$app->session->getFlash('alldefect_removed');?></div>
        <?php }?>
        <?php if (Yii::$app->session->hasFlash('empty_deffect')) {?>
            <div class="alert alert-success text-center"><?=Yii::$app->session->getFlash('empty_deffect');?></div>
        <?php }?>
        <?php if (Yii::$app->session->hasFlash('alldefect_warning')) {?>
            <div class="alert alert-warning text-center"><?=Yii::$app->session->getFlash('alldefect_warning');?></div>
        <?php }?>
        <div class="box box-info color-palette-box">
                <div class="pull-right" style="padding: 10px 15px 0 10px; " >
                    <a href="<?=Yii::$app->urlManager->createUrl(['/admin/industry/all-deffect/']);?>" class="btn btn-primary">Сбросить фильтры</a>
                </div>
            <!--            <div class="box-header">-->
<!--                <div class="pull-right">-->
<!--                    <a href="--><?//=Yii::$app->urlManager->createUrl(['/admin/industry/all-deffect/create'])?><!--" class="btn btn-primary"><i class="fa fa-plus"></i> Добавить дефект</a>-->
<!--                </div>-->
<!--            </div>-->
            <div class="box-body">
                <div class="grid-table">
                    <div class="box-body" id="item-block">

                        <div style="overflow-x: auto; width: 100%;">
                        <?= GridView::widget([
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'summary' => "Страница {begin} - {end} из {totalCount} дефектов<br/><br/>",
                            'emptyText' => 'Дефектов нет',
                            'rowOptions'   => function ($model, $index, $widget, $grid) {
                                return [
                                    'id' => $model['id'],
                                    'onclick' => 'location.href="'
                                        . Yii::$app->urlManager->createUrl('admin/industry/all-deffect/view')
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
//                                [
//                                    'class' => 'yii\grid\CheckboxColumn'
//                                ],
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
                                    'filter' => $departmnets_filter
                                ],
                                [
                                    'attribute'=>'model_id',
                                    'label'=>'<i class="fa fa-sort"></i> Модель',
                                    'encodeLabel' => false,
                                    'contentOptions' => [
                                        'style' => 'width:250px'
                                    ],
                                    'value' => function ($model, $key, $index, $column) {
                                        return $model->model ? $model->model->name_ru : '-';
                                    },
                                    'filter' => $models_filter
                                ],
                                [
                                    'attribute'=>'number_poddon',
                                    'label'=>'<i class="fa fa-sort"></i> Номер поддона',
                                    'encodeLabel' => false,
                                ],
//                                [
//                                    'attribute'=>'current_operation',
//                                    'label'=>'<i class="fa fa-sort"></i> Операция',
//                                    'encodeLabel' => false,
//                                ],
                                [
                                    'attribute'=>'user_id',
                                    'label'=>'<i class="fa fa-sort"></i> Пользователь',
                                    'encodeLabel' => false,
                                    'contentOptions' => [
                                        'style' => 'width:100px, color: red;'
                                    ],
                                    'value' => function ($model, $key, $index, $column) {
                                        return $model->user  ? $model->user->name : '-';
                                    },
                                ],
                                [
                                    'attribute'=>'deffect_id',
                                    'label'=>'<i class="fa fa-sort"></i> Дефект',
                                    'encodeLabel' => false,
                                    'contentOptions' => [
                                        'style' => 'width:100px'
                                    ],
                                    'value' => function ($model, $key, $index, $column) {
                                        return $model->deffect  ? $model->deffect ->name_ru : '-';
                                    },
                                ],
//                                [
//                                    'attribute'=>'detail_id',
//                                    'label'=>'<i class="fa fa-sort"></i> Деталь',
//                                    'encodeLabel' => false,
//                                    'contentOptions' => [
//                                        'style' => 'width:100px'
//                                    ],
//                                    'value' => function ($model, $key, $index, $column) {
//                                        return $model->detail  ? $model->detail ->name_ru : '-';
//                                    },
//                                ],

//                                [
//                                    'attribute'=>'detail_id',
//                                    'label'=>'<i class="fa fa-sort"></i> Пользователь',
//                                    'encodeLabel' => false,
//                                    'contentOptions' => [
//                                        'style' => 'width:100px'
//                                    ],
//                                    'value' => function ($model, $key, $index, $column) {
//                                        return $model->user  ? $model->user ->name : '-';
//                                    },
//                                ],
                                [
                                    'attribute'=>'count_deffect',
                                    'label'=>'<i class="fa fa-sort"></i> Кол-во',
                                    'encodeLabel' => false,
                                    'value' => function ($model, $key, $index, $column) {
                                        return $model->count_deffect ? ($model->count_deffect . ' ' . $model->unit->name_ru ): '-';
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
                                        if ($model->status == 1) {
                                            return '<small class="label label-warning">Активный</small>';
                                        } else {
                                            return '<small class="label label-success">Закрыт</small>';
                                        }
                                    },
                                    'filter' =>     [ '0' => 'Закрыт', '1' => 'Активный' ]
                                ],
                                [
                                    'attribute'=>'is_save',
                                    'label'=>'<i class="fa fa-sort"></i> Переработка',
                                    'encodeLabel' => false,
                                    'format' => 'html',
                                    'contentOptions' => [
                                        'style' => 'width:100px'
                                    ],
                                    'value' => function ($model, $key, $index, $column) {
                                        if ($model->is_save == 1) {
                                            return '<small class="label label-success">Возможно</small>';
                                        } else {
                                            return '<small class="label label-danger">Невозможно</small>';
                                        }
                                    },
                                    'filter' =>     [ '0' => 'Невозможно', '1' => 'Возможно' ]

                                ],

                                [
                                    'attribute'=>'created_at',
                                    'label'=>'<i class="fa fa-sort"></i> Дата создания',
                                    'encodeLabel' => false,
                                    'value' => function ($model, $key, $index, $column) {
                                        return date('d.m.Y H:i',$model->created_at);
                                    },
                                ],
//                                [
//                                    'attribute'=>'updated_at',
//                                    'label'=>'<i class="fa fa-sort"></i> Дата изменения',
//                                    'encodeLabel' => false,
//                                    'value' => function ($model, $key, $index, $column) {
//                                        return   date('d.m.Y H:i',$model->updated_at);
//                                    },
//                                ],

                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'template' => '{update} {delete}',
                                    'contentOptions' => [
                                        'style' => 'width:150px'
                                    ],
                                    'buttons' => [
                                        'update' => function ($url, $model) use ($words) {
                                            return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Yii::$app->urlManager->createUrl(['admin/industry/all-deffect/create', 'id'=>$model->id, 'dep_name'=>$model->department_id, 'id_department'=>$model->id]), ['class'=>'btn btn-primary']);
                                        },
                                        'delete' => function ($url, $model) use ($words) {
                                            return Html::a('<span class="glyphicon glyphicon-trash"></span>', Yii::$app->urlManager->createUrl(['admin/industry/all-deffect/remove', 'id'=>$model->id]), ['class'=>'btn btn-danger remove-object']);
                                        }
                                    ],
                                ]
                            ],
                        ]); ?>
                        </div>
                    </div>
<!--                    --><?//=debug($model->dep_id);?>
                </div>
            </div>
        </div>
    </section>
</div>