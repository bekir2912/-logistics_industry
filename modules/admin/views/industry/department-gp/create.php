<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;


$page = Yii::$app->request->get('id') ? 'Редактировать' : 'Добавить';

$this->title = $page.' операцию в отдел ГП';
$this->params['breadcrumbs'][] = ['label' => 'Gp', 'url' => ['index']];
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
                            <?=$form->field($model, 'model_id')->dropDownList($models, ['class'=>'select-drop form-control','prompt'=>'- Выберите модель -', 'options'=>[$department->model_id? $department->model_id: $model->model_id=>["Selected"=>true]]])->label('Модель');?>
                            <b>Предедущая модель: </b> <?=  $department->model_id? $model->nameModel($department->model_id) : '--' ?>
                        </div>
                        <div class="col-sm-6">
                            <?= $form->field($model, 'current_operation')->textInput(['maxlength' => true, 'value' => $department->current_operation ? $department->current_operation : $model->current_operation, 'readonly' => true])->label('Операция <span class="required-field">*</span>') ?>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="col-sm-6">
                            <?= $form->field($model, 'number_poddon')->textInput(['maxlength' => true, 'value' => $department->number_poddon ? $department->number_poddon : $model->number_poddon])->label('Номер поддона <span class="required-field">*</span>'); ?>
                            <b>Предедущий поддон: </b>   <?=  $department->number_poddon? $department->number_poddon : '--' ?>
                        </div>

                        <div class="col-sm-6">
                            <?=$form->field($model, 'name_ru')->textInput()->input('text', ['placeholder'=>'Введите наименование', 'class'=>'form-control'])->label('Наименование');?>
                        </div>
<!--                        <div class="col-sm-6">-->
<!--                            --><?//= $form->field($model, 'article' )->textInput(['maxlength' => true])->label('Артикул '); ?>
<!--                        </div>-->
                    </div>

                    <div class="col-sm-12">
<!--                        <div class="col-sm-6">-->
<!--                            --><?php
//                            echo '<label class="control-label">Процесс проверки ( от 0 до 10)</label>';
//                            echo \kartik\range\RangeInput::widget([
//                                'model' => $model,
//                                'attribute' => 'value_range',
//                                'options' => ['placeholder' => 'Rate (0 - 10)...'],
//                                'html5Container' => ['style' => 'width:350px'],
//                                'html5Options' => ['min' => 0, 'max' => 10],
//                                'addon' => ['append' => ['content' => 'Оценка']]
//                            ]);
//
//
//                            ?>


                    </div>



                    <div class="col-sm-12">
                            <div class="col-sm-4">
                                <?= $form->field($model, 'amount')->textInput(['maxlength' => true,'value' => $department->amount ? $department->amount : $model->amount])->label('Кол-во <span class="required-field">*</span>') ?>
                            </div>
                            <div class="col-sm-2">
                                <?=$form->field($model, 'unit_id')->dropDownList($units, ['class'=>'select-drop form-control', 'prompt'=>'- Выберите -', 'options'=>[7412 =>["Selected"=>true]]])->label('Ед. измерения');?>
                            </div>

                        <div class="col-sm-6">
                            <?= $form->field($model, 'status')->dropDownList([0 => "В ожидании", 1 => "Готов", 2 => "Проверка"]); ?>
                        </div>
                    </div>

                </div>

                <?=$form->field($model, 'user_id')->hiddenInput(['value'=> Yii::$app->user->identity->id ? Yii::$app->user->identity->id : 1])->label(false);?>
                <?=$form->field($model, 'department_id')->hiddenInput(['value'=>  7])->label(false);?>
                <?=$form->field($model, 'part_model')->hiddenInput(['value'=> Yii::$app->user->identity->is_part ? Yii::$app->user->identity->is_part : 1])->label(false);?>
                <?=Html::submitButton('<i class="fa fa-check-square-o"></i> Сохранить', ['class'=>'btn btn-primary pull-right']);?>
            </div>
        </div>

        <?php ActiveForm::end();?>
    </section>
</div>
