<?php

use kartik\date\DatePicker;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;


$page = Yii::$app->request->get('id') ? 'Редактировать' : 'Добавить';

$this->title = $page.' план';
$this->params['breadcrumbs'][] = ['label' => 'Details', 'url' => ['index']];
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
                    <div class="col-sm-3">
                        <div class="text-center">
                            <a href="<?=Yii::$app->urlManager->createUrl(['/admin/industry/plan-dates/'])?>">
                                <img class="" src="/admin_files/img/planner.png" width="60%" style="padding: 0px 0px 0px 0px ">
                            </a>
                        </div>
                    </div>

                    <div class="col-sm-9">
                        <div class="row">
                            <div class="col-sm-4">
                                <?= $form->field($model, 'plan_id')->textInput(['maxlength' => true])->label('План ID') ?>
                            </div>
                            <div class="col-sm-4">
                                <?= $form->field($model, 'products[date][]')->widget(\kartik\datetime\DateTimePicker::className(), [
                                    'name' => 'date_plan',
                                    'type' => DatePicker::TYPE_COMPONENT_PREPEND,
                                    'layout' => '{picker}{input}{remove}',
                                    'options' => ['placeholder' => 'Выберите дату', 'class' =>'form-control', 'value'=>$model->date ? $model->date  : ''],
                                    'pluginOptions' => [
                                        'language' => 'th',
                                        'todayHighlight' => true,
                                        'autoclose' => true,
                                        'format' => 'yyyy-mm-dd'
                                    ]
                                ])->label('Введите дату');
                                ?>

                            </div>
                            <div class="col-sm-4">
                                <?= $form->field($model, 'value')->textInput() ?>
                            </div>
                        </div>
                    </div>

                </div>

                <?=Html::submitButton('<i class="fa fa-check-square-o"></i> Сохранить', ['class'=>'btn btn-primary pull-right']);?>
            </div>
        </div>

        <?php ActiveForm::end();?>
    </section>
</div>

