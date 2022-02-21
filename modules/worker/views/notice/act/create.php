<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

use kartik\select2\Select2;
use mihaildev\ckeditor\CKEditor;

$this->title = 'Сохранить акт приемки';
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
                <div class="box-header">
                    Данные
                </div>

                <div class="box-body">
                    <?php if ($model) {?>
                        <table class="table table-striped">
                            <tr>
                                <td>ID:</td>
                                <td><?=$model->id ? $model->id : '-';?></td>
                            </tr>
                            <tr>
                                <td>Номер извещения:</td>
                                <td><?=$model->noticeControl->noticeTruck->notice_number ? $model->noticeControl->noticeTruck->notice_number : '-';?></td>
                            </tr>
                            <tr>
                                <td>Дата накладной:</td>
                                <td><?=$model->noticeControl->noticeTruck->noticeWaybill->date_notice ? $model->noticeControl->noticeTruck->noticeWaybill->date_notice : '-';?></td>
                            </tr>
                            <tr>
                                <td>ID номер фуры:</td>
                                <td><?=$model->noticeControl->noticeTruck->noticeWaybill->truck_number ? $model->noticeControl->noticeTruck->noticeWaybill->truck_number : '-';?></td>
                            </tr>
                            <tr>
                                <td>Регистрационный номер фуры:</td>
                                <td><?=$model->noticeControl->noticeTruck->noticeWaybill->truck_number_reg ? $model->noticeControl->noticeTruck->noticeWaybill->truck_number_reg : '-';?></td>
                            </tr>
                            <tr>
                                <td>Номер инвойса:</td>
                                <td><?=$model->noticeControl->noticeTruck->noticeWaybill->invoice_number ? $model->noticeControl->noticeTruck->noticeWaybill->invoice_number : '-';?></td>
                            </tr>
                            <tr>
                                <td>Поставщик:</td>
                                <td><?=$model->noticeControl->noticeTruck->noticeWaybill->provider ? $model->noticeControl->noticeTruck->noticeWaybill->provider->name_ru : '-';?></td>
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
                    <?php }?>
                </div>
            </div>

            <?php if ($model->noticeControl->noticeTruck->noticeWaybill->description) {?>
                <div class="box box-info color-palette-box" style="margin-top:20px">
                    <div class="box-header">
                        Описание
                    </div>
                    <div class="box-body">
                        <?=$model->noticeControl->noticeTruck->noticeWaybill->description;?>
                    </div>
                </div>
            <?php }?>
                        
            <div id="product-form">
                <?php if ($model->noticeActProducts) {?>
                    <?php foreach ($model->noticeActProducts as $k => $product) {?>
                        <div class="product-block">
                            <div class="box">
                                <!-- <div class="box-header"><a href="javascript:;" class="btn btn-danger remove-block-product"><i class="fa fa-trash"></i> Удалить</a></div> -->
                                <div class="box-body box-info color-palette-box">
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <?=$form->field($model, 'products[product][]')->dropDownList($products, ['prompt'=>'Выберите продукт по названию', 'class'=>'select-drop select-drop-name form-control', 'options'=>[$product->product_id=>['selected'=>'selected']]])->label('Список продуктов (название)');?>
                                        </div>
                                        <div class="col-sm-3">
                                            <?=$form->field($model, 'products[article][]')->dropDownList($articles, ['prompt'=>'Выберите продукт по артикулу', 'class'=>'select-drop select-drop-article form-control', 'options'=>[$product->product_id=>['selected'=>'selected']]])->label('Список продуктов (артикул)');?>
                                        </div>
                                        <div class="col-sm-3">
                                            <?=$form->field($model, 'products[amount][]')->textInput()->input('text', ['placeholder'=>'Введите кол-во', 'class'=>'form-control price-sale', 'value'=>$product->amount])->label('Кол-во');?>
                                        </div>
                                        <div class="col-sm-3">
                                            <?=$form->field($model, 'products[unit][]')->dropDownList($units, ['prompt'=>'Выберите ед. измерения', 'class'=>'select-drop select-drop-unit form-control', 'options'=>[$product->unit_id=>['selected'=>'selected']]])->label('Еденица измерения');?>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <?=$form->field($model, 'products[stock_id][]')->dropDownList($stocks, ['prompt'=>'Выберите склад', 'class'=>'select-drop select-drop-stock form-control', 'options'=>[$product->stock_id=>['selected'=>'selected']]])->label('Склады');?>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label class="control-label">Номер стелажа</label>
                                                <select name="NoticeAct[products][stack_id][]" class="form-control select-drop-stack" style="margin-top">
                                                    <option value="">Выбрать стелаж</option>
                                                    <?php foreach ($stacks as $stack) {?>
                                                        <option value="<?=$stack->id;?>"><?=$stack->stack_number;?></option>
                                                    <?php }?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label class="control-label">Номер ячейки</label>
                                                <select name="NoticeAct[products][shelf_id][]" class="form-control select-drop-stack" style="margin-top">
                                                    <option value="">Выбрать ячейку</option>
                                                    <?php foreach ($shelvings as $shelf) {?>
                                                        <option value="<?=$shelf->id;?>"><?=$shelf->stack_number;?></option>
                                                    <?php }?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <?=$form->field($model, 'products[weight][]')->textInput()->input('text', ['placeholder'=>'Введите вес', 'class'=>'form-control price-sale', 'value'=>$product->weight])->label('Вес');?>
                                        </div>
                                    </div>
                                    <?=$form->field($model, 'products[description]')->textarea(['rows' => '6', 'value'=>$product->description])->label('Описание'); ?>
                                </div>
                            </div>
                        </div>
                    <?php }?>
                <?php }?>
            </div>
            <!-- <div class="text-center">
                <a href="javascript:;" class="add-variant-product btn btn-sm btn-warning">
                    <i class="fa fa-plus"></i> Добавить еще
                </a>
            </div>
            <hr/> -->
            <div class="text-right">
                <?=Html::submitButton('<i class="fa fa-check"></i> Сохранить', ['class'=>'btn btn-primary']);?>
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
                url: '/worker/product/get-stacks',
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
                url: '/worker/product/get-shelvings',
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