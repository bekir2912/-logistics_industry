<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

use app\widgets\admin_language_tab\AdminLanguageTab;
use app\widgets\admin_product_menu\AdminProductMenu;

$this->title = 'Товар';
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
        <?php if (Yii::$app->session->hasFlash('product_saved')) {?>
            <div class="alert alert-success text-center">
                <?=Yii::$app->session->getFlash('product_saved');?>
            </div>
        <?php }?>
        <?php if (Yii::$app->session->hasFlash('product_added_shop')) {?>
            <div class="alert alert-success text-center">
                <?=Yii::$app->session->getFlash('product_added_shop');?>
            </div>
        <?php }?>
        <?php if (Yii::$app->session->hasFlash('product_removed_shop')) {?>
            <div class="alert alert-success text-center">
                <?=Yii::$app->session->getFlash('product_removed_shop');?>
            </div>
        <?php }?>
        <?php if (Yii::$app->session->hasFlash('defect_setted')) {?>
            <div class="alert alert-success text-center">
                <?=Yii::$app->session->getFlash('defect_setted');?>
            </div>
        <?php }?>
        <?php if (Yii::$app->session->hasFlash('defect_unsetted')) {?>
            <div class="alert alert-success text-center">
                <?=Yii::$app->session->getFlash('defect_unsetted');?>
            </div>
        <?php }?>
        <div class="row">
            <div class="col-sm-3">
                <?=AdminProductMenu::widget();?>
            </div>
            <div class="col-sm-9">
                <?php if ($model) {?>
                    <div class="box box-info color-palette-box">
                        <div class="box-header">
                            <?php if ($model->status == 1) {?>
                                <a href="<?=Yii::$app->urlManager->createUrl(['/admin/product/set-defect', 'id'=>$model->id]);?>" class="btn btn-danger">Переместить в дефект</a>
                            <?php }?>
                            <?php if ($model->status == 2) {?>
                                <a href="<?=Yii::$app->urlManager->createUrl(['/admin/product/unset-defect', 'id'=>$model->id]);?>" class="btn btn-success">Вытащить из дефекта</a>
                            <?php }?>

                            <?php if ($model->shopProduct) {?>
                                <a href="<?=Yii::$app->urlManager->createUrl(['/admin/product/remove-shop', 'id'=>$model->id]);?>" class="btn btn-warning"> Удалить из магазина</a>
                            <?php } else {?>
                                <a href="javascript:;" class="btn btn-success" data-toggle="modal" data-target="#add-shop"> Добавить в магазин</a>
                            <?php }?>

                            <div class="btn-group"><button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                                <span class="fa fa-cog"></span>
                            </button>
                            <ul class="dropdown-menu pull-left">
                                <li><a href="<?=Yii::$app->urlManager->createUrl(['/admin/product/create']);?>" class="dropdown-item">Добавить товар</a></li>
                                <li><a href="<?=Yii::$app->urlManager->createUrl(['/admin/product/create', 'id'=>$model->id]);?>" class="dropdown-item">Редактировать</a></li>
                                <li><a href="<?=Yii::$app->urlManager->createUrl(['/admin/product/remove', 'id'=>$model->id]);?>" class="dropdown-item" class="remove-object">Удалить</a></li>
                            </ul>
                        </div>
                        <div class="box-body">
                            <table class="table table-striped">
                                <tr>
                                    <td>ID:</td>
                                    <td><?=$model->id ? $model->id : '-';?></td>
                                </tr>
                                <tr>
                                    <td>Название:</td>
                                    <td><?=$model->name_ru ? $model->name_ru : '-';?></td>
                                </tr>
                                <tr>
                                    <td>Артикул:</td>
                                    <td><?=$model->article ? $model->article : '-';?></td>
                                </tr>
                                <tr>
                                    <td>Ряд:</td>
                                    <td><?=$model->stock ? '<a href="'.Yii::$app->urlManager->createUrl(['/admin/stock/view', 'id'=>$model->stock_id]).'">'.$model->stock->name_ru.'</a>' : '-';?></td>
                                </tr>
                                <tr>
                                    <td>Этаж:</td>
                                    <td><?=$model->stack ? $model->stack->stack_number : '-';?></td>
                                </tr>
                                <tr>
                                    <td>Ячейка:</td>
                                    <td><?=$model->stackShelf ? $model->stackShelf->shelf_number : '-';?></td>
                                </tr>
                                <tr>
                                    <td>Статус:</td>
                                    <td>
                                        <?php
                                            if ($model->status == 1) {
                                                echo '<span class="label label-success">Активный</span>';
                                            } else {
                                                echo '<span class="label label-danger">Заблокирован</span>';
                                            }
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Регион:</td>
                                    <td><?=$model->region ? $model->region->name_ru : '-';?></td>
                                </tr>
                                <tr>
                                    <td>Производитель:</td>
                                    <td><?=$model->manufacturer ? $model->manufacturer->name_ru : '-';?></td>
                                </tr>
                                <tr>
                                    <td>Еденица измерения:</td>
                                    <td><?=$model->unit ? $model->unit->name_ru : '-';?></td>
                                </tr>
                                <tr>
                                    <td>Цена:</td>
                                    <td><?=$model->price_sale ? $model->price_sale : '-';?></td>
                                </tr>
                            </table>
                            <br/>
                        </div>
                    </div>
                <?php } else {?>
                    <div class="alert alert-warning text-center">Данных нет</div>
                <?php }?>
            </div>
        </div>
    </section>
