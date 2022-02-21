<?php

use app\widgets\admin_language_tab\AdminLanguageTab;
use app\widgets\admin_shop_menu\AdminShopMenu;

$this->title = 'Магазин';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="content-wrapper">
    <section class="content-header">
        <h1><?=$this->title;?></h1>

        <ol class="breadcrumb">
            <li><a href="<?=Yii::$app->urlManager->createUrl(['/worker_shop/'])?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active"><?=$this->title;?></li>
        </ol>
    </section>
    <section class="content">
        <?php if (Yii::$app->session->hasFlash('shop_saved')) {?>
            <div class="alert alert-success text-center">
                <?=Yii::$app->session->getFlash('shop_saved');?>
            </div>
        <?php }?>
        <div class="row">
            <div class="col-sm-3">
                <?=AdminShopMenu::widget();?>
            </div>
            <div class="col-sm-9">
                <?php if ($model) {?>
                    <div class="box">
                        <div class="box-header">
                            <a href="<?=Yii::$app->urlManager->createUrl(['/worker_shop/shop/create', 'id'=>$model->id]);?>" class="btn btn-primary"><i class="fa fa-pencil"></i> Редактировать</a></li>
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
                                    <td>Статус:</td>
                                    <td>
                                        <?php
                                            if ($model->status == 1) {
                                                echo '<small class="label label-success">Активный</small>';
                                            } else {
                                                echo '<small class="label label-danger">Заблокирован</small>';
                                            }
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Дата обновления:</td>
                                    <td><?=$model->date ? $model->date : '-';?></td>
                                </tr>
                            </table>
                            <br/>
                        </div>
                    </div>
                    <div class="box">
                        <div class="box-header">
                            Описание
                        </div>
                        <div class="box-body">
                            <?php if ($model->description_ru) {?>
                                <?=$model->description_ru;?>
                            <?php } else {?>
                                <div class="alert alert-warning text-center">Описания нет</div>
                            <?php }?>
                        </div>
                    </div>
                <?php } else {?>
                    <div class="alert alert-warning text-center">Данных нет</div>
                <?php }?>
            </div>
        </div>
    </section>
</div>