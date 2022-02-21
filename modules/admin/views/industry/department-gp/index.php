<?php

use app\models\user\User;
use bsadnu\googlecharts\ColumnChart;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = 'ГП';
$this->params['breadcrumbs'][] = $this->title;


$bad_defects = \app\models\industry\AllDeffect::find()->where(['department_id' => 8])->andWhere(['is_save'=>0])->andWhere(['status'=>1])->sum('count_deffect');
$edit_defects = \app\models\industry\AllDeffect::find()->where(['department_id' => 8])->andWhere(['is_save'=>1])->andWhere(['status'=>1])->sum('count_deffect');
$close_defects = \app\models\industry\AllDeffect::find()->where(['department_id' => 8])->andWhere(['status'=>0])->sum('count_deffect');



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



        <?php if (Yii::$app->session->hasFlash('gp_removed')) {?>
            <div class="alert alert-success text-center"><?=Yii::$app->session->getFlash('gp_removed');?></div>
        <?php }?>
        <div class="box box-info color-palette-box">
            <div class="box-header">
                <div id="action-links" style="display:none">
                    <?php $form = ActiveForm::begin(['action' => Url::to(['industry/department-gp/multi']), 'options' => ['class' => 'busercheck__header _check_user']]); ?>
                    <div class="form-group">
                        <button type="submit" class="btn btn-danger"> <i class="fa fa-trash"></i> Удалить</button>
                    </div>
                    <!--                        <a href="javascript:;" class="btn btn-danger" data-value="remove"><i class="fa fa-trash"></i> Удалить</a>-->
                </div>
            </div>
<!--            <div class="row">-->
<!--                <div class="col-md-12">-->
<!---->
<!--                    <ul class="nav nav-tabs" role="tablist">-->
<!--                        <li role="presentation" class="active"><a href="#ru" aria-controls="ru" role="tab"-->
<!--                                                                  data-toggle="tab">Таблица</a></li>-->
<!--                        <li role="presentation"><a href="#uz" aria-controls="uz" role="tab" data-toggle="tab">Визуал</a>-->
<!--                        </li>-->
<!--                    </ul>-->
<!---->
<!---->
<!--                </div>-->
<!--            </div>-->


            <div class="box-body">
                <div class="grid-table">
                    <div class="box-body" id="item-block">


