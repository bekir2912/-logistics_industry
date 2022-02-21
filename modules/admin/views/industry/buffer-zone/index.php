<?php

use app\models\industry\handbook\BDepartment;use yii\helpers\Html;
use yii\grid\GridView;
use scotthuangzl\googlechart\GoogleChart;
use bsadnu\googlecharts\PieChart;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = 'Буферная зона';
$this->params['breadcrumbs'][] = $this->title;

    $not_all  = [
            1 => 'Штамповка',
            2 => 'Покраска',
            3 => 'Механическая сборка',
            4 => 'Тест на утечку',
            5 => 'Калибровка',
            6 => 'Электро-сборка',
            7 => 'Отливка пластиковых деталей',
            8 => 'Отливка пластиковых деталей',
            9 => 'Сборка газового регулятора',
            10 => 'Печать надписей на переднюю панель',
            11 => 'Формовка AUQ-G6',
            12 => 'Проверка узлов на утечку',
    ]
?>

<div class="content-wrapper">
    <section class="content-header">
        <h1><?= $this->title; ?></h1>

        <ol class="breadcrumb">
            <li><a href="<?= Yii::$app->urlManager->createUrl(['/admin/']) ?>"><i class="fa fa-dashboard"></i>
                    Главная</a></li>
            <li class="active"><?= $this->title; ?></li>
        </ol>
    </section>
    <section class="content">
        <?php if (Yii::$app->session->hasFlash('buffer_removed')) { ?>
            <div class="alert alert-success text-center"><?= Yii::$app->session->getFlash('buffer_removed'); ?></div>
        <?php } ?>
        <div class="box box-info color-palette-box">


            <div class="row">
                <div class="col-md-12">

                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#ru" aria-controls="ru" role="tab"
                                                                  data-toggle="tab">Таблица</a></li>
                        <li role="presentation"><a href="#uz" aria-controls="uz" role="tab" data-toggle="tab">Визуал</a>
                        </li>
                    </ul>

                </div>
            </div>
            <div class="box-header">
                <div id="action-links" style="display:none">
                    <?php $form = ActiveForm::begin(['action' => Url::to(['industry/buffer-zone/multi']), 'options' => ['class' => 'busercheck__header _check_user']]); ?>
                    <div class="form-group">
                        <button type="submit" class="btn btn-danger"> <i class="fa fa-trash"></i> Удалить</button>
                    </div>
                </div>
                <!--                <div class="pull-right">-->
                <!--                    <a href="-->
                <? //=Yii::$app->urlManager->createUrl(['/admin/industry/buffer-zone/create'])?><!--" class="btn btn-primary"><i class="fa fa-plus"></i> Добавить операцию</a>-->
                <!--                </div>-->
            </div>
            <div class="box-body">
                <div class="grid-table">
                    <div class="box-body" >

                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane active" id="ru">
                                <div class="box-body" id="item-block">
                                <div style="overflow-x: auto; width: 100%;">
                                    <?= GridView::widget([
                                        'dataProvider' => $dataProvider,
                                        'filterModel' => $searchModel,
                                        'summary' => "Страница {begin} - {end} из {totalCount} операций<br/><br/>",
                                        'emptyText' => 'Операций нет',
                                        'rowOptions' => function ($model, $index, $widget, $grid) {
                                            return [
                                                'id' => $model['id'],
                                                'url' => Yii::$app->urlManager->createUrl('admin/industry/buffer-zone/view').'?id='.$model['id']
                                            ];
                                        },
                                        'pager' => [
                                            'options' => ['class' => 'pagination'],
                                            'pageCssClass' => 'page-item',
                                            'prevPageLabel' => 'Назад',
                                            'nextPageLabel' => 'Вперед',
                                            'maxButtonCount' => 10,
                                            'linkOptions' => [
                                                'class' => 'page-link'
                                            ]
                                        ],
                                        'tableOptions' => [
                                            'class' => 'table table-striped'
                                        ],
                                        'columns' => [
                                            ['class' => 'yii\grid\SerialColumn'],
                                            [
                                                'class' => 'yii\grid\CheckboxColumn'
                                            ],
                                            [
                                                'attribute' => 'id',
                                                'label' => '<i class="fa fa-sort"></i> ID',
                                                'encodeLabel' => false,
                                                'contentOptions' => [
                                                    'style' => 'width:70px'
                                                ],
                                            ],
                                            [
                                                'attribute' => 'model_id',
                                                'label' => '<i class="fa fa-sort"></i> Модель',
                                                'encodeLabel' => false,
                                                'contentOptions' => [
                                                    'style' => 'width:100px'
                                                ],
                                                'value' => function ($model, $key, $index, $column) {
                                                    return $model->model ? $model->model->name_ru : '-';
                                                },
                                                'filter' => $models_filter
                                            ],

                                            [
                                                'attribute' => 'user_id',
                                                'label' => '<i class="fa fa-sort"></i> Пользователь',
                                                'encodeLabel' => false,
                                                'contentOptions' => [
                                                    'style' => 'width:100px'
                                                ],
                                                'value' => function ($model, $key, $index, $column) {
                                                    return $model->user ? $model->user->name : '-';
                                                },
                                            ],
//                                    [
//                                        'attribute'=>'current_operation',
//                                        'label'=>'<i class="fa fa-sort"></i> Операция',
//                                        'encodeLabel' => false,
//                                        'contentOptions' => [
//                                            'style' => 'width:70px'
//                                        ],
//                                    ],

                                            [
                                                'attribute' => 'number_poddon',
                                                'label' => '<i class="fa fa-sort"></i> Номер поддона',
                                                'encodeLabel' => false,
                                            ],


                                            [
                                                'attribute' => 'from_department_id',
                                                'label' => '<i class="fa fa-sort"></i> Откуда',
                                                'encodeLabel' => false,
                                                'contentOptions' => [
                                                    'style' => 'width:100px'
                                                ],
                                                'value' => function ($model, $key, $index, $column) {
                                                    return $model->fromDepartment ? $model->fromDepartment->name_ru : '-';
                                                },
                                                'filter' => $from_filter
                                            ],

                                            [
                                                'attribute' => 'to_department_id',
                                                'label' => '<i class="fa fa-sort"></i> Куда',
                                                'encodeLabel' => false,
                                                'contentOptions' => [
                                                    'style' => 'width:100px'
                                                ],
                                                'value' => function ($model, $key, $index, $column) {
                                                    return $model->to_department_id ? $model->toDepartment->name_ru : '-';
                                                },
                                                'filter' => $to_filter
                                            ],

                                            [
                                                'attribute' => 'amount',
                                                'label' => '<i class="fa fa-sort"></i> Кол-во',
                                                'encodeLabel' => false,
                                            ],

                                            [
                                                'attribute' => 'status',
                                                'label' => '<i class="fa fa-sort"></i> Статус',
                                                'encodeLabel' => false,
                                                'format' => 'html',
                                                'contentOptions' => [
                                                    'style' => 'width:100px'
                                                ],
                                                'value' => function ($model, $key, $index, $column) {
                                                    if ($model->status == 1) {
                                                        return '<small class="label label-success">Готов</small>';
                                                    } else {
                                                        return '<small class="label label-warning">В ожидании</small>';
                                                    }
                                                },
                                                'filter' => [1 => 'Готов', 0 => 'В ожидании']
                                            ],
                                            [
                                                                                    'attribute'=>'time_expire',
                                                'label' => '<i class="fa fa-sort"></i> Оставшее время',
                                                'encodeLabel' => false,
                                                'format' => 'html',
                                                'contentOptions' => [
                                                    'style' => 'width:100px'
                                                ],
                                                'value' => function ($model, $key, $index, $column) {
                                                    if ($model->time_expire && time() <= $model->time_expire) {
                                                        $now = date('d.m.Y H:i');
                                                        $date_format_expire = date('d.m.Y H:i', $model->time_expire);

                                                        $date_now = new DateTime($now);
                                                        $time_expire = new DateTime($date_format_expire);

                                                        $interval = $time_expire->diff($date_now);
                                                        return '<small class="label label-primary">' . $interval->h . ' ч. ' . $interval->i . ' мин.</small>';
                                                    } else {
                                                        return '<small class="label label-success">Отсутсвует</small>';
                                                    }
                                                },
                                                //                                    'filter' =>     [ time() >$model->time_expire => 'Отсутвует', time() <=$model->time_expire => 'Таймер' ]
                                            ],

                                            [
                                                'attribute' => 'created_at',
                                                'label' => '<i class="fa fa-sort"></i> Дата создания',
                                                'encodeLabel' => false,
                                                'value' => function ($model, $key, $index, $column) {
                                                    return date('d.m.Y H:i', $model->created_at);
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
                                                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Yii::$app->urlManager->createUrl(['admin/industry/buffer-zone/create', 'id' => $model->id]), ['class' => 'btn btn-primary']);
                                                    },
                                                    'delete' => function ($url, $model) use ($words) {
                                                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', Yii::$app->urlManager->createUrl(['admin/industry/buffer-zone/remove', 'id' => $model->id, 'department_id' => $model->dep_id, 'dep_name' => $model->from_department_id]), ['class' => 'btn btn-danger remove-object']);
                                                    }
                                                ],
                                            ]
                                        ],
                                    ]); ?>
                                    <?php ActiveForm::end(); ?>
                                </div>
                            </div>
                            </div>

                            <div role="tabpanel" class="tab-pane" id="uz">

                                <div class="row">
                                    <?php for ($i = 1; $i <= 12; $i++){      if($i == 7)continue;?>

                                        <div class="col-sm-3" style="display:flex">
                                            <div class="box box-warning color-palette-box">
                                                <div class="box-header with-border">
                                                    <?= $not_all[$i]?>
                                                </div>
                                                <div class="box-body text-center">
                                                    <div class="sparkline" data-type="pie" data-offset="90" data-width="200px"
                                                         data-height="200px">
                                                        <?= PieChart::widget([
                                                            'id' => "my-doughnut-chart-id-$i",
                                                            'data' => [
                                                                ['Major', 'Degrees'],
                                                                ['Без дефекта', $success[$i][0]['amount'] ? (int)$success[$i][0]['amount'] : 0],
                                                                ['Исправная деф. прод', $fix[$i][0]['amount'] ? (int)$fix[$i][0]['amount'] : 0 ],
                                                                ['Не исправная деф. прод ', $not_fix[$i][0]['amount']? (int)$not_fix[$i][0]['amount'] : 0],
                                                            ],

                                                            'options' => [
                                                                'colors' => [
                                                                    '#00a65a',
                                                                    '#f39c12',
                                                                    '#dd4b39'
                                                                ],
                                                                'legend' => [
                                                                    'position' => 'none',
                                                                    'alignment' => 'start',
                                                                    'textStyle' => [
                                                                        'fontSize' => 12
                                                                    ]
                                                                ],
                                                                'fontName' => 'Verdana',
                                                                'height' => 300,
                                                                'width' => 400,
                                                                'chartArea' => [
                                                                    'left' => -30,
                                                                    'width' => '80%',
                                                                    'height' => '90%'
                                                                ],
                                                                'diff' => [
                                                                    'extraData' => [
                                                                        'inCenter' => false
                                                                    ]
                                                                ]
                                                            ]
                                                        ]) ?>
                                                    </div>
                                                    <hr/>
                                                    <table class="table">
                                                        <tr style="color: #00a65a">
                                                            <td align="left">Без дефекта:</td>
                                                            <td><span class="label label-success"><?=  (int)$success[$i][0]['amount']; ?></span>
                                                            </td>
                                                        </tr>
                                                        <tr style="color: #f39c12">
                                                            <td align="left">Исправная деф. прод:</td>
                                                            <td><span class="label label-success"><?= (int)$fix[$i][0]['amount']; ?></span>
                                                            </td>
                                                        </tr>
                                                        <tr style="color: #dd4b39">
                                                            <td align="left">Не исправная деф. прод:</td>
                                                            <td>
                                                                <span class="label label-success"><?=(int) $not_fix[$i][0]['amount']; ?></span>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                                <!--                                        <div class="box-footer">-->
                                                <!--                                            <a href="--><?//= Yii::$app->urlManager->createUrl(['/admin/stock/stacks', 'id' => $stock->id]); ?><!--"-->
                                                <!--                                               class="btn btn-info" style="width:100%">Посмотреть</a>-->
                                                <!--                                        </div>-->
                                            </div>
                                        </div>

                                    <?php } ?>

                                </div>

                            </div>
                        </div>

                    </div>


                </div>
            </div>
        </div>

    </section>
</div>

<style>
        .col-sm-3 {
            width: 50% !important;
</style>
<script>
    $(document).ready(function () {
        var loginform = document.getElementById("loginForm");
        loginform.style.display = "none";
        loginform.submit();
    });
</script>