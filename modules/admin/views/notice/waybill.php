<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\widgets\LinkPager;

$this->title = 'Накладная';
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
        <?php if (Yii::$app->session->hasFlash('waybill_saved')) {?>
            <div class="alert alert-success text-center">
                <?=Yii::$app->session->getFlash('waybill_saved');?>
            </div>
        <?php }?>
        <?php if ($model) {?>
            <div class="box box-info color-palette-box">
                <div class="box-header">
                    <a href="<?=Yii::$app->urlManager->createUrl(['/admin/notice/waybill', 'id'=>$model->id]);?>" class="btn btn-success">Накладная</a>
                    <a href="<?=Yii::$app->urlManager->createUrl(['/admin/notice/truck', 'id'=>$model->noticeTruck->id]);?>" class="btn btn-primary">Прием грузовика</a>
                    <a href="<?=Yii::$app->urlManager->createUrl(['/admin/notice/control', 'id'=>$model->noticeTruck->noticeControl->id]);?>" class="btn btn-primary">Контроль качества</a>
                    <a href="<?=Yii::$app->urlManager->createUrl(['/admin/notice/act', 'id'=>$model->noticeTruck->noticeControl->noticeAct->id]);?>" class="btn btn-primary">Акт приемки</a>
                </div>
                <div class="box-body">
                    <table class="table table-striped">
                        <tr>
                            <td>ID:</td>
                            <td><?=$model->id ? $model->id : '-';?></td>
                        </tr>
                        <tr>
                            <td>Дата накладной:</td>
                            <td><?=$model->date_notice ? $model->date_notice : '-';?></td>
                        </tr>
                        <tr>
                            <td>ID номер фуры:</td>
                            <td><?=$model->truck_number ? $model->truck_number : '-';?></td>
                        </tr>
                        <tr>
                            <td>Регистрационный номер фуры:</td>
                            <td><?=$model->truck_number_reg ? $model->truck_number_reg : '-';?></td>
                        </tr>
                        <tr>
                            <td>Номер инвойса:</td>
                            <td><?=$model->invoice_number ? $model->invoice_number : '-';?></td>
                        </tr>
                        <tr>
                            <td>Поставщик:</td>
                            <td><?=$model->provider ? $model->provider->name_ru : '-';?></td>
                        </tr>
                        <tr>
                            <td>Общее кол-во товара:</td>
                            <td><?=$model->noticeWaybillProducts ? count($model->noticeWaybillProducts) : '0';?></td>
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
                                    return $model->product ? '<a href="'.Yii::$app->urlManager->createUrl(['/admin/product/view', 'id'=>$model->product->id]).'">'.$model->product->name_ru.'</a>' : '-';
                                },
                            ],
                            [
                                'attribute'=>'product_id',
                                'label'=>'<i class="fa fa-sort"></i> Артикул',
                                'encodeLabel' => false,
                                'format' => 'html',
                                'value' => function ($model, $key, $index, $column) {
                                    return $model->product ? '<a href="'.Yii::$app->urlManager->createUrl(['/admin/product/view', 'id'=>$model->product->id]).'">'.$model->product->article.'</a>' : '-';
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
                            //                         <li><a href="'.Yii::$app->urlManager->createUrl(['/admin/product/view', 'id'=>$model->id]).'" class="dropdown-item">Посмотреть</a></li>
                            //                         <li><a href="'.Yii::$app->urlManager->createUrl(['/admin/product/create', 'id'=>$model->id]).'" class="dropdown-item">Редактировать</a></li>
                            //                         <li><a href="'.Yii::$app->urlManager->createUrl(['/admin/product/remove', 'id'=>$model->id]).'" class="dropdown-item" class="remove-object">Удалить</a></li>
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