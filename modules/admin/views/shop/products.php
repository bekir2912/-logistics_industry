<?php
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\widgets\LinkPager;

use kartik\select2\Select2;
use kartik\daterange\DateRangePicker;

$this->title = 'Продукция магазина';
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
        <?php if ($shop) {?>
            <div class="box box-info color-palette-box">
                <div class="box-header">
                    Магазин
                </div>
                <div class="box-body">
                    <a href="<?=Yii::$app->urlManager->createUrl(['/admin/shop'])?>">
                        <?=$shop->name_ru;?>
                    </a>
                    <hr/>
                    Кол-во товаров: <strong><?=$total_amount;?></strong>
                </div>
            </div>
        <?php }?>
        <div class="box box-info color-palette-box">
            <div class="box-header">
                <?=$this->title;?>
            </div>
            <div class="box-body">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'summary' => "Страница {begin} - {end} из {totalCount} данных<br/><br/>",
                    'emptyText' => 'Данных нет',
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
                            'attribute'=>'name_ru',
                            'label'=>'<i class="fa fa-sort"></i> Название',
                            'encodeLabel' => false,
                            'format' => 'html',
                            'contentOptions' => [
                                'style' => 'width:150px'
                            ],
                            'value' => function ($model, $key, $index, $column) {
                                return '<a href="'.Yii::$app->urlManager->createUrl(['/stock/product/view', 'id'=>$model->product->id]).'">'.$model->product->name_ru.'</a>';
                            },
                        ],
                        [
                            'attribute'=>'article',
                            'label'=>'<i class="fa fa-sort"></i> Артикул',
                            'encodeLabel' => false,
                            'format' => 'html',
                            'value' => function ($model, $key, $index, $column) {
                                return $model->product->article;
                            },
                        ],
                        [
                            'attribute'=>'shop_stack_id',
                            'label'=>'<i class="fa fa-sort"></i>  Этаж',
                            'encodeLabel' => false,
                            'contentOptions' => [
                                'style' => 'width:70px'
                            ],
                            'value' => function ($model, $key, $index, $column) {
                                return $model->shopStack ? $model->shopStack->stack_number : '-';
                            },
                        ],
                        [
                            'attribute'=>'shop_stack_shelving_id',
                            'label'=>'<i class="fa fa-sort"></i>  Ячека',
                            'encodeLabel' => false,
                            'contentOptions' => [
                                'style' => 'width:100px'
                            ],
                            'value' => function ($model, $key, $index, $column) {
                                return $model->shopStackShelving ? $model->shopStackShelving->shelf_number : '-';
                            },
                        ],
                        [
                            'attribute'=>'amount',
                            'label'=>'<i class="fa fa-sort"></i> Кол-во',
                            'encodeLabel' => false,
                            'value' => function ($model, $key, $index, $column) {
                                return $model->amount ? $model->amount : '-';
                            },
                        ],
                        [
                            'attribute'=>'date',
                            'label'=>'<i class="fa fa-sort"></i> Дата',
                            'encodeLabel' => false,
                            'value' => function ($model, $key, $index, $column) {
                                return $model->date;
                            },
                        ],
                        [
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{view}',
                            'buttons' => [
                                'view' => function ($url, $model) {
                                    return '<div class="btn-group"><button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                                                <span class="fa fa-cog"></span>
                                            </button>
                                            <ul class="dropdown-menu pull-left">
                                                <li><a href="'.Yii::$app->urlManager->createUrl(['/admin/product/view', 'id'=>$model->product->id]).'" class="dropdown-item">Посмотреть</a></li>
                                                <li><a href="'.Yii::$app->urlManager->createUrl(['/admin/product/remove-shop', 'id'=>$model->product->id]).'" class="dropdown-item" class="remove-object">Удалить</a></li>
                                            </ul>';
                                }
                            ],
                        ]
                    ],
                ]); ?>
            </div>
        </div>
    </section>
</div>