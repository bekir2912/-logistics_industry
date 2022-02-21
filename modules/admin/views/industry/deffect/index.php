<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;



$this->title = 'Дефекты';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="content-wrapper">
    <section class="content-header">
        <h1><?=$this->title;?></h1>

        <ol class="breadcrumb">
            <li><a href="<?=Yii::$app->urlManager->createUrl(['/admin/'])?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active"><?=$this->title;?></li>
        </ol>
    </section>
    <section class="content">
        <?php if (Yii::$app->session->hasFlash('deffect_removed')) {?>
            <div class="alert alert-success text-center"><?=Yii::$app->session->getFlash('deffect_removed');?></div>
        <?php }?>
        <div class="box box-info color-palette-box">
            <div class="box-header">
                <div class="pull-right">
                    <a href="<?=Yii::$app->urlManager->createUrl(['/admin/industry/deffect/create'])?>" class="btn btn-primary"><i class="fa fa-plus"></i> Добавить дефект</a>
                </div>
                <div id="action-links" style="display:none">
                    <?php $form = ActiveForm::begin(['action' => Url::to(['industry/deffect/multi']), 'options' => ['class' => 'busercheck__header _check_user']]); ?>
                    <div class="form-group">
                        <button type="submit" class="btn btn-danger"> <i class="fa fa-trash"></i> Удалить</button>
                    </div>
                    <!--                        <a href="javascript:;" class="btn btn-danger" data-value="remove"><i class="fa fa-trash"></i> Удалить</a>-->
                </div>
            </div>
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
                                    'url' => Yii::$app->urlManager->createUrl('admin/industry/deffect/view').'?id='.$model['id']
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
                                    'template' => '{update} {delete}',
                                    'contentOptions' => [
                                        'style' => 'width:150px'
                                    ],
                                    'buttons' => [
                                        'update' => function ($url, $model) use ($words) {
                                            return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Yii::$app->urlManager->createUrl(['admin/industry/deffect/create', 'id'=>$model->id]), ['class'=>'btn btn-primary']);
                                        },
                                        'delete' => function ($url, $model) use ($words) {
                                            return Html::a('<span class="glyphicon glyphicon-trash"></span>', Yii::$app->urlManager->createUrl(['admin/industry/deffect/remove', 'id'=>$model->id]), ['class'=>'btn btn-danger remove-object']);
                                        }
                                    ],
                                ]
                            ],
                        ]); ?>
                        <?php ActiveForm::end(); ?>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>