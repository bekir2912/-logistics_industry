<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

use kartik\select2\Select2;
use mihaildev\ckeditor\CKEditor;

$this->title = 'Сохранить отгрузку';
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
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
        <?php if (Yii::$app->session->hasFlash('photo_removed')) {?>
            <div class="alert alert-success text-center alert-bottom"><?=Yii::$app->session->getFlash('photo_removed');?></div>
        <?php }?>
        <?php $form = ActiveForm::begin(); ?>
            <div class="box">
                <div class="box-header">
                    Данные отгрузки
                </div>

                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <?=$form->field($model, 'agent_id')->dropDownList($agents, ['prompt'=>'Выберите контрагента', 'class'=>'select-drop form-control'])->label('Контрагенты');?>
                        </div>
                        <div class="col-sm-6">
                            <?=$form->field($model, 'type_id')->dropDownList($types, ['prompt'=>'Выберите тип отгрузки', 'class'=>'select-drop form-control'])->label('Тип отгрузки');?>
                        </div>
                        <div class="col-sm-6">
                            <?=$form->field($model, 'fio')->textInput()->input('text', ['placeholder'=>'Введите Ф.И.О', 'class'=>'form-control'])->label('Ф.И.О <span class="required-field">*</span>');?>
                        </div>
                        <div class="col-sm-6">
                            <?=$form->field($model, 'date_shipment')->textInput()->input('date', ['placeholder'=>'Введите дату', 'class'=>'form-control'])->label('Дата');?>
                        </div>
                    </div>
                    <?=$form->field($model, 'comment')->textarea(['placeholder'=>'Введите примечание', 'class'=>'form-control'])->label('Прмечание');?>
                </div>
            </div>
            <div id="product-form">
                <?php if ($model->shipmentProducts) {?>
                    <?php foreach ($model->shipmentProducts as $k => $product) {?>
                        <div class="product-block">
                            <div class="box">
                                <div class="box-header"><a href="javascript:;" class="btn btn-danger remove-block-product"><i class="fas fa-trash"></i> Удалить</a></div>
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <?=$form->field($model, 'products[product][]')->dropDownList($products, ['prompt'=>'Выберите товар', 'class'=>'select-drop form-control', 'options'=>[$product->product_id=>['selected'=>'selected']]])->label('Список товаров');?>
                                        </div>
                                        <div class="col-sm-4">
                                            <?=$form->field($model, 'products[article][]')->textInput()->input('text', ['placeholder'=>'Введите артикул товара', 'class'=>'form-control', 'value'=>$product->artice])->label('Артикул');?>
                                        </div>
                                        <div class="col-sm-4">
                                            <?=$form->field($model, 'products[amount][]')->textInput()->input('text', ['placeholder'=>'Введите количество', 'class'=>'form-control', 'value'=>$product->amount])->label('Количество');?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php }?>
                <?php } else {?>
                    <div class="product-block">
                        <div class="box">
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <?=$form->field($model, 'products[product][]')->dropDownList($products, ['prompt'=>'Выберите товар', 'class'=>'select-drop form-control'])->label('Список товаров');?>
                                    </div>
                                    <div class="col-sm-4">
                                        <?=$form->field($model, 'products[article][]')->textInput()->input('text', ['placeholder'=>'Введите артикул', 'class'=>'form-control'])->label('Артикул');?>
                                    </div>
                                    <div class="col-sm-4">
                                        <?=$form->field($model, 'products[amount][]')->textInput()->input('text', ['placeholder'=>'Введите количество', 'class'=>'form-control'])->label('Количество');?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php }?>
            </div>
            <div class="text-center">
                <a href="javascript:;" class="add-variant-product btn btn-sm btn-primary">
                    <i class="fa fa-plus"></i> Добавить еще
                </a>
            </div>
            <br/>
            <?=Html::submitButton('<i class="fa fa-check"></i> Сохранить', ['class'=>'btn btn-success', 'style'=>'width:100%']);?>
        <?php ActiveForm::end();?>
    </section>
</div>