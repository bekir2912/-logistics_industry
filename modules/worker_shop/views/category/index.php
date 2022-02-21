<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

use mihaildev\ckeditor\CKEditor;

use app\widgets\admin_language_tab\AdminLanguageTab;

$this->title = 'Категории';
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
        <div class="category-result alert alert-success text-center alert-bottom" id="save-category-success">Категории успешно сохранены</div>
        <div class="category-result alert alert-danger text-center alert-bottom" id="save-category-error">Ошибка при сохранении категорий</div>
        <?php if (Yii::$app->session->hasFlash('category_saved')) {?>
            <div class="callout callout-success text-center">
                <?=Yii::$app->session->getFlash('category_saved');?>
            </div>
        <?php }?>
        <?php if (Yii::$app->session->hasFlash('category_removed')) {?>
            <div class="callout callout-success text-center">
                <?=Yii::$app->session->getFlash('category_removed');?>
            </div>
        <?php }?>
        <?php if (Yii::$app->session->hasFlash('photo_removed')) {?>
            <div class="callout callout-success text-center">
                <?=Yii::$app->session->getFlash('photo_removed');?>
            </div>
        <?php }?>
        
        <?php if ($categories) {?>
            <div id="category-form-block">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="box box-info color-palette-box">
                            <div class="box-header with-border">
                                <strong>Список категорий</strong>
                            </div>
                            <div class="box-body">
                                <div id="nestable3" class="dd">
                                    <ol class="dd-list">
                                        <?php function category($data, $count = 0) {?>
                                            <?php foreach ($data as $k => $c) {?>
                                                <li class="dd-item dd3-item dd-collapsed">
                                                    <div class="dd-handle dd3-handle"></div>
                                                    <div class="dd3-content">
                                                        <div class="btn-group pull-right">
                                                            <a href="javascript:;" type="button" class="dropdown-toggle" data-toggle="dropdown">
                                                                <span class="fa fa-cog"></span>
                                                            </a>
                                                            <ul class="dropdown-menu pull-left">
                                                                <li><a href="javascript:;" class="add_category" data-value="<?=$c['id'];?>" data-toggle="modal" data-target="#add-category">Добавить подкатегорию</a></li>
                                                                <li><a href="javascript:;" class="view_category" data-value="<?=$c['id'];?>" data-toggle="modal" data-target="#view-category">Посмотреть</a></li>
                                                                <li><a href="javascript:;" class="update_category" data-value="<?=$c['id'];?>" data-toggle="modal" data-target="#update-category">Редактировать</a></li>
                                                                <li><a href="<?=Yii::$app->urlManager->createUrl(['/admin/category/remove', 'id'=>$c['id']]);?>" class="remove-object">Удалить</a></li>
                                                            </ul>
                                                        </div>
                                                        <?=$c['name_ru'];?>
                                                    </div>
                                                    <?php if (array_key_exists('children', $c)) {?>
                                                        <ol class="dd-list">
                                                            <?php category($c['children'], $count++);?>
                                                        </ol>
                                                    <?php }?>
                                                    <?php if ($count == 3) {?>
                                                        <input type="hidden" name="third" class="third"  value="<?=$c['id']?>"/>
                                                    <?php }?>
                                                    <?php if ($count == 1) {?>
                                                        <input type="hidden" name="top" class="top" value="<?=$c['id']?>"/>
                                                    <?php }?>
                                                    <?php if ($count == 0) {?>
                                                        <input type="hidden" name="second" class="second" value="<?=$c['id']?>"/>
                                                    <?php }?>
                                                </li>
                                            <?php }?>
                                        <?php } category($categories);?>
                                    </ol>
                                </div>
                            </div>
                            <div class="box-footer">
                                <?=Html::submitButton('<i class="fa fa-check-square-o"></i> Сохранить сортировку', ['name'=>'save_sort', 'class'=>'btn btn-primary width-full', 'id'=>'save-category-sort']);?>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="box box-warning color-palette-box">
                            <div class="box-header with-border">
                                <strong>Добавить новую категорию</strong>
                            </div>
                            <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]);?>
                                <div class="box-body">
                                    <?=AdminLanguageTab::widget();?>
                                    <br/>
                                    <?//=$form->field($model, 'icon', ['template'=>'{label}<div class="input-group"><span class="input-group-addon"><i class="fa fa-image"></i></span>{input}</div>{error}'])->textInput()->input('text', ['class'=>'form-control'])->label('Иконка (font-awesome)')?>
                                    <div class="lang-block lang-block-ru">
                                        <?=$form->field($model, 'name_ru', ['template'=>'{label}<div class="input-group"><span class="input-group-addon"><i class="fa fa-pencil"></i></span>{input}</div>{error}'])->textInput()->input('text', ['class'=>'form-control'])->label('Название категории (RU) <span class="required-field">*</span>')?>
                                        <?=$form->field($model, 'description_ru')->widget(CKEditor::className(),[
                                            'editorOptions' => [
                                                'preset' => 'basic',
                                                'inline' => false,
                                                'height' => 150
                                            ],
                                        ])->label('Контент (RU)');?>
                                    </div>
                                    <div class="lang-block lang-block-uz">
                                        <?=$form->field($model, 'name_uz', ['template'=>'{label}<div class="input-group"><span class="input-group-addon"><i class="fa fa-pencil"></i></span>{input}</div>{error}'])->textInput()->input('text', ['class'=>'form-control'])->label('Название категории (UZ)');?>
                                        <?=$form->field($model, 'description_uz')->widget(CKEditor::className(),[
                                            'editorOptions' => [
                                                'preset' => 'basic',
                                                'inline' => false,
                                                'height' => 150
                                            ],
                                        ])->label('Контент (UZ)');?>
                                    </div>
                                    <div class="lang-block lang-block-en">
                                        <?=$form->field($model, 'name_en', ['template'=>'{label}<div class="input-group"><span class="input-group-addon"><i class="fa fa-pencil"></i></span>{input}</div>{error}'])->textInput()->input('text', ['class'=>'form-control'])->label('Название категории (EN)');?>
                                        <?=$form->field($model, 'description_en')->widget(CKEditor::className(),[
                                            'editorOptions' => [
                                                'preset' => 'basic',
                                                'inline' => false,
                                                'height' => 150
                                            ],
                                        ])->label('Контент (EN)');?>
                                    </div>
                                    <?=$form->field($model, 'imageFiles[]')->fileInput(['multiple' => true, 'accept' => 'image/*', 'class'=>'file-upload-ajax'])->label('Баннер');?>
                                    
                                </div>
                                <div class="box-footer">
                                    <?=Html::submitButton('<i class="fa fa-check-square-o"></i> Добавить', ['name'=>'add_item', 'class'=>'btn btn-primary width-full']);?>
                                </div>
                            <?php ActiveForm::end();?>
                        </div>
                    </div>
                </div>
                <hr/>
            </div>
        <?php } else {?>
            <div class="callout callout-warning text-center"> Категорий пока нет, создайте первую категорию</div>
            <div class="box box-info color-palette-box">
                <?php $form = ActiveForm::begin(); ?>
                    <div class="box-body">
                        <?=AdminLanguageTab::widget();?>
                        <br/>
                        <?//=$form->field($model, 'icon', ['template'=>'{label}<div class="input-group"><span class="input-group-addon"><i class="fa fa-image"></i></span>{input}</div>{error}'])->textInput()->input('text', ['class'=>'form-control'])->label('Иконка (font-awesome)')?>
                        <div class="lang-block lang-block-ru">
                            <?=$form->field($model, 'name_ru', ['template'=>'{label}<div class="input-group"><span class="input-group-addon"><i class="fa fa-pencil"></i></span>{input}</div>{error}'])->textInput()->input('text')->label('Название категории (RU) <span class="required-field">*</span>');?>
                            <?=$form->field($model, 'description_ru')->widget(CKEditor::className(),[
                                'editorOptions' => [
                                    'preset' => 'basic',
                                    'inline' => false,
                                    'height' => 200
                                ],
                            ])->label('Контент (RU)');?>
                        </div>
                        <div class="lang-block lang-block-uz">
                            <?=$form->field($model, 'name_uz', ['template'=>'{label}<div class="input-group"><span class="input-group-addon"><i class="fa fa-pencil"></i></span>{input}</div>{error}'])->textInput()->input('text')->label('Название категории (UZ)');?>
                            <?=$form->field($model, 'description_uz')->widget(CKEditor::className(),[
                                'editorOptions' => [
                                    'preset' => 'basic',
                                    'inline' => false,
                                    'height' => 200
                                ],
                            ])->label('Контент (UZ)');?>
                        </div>
                        <div class="lang-block lang-block-en">
                            <?=$form->field($model, 'name_en', ['template'=>'{label}<div class="input-group"><span class="input-group-addon"><i class="fa fa-pencil"></i></span>{input}</div>{error}'])->textInput()->input('text')->label('Название категории (EN)');?>
                            <?=$form->field($model, 'description_en')->widget(CKEditor::className(),[
                                'editorOptions' => [
                                    'preset' => 'basic',
                                    'inline' => false,
                                    'height' => 200
                                ],
                            ])->label('Контент (EN)');?>
                        </div>
                        <?=$form->field($model, 'imageFiles[]')->fileInput(['multiple' => true, 'accept' => 'image/*', 'class'=>'file-upload-ajax'])->label('Баннер');?>
                        
                    </div>
                    <div class="box-footer">
                        <div class="text-right">
                            <?=Html::submitButton('<i class="fa fa-check-square-o"></i> Сохранить', ['class'=>'btn btn-primary']);?>
                        </div>
                    </div>
                <?php ActiveForm::end()?>
            </div>
        <?php }?>
    </section>
