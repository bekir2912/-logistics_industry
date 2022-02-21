<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

use kartik\select2\Select2;
use mihaildev\ckeditor\CKEditor;

use app\widgets\admin_shop_menu\AdminShopMenu;

$this->title = 'Сохранить стелаж';
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
        <div class="row">
            <div class="col-sm-3">
                <?=AdminShopMenu::widget();?>
            </div>
            <div class="col-sm-9">
                <?php $form = ActiveForm::begin(); ?>
                    <div class="box box-info color-palette-box">
                        <div class="box-header">
                            Данные стелажа
                        </div>
                    
                        <div class="box-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    <?=$form->field($model, 'stack_number')->textInput()->input('text', ['placeholder'=>'Введите номер стелажа', 'class'=>'form-control'])->label('Номер стелажа <span class="required-field">*</span>');?>
                                </div>
                                <div class="col-sm-6">
                                    <?=$form->field($model, 'shelfs_count')->textInput()->input('text', ['placeholder'=>'Введите кол-во ячеек', 'class'=>'form-control', 'id'=>'shop-shelfs-count'])->label('Кол-во ячеек <span class="required-field">*</span>');?>
                                </div>
                            </div>
                            <div id="shelf-blocks">
                                <?php if ($model->shopStackShelvings) {?>
                                    <?php foreach ($model->shopStackShelvings as $shelf) {?>
                                        <input type="text" class="form-control" name="ShopStack[shelfs][]" placeholder="Введите номер полки" aria-required="true" value="<?=$shelf->shelf_number;?>"><br/>
                                    <?php }?>
                                <?php }?>
                            </div>
                        </div>

                        <div class="box-footer">
                            <?=Html::submitButton('<i class="fa fa-check"></i> Сохранить', ['class'=>'btn btn-primary']);?>
                        </div>
                    </div>
                <?php ActiveForm::end();?>
            </div>
        </div>
    </section>
</div>