<!--                        <div class="tab-content">-->
<!--                            <div role="tabpanel" class="tab-pane active" id="ru">-->
                            <div class="box-body" id="item-block">
                                <div style="overflow-x: auto; width: 100%;">
                                    <?= GridView::widget([
                                        'dataProvider' => $dataProvider,
                                        'filterModel' => $searchModel,
                                        'summary' => "Страница {begin} - {end} из {totalCount} операций<br/><br/>",
                                        'emptyText' => 'Операций нет',
                                        'rowOptions'   => function ($model, $index, $widget, $grid) {
                                            return [
                                                'id' => $model['id'],
                                                'url' => Yii::$app->urlManager->createUrl('admin/industry/department-gp/view').'?id='.$model['id']
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
                                                'attribute'=>'number_poddon',
                                                'label'=>'<i class="fa fa-sort"></i> Номер поддона',
                                                'encodeLabel' => false,
                                            ],
                                            [
                                                'label'=>'<i class="fa fa-sort"></i> Артикул',
                                                'encodeLabel' => false,
                                                'contentOptions' => [
                                                    'style' => 'width:250px'
                                                ],
                                                'value' => function ($model, $key, $index, $column) {
                                                    return $model->model ? $model->model->articul : '-';
                                                },
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
                                                'attribute'=>'amount',
                                                'label'=>'<i class="fa fa-sort"></i> Кол-во в поддоне',
                                                'encodeLabel' => false,
                                            ],

                                            [
                                                'attribute'=>'user_id',
                                                'label'=>'<i class="fa fa-sort"></i> Пользователь',
                                                'encodeLabel' => false,
                                                'contentOptions' => [
                                                    'style' => 'width:200px'
                                                ],
                                                'value' => function ($model, $key, $index, $column) {
                                                    return $model->user ? $model->user->name : '-';
                                                },
                                            ],

//                                [
//                                    'attribute'=>'current_operation',
//                                    'label'=>'<i class="fa fa-sort"></i> Операция',
//                                    'encodeLabel' => false,
//                                    'contentOptions' => [
//                                        'style' => 'width:70px'
//                                    ],
//                                ],
//                                            [
//                                                'attribute'=>'article',
//                                                'label'=>'<i class="fa fa-sort"></i> Артикул',
//                                                'encodeLabel' => false,
//                                            ],
//                                            [
//                                                'attribute'=>'user_id',
//                                                'label'=>'<i class="fa fa-sort"></i> Пользователь',
//                                                'encodeLabel' => false,
//                                                'contentOptions' => [
//                                                    'style' => 'width:100px'
//                                                ],
//                                                'value' => function ($model, $key, $index, $column) {
//                                                    return $model->user ? $model->user->name : '-';
//                                                },
//                                            ],
//
//                                            [
//                                                'attribute'=>'amount',
//                                                'label'=>'<i class="fa fa-sort"></i> Кол-во в поддоне',
//                                                'encodeLabel' => false,
//                                                'value' => function ($model, $key, $index, $column) {
//                                                    return $model->amount ? ($model->amount . ' ' . $model->unit->name_ru ): '-';
//                                                },
//                                            ],
//
//                                            [
//                                                'attribute'=>'value_range',
//                                                'label'=>'<i class="fa fa-sort"></i> Процесс проверки',
//                                                'encodeLabel' => false,
//                                            ],

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
                                                        return '<small class="label label-success">Готов</small>';
                                                    }elseif ($model->status == 2){
                                                        return '<small class="label label-primary">Проверка</small>';
                                                    }elseif($model->status == 0) {
                                                        return '<small class="label label-warning">В ожидании </small>';
                                                    }elseif($model->status == 3) {
                                                        return '<small class="label label-success">На складе </small>';
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
                                                        if (Yii::$app->user->identity->is_edit == false  ) return false;
                                                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Yii::$app->urlManager->createUrl(['admin/industry/department-gp/create', 'id'=>$model->id]), ['class'=>'btn btn-primary']);
                                                    },
                                                    'delete' => function ($url, $model) use ($words) {
                                                        if (Yii::$app->user->identity->is_remove == false  ) return false;
                                                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', Yii::$app->urlManager->createUrl(['admin/industry/department-gp/remove', 'id'=>$model->id]), ['class'=>'btn btn-danger remove-object']);
                                                    }
                                                ],
                                            ]
                                        ],
                                    ]); ?>
                                    <?php ActiveForm::end(); ?>

                                </div>
                            </div>

<!--                            </div>-->

<!--                            <div role="tabpanel" class="tab-pane " id="uz">-->
<!--                                <div style="overflow-x: auto; width: 100%;">-->
<!--                                        --><?//= ColumnChart::widget([
//                                            'id' => 'my-column-trendlines-chart-id',
//                                            'data' => $result,
//                                            'options' => [
//                                                'fontName' => 'Verdana',
//                                                'height' => 450,
//                                                'width' => 1800,
//                                                'curveType' => 'function',
//                                                'fontSize' => 12,
//                                                'chartArea' => [
//                                                    'left' => 50,
//                                                    'width' => 1200,
//                                                    'height' => 350
//                                                ],
//                                                'hAxis' => [
//                                                    'format' => '#',
//                                                    'viewWindow' => [
//                                                        'min' => 0,
//                                                        'max' => 6
//                                                    ],
//                                                    'gridlines' => [
//                                                        'count' => 6
//                                                    ]
//                                                ],
//                                                'vAxis' => [
//                                                    'title' => 'Кол-во',
//                                                    'titleTextStyle' => [
//                                                        'fontSize' => 13,
//                                                        'italic' => true
//                                                    ],
//                                                    'gridlines' => [
//                                                        'color' => '#e5e5e5',
//                                                        'count' => 10
//                                                    ],
//                                                    'minValue' => 0
//                                                ],
//                                                'colors' => [
//                                                    '#25dbd2',
//                                                    '#FB8C00'
//                                                ],
//                                                'trendlines' => [
//                                                    0 => [
//                                                        'labelInLegend' => 'Bug line',
//                                                        'visibleInLegend' => true
//                                                    ],
//                                                    1 => [
//                                                        'labelInLegend' => 'Test line',
//                                                        'visibleInLegend' => true
//                                                    ]
//                                                ],
//                                                'legend' => [
//                                                    'position' => 'top',
//                                                    'alignment' => 'end',
//                                                    'textStyle' => [
//                                                        'fontSize' => 12
//                                                    ]
//                                                ]
//                                            ]
//                                        ]) ?>
<!--                                </div>-->
<!--                            </div>-->

<!--                        </div>-->




                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