</div>

<div class="modal fade" id="view-category">
    <div class="modal-dialog">
        <div class="text-center" id="preloader">
            <img src="/assets_files/images/preloader.gif">
        </div>
        <div class="modal-content" id="category-view-block">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">Просмотр категории</h4>
            </div>
            <div class="modal-body">
                <?=AdminLanguageTab::widget();?>
                <br/>
                <div class="row">
                    <div class="col-sm-3">
                        <img src="" id="category-view-icon" width="100%"/>
                    </div>
                    <div class="col-sm-9">
                        <i id="category-icon-fa"></i>
                        <div class="lang-block lang-block-ru">
                            <strong id="category-name-ru"></strong>
                            <p id="category-description-ru"></p>
                        </div>
                        <div class="lang-block lang-block-uz">
                            <strong id="category-name-uz"></strong>
                            <p id="category-description-uz"></p>
                        </div>
                        <div class="lang-block lang-block-en">
                            <strong id="category-name-en"></strong>
                            <p id="category-description-en"></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Закрыть</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="update-category">
    <div class="modal-dialog">
        <div class="text-center" id="preloader-update">
            <img src="/assets_files/images/preloader.gif">
        </div>
        <div class="modal-content" id="category-update-block">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">Редактирование категории</h4>
            </div>
            <?php $form = ActiveForm::begin(); ?>
                <?=$form->field($model, 'id')->hiddenInput(['id'=>'category-update-id'])->label(false);?>
                <?=$form->field($model, 'parent_id')->hiddenInput(['id'=>'category-update-parent_id'])->label(false);?>
                <div class="modal-body">
                    <?=AdminLanguageTab::widget();?>
                    <br/>
                    <?//=$form->field($model, 'icon', ['template'=>'{label}<div class="input-group"><span class="input-group-addon"><i class="fa fa-image"></i></span>{input}</div>{error}'])->textInput()->input('text', ['id'=>'category-update-icon-fa'])->label('Иконка (font-awesome)')?>
                    <div class="lang-block lang-block-ru">
                        <?=$form->field($model, 'name_ru', ['template'=>'{label}<div class="input-group"><span class="input-group-addon"><i class="fa fa-pencil"></i></span>{input}</div>{error}'])->textInput()->input('text', ['id'=>'category-update-name-ru'])->label('Название категории (RU) <span class="required-field">*</span>');?>
                        <?=$form->field($model, 'description_ru')->widget(CKEditor::className(),[
                            'editorOptions' => [
                                'preset' => 'basic',
                                'inline' => false,
                                'height' => 200
                            ],
                            'options' => [
                                'id' => 'category-update-description-ru'
                            ]
                        ])->label('Контент (RU)');?>
                    </div>
                    <div class="lang-block lang-block-uz">
                        <?=$form->field($model, 'name_uz', ['template'=>'{label}<div class="input-group"><span class="input-group-addon"><i class="fa fa-pencil"></i></span>{input}</div>{error}'])->textInput()->input('text', ['id'=>'category-update-name-uz'])->label('Название категории (UZ)');?>
                        <?=$form->field($model, 'description_uz')->widget(CKEditor::className(),[
                            'editorOptions' => [
                                'preset' => 'basic',
                                'inline' => false,
                                'height' => 200
                            ],
                            'options' => [
                                'id' => 'category-update-description-uz'
                            ]
                        ])->label('Контент (UZ)');?>
                    </div>
                    <div class="lang-block lang-block-en">
                        <?=$form->field($model, 'name_en', ['template'=>'{label}<div class="input-group"><span class="input-group-addon"><i class="fa fa-pencil"></i></span>{input}</div>{error}'])->textInput()->input('text', ['id'=>'category-update-name-en'])->label('Название категории (EN)');?>
                        <?=$form->field($model, 'description_en')->widget(CKEditor::className(),[
                            'editorOptions' => [
                                'preset' => 'basic',
                                'inline' => false,
                                'height' => 200
                            ],
                            'options' => [
                                'id' => 'category-update-description-en'
                            ]
                        ])->label('Контент (EN)');?>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <?=$form->field($model, 'imageFiles[]')->fileInput(['multiple' => true, 'accept' => 'image/*', 'class'=>'file-upload-ajax'])->label('Иконка');?>
                        </div>
                        <div class="col-sm-6">
                            <img id="category-update-icon" width="200px"/>
                            <br/>
                            <a id="remove-photo" class="remove-object">Удалить фото</a>
                        </div>
                    </div>
                    <?=$form->field($model, 'main')->checkbox(['id'=>'check-main'])->label('Показать на главной');?>
                </div>
                <div class="modal-footer">
                    <a type="button" class="btn btn-default pull-left" data-dismiss="modal">Закрыть</a>
                    <?=Html::submitButton('<i class="fa fa-check-square-o"></i> Сохранить', ['class'=>'btn btn-primary']);?>
                </div>
            <?php ActiveForm::end();?>
        </div>
    </div>
