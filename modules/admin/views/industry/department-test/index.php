<?php

use app\models\user\User;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\ActiveForm;


$this->title = 'Тест на утечку';
$this->params['breadcrumbs'][] = $this->title;


$bad_defects = \app\models\industry\AllDeffect::find()->where(['department_id' => 4])->andWhere(['is_save'=>0])->andWhere(['status'=>1])->sum('count_deffect');
$edit_defects = \app\models\industry\AllDeffect::find()->where(['department_id' => 4])->andWhere(['is_save'=>1])->andWhere(['status'=>1])->sum('count_deffect');
$close_defects = \app\models\industry\AllDeffect::find()->where(['department_id' => 4])->andWhere(['status'=>0])->andWhere(['status'=>0])->sum('count_deffect');



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

        <div class="row">
            <div class="col-md-3 col-sm-6 col-xs-12">
                <a href="<?=Yii::$app->urlManager->createUrl(['/admin/industry/all-deffect/index', 'department_id'=>4, 'is_save' => 1, 'status' => 1]);?>">
                    <div class="info-box">
                        <span class="info-box-icon bg-yellow"><i class="fa fa-cog"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text" style="white-space: pre-wrap;">Деф-ая продукция</span>
                            <span class="info-box-number"><?= $edit_defects ? $edit_defects : 0;?></span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <a href="<?=Yii::$app->urlManager->createUrl(['/admin/industry/all-deffect/index', 'department_id'=>4, 'is_save' => 0, 'status' => 1]);?>">
                    <div class="info-box">
                        <span class="info-box-icon bg-red" ><i class="fa fa-cog"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text" style="white-space: pre-wrap;">Неисправная деф-ая продукция</span>
                            <span class="info-box-number"><?= $bad_defects ? $bad_defects : 0;?></span>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-3 col-sm-6 col-xs-12">
                <a href="<?=Yii::$app->urlManager->createUrl(['/admin/industry/all-deffect/index', 'department_id'=>4, 'status' => 0]);?>">
                    <div class="info-box">
                        <span class="info-box-icon bg-green" ><i class="fa fa-cog"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text" style="white-space: pre-wrap;">Закрытая деф. продукция</span>
                            <span class="info-box-number"><?= $close_defects ? $close_defects : 0;?></span>
                        </div>
                    </div>
                </a>
            </div>

            <!-- fix for small devices only -->
            <div class="clearfix visible-sm-block"></div>
        </div>



        <?php if (Yii::$app->session->hasFlash('test_removed')) {?>
            <div class="alert alert-success text-center"><?=Yii::$app->session->getFlash('test_removed');?></div>
        <?php }?>
        <div class="box box-info color-palette-box">
            <div class="box-header">
                <div class="pull-right">
                    <?php if(Yii::$app->user->identity->role == User::ROLE_ADMIN || Yii::$app->user->identity->is_part == User::ROLE_UCHET) {?>
                        <a href="<?=Yii::$app->urlManager->createUrl(['/admin/industry/department-test/create'])?>" class="btn btn-primary"><i class="fa fa-plus"></i> Добавить операцию</a>
                    <?php } ?>

                </div>
                <div id="action-links" style="display:none">
                    <?php $form = ActiveForm::begin(['action' => Url::to(['industry/department-test/multi']), 'options' => ['class' => 'busercheck__header _check_user']]); ?>
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
                            'summary' => "Страница {begin} - {end} из {totalCount} операций<br/><br/>",
                            'emptyText' => 'Операций нет',
                            'rowOptions'   => function ($model, $index, $widget, $grid) {
                                return [
                                    'id' => $model['id'],
                                    'url' => Yii::$app->urlManager->createUrl('admin/industry/department-test/view').'?id='.$model['id']
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
                                    'attribute'=>'articul',
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
                                    'attribute'=>'previous_department_id',
                                    'label'=>'<i class="fa fa-sort"></i> Поддон поступил из отдела',
                                    'encodeLabel' => false,
                                    'format' => 'html',
                                    'contentOptions' => [
                                        'style' => 'width:200px'
                                    ],
                                    'value' => function ($model, $key, $index, $column) {
                                        return $model->previous_department_id ? $model->previous->name_ru : '-';
                                    },
                                    'filter' => $departments_filter
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
                                        } else {
                                            return '<small class="label label-warning">В ожидании</small>';
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



//                                [
//                                    'attribute'=>'id',
//                                    'label'=>'<i class="fa fa-sort"></i> ID',
//                                    'encodeLabel' => false,
//                                    'contentOptions' => [
//                                        'style' => 'width:70px'
//                                    ],
//                                ],
//                                [
//                                    'attribute'=>'current_operation',
//                                    'label'=>'<i class="fa fa-sort"></i> Операция',
//                                    'encodeLabel' => false,
//                                    'contentOptions' => [
//                                        'style' => 'width:70px'
//                                    ],
//                                ],
//                                [
//                                    'attribute'=>'line_id',
//                                    'label'=>'<i class="fa fa-sort"></i> Линия',
//                                    'encodeLabel' => false,
//                                    'contentOptions' => [
//                                        'style' => 'width:100px'
//                                    ],
//                                    'value' => function ($model, $key, $index, $column) {
//                                        return $model->line ? $model->line->name_ru : '-';
//                                    },
//                                ],

//                                [
//                                    'attribute'=>'is_defect',
//                                    'label'=>'<i class="fa fa-sort"></i> Дефект',
//                                    'encodeLabel' => false,
//                                    'format' => 'html',
//                                    'contentOptions' => [
//                                        'style' => 'width:100px'
//                                    ],
//                                    'value' => function ($model, $key, $index, $column) {
//                                        if ($model->is_defect == 1) {
//                                            return '<small class="label label-danger">Да</small>';
//                                        } else {
//                                            return '<small class="label label-success">Нет</small>';
//                                        }
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
                                            if (Yii::$app->user->identity->is_edit == false  ) return false;
                                            return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Yii::$app->urlManager->createUrl(['admin/industry/department-test/create', 'id'=>$model->id]), ['class'=>'btn btn-primary']);
                                        },
                                        'delete' => function ($url, $model) use ($words) {
                                            if (Yii::$app->user->identity->is_remove == false  ) return false;
                                            return Html::a('<span class="glyphicon glyphicon-trash"></span>', Yii::$app->urlManager->createUrl(['admin/industry/department-test/remove', 'id'=>$model->id]), ['class'=>'btn btn-danger remove-object']);
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
