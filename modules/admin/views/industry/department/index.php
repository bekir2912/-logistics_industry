<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap\ActiveForm;


$this->title = 'Отделы';
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
        <?php if (Yii::$app->session->hasFlash('department_removed')) {?>
            <div class="callout callout-success text-center">
                <?=Yii::$app->session->getFlash('department_removed');?>
            </div>
        <?php }?>
        <div class="box box-info color-palette-box">
            <div class="box-header with-border">
                <div class="box-title pull-right" style="font-size: 14px">
                    <?php if (!Yii::$app->request->get('type')) {?>
                        <a href="<?=Yii::$app->urlManager->createUrl(['/admin/industry/department/create'])?>" class="btn btn-primary">
                            <i class="fa fa-pencil"></i>
                            Добавить отдел
                        </a>
                    <?php }?>
                </div>
<!--                <div id="action-links" style="display:none">-->
<!--                    <a href="javascript:;" class="btn btn-danger" data-value="remove"><i class="fa fa-trash"></i> Удалить</a>-->
<!--                    <a href="javascript:;" class="btn btn-warning" data-value="disable"><i class="fa fa-lock"></i> Заблокировать</a>-->
<!--                    <a href="javascript:;" class="btn btn-success" data-value="enable"><i class="fa fa-unlock"></i> Разблокировать</a>-->
<!--                </div>-->
            </div>
            <div class="box-body" id="item-block">

                <div style="overflow-x: auto; width: 100%;">
                    <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'summary' => "Страница {begin} - {end} из {totalCount} отделов<br/><br/>",
                    'emptyText' => 'Товаров нет',
                    'rowOptions'   => function ($model, $index, $widget, $grid) {
                        return [
                            'id' => $model['id'],
                            'url' => Yii::$app->urlManager->createUrl('admin/industry/department/view').'?id='.$model['id']
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
                            'attribute'=>'id',
                            'label'=>'<i class="fa fa-sort"></i> ID',
                            'encodeLabel' => false,
                            'contentOptions' => [
                                'style' => 'width:70px'
                            ],
                        ],
                        [
                            'attribute'=>'name_ru',
                            'label'=>'<i class="fa fa-sort"></i> Название',
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
                            'attribute'=>'updated_at',
                            'label'=>'<i class="fa fa-sort"></i> Дата изменения',
                            'encodeLabel' => false,
                            'value' => function ($model, $key, $index, $column) {
                                return  date('d.m.Y H:i',$model->updated_at);
                            },
                        ],

                        [
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{update}',
                            'contentOptions' => [
                                'style' => 'width:150px'
                            ],
                            'buttons' => [
                                'update' => function ($url, $model) use ($words) {
                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Yii::$app->urlManager->createUrl(['admin/industry/department/create', 'id'=>$model->id]), ['class'=>'btn btn-primary']);
                                },
                            ],
                        ]
                    ],
                ]); ?>

            </div>
            </div>
</div>
</section>
</div>

