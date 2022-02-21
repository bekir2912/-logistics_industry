<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;


$page = Yii::$app->request->get('id') ? 'Редактировать' : 'Добавить';

$this->title = "Калибровка: " . $page." операцию  " . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Sizing', 'url' => ['index']];
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

        <?php if (Yii::$app->session->hasFlash('buffer_alert_time')) {?>
            <div class="alert alert-warning text-center">
                <?=Yii::$app->session->getFlash('buffer_alert_time');?>
            </div>
        <?php } ?>


        <?php $form = ActiveForm::begin(); ?>

        <div class="box box-info color-palette-box">
            <div class="box-body">
                <div class="row">

                    <div class="col-sm-12">
                        <div class="col-sm-4">
                            <?php if($model->model_id){?>
                            <?=$form->field($model, 'model_id')->dropDownList($models, ['class'=>'select-drop form-control','prompt'=>'- Выберите модель -', 'options'=>[$department->model_id? $department->model_id: $model->model_id=>["Selected"=>true]]])->label('Модель');?>
                            <?php }else{?>
                                <?=$form->field($model, 'model_id')->dropDownList($models, ['class'=>'select-drop form-control','prompt'=>'- Выберите модель -', 'options'=>[$department->model_id? $department->model_id: $model->model_id=>["Selected"=>true]]])->label('Модель');?>
                            <?php }?>
                            <?php if($department->model_id){?>
                                <b style="font-size: 2rem">Предедущая модель: </b> <?=  $department->model_id? $model->nameModel($department->model_id) : '--' ?>
                            <?php }?>

                        </div>
                        <div class="col-sm-4">
                            <?= $form->field($model, 'current_operation')->textInput(['maxlength' => true, 'value' => $department->current_operation ? $department->current_operation : ($model->current_operation ? $model->current_operation : \Faker\Provider\Uuid::uuid()), 'readonly' => true ])->label('Операция') ?>
                        </div>
                        <?php  if(($model->time_expire &&  $model->id && time() > $model->time_expire)){?>
                        <div class="col-sm-4">
                            <?=$form->field($model, 'line_id')->dropDownList($lines, ['class'=>'select-drop form-control','prompt'=>'- Выберите линию -'   ])->label('Линия');?>
                        </div>
                        <?php }?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <?php  if(!$model->id){  ?>
                            <div class="col-sm-4">
                                <?= $form->field($model, 'amount')->textInput(['maxlength' => true, 'value' =>  $department->amount ? $department->amount : $model->amount])->label('Кол-во <span class="required-field">*</span>') ?>
                            </div>
                        <?php }else {?>
                            <div class="col-sm-4">
                                <?= $form->field($model, 'amount')->textInput(['maxlength' => true, 'readonly' => true, 'value' =>  $department->amount ? $department->amount : $model->amount])->label('Кол-во <span class="required-field">*</span>') ?>
                            </div>
                        <?php }?>
                        <div class="col-sm-4">
                            <?= $form->field($model, 'number_poddon')->textInput(['maxlength' => true, 'value' => $department->number_poddon ? $department->number_poddon : $model->number_poddon])->label('Номер поддона <span class="required-field">*</span>'); ?>
                            <?php if($department->number_poddon){?>
                                <b>Предедущий поддон: </b> <?=  $department->number_poddon? $department->number_poddon : '--' ?>
                            <?php }?>

                        </div>
                        <div class="col-sm-4">
                            <?= $form->field($model, 'status')->dropDownList([ "0" => "В ожидании", "1" => "Готов"]); ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                    <?php  if(( $model->time_expire && $model->id && time() > $model->time_expire)){?>
                        <div class="col-sm-4">
                            <?= $form->field($model, 'new_number_poddon')->textInput(['maxlength' => true, 'required', 'value' =>  $department->new_number_poddon ? $department->new_number_poddon : $model->new_number_poddon])->label('Новый поддон <span class="required-field">*</span>') ?>
                        </div>
                    <?php }?>
                    </div>
                </div>

                <?=$form->field($model, 'previous_department_id')->hiddenInput([ 'value'=> $department->department_id ? ($department->department_id)  : ($model->previous_department_id ? $model->previous_department_id : 4)])->label(false);?>
                <?=$form->field($model, 'user_id')->hiddenInput(['value'=> Yii::$app->user->identity->id ? Yii::$app->user->identity->id : 1])->label(false);?>
                <?=$form->field($model, 'department_id')->hiddenInput([ 'value'=> $department->department_id ? ($department->department_id +1)  : ($model->department_id? $model->department_id : 5)])->label(false);?>
                <?=Html::submitButton('<i class="fa fa-check-square-o"></i> Сохранить', ['class'=>'btn btn-primary pull-right']);?>
            </div>
        </div>

        <?php ActiveForm::end();?>
    </section>
</div>
