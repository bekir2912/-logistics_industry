<?php

use yii\helpers\Html;

use yii\bootstrap\ActiveForm;


$page = Yii::$app->request->get('id') ? 'Редактировать' : 'Добавить';

$this->title = "Механическая сборка: " . $page." операцию  " . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Stamping', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;



?>

<div class="content-wrapper">
    <section class="content-header">
        <h1><?=$this->title?></h1>

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
                            <?php if(!$department){ ?>
                                <?=$form->field($model, 'model_id')->dropDownList($models, [ 'class'=>'select-drop form-control','prompt'=>'- Выберите модель -', 'options'=>[$department->model_id? $department->model_id: $model->model_id=>["Selected"=>true]]])->label('Модель <span class="required-field">*</span>');?>
                            <?php } else{?>
                                <?=$form->field($model, 'model_id')->dropDownList($models, ['disabled'=> ($department ? true : false), 'class'=>'select-drop form-control','prompt'=>'- Выберите модель -', 'options'=>[$department->model_id? $department->model_id: $model->model_id=>["Selected"=>true]]])->label('Модель <span class="required-field">*</span>');?>
                                <?=$form->field($model, 'model_id')->hiddenInput(['value'=>$department->model_id? $department->model_id: $model->model_id])->label(false);?>
                            <?php } ?>
                            <b>Предедущая модель: </b> <?=  $department->model_id? $model->nameModel($department->model_id) : '--' ?>
                        </div>
                        <div class="col-sm-6">
                            <?=$form->field($model, 'line_id')->dropDownList($lines, ['class'=>'select-drop form-control','prompt'=>'- Выберите линию -'])->label('Линия');?>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="col-sm-6">
                            <?= $form->field($model, 'current_operation')->textInput(['maxlength' => true, 'value' => $department->current_operation ? $department->current_operation : ($model->id ?$model->current_operation :  \Faker\Provider\Uuid::uuid()) , 'readonly' => true ])->label('Операция <span class="required-field">*</span>') ?>
                        </div>
                        <?php if($department->id|| !empty($model)) { ?>
                        <div class="col-sm-6">
                            <?= $form->field($model, 'number_poddon')->textInput(['maxlength' => true, 'value' => $department->number_poddon ? $department->number_poddon : $model->number_poddon])->label('Номер поддона <span class="required-field">*</span>'); ?>
                            <b>Предедущий поддон: </b> <?=  $department->number_poddon? $department->number_poddon : '--' ?>
                        </div>
                            <?php }?>
<!--                        --><?php //if($department->department_id != 12 && $model->id != NULL) { ?>
<!--                            <div class="col-sm-6">-->
<!--                                --><?//= $form->field($model, 'new_number_poddon')->textInput(['maxlength' => true, 'value' => $model->new_number_poddon ? $model->new_number_poddon : "" ])->label('Новый номер поддона <span class="required-field">*</span>') ?>
<!--                            </div>-->
<!--                        --><?php //}?>
                    </div>
                    <div class="col-sm-12">
                        <?php  if(!$model->id){  ?>
                            <div class="col-sm-6">
                                <?= $form->field($model, 'amount')->textInput(['maxlength' => true, 'readonly' => false, 'value' => $department->amount ? $department->amount : $model->amount])->label('Кол-во <span class="required-field">*</span>') ?>
                            </div>
                        <?php }else {?>
                            <div class="col-sm-6">
                                <?= $form->field($model, 'amount')->textInput(['maxlength' => true, 'readonly' => true, 'value' => $department->amount ? $department->amount : $model->amount])->label('Кол-во <span class="required-field">*</span>') ?>
                            </div>
                        <?php }?>
                        <?php if($department->department_id == 2){?>
                            <div class="col-sm-6">
                                <?= $form->field($model, 'status')->dropDownList([ "1" => "Готов"]); ?>
                            </div>
                        <?php }elseif ($department->department_id == 12){?>
                        <div class="col-sm-6">
                            <?= $form->field($model, 'status')->dropDownList([ "1" => "Готов"]); ?>
                        </div>
                        <?php }else {?>
                        <div class="col-sm-6">
                            <?= $form->field($model, 'status')->dropDownList([ "0" => "В ожидании", "1" => "Готов" ]); ?>
                        </div>
                        <?php }?>

                    </div>
                    <?=$form->field($model, 'previous_department_id')->hiddenInput([ 'value'=> $department->department_id ? ($department->department_id)  : ($model->previous_department_id ? $model->previous_department_id : 3)])->label(false);?>
                </div>
                <?=$form->field($model, 'user_id')->hiddenInput(['value'=> Yii::$app->user->identity->id ? Yii::$app->user->identity->id : 1])->label(false);?>
                <?php if($department->department_id == 2){ ?>
                    <?=$form->field($model, 'department_id')->hiddenInput([ 'value'=> $department->department_id ? ($department->department_id +1)  : $model->department_id])->label(false);?>
                <?php }elseif ($department->department_id == 12){ ?>
                    <?=$form->field($model, 'department_id')->hiddenInput([ 'value'=> $department->department_id ? 3  : $model->department_id])->label(false);?>
                <?php }elseif (empty($department->department_id)){ ?>
                    <?=$form->field($model, 'department_id')->hiddenInput([ 'value'=> 3 ])->label(false);?>
                <?php } ?>
                <?=Html::submitButton('<i class="fa fa-check-square-o"></i> Сохранить', ['class'=>'btn btn-primary pull-right']);?>
            </div>
        </div>

        <?php ActiveForm::end();?>
    </section>
</div>

