<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\widgets\LinkPager;

$this->title = 'Заявка';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="content-wrapper">
    <section class="content-header">
        <h1><?=$this->title;?></h1>
        
        <ol class="breadcrumb">
            <li><a href="<?=Yii::$app->urlManager->createUrl(['/worker_shop/'])?>"><i class="fa fa-dashboard"></i> Главная</a></li>
            <li class="active"><?=$this->title;?></li>
        </ol>
    </section>
    <section class="content">
        <?php if (Yii::$app->session->hasFlash('notice_shop_accepted')) {?>
            <div class="alert alert-success text-center">
                <?=Yii::$app->session->getFlash('notice_shop_accepted');?>
            </div>
        <?php }?>
        <?php if ($model) {?>
            <div class="box box-info color-palette-box">
                <div class="box-header">
                    <div class="btn-group">
                        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                            <span class="fa fa-cog"></span>
                        </button>
                        <ul class="dropdown-menu pull-right">
                            <!-- <li><a href="<?=Yii::$app->urlManager->createUrl(['/worker_shop/notice-shop/create']);?>" class="dropdown-item">Добавить заявку</a></li> -->
                            <!-- <li><a href="<?=Yii::$app->urlManager->createUrl(['/worker/waybill/waybill-create', 'id'=>$model->id]);?>" class="dropdown-item">Редактировать</a></li> -->
                            <li><a href="<?=Yii::$app->urlManager->createUrl(['/worker_shop/notice-shop/remove', 'id'=>$model->id]);?>" class="dropdown-item" class="remove-object">Удалить</a></li>
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
                            <td>Статус:</td>
                            <td>
                                <?php
                                    if ($model->status == 1) {
                                        echo '<span class="label label-success">Активный</span>';
                                    } else {
                                        echo '<span class="label label-danger">Не активный</span>';
                                    }
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td>Дата:</td>
                            <td><?=$model->date ? $model->date : '-';?></td>
                        </tr>
                    </table>
                </div>
            </div>
            <?php if ($model->description) {?>
                <div class="box box-info color-palette-box" style="margin-top:20px">
                    <div class="box-header">
                        Описание
                    </div>
                    <div class="box-body">
                        <?=$model->description;?>
                    </div>
                </div>
            <?php }?>
            <div class="box box-info color-palette-box">
                <div class="box-header">
                    Продукция
                </div>
                <div class="box-body">
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'summary' => "Страница {begin} - {end} из {totalCount} товаров<br/><br/>",
                        'emptyText' => 'Товаров нет',
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
                                'attribute'=>'product_id',
                                'label'=>'<i class="fa fa-sort"></i> Название',
                                'encodeLabel' => false,
                                'format' => 'html',
                                'value' => function ($model, $key, $index, $column) {
                                    return $model->product ? '<a href="'.Yii::$app->urlManager->createUrl(['/worker/product/view', 'id'=>$model->product->id]).'">'.$model->product->name_ru.'</a>' : '-';
                                },
                            ],
                            [
                                'attribute'=>'product_id',
                                'label'=>'<i class="fa fa-sort"></i> Артикул',
                                'encodeLabel' => false,
                                'format' => 'html',
                                'value' => function ($model, $key, $index, $column) {
                                    return $model->product ? '<a href="'.Yii::$app->urlManager->createUrl(['/worker/product/view', 'id'=>$model->product->id]).'">'.$model->product->article.'</a>' : '-';
                                },
                            ],
                            [
                                'attribute'=>'unit_id',
                                'label'=>'<i class="fa fa-sort"></i> Ед. измерения',
                                'encodeLabel' => false,
                                'format' => 'html',
                                'value' => function ($model, $key, $index, $column) {
                                    return $model->unit ? $model->unit->name_ru : '-';
                                },
                            ],
                            [
                                'attribute'=>'amount',
                                'label'=>'<i class="fa fa-sort"></i>  Кол-во',
                                'encodeLabel' => false,
                                'value' => function ($model, $key, $index, $column) {
                                    return $model->amount ? $model->amount : '0';
                                },
                            ],
                            // [
                            //     'class' => 'yii\grid\ActionColumn',
                            //     'template' => '{view}',
                            //     'buttons' => [
                            //         'view' => function ($url, $model) {
                            //             return '<div class="btn-group"><button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                            //                         <span class="fa fa-cog"></span>
                            //                     </button>
                            //                     <ul class="dropdown-menu pull-right">
                            //                         <li><a href="'.Yii::$app->urlManager->createUrl(['/worker/product/view', 'id'=>$model->id]).'" class="dropdown-item">Посмотреть</a></li>
                            //                         <li><a href="'.Yii::$app->urlManager->createUrl(['/worker/product/create', 'id'=>$model->id]).'" class="dropdown-item">Редактировать</a></li>
                            //                         <li><a href="'.Yii::$app->urlManager->createUrl(['/worker/product/remove', 'id'=>$model->id]).'" class="dropdown-item" class="remove-object">Удалить</a></li>
                            //                     </ul>';
                            //         }
                            //     ],
                            // ]
                        ],
                    ]); ?>
                </div>
            </div>
        <?php } else {?>
            <div class="alert alert-warning text-center">Данных нет</div>
        <?php }?>
    </section>
</div>