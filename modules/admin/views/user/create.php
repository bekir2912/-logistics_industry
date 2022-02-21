<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$page = Yii::$app->request->get('id') ? 'Редактировать' : 'Добавить'; 

$this->title = $page.' клиента';
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
        <?php if (Yii::$app->session->hasFlash('user_created')) {?>
            <div class="alert alert-success text-center alert-bottom"><?=Yii::$app->session->getFlash('user_created');?></div>
        <?php }?>
        <?php if (Yii::$app->session->hasFlash('photo_removed')) {?>
            <div class="alert alert-success text-center alert-bottom"><?=Yii::$app->session->getFlash('photo_removed');?></div>
        <?php }?>
        <?php $form = ActiveForm::begin(); ?>
            <div class="box box-info color-palette-box">
                <div class="box-header">
                    Личные данные
                </div>

                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-5">
                            <div class="row">
                                <div class="col-xs-6"> 
                                    <?=$form->field($model, 'imageFiles[]')->fileInput(['class'=>'file-upload-ajax'])->label('Фото'); ?>
                                </div>
                                <div class="col-xs-6">
                                    <div style="margin-left:50px">
                                        <img src="<?=$model->getPhoto('200x200/')?>" width="200" class="photo-admin-user"/>
                                        <?php if ($model->image) {?>
                                            <br/>
                                            <a href="<?=Yii::$app->urlManager->createUrl(['/admin/default/remove-photo', 'id'=>$model->image->id])?>" class="edit-user remove-object"><i class="fa fa-times"></i> Удалить фото</a>
                                        <?php }?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-7">
                            <?=$form->field($model, 'name')->textInput()->input('text', ['placeholder'=>'Введите имя', 'class'=>'form-control'])->label('Имя <span class="required-field">*</span>');?>
                            <?=$form->field($model, 'lastname')->textInput()->input('text', ['placeholder'=>'Введите фамилию', 'class'=>'form-control'])->label('Фамилия');?>
                            <?=$form->field($model, 'fname')->textInput()->input('text', ['placeholder'=>'Введите отчество', 'class'=>'form-control'])->label('Отчество');?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="box box-info color-palette-box">
                <div class="box-header">
                    Доступ
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <?=$form->field($model, 'birthday')->textInput()->input('date', ['class'=>'form-control', 'placeholder'=>'Дата рождения'])->label('Дата рождения');?>
                        </div>
                        <div class="col-sm-6">
                            <?=$form->field($model, 'phone')->textInput()->input('text', ['class'=>'form-control sms-phone', 'placeholder'=>'Введите телефон'])->label('Телефон <span class="required-field">*</span>');?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <?=$form->field($model, 'email')->textInput()->input('text', ['class'=>'form-control', 'placeholder'=>'Введите e-mail'])->label('E-mail <span class="required-field">*</span>');?>
                        </div>
                        <div class="col-sm-6">
                            <?php $required = !$model->id ? ' <span class="required-field">*</span>' : '';?>
                            <?=$form->field($model, 'password')->textInput()->input('password', ['value'=>'', 'class'=>'form-control', 'placeholder'=>'Придумайте пароль'])->label('Пароль'.$required);?>
                        </div>
                    </div>
                </div>
            
                <div class="box-footer">
                    <?=Html::submitButton('<i class="fa fa-check-square-o"></i> Сохранить', ['class'=>'btn btn-primary']);?>
                </div>
            </div>
        <?php ActiveForm::end();?>
    </section>
</div>