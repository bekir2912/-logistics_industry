<?php

use app\models\industry\handbook\BDepartment;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\date\DatePicker;
use app\modules\admin\controllers\industry\BPlanController;


$page = Yii::$app->request->get('id') ? 'Редактировать' : 'Добавить';

$this->title = $page.' план';
$this->params['breadcrumbs'][] = ['label' => 'Plans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;



?>

<div class="content-wrapper">
    <section class="content-header">
        <h1><?=$this->title?></h1>

        <ol class="breadcrumb">
            <li><a href="<?=Yii::$app->urlManager->createUrl(['/admin/'])?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active"><?=$this->title;?></li>
        </ol>
    </section>

    <section class="content">
        <?php $form = ActiveForm::begin(); ?>

        <div class="box box-info color-palette-box">
            <?php if (Yii::$app->session->hasFlash('dublicate_saved')) {?>
                <div class="alert alert-danger text-center"><?=Yii::$app->session->getFlash('dublicate_saved');?></div>
            <?php }?>
            <div class="box-body">

                <div class="row">
                    <div class="col-sm-2">
                        <div class="text-center">
                            <a href="<?=Yii::$app->urlManager->createUrl(['/admin/industry/b-plan/'])?>">
                                <img class="" src="/admin_files/img/planner.png" width="60%" style="padding: 0px 0px 0px 0px ">
                            </a>
                        </div>
                    </div>

                    <div class="col-sm-10">
<!--                        <div class="col-sm-6">-->
<!--                            --><?//=$form->field($model, 'value')->textInput(['maxlength' => true])->label();?>
<!--                        </div>-->


                        <div class="col-sm-6">
                            <?= $form->field($model, 'date_start')->widget(DatePicker::className(), [
                                'name' => 'date_plan',
                                'type' => DatePicker::TYPE_COMPONENT_PREPEND,
                                'value'=> $start ? $start: $model->date_start,
                                'layout' => '{picker}{input}{remove}',
                                'options' => ['placeholder' => 'Выберите дату','value' =>  $start ? $start: $model->date_start],
                                'pluginOptions' => [
                                    'language' => 'th',
                                    'todayHighlight' => true,
                                    'autoclose' => true,
                                    'format' => 'yyyy-mm-dd'
                                ]
                            ])->label('Начало даты');
                            ?>
                        </div>
                        <div class="col-sm-6">
                            <?= $form->field($model, 'date_end')->widget(DatePicker::className(), [
                                'name' => 'date_plan',
                                'type' => DatePicker::TYPE_COMPONENT_PREPEND,
                                'layout' => '{picker}{input}{remove}',
                                'options' => ['placeholder' => 'Выберите дату','value' =>  $end ? $end: $model->date_end],
                                'pluginOptions' => [
                                    'language' => 'th',
                                    'todayHighlight' => true,
                                    'autoclose' => true,
                                    'format' => 'yyyy-mm-dd'
                                ]
                            ])->label('Конец даты');
                            ?>
                        </div>

                    </div>

                    <div class="col-sm-10">
                        <div class="col-sm-6">
                            <?=$form->field($model, 'department_id')->dropDownList($departments, ['class'=>'select-drop form-control','prompt'=>'- Выберите отдел -', 'options'=>[$departments->id? $departments->id: $model->department_id=>["Selected"=>true]]])->label('Отдел');?>
                        </div>
                        <div class="col-sm-6">
                            <?= $form->field($model, 'status')->dropDownList(["1" => "Активен", "0" => "Заблокирован"]) ?>
                        </div>
                    </div>
                </div>

                <br><br>


                    <?php if ($date_interval) {?>


                        <?php foreach ($date_interval as $k => $product) { ?>

                <div class="row">
                        <div class="col-sm-2">

                        </div>


                        <div class="col-sm-10">



                            <div class="col-sm-6">
                                <?=$form->field($model, 'products[value][]')->textInput()->input('text', ['required' => true,  'placeholder'=>'Введите количество', 'class'=>'form-control', 'value'=>$product->amount ? $product->amount : ''])->label('Количество');?>
                            </div>
                            <div class="col-sm-6">
                                <?= $form->field($model, 'products[date][]')->widget(DatePicker::className(), [
                                    'name' => 'date_plan',
                                    'type' => DatePicker::TYPE_COMPONENT_PREPEND,
                                    'layout' => '{picker}{input}{remove}',
                                    'options' => ['placeholder' => 'Выберите дату', 'class' =>'form-control', 'value'=>$product],
                                    'pluginOptions' => [
                                        'language' => 'th',
                                        'todayHighlight' => true,
                                        'autoclose' => true,

                                        'format' => 'yyyy-mm-dd'
                                    ]
                                ])->label('Введите дату');
                                ?>
                            </div>
                        </div>
                    </div>

                        <?php }?>
                        <?php } else {?>
                        <div class="row">
                            <div class="col-sm-2">

                            </div>

                            <div class="col-sm-10">
                                <div class="col-sm-6">

                                              <?=$form->field($model, 'products[value][]')->textInput()->input('text', ['required' => true, 'placeholder'=>'Введите количество', 'class'=>'form-control',  'value' => ''])->label('Значение');?>
                                            </div>
                                            <div class="col-sm-6">
                                                <?= $form->field($model, 'products[date][]')->widget(DatePicker::className(), [
                                                    'name' => 'date_plan',
                                                    'type' => DatePicker::TYPE_COMPONENT_PREPEND,
                                                    'layout' => '{picker}{input}{remove}',
                                                    'options' => ['placeholder' => 'Выберите дату', 'class' =>'form-control'],
                                                    'pluginOptions' => [
                                                        'language' => 'th',
                                                        'todayHighlight' => true,
                                                        'autoclose' => true,
                                                        'format' => 'yyyy-mm-dd'
                                                    ]
                                                ])->label('Введите дату');
                                                ?>
                                            </div>
                                    </div>
                            </div>
                        <?php }?>





                <?=Html::submitButton('<i class="fa fa-check-square-o"></i> Сохранить', ['class'=>'btn btn-primary pull-right']);?>
            </div>
        </div>
        <?php

//        debug(BPlanController::getDatesFromRange('2010-10-01', '2010-10-05'));


        ?>
        <?php ActiveForm::end();?>
    </section>
</div>

