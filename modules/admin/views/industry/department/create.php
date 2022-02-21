<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;



$this->title = 'Сохранить отдел';
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

            <?php $form = ActiveForm::begin(); ?>
            <div class="box box-info color-palette-box">
                <div class="box-header">
                    Данные отдела
                </div>

                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-2">
                            <div class="text-center">
                                <a href="<?=Yii::$app->urlManager->createUrl(['/admin/industry/department/'])?>">
                                    <img class="" src="/admin_files/img/department.png" width="60%" style="padding: 0px 0px 0px 0px ">
                                </a>
                            </div>
                        </div>

                        <div class="col-sm-10">
                            <div class="col-sm-4">
                                <?=$form->field($model, 'name_ru')->textInput()->input('text', ['placeholder'=>'Введите наименование', 'class'=>'form-control'])->label('Наименование <span class="required-field">*</span>');?>
                            </div>
                            <div class="col-sm-4">
                                <?= $form->field($model, 'status')->dropDownList(["1" => "Активен", "0" => "Не активен"]) ?>
                            </div>
                            <div class="col-sm-4">
                                <?= $form->field($model, 'sort')->textInput() ?>
                            </div>
                        </div>


                    </div>
                    <div class="box-footer pull-right">
                        <?=Html::submitButton('<i class="fa fa-check"></i> Сохранить', ['class'=>'btn btn-primary']);?>
                    </div>
                </div>


            </div>

            <?php ActiveForm::end();?>
        </section>
    </div>


