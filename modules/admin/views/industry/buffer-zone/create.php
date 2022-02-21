<?php


use kartik\datetime\DateTimePicker;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$page = Yii::$app->request->get('id') ? 'Редактировать' : 'Добавить';

$this->title = $page." операцию  ";
$this->params['breadcrumbs'][] = ['label' => 'Buffer', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;



?>

<div class="content-wrapper">
    <section class="content-header">
        <h1><?= "Буферная зона:  ". $this->title . " " . $model->current_operation;?></h1>

        <ol class="breadcrumb">
            <li><a href="<?=Yii::$app->urlManager->createUrl(['/admin/'])?>"><i class="fa fa-dashboard"></i> Главная</a></li>
            <li class="active"><?=$this->title;?></li>
        </ol>
    </section>
    <section class="content">
        <?php if (Yii::$app->session->hasFlash('error_save')) {?>
            <div class="alert alert-error text-center">
                <?=Yii::$app->session->getFlash('error_save');?>
            </div>
        <?php } ?>
        <?php if (Yii::$app->session->hasFlash('buffer_alert_time')) {?>
            <div class="alert alert-warning text-center">
                <?=Yii::$app->session->getFlash('buffer_alert_time');?>
            </div>
        <?php } ?>

        <?php if (Yii::$app->session->hasFlash('buffer_alert_time_expire') && $department->department_id == 3) {?>
            <div class="alert alert-warning text-center">
                <?=Yii::$app->session->getFlash('buffer_alert_time_expire');?>
            </div>
        <?php } ?>


        <?php $form = ActiveForm::begin(); ?>

        <div class="box box-info color-palette-box">
            <div class="box-body">


                <div class="row">

                    <div class="col-sm-12">
                        <div class="col-sm-6">
                            <?php if($department->model_id){?>
                            <?=$form->field($model, 'model_id')->dropDownList($models, ['disabled'=>true, 'class'=>'select-drop form-control','prompt'=>'- Выберите модель -', 'options'=>[$department->model_id? $department->model_id: $model->model_id=>["Selected"=>true]]])->label('Модель <span class="required-field">*</span>');?>
                            <?=$form->field($model, 'model_id')->hiddenInput(['value'=>$department->model_id? $department->model_id: $model->model_id])->label(false);?>
                            <?php }else{ ?>
                                <?=$form->field($model, 'model_id')->dropDownList($models, [  'class'=>'select-drop form-control','prompt'=>'- Выберите модель -', 'options'=>[$department->model_id? $department->model_id: $model->model_id=>["Selected"=>true]]])->label('Модель <span class="required-field">*</span>');?>
                            <?php }?>

                        </div>
                           <div class="col-sm-6">
                            <?= $form->field($model, 'current_operation')->textInput(['maxlength' => true ,'value' => $department->current_operation ? $department->current_operation : $model->current_operation, 'readonly' => true ])->label('Операция <span class="required-field">*</span>') ?>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="col-sm-4">
                            <?= $form->field($model, 'amount')->textInput(['maxlength' => true, 'value' => $department->amount ? $department->amount : $model->amount])->label('Кол-во <span class="required-field">*</span>') ?>
                        </div>
                        <?php
                        if($department->department_id == 5){?>
                            <div class="col-sm-4">
                                <?= $form->field($model, 'number_poddon')->textInput(['maxlength' => true, 'value' => $department->new_number_poddon ? $department->new_number_poddon : $department->number_poddon])->label('Номер поддона <span class="required-field">*</span>') ?>
                            </div>

                        <?php }else{?>
                            <div class="col-sm-4">
                                <?= $form->field($model, 'number_poddon')->textInput(['maxlength' => true, 'value' => $department->number_poddon ? $department->number_poddon : $model->number_poddon])->label('Номер поддона <span class="required-field">*</span>') ?>
                            </div>
                        <?php } ?>

                        <div class="col-sm-4">
                            <?= $form->field($model, 'status')->dropDownList([ "0" => "В ожидании", "1" => "Готов"]); ?>
                        </div>
                    </div>

                    <div class="col-sm-12">
                        <div class="col-sm-6">
                            <?=$form->field($model, 'from_department_id')->dropDownList($departments, ['class'=>'select-drop form-control','prompt'=>'- Откуда -', 'options'=>[$department->department_id? $department->department_id: $model->from_department_id=>["Selected"=>true]],'disabled' => true] )->label('Откуда <span class="required-field">*</span>');?>
                        </div>
                        <div class="col-sm-6">
                            <?php if($department->department_id  == 12){?>
                                <?=$form->field($model, 'to_department_id')->dropDownList($departments, ['class'=>'select-drop form-control','prompt'=>'- Куда -',  'options'=>[$department->department_id? 3: $model->to_department_id =>["Selected"=>true]] ,'disabled' => 'disabled'])->label('Куда <span class="required-field">*</span>');?>
                            <?php } elseif ($department->department_id  == 10) {?>
                                 <?=$form->field($model, 'to_department_id')->dropDownList($departments, ['class'=>'select-drop form-control','prompt'=>'- Куда -',  'options'=>[$department->department_id? 6: $model->to_department_id =>["Selected"=>true]], 'disabled' => 'disabled'])->label('Куда <span class="required-field">*</span>');?>
                            <?php }else{?>
                            <?=$form->field($model, 'to_department_id')->dropDownList($departments, ['class'=>'select-drop form-control','prompt'=>'- Куда -',  'options'=>[$department->department_id? ($department->department_id + 1): $model->to_department_id =>["Selected"=>true]] ,'disabled' => 'disabled'])->label('Куда <span class="required-field">*</span>');?>
                            <?php }?>
                        </div>
                    </div>
                </div>
                <?=$form->field($model, 'dep_id')->hiddenInput(['value'=> $department->id])->label(false);?>

                <?=$form->field($model, 'user_id')->hiddenInput(['value'=> Yii::$app->user->identity->id ? Yii::$app->user->identity->id : 1])->label(false);?>
                <?=$form->field($model, 'from_department_id')->hiddenInput(['value'=>$department->department_id? $department->department_id : $model->from_department_id])->label(false);?>
                <?php if($department->department_id  == 12){?>
                    <?=$form->field($model, 'to_department_id')->hiddenInput(['value'=>$department->department_id? 3 : $model->from_department_id])->label(false);?>
                <?php } elseif ($department->department_id  == 10) {?>
                    <?=$form->field($model, 'to_department_id')->hiddenInput(['value'=>$department->department_id? 6 : $model->from_department_id])->label(false);?>
                <?php }else{?>
                    <?=$form->field($model, 'to_department_id')->hiddenInput(['value'=>$department->department_id? $department->department_id +1 : $model->from_department_id])->label(false);?>
                <?php }?>

<!--                --><?//=$form->field($model, 'to_department_id')->hiddenInput(['value'=> $department->department_id? 3: $model->to_department_id])->label(false);?>
                <!--                --><?//=$form->field($model, 'part_model')->hiddenInput(['value'=> Yii::$app->user->identity->is_part ? Yii::$app->user->identity->is_part : 1])->label(false);?>
                <?=Html::submitButton('<i class="fa fa-check-square-o"></i> Сохранить', ['class'=>'btn btn-primary pull-right']);?>
            </div>
        </div>

        <?php ActiveForm::end();?>
    </section>
</div>
