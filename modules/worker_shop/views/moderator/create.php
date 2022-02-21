<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$page = Yii::$app->request->get('id') ? 'Редактировать' : 'Добавить'; 

$this->title = $page.' модератора';
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
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
            <div class="box">
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-4">
                            <?=$form->field($model, 'name')->textInput()->input('text', ['placeholder'=>'Введите имя', 'class'=>'form-control'])->label('Имя <span class="required-field">*</span>');?>
                        </div>
                        <div class="col-sm-4">
                            <?=$form->field($model, 'lastname')->textInput()->input('text', ['placeholder'=>'Введите фамилию', 'class'=>'form-control'])->label('Фамилия');?>
                        </div>
                        <div class="col-sm-4">
                            <?=$form->field($model, 'fname')->textInput()->input('text', ['placeholder'=>'Введите отчество', 'class'=>'form-control'])->label('Отчество');?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <?=$form->field($model, 'birthday')->textInput()->input('date', ['class'=>'form-control', 'placeholder'=>'Дата рождения'])->label('Дата рождения');?>
                        </div>
                        <div class="col-sm-4">
                            <?=$form->field($model, 'login')->textInput()->input('text', ['class'=>'form-control', 'placeholder'=>'Введите логин'])->label('Логин <span class="required-field">*</span>');?>
                        </div>
                        <div class="col-sm-4">
                            <?=$form->field($model, 'phone')->textInput()->input('text', ['class'=>'form-control sms-phone', 'placeholder'=>'Введите телефон'])->label('Телефон');?>
                        </div>
                    </div>
                    <?php $required = !$model->id ? ' <span class="required-field">*</span>' : '';?>
                    <?=$form->field($model, 'password')->textInput()->input('password', ['value'=>'', 'class'=>'form-control', 'placeholder'=>'Придумайте пароль'])->label('Пароль'.$required);?>
                    <div class="row">
                        <div class="col-xs-4"> 
                            <?=$form->field($model, 'imageFiles[]')->fileInput(['class'=>'file-upload-ajax'])->label('Фото'); ?>
                        </div>
                        <div class="col-xs-4">
                            <img src="<?=$model->getPhoto('200x200/');?>" width="200" class="photo-admin-user"/>
                            <?php if ($model->image) {?>
                                <br/>
                                <a href="<?=Yii::$app->urlManager->createUrl(['/admin/default/remove-photo', 'id'=>$model->image->id])?>" class="edit-user remove-object"><i class="fa fa-times"></i> Удалить фото</a>
                            <?php }?>
                        </div>
                    </div>
                </div>
                <?php if (!$urls) {?>
                    <div class="box-footer">
                        <?=Html::submitButton('<i class="fa fa-check-square-o"></i> Сохранить', ['class'=>'btn btn-primary']);?>
                    </div>
                <?php }?>
            </div>
            <?php if ($urls) {?>
                <div class="box">
                    <div class="box-header">
                        Доступ
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-sm-3">
                                <strong>Страницы</strong><hr/>
                                <?php foreach ($urls as $v) {?>
                                    <?php if ($v->type == 'single') {?>
                                        <?=$form->field($model, 'moderator_access[]')->input('checkbox', ['value'=>$v->id, 'id'=>$v->id, 'checked'=>$v->moderatorAccessUser ? true : false, 'class'=>''])->label($v->name, ['class'=>'', 'for'=>$v->id]);?>
                                    <?php }?>
                                <?php }?>
                            </div>
                            <div class="col-sm-3">
                                <strong>Пользователи</strong><hr/>
                                <?php foreach ($urls as $v) {?>
                                    <?php if ($v->type == 'user') {?>
                                        <?=$form->field($model, 'moderator_access[]')->input('checkbox', ['value'=>$v->id, 'id'=>$v->id, 'checked'=>$v->moderatorAccessUser ? true : false, 'class'=>''])->label($v->name, ['class'=>'', 'for'=>$v->id]);?>
                                    <?php }?>
                                <?php }?>
                            </div>
                            <div class="col-sm-3">
                                <strong>Управление</strong><hr/>
                                <?php foreach ($urls as $v) {?>
                                    <?php if ($v->type == 'management') {?>
                                        <?=$form->field($model, 'moderator_access[]')->input('checkbox', ['value'=>$v->id, 'id'=>$v->id, 'checked'=>$v->moderatorAccessUser ? true : false, 'class'=>''])->label($v->name, ['class'=>'', 'for'=>$v->id]);?>
                                    <?php }?>
                                <?php }?>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <?=Html::submitButton('<i class="fa fa-check-square-o"></i> Сохранить', ['class'=>'btn btn-primary']);?>
                    </div>
                </div>
            <?php }?>
        <?php ActiveForm::end();?>
    </section>
</div>

<?php
$script = <<<JS
    $('input').iCheck({
        checkboxClass: 'icheckbox_square-blue',
        radioClass: 'iradio_square-blue',
        increaseArea: '20%'
    });
JS;

$this->registerJs($script);
?>