</div>

<div class="modal fade" id="add-category">
    <div class="modal-dialog">
        <div class="text-center" id="preloader-add">
            <img src="/assets_files/images/preloader.gif">
        </div>
        <div class="modal-content" id="category-add-block">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">Добавить категорию</h4>
            </div>
            <?php $form = ActiveForm::begin(); ?>
                <?=$form->field($model, 'parent_id')->hiddenInput(['id'=>'category-add-parent_id'])->label(false);?>
                <div class="modal-body">
                    <?=AdminLanguageTab::widget();?>
                    <br/>
                    <?//=$form->field($model, 'icon', ['template'=>'{label}<div class="input-group"><span class="input-group-addon"><i class="fa fa-image"></i></span>{input}</div>{error}'])->textInput()->input('text', ['id'=>'category-add-icon-fa'])->label('Иконка (font-awesome)')?>
                    <div class="lang-block lang-block-ru">
                        <?=$form->field($model, 'name_ru', ['template'=>'{label}<div class="input-group"><span class="input-group-addon"><i class="fa fa-pencil"></i></span>{input}</div>{error}'])->textInput()->input('text', ['id'=>'category-add-name-ru'])->label('Название категории (RU) <span class="required-field">*</span>');?>
                        <?=$form->field($model, 'description_ru')->widget(CKEditor::className(),[
                            'editorOptions' => [
                                'preset' => 'basic',
                                'inline' => false,
                                'height' => 200
                            ],
                            'options' => [
                                'id' => 'category-add-description-ru'
                            ]
                        ])->label('Контент (RU)');?>
                    </div>
                    <div class="lang-block lang-block-uz">
                        <?=$form->field($model, 'name_uz', ['template'=>'{label}<div class="input-group"><span class="input-group-addon"><i class="fa fa-pencil"></i></span>{input}</div>{error}'])->textInput()->input('text', ['id'=>'category-add-name-uz'])->label('Название категории (UZ)');?>
                        <?=$form->field($model, 'description_uz')->widget(CKEditor::className(),[
                            'editorOptions' => [
                                'preset' => 'basic',
                                'inline' => false,
                                'height' => 200
                            ],
                            'options' => [
                                'id' => 'category-add-description-uz'
                            ]
                        ])->label('Контент (UZ)');?>
                    </div>
                    <div class="lang-block lang-block-en">
                        <?=$form->field($model, 'name_en', ['template'=>'{label}<div class="input-group"><span class="input-group-addon"><i class="fa fa-pencil"></i></span>{input}</div>{error}'])->textInput()->input('text', ['id'=>'category-add-name-en'])->label('Название категории (EN)');?>
                        <?=$form->field($model, 'description_en')->widget(CKEditor::className(),[
                            'editorOptions' => [
                                'preset' => 'basic',
                                'inline' => false,
                                'height' => 200
                            ],
                            'options' => [
                                'id' => 'category-add-description-en'
                            ]
                        ])->label('Контент (EN)');?>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <?=$form->field($model, 'imageFiles[]')->fileInput(['multiple' => true, 'accept' => 'image/*', 'class'=>'file-upload-ajax'])->label('Баннер');?>
                        </div>
                        <div class="col-sm-6">
                            <img id="category-add-icon" width="200px"/>
                        </div>
                    </div>
                    
                </div>
                <div class="modal-footer">
                    <a type="button" class="btn btn-default pull-left" data-dismiss="modal">Закрыть</a>
                    <?=Html::submitButton('<i class="fa fa-check-square-o"></i> Сохранить', ['class'=>'btn btn-primary']);?>
                </div>
            <?php ActiveForm::end();?>
        </div>
    </div>
</div>