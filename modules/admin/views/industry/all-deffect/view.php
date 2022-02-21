<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

$this->title = 'Дефект';
$this->params['breadcrumbs'][] = $this->title;

?>



<div class="content-wrapper">
    <section class="content-header">
        <h1><?=$this->title . ' ' . $model->number_poddon;?></h1>

        <ol class="breadcrumb">
            <li><a href="<?=Yii::$app->urlManager->createUrl(['/admin/'])?>"><i class="fa fa-dashboard"></i> Главная</a></li>
            <li class="active"><?=$this->title;?></li>
        </ol>
    </section>
    <section class="content">
        <?php if (Yii::$app->session->hasFlash('alldefect_saved')) {?>
            <div class="alert alert-success text-center">
                <?=Yii::$app->session->getFlash('alldefect_saved');?>
            </div>
        <?php } ?>
        <?php if (Yii::$app->session->hasFlash('close_deffect')) {?>
            <div class="alert alert-success text-center">
                <?=Yii::$app->session->getFlash('close_deffect');?>
            </div>
        <?php } ?>

        <div class="row">
            <div class="col-sm-12">
                <?php if ($model) {?>
                <div class="box box-info color-palette-box">
                    <div class="box-header">

                        <?php if ($model->in_stock == 0) {?>
                            <a href="<?=Yii::$app->urlManager->createUrl(['/admin/industry/all-deffect/create', 'id_department' => $model->dep_id, 'dep_name' => $model->department_id ]);?>" class="btn btn-danger">Добавить еще дефект</a>
                        <?php }?>

                        <?php if ($model->is_save == 0 && $model->in_stock == 0) {?>
                            <a href="<?=Yii::$app->urlManager->createUrl(['/admin/industry/all-deffect/stock', 'id' => $model->id ]);?>" class="btn btn-warning">Отправить на склад</a>
                        <?php }?>



                        <div class="btn-group"><button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                                <span class="fa fa-cog"></span>
                            </button>
                            <ul class="dropdown-menu pull-left">
                                <li><a href="<?=Yii::$app->urlManager->createUrl(['/admin/industry/all-deffect']);?>" class="dropdown-item">Все деффекты</a></li>
                                <li><a href="<?=$url = Url::to(['/admin/industry/all-deffect/create', 'id'=>$model->id , 'id_department'=> $model->dep_id, 'dep_name'=> $model->department_id]);?>" class="dropdown-item">Редактировать</a></li>
                                <li><a href="<?=Yii::$app->urlManager->createUrl(['/admin/industry/all-deffect/remove', 'id'=>$model->id]);?>" class="dropdown-item" class="remove-object">Удалить</a></li>
                            </ul>
                        </div>

                        <div class="box-body">
                            <table class="table table-striped">
                                <tr>
                                    <td>ID:</td>
                                    <td><?=$model->id ? $model->id : '-';?></td>
                                </tr>
                                <tr>
                                    <td>Отдел:</td>
                                    <td><?=$model->department_id ? $model->department->name_ru : '-';?></td>
                                </tr>
                                <tr>
                                    <td>Дефект:</td>
                                    <td><?=$model->deffect ? $model->deffect->name_ru : '-';?></td>
                                </tr>
                                <tr>
                                    <td>Модель:</td>
                                    <td><?=$model->model_id ? $model->model->name_ru : '-';?></td>
                                </tr>
                                <tr>
                                    <td>Деталь:</td>
                                    <td><?=$model->detail ? $model->detail->name_ru : '-';?></td>
                                </tr>
                                <tr>
                                    <td>Пользатель:</td>
                                    <td><?=$model->user ? $model->user->name : '-';?></td>
                                </tr>
                                <tr>
                                    <td>Линия:</td>
                                    <td><?=$model->line ? $model->line->name_ru : '-';?></td>
                                </tr>
                                <tr>
                                    <td>Номер поддона:</td>
                                    <td><?=$model->number_poddon ? $model->number_poddon : '-';?></td>
                                </tr>
                                <tr>
                                    <td>Текущая операция:</td>
                                    <td><?=$model->current_operation ? $model->current_operation : '-';?></td>
                                </tr>
                                <tr>
                                    <td>Кол-во дефектов:</td>
                                    <td><?=$model->count_deffect ? ($model->count_deffect . ' ' . $model->unit->name_ru ) : '-';?></td>
                                </tr>
                                <tr>
                                    <td>Описание:</td>
                                    <td><?=$model->description_ru ? $model->description_ru : '-';?></td>
                                </tr>
                                <tr>
                                    <td>Доработка:</td>
                                    <td>
                                        <?php
                                        if ($model->is_save == 1) {
                                            echo '<span class="label label-success">Возможно</span>';
                                        } else {
                                            echo '<span class="label label-danger">Невозможно</span>';
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Статус:</td>
                                    <td>
                                        <?php
                                        if ($model->status == 1) {
                                            echo '<span class="label label-warning">Активный</span>';
                                        } else {
                                            echo '<span class="label label-success">Закрыт</span>';
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>На складе:</td>
                                    <td>
                                        <?php
                                        if ($model->in_stock == 0) {
                                            echo '<span class="label label-primary">Нет</span>';
                                        } else {
                                            echo '<span class="label label-success">Да</span>';
                                        }
                                        ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td>Дата изменения:</td>
                                    <td><?=$model->updated_at ? date('d.m.Y H:i',$model->updated_at) : '-';?></td>
                                </tr>
                                <tr>
                                    <td>Дата создания:</td>
                                    <td><?=$model->created_at ? date('d.m.Y H:i',$model->created_at) : '-';?></td>
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
