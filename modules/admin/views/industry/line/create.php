<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;


$page = Yii::$app->request->get('id') ? 'Редактировать' : 'Добавить';

$this->title = $page.' линию';
$this->params['breadcrumbs'][] = ['label' => 'Lines', 'url' => ['index']];
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
            <?php $form = ActiveForm::begin(); ?>

            <div class="box box-info color-palette-box">
                <div class="box-body">
                    <div class="row">

                        <div class="col-sm-2">
                            <div class="text-center">
                                <a href="<?=Yii::$app->urlManager->createUrl(['/admin/industry/line/'])?>">
                                    <img class="" src="/admin_files/img/line.png" width="70%" style="padding: 0px 0px 0px 0px ">
                                </a>
                            </div>
                        </div>


                        <div class="col-sm-10">
                            <div class="col-sm-4">
                                <?=$form->field($model, 'department_id')->dropDownList($departments, ['class'=>'select-drop form-control','prompt'=>'- Выберите отдел -'])->label('Производитель');?>
                            </div>
                            <div class="col-sm-4">
                                <?= $form->field($model, 'name_ru')->textInput(['maxlength' => true])->label('Наименование RU <span class="required-field">*</span>') ?>
                            </div>
                            <div class="col-sm-4">
                                <?= $form->field($model, 'status')->dropDownList(["1" => "Активен", "0" => "Не активен"]) ?>
                            </div>
                        </div>

                    </div>

                    <?=Html::submitButton('<i class="fa fa-check-square-o"></i> Сохранить', ['class'=>'btn btn-primary pull-right']);?>
                </div>
            </div>

            <?php ActiveForm::end();?>
        </section>
    </div>

