<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

use kartik\select2\Select2;
use mihaildev\ckeditor\CKEditor;

$this->title = 'Сохранить товар в ГП';
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
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

        <?php if (Yii::$app->session->hasFlash('photo_removed')) {?>
            <div class="alert alert-success text-center alert-bottom"><?=Yii::$app->session->getFlash('photo_removed');?></div>
        <?php }?>
        <?php $form = ActiveForm::begin(); ?>
            <div class="box">
                <div class="box-header">
                    Данные товара
                </div>
            
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <?=$form->field($model, 'name_ru')->textInput()->input('text', ['placeholder'=>'Введите наименование', 'class'=>'form-control'])->label('Наименование <span class="required-field">*</span>');?>
                        </div>
                        <div class="col-sm-6">
                            <?=$form->field($model, 'article')->textInput()->input('text', ['placeholder'=>'Введите артикул', 'class'=>'form-control'])->label('Артикул <span class="required-field">*</span>');?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <?=$form->field($model, 'region_id')->dropDownList($regions, ['class'=>'select-drop form-control', 'prompt'=>'Выберите регион'])->label('Регион');?>
                        </div>
                        <div class="col-sm-6">
                            <?=$form->field($model, 'category_id')->dropDownList($categories, ['class'=>'select-drop form-control', 'prompt'=>'Выберите категорию'])->label('Категории');?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <?=$form->field($model, 'price_sale')->textInput()->input('text', ['placeholder'=>'Введите цену', 'class'=>'form-control'])->label('Цена');?>
                        </div>
                        <div class="col-sm-6">
                            <?=$form->field($model, 'amount')->textInput()->input('text', ['placeholder'=>'Введите количество', 'class'=>'form-control'])->label('Количество');?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <?=$form->field($model, 'manufacturer_id')->dropDownList($manufacturers, ['class'=>'select-drop form-control'])->label('Производитель');?>
                        </div>
                        <div class="col-sm-6">
                            <?=$form->field($model, 'unit_id')->dropDownList($units, ['class'=>'select-drop form-control'])->label('Еденица измерения');?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-6"> 
                            <?=$form->field($model, 'imageFiles[]')->fileInput(['class'=>'file-upload-ajax'])->label('Фото'); ?>
                        </div>
                        <div class="col-xs-6">
                            <div style="margin-left:50px">
                                <img src="<?=$model->getPhoto('200x200/')?>" width="200" class="photo-stock-user"/>
                                <?php if ($model->image) {?>
                                    <br/>
                                    <a href="<?=Yii::$app->urlManager->createUrl(['/admin/default/remove-photo', 'id'=>$model->image->id])?>" class="edit-user remove-object"><i class="fa fa-times"></i> Удалить фото</a>
                                <?php }?>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
            <div class="box">
                <div class="box-header">
                    Склад
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-4">
                            <?=$form->field($model, 'stock_id')->dropDownList($stocks, ['prompt'=>'Выберите склад', 'class'=>'select-drop select-drop-stock form-control', 'options'=>[$product->stock_id=>['selected'=>'selected']]])->label('Склады');?>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label">Номер этажа</label>
                                <select name="Product[stack_id]" class="form-control select-drop-stack" style="margin-top">
                                    <option value="">Выбрать этаж</option>
                                    <?php foreach ($stacks as $stack) {?>
                                        <option value="<?=$stack->id;?>"><?=$stack->stack_number;?></option>
                                    <?php }?>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label">Номер ячейки</label>
                                <select name="Product[shelf_id]" class="form-control select-drop-stack" style="margin-top">
                                    <option value="">Выбрать ячейку</option>
                                    <?php foreach ($shelvings as $shelf) {?>
                                        <option value="<?=$shelf->id;?>"><?=$shelf->stack_number;?></option>
                                    <?php }?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <?=Html::submitButton('<i class="fa fa-check"></i> Сохранить', ['class'=>'btn btn-primary']);?>
                </div>
            </div>
        <?php ActiveForm::end();?>
    </section>
</div>


<?php
$script = <<<JS
    $(document).on('change', '.select-drop-stock', function() {
        var id = $(this).val();
        var block = $(this).parent().parent().next().find('select');
        var shelf = $(this).parent().parent().next().next().find('select');
        block.html('');
        shelf.html('');

        if (id) {
            $.ajax({
                url: '/admin/product/get-stacks',
                type: 'post',
                data: {'id':id},
                success: function(data) {
                    if (data && data['data']) {
                        block.append('<option value="">Выбрать этаж</option>');
                        for (var i in data['data']) {
                            block.append('<option value="'+data['data'][i]['id']+'">'+data['data'][i]['stack_number']+'</option>');
                        }
                    }
                }
            });
        }

        return false;
    });

    $(document).on('change', '.select-drop-stack', function() {
        var id = $(this).val();
        var block = $(this).parent().parent().next().find('select');
        block.html('');

        if (id) {
            $.ajax({
                url: '/admin/product/get-shelvings',
                type: 'post',
                data: {'id':id},
                success: function(data) {
                    if (data && data['data']) {
                        block.append('<option value="">Выбрать ячейку</option>');
                        for (var i in data['data']) {
                            block.append('<option value="'+data['data'][i]['id']+'">'+data['data'][i]['shelf_number']+'</option>');
                        }
                    }
                }
            });
        }

        return false;
    });
JS;

$this->registerJs($script);
?>