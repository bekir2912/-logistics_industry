<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Перевод сайта';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="main-content">
    <section class="section">
        <?php if (Yii::$app->session->hasFlash('word_saved')) {?>
            <div class="alert alert-success text-center">
                <?=Yii::$app->session->getFlash('word_saved');?>
            </div>
        <?php }?>
        <?php if (Yii::$app->session->hasFlash('word_removed')) {?>
            <div class="alert alert-success text-center">
                <?=Yii::$app->session->getFlash('word_removed');?>
            </div>
        <?php }?>
        <div class="card">
            <div class="card-header">
                <div class="pull-right">
                    <a href="<?=Yii::$app->urlManager->createUrl(['/worker/translate/create']);?>" class="btn btn-primary">
                        <i class="fa fa-pencil"></i> Добавить перевод
                    </a>
                </div>
            </div>
            <?php if ($words) {?>
                <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data'], 'id' => 'form-profile']);?>
                    <div class="card-body">
                        <table class="table table-striped">
                            <tr>
                                <th><img src="/assets_files/images/languages/ru.png" width="25"> Русский</th>
                                <th><img src="/assets_files/images/languages/uz.png" width="25"> Узбекский</th>
                                <th><img src="/assets_files/images/languages/en.png" width="25"> Английский</th>
                                <th>Действия</th>
                            </tr>
                            <?php foreach ($words as $k => $v) {?>
                                <tr>
                                    <td>
                                        <input type="hidden" name="Words[ids][]" value="<?=$v->id;?>"/>
                                        <input type="text" name="Words[names_ru][]" value="<?=$v->name_ru;?>" class="form-control"/>
                                    </td>
                                    <td><input type="text" name="Words[names_uz][]" value="<?=$v->name_uz;?>" class="form-control"/></td>
                                    <td><input type="text" name="Words[names_en][]" value="<?=$v->name_en;?>" class="form-control"/></td>
                                    <td><a href="<?=Yii::$app->urlManager->createUrl(['/worker/translate/remove', 'id'=>$v->id]);?>" class="remove-object btn btn-danger"><i class="fas fa-trash"></i> Удалить</a></td>
                                </tr>
                            <?php }?>
                        </table>
                    </div>
                    <div class="card-footer">
                        <?=Html::submitButton('<i class="fa fa-check-square-o"></i> Сохранить', ['class'=>'btn btn-primary']);?>
                    </div>
                <?php ActiveForm::end();?>
            <?php } else {?>
                <div class="card-body">
                    <div class="alert alert-warning text-center">Перевода нет</div>
                </div>
            <?php }?>
        </div>
    </section>
</div>  