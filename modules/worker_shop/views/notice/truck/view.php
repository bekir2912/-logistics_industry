<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\widgets\LinkPager;

$this->title = 'Прием грузовика';
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
        <?php if (Yii::$app->session->hasFlash('truck_notice_saved')) {?>
            <div class="alert alert-success text-center">
                <?=Yii::$app->session->getFlash('truck_notice_saved');?>
            </div>
        <?php }?>
        <?php if ($model) {?>
            <div class="box box-info color-palette-box">
                <!-- <div class="box-header">
                    <div class="btn-group">
                        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                            <span class="fa fa-cog"></span>
                        </button>
                        <ul class="dropdown-menu pull-right">
                            <li><a href="<?=Yii::$app->urlManager->createUrl(['/worker/waybill/waybill-create']);?>" class="dropdown-item">Добавить заявку</a></li>
                            <li><a href="<?=Yii::$app->urlManager->createUrl(['/worker/waybill/waybill-create', 'id'=>$model->id]);?>" class="dropdown-item">Редактировать</a></li>
                            <li><a href="<?=Yii::$app->urlManager->createUrl(['/worker/waybill/waybill-remove', 'id'=>$model->id]);?>" class="dropdown-item" class="remove-object">Удалить</a></li>
                        </ul>
                    </div>
                </div> -->
                <div class="box-body">
                    <table class="table table-striped">
                        <tr>
                            <td>ID:</td>
                            <td><?=$model->id ? $model->id : '-';?></td>
                        </tr>
                        <tr>
                            <td>Номер извещения:</td>
                            <td><?=$model->notice_number ? $model->notice_number : '-';?></td>
                        </tr>
                        <tr>
                            <td>Дата накладной:</td>
                            <td><?=$model->noticeWaybill->date_notice ? $model->noticeWaybill->date_notice : '-';?></td>
                        </tr>
                        <tr>
                            <td>ID номер фуры:</td>
                            <td><?=$model->noticeWaybill->truck_number ? $model->noticeWaybill->truck_number : '-';?></td>
                        </tr>
                        <tr>
                            <td>Регистрационный номер фуры:</td>
                            <td><?=$model->noticeWaybill->truck_number_reg ? $model->noticeWaybill->truck_number_reg : '-';?></td>
                        </tr>
                        <tr>
                            <td>Номер инвойса:</td>
                            <td><?=$model->noticeWaybill->invoice_number ? $model->noticeWaybill->invoice_number : '-';?></td>
                        </tr>
                        <tr>
                            <td>Поставщик:</td>
                            <td><?=$model->noticeWaybill->provider ? $model->noticeWaybill->provider->name_ru : '-';?></td>
                        </tr>
                        <tr>
                            <td>Статус:</td>
                            <td>
                                <?php
                                    if ($model->status == 1) {
                                        echo '<span class="label label-success">Активный</span>';
                                    } else {
                                        echo '<span class="label label-danger">Не обработан</span>';
                                    }
                                ?>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <?php if ($model->noticeWaybill->description) {?>
                <div class="box box-info color-palette-box" style="margin-top:20px">
                    <div class="box-header">
                        Описание
                    </div>
                    <div class="box-body">
                        <?=$model->noticeWaybill->description;?>
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
            <?php if ($model->status == 0) {?>
                <div class="box box-info color-palette-box" style="margin-top:20px">
                    <div class="box-header">
                        Подтверждение
                    </div>
                    <?php $form = ActiveForm::begin(); ?>
                        <div class="box-body">
                            <?=$form->field($model, 'notice_number')->textInput()->input('text', ['placeholder'=>'Введите номер извещения', 'class'=>'form-control'])->label('Номер извещения <span class="required-field">*</span>');?>
                        </div>
                        <div class="box-footer">
                            <?=Html::submitButton('<i class="fa fa-check"></i> Сохранить', ['class'=>'btn btn-primary']);?>
                        </div>
                    <?php ActiveForm::end();?>
                </div>
            <?php }?>
        <?php } else {?>
            <div class="alert alert-warning text-center">Данных нет</div>
        <?php }?>
    </section>
</div>