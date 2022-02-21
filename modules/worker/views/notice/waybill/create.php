<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

use kartik\select2\Select2;
use mihaildev\ckeditor\CKEditor;

$this->title = 'Сохранить накладную';
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
        <?php $form = ActiveForm::begin(); ?>
            <div class="box box-info color-palette-box">
                <div class="box-header with-border">
                    Данные
                </div>
            
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <?=$form->field($model, 'date_notice')->textInput()->input('text', ['placeholder'=>'Введите дату накладной', 'class'=>'form-control', 'value'=>date('Y-m-d')])->label('Дата <span class="required-field">*</span>');?>
                        </div>
                        <div class="col-sm-6">
                            <?=$form->field($model, 'truck_number')->textInput()->input('text', ['placeholder'=>'Введите ID номер фуры', 'class'=>'form-control'])->label('ID номер фуры <span class="required-field">*</span>');?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <?=$form->field($model, 'truck_number_reg')->textInput()->input('text', ['placeholder'=>'Введите регистрационный номер фуры', 'class'=>'form-control'])->label('Регистрационный номер фуры <span class="required-field">*</span>');?>
                        </div>
                        <div class="col-sm-6">
                            <?=$form->field($model, 'invoice_number')->textInput()->input('text', ['placeholder'=>'Введите номер инвойса', 'class'=>'form-control'])->label('Номер инвойса');?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <?=$form->field($model, 'provider_id')->dropDownList($providers, ['class'=>'select-drop form-control', 'prompt'=>'Выберите'])->label('Поставщики');?>
                        </div>
                    </div>
                    
                </div>
            </div>

            <div class="box box-info color-palette-box">
                <div class="box-header with-border">
                    Описание
                </div>
            
                <div class="box-body">
                    <?=$form->field($model, 'description')->textarea(['rows' => '6'])->label(false); ?>
                </div>
            </div>
            
            <div id="product-form">
                <?php if ($model->noticeWaybillProducts) {?>
                    <?php foreach ($model->noticeWaybillProducts as $k => $product) {?>
                        <div class="product-block">
                            <div class="box box-info color-palette-box">
                                <div class="box-header with-border"><a href="javascript:;" class="btn btn-danger remove-block-product"><i class="fas fa-trash"></i> Удалить</a></div>
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <?=$form->field($model, 'products[product][]')->dropDownList($products, ['prompt'=>'Выберите продукт по названию', 'class'=>'select-drop select-drop-name form-control', 'options'=>[$product->product_id=>['selected'=>'selected']]])->label('Список продуктов (название)');?>
                                        </div>
                                        <div class="col-sm-6">
                                            <?=$form->field($model, 'products[article][]')->dropDownList($articles, ['prompt'=>'Выберите продукт по артикулу', 'class'=>'select-drop select-drop-article form-control', 'options'=>[$product->product_id=>['selected'=>'selected']]])->label('Список продуктов (артикул)');?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <?=$form->field($model, 'products[unit][]')->dropDownList($units, ['prompt'=>'Выберите ед. измерения', 'class'=>'select-drop select-drop-unit form-control', 'options'=>[$product->unit_id=>['selected'=>'selected']]])->label('Еденица измерения');?>
                                        </div>
                                        <div class="col-sm-6">
                                            <?=$form->field($model, 'products[amount][]')->textInput()->input('text', ['placeholder'=>'Введите кол-во', 'class'=>'form-control price-sale', 'value'=>$product->amount])->label('Кол-во');?>
                                        </div>
                                    </div>
                                    <?=$form->field($model, 'products[description]')->textarea(['rows' => '6', 'value'=>$product->description])->label('Описание'); ?>
                                </div>
                            </div>
                        </div>
                    <?php }?>
                <?php } else {?>
                    <div class="product-block">
                        <div class="box box-info color-palette-box">
                            <div class="box-body with-border">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <?=$form->field($model, 'products[product][]')->dropDownList($products, ['prompt'=>'Выберите продукт по названию', 'class'=>'select-drop select-drop-name form-control'])->label('Список продуктов (название)');?>
                                    </div>
                                    <div class="col-sm-6">
                                        <?=$form->field($model, 'products[article][]')->dropDownList($articles, ['prompt'=>'Выберите продукт по артикулу', 'class'=>'select-drop select-drop-article form-control select'])->label('Список продуктов (артикул)');?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <?=$form->field($model, 'products[unit][]')->dropDownList($units, ['prompt'=>'Выберите ед. измерения', 'class'=>'select-drop select-drop-unit form-control select'])->label('Еденица измерения');?>
                                    </div>
                                    <div class="col-sm-6">
                                        <?=$form->field($model, 'products[amount][]')->textInput()->input('text', ['placeholder'=>'Введите кол-во', 'class'=>'form-control price-sale'])->label('Кол-во');?>
                                    </div>
                                </div>
                                <?=$form->field($model, 'products[description][]')->textarea(['rows' => '6'])->label('Описание'); ?>
                            </div>
                        </div>
                    </div>
                <?php }?>
            </div>
            <div class="text-center">
                <a href="javascript:;" class="add-variant-product btn btn-sm btn-warning">
                    <i class="fa fa-plus"></i> Добавить еще
                </a>
            </div>
            <hr/>
            <div class="text-right">
                <?=Html::submitButton('<i class="fa fa-check"></i> Сохранить', ['class'=>'btn btn-primary']);?>
            </div>
        <?php ActiveForm::end();?>
    </section>
</div>