</div>

<div class="modal fade" id="add-shop">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">Добавить в магазин</h4>
            </div>
            <?php $form = ActiveForm::begin(); ?>
                <div class="modal-body">
                    <?=$form->field($shop, 'product_id')->hiddenInput(['value'=>$model->id])->label(false);?>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label">Номер этажа</label>
                                <select name="ShopProduct[shop_stack_id]" class="form-control select-drop-stack" style="margin-top">
                                    <option value="">Выбрать этаж</option>
                                    <?php foreach ($stacks as $stack_key => $stack) {?>
                                        <option value="<?=$stack_key;?>"><?=$stack;?></option>
                                    <?php }?>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label">Номер ячейки</label>
                                <select name="ShopProduct[shop_stack_shelving_id]" class="form-control select-drop-stack" style="margin-top">
                                    <option value="">Выбрать ячейку</option>
                                    <!-- <?php foreach ($shelvings as $shef_key => $shelf) {?>
                                        <option value="<?=$shelf_key;?>"><?=$shelf;?></option>
                                    <?php }?> -->
                                </select>
                            </div>
                        </div>
                    </div>
                    <?=$form->field($shop, 'amount')->textInput()->input('text', ['placeholder'=>'Введите кол-во', 'class'=>'form-control'])->label('Кол-во');?>
                </div>
                <div class="modal-footer">
                    <a type="button" class="btn btn-default pull-left" data-dismiss="modal">Закрыть</a>
                    <?=Html::submitButton('<i class="fa fa-check-square-o"></i> Сохранить', ['class'=>'btn btn-primary']);?>
                </div>
            <?php ActiveForm::end();?>
        </div>
    </div>
</div>

<?php
$script = <<<JS
    $(document).on('change', '.select-drop-stack', function() {
        var id = $(this).val();
        var block = $(this).parent().parent().next().find('select');
        block.html('');

        if (id) {
            $.ajax({
                url: '/admin/shop/get-shelvings',
                type: 'post',
                data: {'id':id},
                success: function(data) {
                    if (data && data['data']) {
                        block.append('<option value="">Выбрать ячейку</option>');
                        for (var i in data['data']) {
                            block.append('<option value="'+data['data'][i]['id']+'">'+data['data'][i]['shelf_number']+'</option>');
                        }
                    }
                }
            });
        }

        return false;
    });
JS;

$this->registerJs($script);
?>