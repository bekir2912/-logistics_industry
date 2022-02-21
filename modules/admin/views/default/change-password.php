<?php
$this->title = 'Сменить пароль';

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
?>

<div class="main-content">
    <section class="section">
        <div class="card">
            <div class="card-body">
                <?php $form = ActiveForm::begin([
                    'id' => 'login-form',
                    'options' => ['class' => 'form-horizontal'],
                    'fieldConfig' => [
                        'template' => "{label}\n<div class=\"col-sm-6\">{input}{error}</div>",
                        'labelOptions' => ['class' => 'col-sm-3 control-label'],
                        'inputOptions' => ['class' => 'form-control'],
                        'errorOptions' => ['class' => 'help-block text-danger'],
                    ],]); ?>
                    <?=$form->field($model, 'password')->passwordInput()->input('password', ['value'=>''])->label('Новый пароль') ?>
                    
                    <div class="row">
                        <div class="col-sm-6 col-sm-offset-3">
                            <?php echo Html::submitButton('Сохранить', ['class'=>'btn btn-primary', 'style'=>'width:100%'])?>
                        </div>
                    </div>
                <?php ActiveForm::end();?>
			</div>
		</div>
	</section>
</div>