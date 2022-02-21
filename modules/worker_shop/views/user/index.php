<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

$this->title = 'Клиенты';
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
        <?php if (Yii::$app->session->hasFlash('user_removed')) {?>
            <div class="alert alert-success text-center alert-bottom"><?=Yii::$app->session->getFlash('user_removed');?></div>
        <?php }?>
        <div class="box">
            <div class="box-header">
                <a href="<?=Yii::$app->urlManager->createUrl(['/worker/user/create'])?>" class="btn btn-primary">
                    <i class="fa fa-user-plus"></i>
                    Добавить клиента
                </a>
            </div>
            <div class="box-body">
                <div class="grid-table">
                    
                        <?= GridView::widget([
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'summary' => "Страница {begin} - {end} из {totalCount} клиентов<br/><br/>",
                            'emptyText' => 'Клиентов не найдено',
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
                                    'attribute'=>'id',
                                    'label'=>'<i class="fa fa-sort"></i> ID',
                                    'encodeLabel' => false,
                                ],
                                [
                                    'attribute'=>'status',
                                    'label'=>'<i class="fa fa-sort"></i> Статус',
                                    'encodeLabel' => false,
                                    'format' => 'html',
                                    'value' => function ($model, $key, $index, $column) {
                                        if ($model->status == 0) {
                                            return '<span class="badge badge-warning">В ожидании</span>';
                                        }
                                        if ($model->status == 1) {
                                            return '<span class="badge badge-success">Активный</span>';
                                        }
                                    },
                                ],
                                [
                                    'attribute'=>'email',
                                    'label'=>'<i class="fa fa-sort"></i> E-mail',
                                    'encodeLabel' => false,
                                    'value' => function ($model, $key, $index, $column) {
                                        return ($model->{$column->attribute}) ? $model->{$column->attribute} : 'Не указано';
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
                                                        <a href="'.Yii::$app->urlManager->createUrl(['/worker/user/view', 'id'=>$model->id]).'" class="dropdown-item">Посмотреть</a>
                                                        <a href="'.Yii::$app->urlManager->createUrl(['/worker/user/create', 'id'=>$model->id]).'" class="dropdown-item">Редактировать</a>
                                                        <a href="'.Yii::$app->urlManager->createUrl(['/worker/user/remove', 'id'=>$model->id]).'" class="dropdown-item remove-object">Удалить</a>
                                                    </div>';
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