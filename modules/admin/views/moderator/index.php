<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

$this->title = 'Сотрудники';
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
        <?php if (Yii::$app->session->hasFlash('moderator_removed')) {?>
            <div class="alert alert-success text-center"><?=Yii::$app->session->getFlash('moderator_removed');?></div>
        <?php }?>
        <div class="box box-info color-palette-box">
            <div class="box-header">
                <div class="pull-right">
                <a href="<?=Yii::$app->urlManager->createUrl(['/admin/moderator/create'])?>" class="btn btn-primary"><i class="fa fa-plus"></i> Добавить сотрудника</a>
                </div>
            </div>
            <div class="box-body">
                <div class="grid-table">
                    
                        <?= GridView::widget([
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'summary' => "Страница {begin} - {end} из {totalCount} сотрудников<br/><br/>",
                            'emptyText' => 'Сотрудники не найдены',
                            'rowOptions'   => function ($model, $index, $widget, $grid) {
                                if (true)
                                    return [
                                        'id' => $model['id'],
                                        'onclick' => 'location.href="'
                                            . Yii::$app->urlManager->createUrl('admin/moderator/view')
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
                                    'label' => 'Фото',
                                    'format' => 'html',
                                    'value' => function($data) { return Html::img($data->getPhoto('50x50'), ['width'=>'50']); },
                                ],
                                [
                                    'attribute' => 'department_id',
                                    'label' => '<i class="fa fa-sort"></i> Отдел',
                                    'encodeLabel' => false,
                                    'contentOptions' => [
                                        'style' => 'width:200px'
                                    ],
                                    'value' => function ($model, $key, $index, $column) {
                                        return $model->department ? $model->department->name_ru : '-';
                                    },
                                ],
                                [
                                    'attribute'=>'is_part',
                                    'label'=>'<i class="fa fa-sort"></i> Сотрудник',
                                    'encodeLabel' => false,
                                    'format' => 'html',
                                    'value' => function ($model, $key, $index, $column) {
                                        if ($model->is_part == 1) {
                                            return '<span class="badge badge-warning">Контроль качества</span>';
                                        }
                                        if ($model->is_part == 2) {
                                            return '<span class="badge badge-success">Контроль учета </span>';
                                        }
                                    },
                                ],
                                [
                                    'attribute'=>'id',
                                    'label'=>'<i class="fa fa-sort"></i> ID',
                                    'encodeLabel' => false,
                                ],
                                [
                                    'attribute'=>'name',
                                    'label'=>'<i class="fa fa-sort"></i> Имя',
                                    'encodeLabel' => false,
                                    'value' => function ($model, $key, $index, $column) {
                                        return ($model->{$column->attribute}) ? $model->{$column->attribute} : 'Не указано';
                                    },
                                ],
                                [
                                    'attribute'=>'lastname',
                                    'label'=>'<i class="fa fa-sort"></i> Фамилия',
                                    'encodeLabel' => false,
                                    'value' => function ($model, $key, $index, $column) {
                                        return ($model->{$column->attribute}) ? $model->{$column->attribute} : 'Не указано';
                                    },
                                ],
                                [
                                    'attribute'=>'login',
                                    'label'=>'<i class="fa fa-sort"></i> Логин',
                                    'encodeLabel' => false,
                                    'value' => function ($model, $key, $index, $column) {
                                        return ($model->{$column->attribute}) ? $model->{$column->attribute} : 'Не указано';
                                    },
                                ],
                                [
                                    'attribute'=>'date',
                                    'label'=>'<i class="fa fa-sort"></i> Дата',
                                    'encodeLabel' => false,
                                    'value' => function ($model, $key, $index, $column) {
                                        return ($model->{$column->attribute}) ? $model->{$column->attribute} : 'Не указано';
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
                                            return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Yii::$app->urlManager->createUrl(['admin/moderator/create', 'id'=>$model->id]), ['class'=>'btn btn-primary']);
                                        },
                                        'delete' => function ($url, $model) use ($words) {
                                            return Html::a('<span class="glyphicon glyphicon-trash"></span>', Yii::$app->urlManager->createUrl(['admin/moderator/remove', 'id'=>$model->id]), ['class'=>'btn btn-danger remove-object']);
                                        }
                                    ],
                                ]
                            ],
                        ]); ?>
                    
                </div>
            </div>
        </div>
    </section>
</div>