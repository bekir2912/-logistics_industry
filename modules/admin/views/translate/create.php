<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Добавить перевод';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="main-content">
    <section class="section">
        <div class="card">
            <div class="card-header">
                <div class="pull-right">
                    <a href="<?=Yii::$app->urlManager->createUrl(['/admin/translate/']);?>" class="btn btn-primary">
                        <i class="fa fa-eye"></i> Посмотреть перевод
                    </a>
                </div>
            </div>
            <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data'], 'id' => 'form-profile']);?>
                <div class="card-body">
                
                    <div id="block-form">
                        <div class="block-block">
                            <div class="row">
                                <div class="col-sm-4">
                                    <?=$form->field($model, 'names_ru[]')->textInput()->input('text')->label('Русский');?>
                                </div>
                                <div class="col-sm-4">
                                    <?=$form->field($model, 'names_uz[]')->textInput()->input('text')->label('Узбекский');?>
                                </div>
                                <div class="col-sm-4">
                                    <?=$form->field($model, 'names_en[]')->textInput()->input('text')->label('Английский');?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center">
                        <a href="javascript:;" class="add-block btn btn-warning">
                            <i class="fa fa-plus"></i> Добавить еще
                        </a>
                    </div>
                </div>
                <div class="card-footer">
                    <?=Html::submitButton('<i class="fa fa-check-square-o"></i> Сохранить', ['class'=>'btn btn-primary']);?>
                </div>
            <?php ActiveForm::end();?>
        </div>
    </section>
</div>  