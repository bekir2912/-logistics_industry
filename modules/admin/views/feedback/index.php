<?php
use yii\grid\GridView;
use yii\widgets\Pjax;

$this->title = 'Обратная связь';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="main-content">
    <section class="section">
        <?php if (Yii::$app->session->hasFlash('feedback_removed')) {?>
            <div class="alert alert-success text-center">
                <?=Yii::$app->session->getFlash('feedback_removed');?>
            </div>
        <?php }?>
        <div class="card">
            <div class="card-headerr">
                Заявки
            </div>
            <div class="card-body">
                <?php if ($dataProvider) {?>
                    <div class="grid-table">
                    <?php Pjax::begin(); ?>
 <div style="overflow-x: auto; width: 100%;">
                        <?= GridView::widget([
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'summary' => "Страница {begin} - {end} из {totalCount} заявок<br/><br/>",
                            'emptyText' => 'Заявок не найдено',
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
                                    'attribute'=>'email',
                                    'label'=>'<i class="fa fa-sort"></i> E-mail',
                                    'encodeLabel' => false,
                                ],
                                [
                                    'attribute'=>'status',
                                    'label'=>'<i class="fa fa-sort"></i> Статус',
                                    'encodeLabel' => false,
                                    'format' => 'html',
                                    'value' => function ($model, $key, $index, $column) {
                                        if ($model->status == 0) {
                                            return '<span class="label bg-yellow">В ожидании</span>';
                                        }

                                        if ($model->status == 1) {
                                            return '<span class="label bg-green">Просмотрен</span>';
                                        }
                                    },
                                ],
                                [
                                    'attribute'=>'date',
                                    'label'=>'<i class="fa fa-sort"></i> Дата',
                                    'encodeLabel' => false,
                                ],
                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'template' => '{view}',
                                    'buttons' => [
                                        'view' => function ($url, $model) {
                                            return '<button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-expanded="true">
                                                        <span class="fas fa-cog"></span>
                                                    </button>
                                                    <div class="dropdown-menu" x-placement="bottom-start">
                                                        <a href="'.Yii::$app->urlManager->createUrl(['/admin/feedback/view', 'id'=>$model->id]).'" class="dropdown-item">Посмотреть</a>
                                                        <a href="'.Yii::$app->urlManager->createUrl(['/admin/feedback/remove', 'id'=>$model->id]).'" class="dropdown-item remove-object">Удалить</a>
                                                    </div>';
                                        }
                                    ],
                                ]
                            ],
                        ]); ?>
                    <?php Pjax::end(); ?>
                </div>
                <?php } else {?>
                    <div class="callout callout-warning text-center">Заявок нет</div>
                <?php }?>
            </div>
        </div>
    </section>
</div>