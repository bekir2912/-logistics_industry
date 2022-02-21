<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\widgets\admin_gp_menu\AdminGpMenu;




$this->title = 'Отдел';
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
        <?php if (Yii::$app->session->hasFlash('department_saved')) {?>
            <div class="alert alert-success text-center">
                <?=Yii::$app->session->getFlash('department_saved');?>
            </div>
        <?php }?>
        <div class="row">
            <div class="col-sm-12">
                <?php if ($model) {?>
                <div class="box box-info color-palette-box">
                    <div class="box-header">
                        <div class="btn-group"><button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                                <span class="fa fa-cog"></span>
                            </button>
                            <ul class="dropdown-menu pull-left">
                                <li><a href="<?=Yii::$app->urlManager->createUrl(['/admin/industry/department/create']);?>" class="dropdown-item">Добавить отдел</a></li>
                                <li><a href="<?=Yii::$app->urlManager->createUrl(['/admin/industry/department/create', 'id'=>$model->id]);?>" class="dropdown-item">Редактировать</a></li>
<!--                                <li><a href="--><?//=Yii::$app->urlManager->createUrl(['/admin/industry/department/remove', 'id'=>$model->id]);?><!--" class="dropdown-item" class="remove-object">Удалить</a></li>-->
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
                                    <td>Сортировка:</td>
                                    <td><?=$model->sort ? $model->sort : '-';?></td>
                                </tr>
                                <tr>
                                    <td>Дата изменения:</td>
                                    <td><?=$model->updated_at ? date('d.m.Y H:i',$model->updated_at) : '-';?></td>
                                </tr>
                                <tr>
                                    <td>Дата создания:</td>
                                    <td><?=$model->created_at ? date('d.m.Y H:i',$model->updated_at) : '-';?></td>
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