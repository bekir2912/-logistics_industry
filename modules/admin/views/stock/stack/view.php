<?php
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\widgets\LinkPager;

use app\widgets\admin_language_tab\AdminLanguageTab;
use app\widgets\admin_stock_menu\AdminStockMenu;

$this->title = 'Этаж';
$this->params['breadcrumbs'][] = $this->title;

$id = Yii::$app->request->get('id');
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
        <?php if (Yii::$app->session->hasFlash('stock_saved')) {?>
            <div class="alert alert-success text-center">
                <?=Yii::$app->session->getFlash('stock_saved');?>
            </div>
        <?php }?>
        <!-- <div class="row">
            <div class="col-sm-3">
                <?=AdminStockMenu::widget();?>
            </div>
            <div class="col-sm-9"> -->
                <?php if ($model) {?>
                    <div class="box box-info color-palette-box">
                        <div class="box-header">
                            <div class="btn-group">
                                <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                                    <span class="fa fa-cog"></span>
                                </button>
                                <ul class="dropdown-menu pull-left">
                                    <li><a href="<?=Yii::$app->urlManager->createUrl(['/admin/stock/stack-create', 'id'=>$id]);?>" class="dropdown-item">Добавить этаж</a></li>
                                    <li><a href="<?=Yii::$app->urlManager->createUrl(['/admin/stock/stack-create', 'id'=>$id, 'stack_id'=>$model->id]);?>" class="dropdown-item">Редактировать</a></li>
                                    <li><a href="<?=Yii::$app->urlManager->createUrl(['/admin/stock/stack-remove', 'id'=>$id, 'stack_id'=>$model->id]);?>" class="dropdown-item" class="remove-object">Удалить</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="box-body">
                            <table class="table table-striped">
                                <tr>
                                    <td>ID:</td>
                                    <td><?=$model->id ? $model->id : '-';?></td>
                                </tr>
                                <tr>
                                    <td>Номер этажа:</td>
                                    <td><?=$model->stack_number ? $model->stack_number : '-';?></td>
                                </tr>
                                <tr>
                                    <td>Кол-во ячеек:</td>
                                    <td><?=$model->shelfs_count ? $model->shelfs_count : '-';?></td>
                                </tr>
                                <tr>
                                    <td>Статус:</td>
                                    <td>
                                        <?php
                                            if ($model->status == 1) {
                                                echo '<small class="label label-success">Активный</small>';
                                            } else {
                                                echo '<small class="label label-danger">Заблокирован</small>';
                                            }
                                        ?>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="box box-info color-palette-box">
                        <div class="box-header">
                            Ячейки
                        </div>
                        <div class="box-body">
                            <?php if ($model->stackShelvings) {?>
                                <table class="table table-striped">
                                    <tr>
                                        <td>ID</td>
                                        <td>Номер ячейки</td>
                                        <td>Кол-во продуктов</td>
                                    </tr>
                                    <?php foreach ($model->stackShelvings as $shelf) {?>
                                        <tr>
                                            <td><?=$shelf->id ? $shelf->id : '-';?></td>
                                            <td><?=$shelf->shelf_number ? $shelf->shelf_number : '-';?></td>
                                            <td><?=$shelf->products ? count($shelf->products) : 0;?></td>
                                        </tr>
                                    <?php }?>
                                </table>
                            <?php } else {?>
                                <div class="alert alert-warning text-center">Описания нет</div>
                            <?php }?>
                        </div>
                    </div>
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
                                                return '<a href="'.Yii::$app->urlManager->createUrl(['/stock/product/view', 'id'=>$model->id]).'">'.$model->name_ru.'</a>';
                                            },
                                        ],
                                        [
                                            'attribute'=>'article',
                                            'label'=>'<i class="fa fa-sort"></i> Артикул',
                                            'encodeLabel' => false,
                                            'format' => 'html',
                                            'value' => function ($model, $key, $index, $column) {
                                                return $model->article;
                                            },
                                        ],
                                        [
                                            'attribute'=>'stock_id',
                                            'label'=>'<i class="fa fa-sort"></i> Ряд',
                                            'encodeLabel' => false,
                                            'contentOptions' => [
                                                'style' => 'width:100px'
                                            ],
                                            'value' => function ($model, $key, $index, $column) {
                                                return $model->stock ? $model->stock->name_ru : '-';
                                            },
                                        ],
                                        [
                                            'attribute'=>'stack_id',
                                            'label'=>'<i class="fa fa-sort"></i>  Этаж',
                                            'encodeLabel' => false,
                                            'contentOptions' => [
                                                'style' => 'width:70px'
                                            ],
                                            'value' => function ($model, $key, $index, $column) {
                                                return $model->stack ? $model->stack->stack_number : '-';
                                            },
                                        ],
                                        [
                                            'attribute'=>'shelf_id',
                                            'label'=>'<i class="fa fa-sort"></i>  Ячека',
                                            'encodeLabel' => false,
                                            'contentOptions' => [
                                                'style' => 'width:100px'
                                            ],
                                            'value' => function ($model, $key, $index, $column) {
                                                return $model->stackShelf ? $model->stackShelf->shelf_number : '-';
                                            },
                                        ],
                                        [
                                            'attribute'=>'price_sale',
                                            'label'=>'<i class="fa fa-sort"></i> Стоимость',
                                            'encodeLabel' => false,
                                            'value' => function ($model, $key, $index, $column) {
                                                return $model->price_sale ? $model->price_sale : '-';
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
                                            'attribute'=>'price_total',
                                            'label'=>'<i class="fa fa-sort"></i> Итого',
                                            'encodeLabel' => false,
                                            'value' => function ($model, $key, $index, $column) {
                                                return $model->price_sale * $model->amount;
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
                                                                <li><a href="'.Yii::$app->urlManager->createUrl(['/admin/product/view', 'id'=>$model->id]).'" class="dropdown-item">Посмотреть</a></li>
                                                                
                                                                <li><a href="'.Yii::$app->urlManager->createUrl(['/admin/product/view', 'id'=>$model->id]).'" class="dropdown-item" class="remove-object">Удалить</a></li>
                                                            </ul>';
                                                }
                                            ],
                                        ]
                                    ],
                                ]); ?>
                            
                        </div>
                    </div>
                    
                <?php } else {?>
                    <div class="alert alert-warning text-center">Данных нет</div>
                <?php }?>
            <!-- </div>
        </div> -->
    </section>
</div>