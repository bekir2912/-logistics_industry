<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;


$page = Yii::$app->request->get('id') ? 'Редактировать' : 'Добавить';

$this->title = "Проверка узлов на утечку: " . $page." операцию  " . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Checking', 'url' => ['index']];
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
        <?php $form = ActiveForm::begin(); ?>

        <div class="box box-info color-palette-box">
            <div class="box-body">
                <div class="row">

                    <div class="col-sm-12">
                        <div class="col-sm-6">
                            <?=$form->field($model, 'model_id')->dropDownList($models, ['class'=>'select-drop form-control','prompt'=>'- Выберите модель -'])->label('Модель');?>
                            <b>Предедущая модель: </b> <?=  $department->model_id? $model->nameModel($department->model_id) : '--' ?>
                        </div>
                        <div class="col-sm-6">
                            <?= $form->field($model, 'current_operation')->textInput([ 'maxlength' => true, 'value'=> ($model->current_operation ?$model->current_operation : \Faker\Provider\Uuid::uuid()), 'readonly' => true ])->label('Операция (это уникальное значение) <span class="required-field">*</span>') ?>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <?php  if(!$model->id){  ?>
                            <div class="col-sm-4">
                                <?= $form->field($model, 'amount')->textInput(['maxlength' => true])->label('Кол-во <span class="required-field">*</span>') ?>
                            </div>
                        <?php }else {?>
                            <div class="col-sm-4">
                                <?= $form->field($model, 'amount')->textInput(['maxlength' => true, 'disabled' => true])->label('Кол-во <span class="required-field">*</span>') ?>
                            </div>
                        <?php }?>

                        <div class="col-sm-4">
                            <?= $form->field($model, 'number_poddon')->textInput(['maxlength' => true])->label('Номер поддона <span class="required-field">*</span>'); ?>
                            <b>Предедущий поддон: </b> <?=  $department->number_poddon? $department->number_poddon : '--' ?>
                        </div>

                        <div class="col-sm-4">
                            <?= $form->field($model, 'status')->dropDownList(["0" => "В ожидании", "1" => "Готов"]); ?>
                        </div>
                    </div>

                </div>

                <?=$form->field($model, 'user_id')->hiddenInput(['value'=> Yii::$app->user->identity->id ? Yii::$app->user->identity->id : 1])->label(false);?>
                <?=$form->field($model, 'department_id')->hiddenInput(['value'=>  12])->label(false);?>
                <?=$form->field($model, 'part_model')->hiddenInput(['value'=> Yii::$app->user->identity->is_part ? Yii::$app->user->identity->is_part : 1])->label(false);?>
                <?=Html::submitButton('<i class="fa fa-check-square-o"></i> Сохранить', ['class'=>'btn btn-primary pull-right']);?>
            </div>
        </div>

        <?php ActiveForm::end();?>
    </section>
</div>
