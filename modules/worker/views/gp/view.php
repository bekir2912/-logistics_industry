<?php

use app\widgets\admin_language_tab\AdminLanguageTab;
use app\widgets\admin_product_menu\AdminProductMenu;

$this->title = 'Товар ГП';
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
        <div class="row">
            <div class="col-sm-3">
                <?=AdminProductMenu::widget();?>
            </div>
            <div class="col-sm-9">
                <?php if ($model) {?>
                    <div class="box">
                        <div class="box-header">
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
                                                echo '<small class="badge badge-success">Активный</small>';
                                            } else {
                                                echo '<small class="badge badge-danger">Заблокирован</small>';
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