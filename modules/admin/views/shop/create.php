<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

use kartik\select2\Select2;
use mihaildev\ckeditor\CKEditor;

$this->title = 'Сохранить магазин';
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="content-wrapper">
    <section class="content-header">
        <h1><?=$this->title;?></h1>

        <ol class="breadcrumb">
            <li><a href="<?=Yii::$app->urlManager->createUrl(['/admin/'])?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active"><?=$this->title;?></li>
        </ol>
    </section>
    <section class="content">

        <?php if (Yii::$app->session->hasFlash('photo_removed')) {?>
            <div class="alert alert-success text-center alert-bottom"><?=Yii::$app->session->getFlash('photo_removed');?></div>
        <?php }?>
        <?php $form = ActiveForm::begin(); ?>
            <div class="box box-info color-palette-box">
                <div class="box-header">
                    Данные магазина
                </div>
            
                <div class="box-body">
                    <?=$form->field($model, 'name_ru')->textInput()->input('text', ['placeholder'=>'Введите название', 'class'=>'form-control'])->label('Название <span class="required-field">*</span>');?>
                    <?=$form->field($model, 'description_ru')->textarea(['rows' => '6'])->label('Описание <span class="required-field">*</span>');?>
                </div>

                <div class="box-footer">
                    <?=Html::submitButton('<i class="fa fa-check"></i> Сохранить', ['class'=>'btn btn-primary']);?>
                </div>
            </div>
        <?php ActiveForm::end();?>
    </section>
</div>