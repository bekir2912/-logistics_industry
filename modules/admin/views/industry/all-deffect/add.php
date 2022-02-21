<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$page = Yii::$app->request->get('id') ? 'Добавить дополнительный' : 'Добавить';

$this->title = $page . ' дефект';
$this->params['breadcrumbs'][] = ['label' => 'Lines', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;


?>

<div class="content-wrapper">
    <section class="content-header">
        <?php if (Yii::$app->session->hasFlash('error_saved')) { ?>
            <div class="alert alert-error text-center">
                <?= Yii::$app->session->getFlash('error_saved'); ?>
            </div>
        <?php } ?>
        <h1><?= $this->title ?></h1>

        <ol class="breadcrumb">
            <li><a href="<?= Yii::$app->urlManager->createUrl(['/admin/']) ?>"><i class="fa fa-dashboard"></i>
                    Главная</a></li>
            <li class="active"><?= $this->title; ?></li>
        </ol>
    </section>
    <section class="content">
        <?php $form = ActiveForm::begin(); ?>

        <div class="box box-info color-palette-box">
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
<!--                        --><?//= $form->field($model, 'department_id')->dropDownList($departments, ['class' => ' form-control', 'prompt' => '- Выберите отдел -', 'options' => [ $oldModel->department_id => ["Selected" => true]]])->label('Отдел <span class="required-field">*</span>'); ?>
                        <?= $form->field($model, 'department_id')->dropDownList($departments, ['class' => ' form-control', 'disabled'=>'readonly',  'prompt' => '- Выберите отдел -', 'options' => [$oldModel->department_id ? $oldModel->department_id : $model->department_id => ["Selected" => true]]])->label('Отдел <span class="required-field">*</span>'); ?>
                        <?= $form->field($model, 'department_id')->hiddenInput(['value' => $select_deffect->department_id ? $select_deffect->department_id : $model->department_id ])->label(false); ?>
                    </div>
                </div>
                <?php if ( ($oldModel->department_id != 1 && $oldModel->department_id != 2) && ($oldModel->department_id != 8 && $oldModel->department_id != 8) && ($oldModel->department_id != 9 && $oldModel->department_id != 9) && ($oldModel->department_id != 10 && $oldModel->department_id != 10) && ($oldModel->department_id != 11 && $oldModel->department_id != 11) && ($oldModel->department_id != 12 && $oldModel->department_id != 12)) { ?>
                    <div class="row">
                        <?php if (($oldModel->department_id != 3) && ($oldModel->department_id != 4)  && ($oldModel->department_id != 5)  && ($oldModel->department_id != 8)) { ?>
                            <div class="col-sm-6">
                                <?= $form->field($model, 'detail_id')->dropDownList($details, ['class' => 'select-drop form-control', 'prompt' => '- Выберите деталь -'])->label('Деталь'); ?>
                            </div>
                        <?php } ?>
                        <div class="col-sm-6">
                            <?= $form->field($model, 'line_id')->dropDownList($lines, ['class' => 'select-drop form-control', 'prompt' => '- Выберите линию -', 'options' => [$oldModel->line_id ? $oldModel->line_id : $model->line_id => ["Selected" => true]]])->label('Линия'); ?>
                        </div>
                    </div>
                <?php } ?>

                <div class="row">
                    <div class="col-sm-6">
                        <div class="row">
                            <div class="col-sm-6">
                                <?= $form->field($model, 'number_poddon')->textInput(['maxlength' => true, 'value' => $oldModel->number_poddon ? $oldModel->number_poddon : $model->number_poddon])->label('Номер поддона') ?>
                            </div>
                            <div class="col-sm-6">
                                <?= $form->field($model, 'status')->dropDownList(["1" => "Активен", "0" => "Закрыт"], ['disabled' => $model->id ? true : false]); ?>
                                <?= $form->field($model, 'current_operation')->hiddenInput(['maxlength' => true, 'value' => $oldModel->current_operation ? $oldModel->current_operation : $model->current_operation])->label(false) ?>
                            </div>
                        </div>

                        <hr>

                                <div class="row">
                                    <div class="row">
                                        <div class="col-sm-6 button-add">
                                        </div>
                                    </div>
                                    <div class="col-sm-6 ">

                                        <?php if ($model->id && $model->is_save == 0) { ?>
                                            <?= $form->field($model, 'count_deffect')->textInput(['maxlength' => true, 'disabled' => 'disable'])->label('Кол-во дефектов <span class="required-field">*</span>'); ?>
                                        <?php } else { ?>
                                            <?= $form->field($model, 'count_deffect')->textInput(['maxlength' => true])->label('Кол-во дефектов <span class="required-field">*</span>') ?>
                                        <?php } ?>
                                    </div>
                                    <div class="col-sm-6">
                                        <?= $form->field($model, 'unit_id')->dropDownList($units, ['class' => ' form-control', 'prompt' => '- Выберите -', 'options' => [7412 => ["Selected" => true]]])->label('Ед. измерения'); ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <?= $form->field($model, 'is_save')->dropDownList(["1" => "Возможно", "0" => "Невозможно"]); ?>
                                    </div>
                                    <div class="col-sm-6">
                                        <?= $form->field($model, 'deffect_id')->dropDownList($defects, ['class' => ' form-control', 'prompt' => '- Выберите дефект -'])->label('Дефект <span class="required-field">*</span>'); ?>
                                    </div>
                                </div>


                    </div>
                    <div class="col-sm-6">
                        <?= $form->field($model, 'description_ru')->textarea(['rows' => 8]) ?>
                    </div>
                </div


                        <!--                        there is some hedden input-->
                    <?= $form->field($model, 'user_id')->hiddenInput(['value' => Yii::$app->user->identity->id])->label(false); ?>
                    <?= $form->field($model, 'dep_id')->hiddenInput(['value' => $oldModel->dep_id])->label(false); ?>


                    <?= Html::submitButton('<i class="fa fa-check-square-o "></i> Сохранить', ['class' => 'btn btn-primary pull-right']); ?>
            </div>
        </div>

        <?php ActiveForm::end(); ?>
    </section>
</div>

