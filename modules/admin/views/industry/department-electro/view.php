<?php

use app\models\industry\AllDeffect;
use app\models\industry\BufferZone;
use app\models\user\User;
use yii\helpers\Html;
use yii\widgets\DetailView;

if($model->id){
    $defect = AllDeffect::find()->where(['dep_id' =>$model->id])->andWhere(['department_id' => 6])->one();
    $buffer = BufferZone::find()->where(['dep_id' =>$model->id])->andWhere(['from_department_id' => 6])->one();
}


$this->title = 'Электросборка №' . ' ' . $model->id; ;
$this->params['breadcrumbs'][] = $this->title;

?>



<div class="content-wrapper">
    <section class="content-header">
        <h1><?=$this->title ?></h1>

        <ol class="breadcrumb">
            <li><a href="<?=Yii::$app->urlManager->createUrl(['/admin/'])?>"><i class="fa fa-dashboard"></i> Главная</a></li>
            <li class="active"><?=$this->title;?></li>
        </ol>
    </section>
    <section class="content">
        <?php if (Yii::$app->session->hasFlash('electro_saved')) {?>
            <div class="alert alert-success text-center">
                <?=Yii::$app->session->getFlash('electro_saved');?>
            </div>
        <?php } ?>

        <?php if (Yii::$app->session->hasFlash('ckeck_status')) {?>
            <div class="alert alert-success text-center">
                <?=Yii::$app->session->getFlash('ckeck_status');?>
            </div>
        <?php }?>

        <div class="row">
            <div class="col-sm-12">
                <?php if ($model) {?>
                <div class="box box-info color-palette-box">
                    <div class="box-header">
                        <?php if ( ($model->is_defect == 0 && count($defect) == 0 ) && $model->status == 0 &&  (Yii::$app->user->identity->is_part == User::ROLE_OTK  || Yii::$app->user->identity->role == User::ROLE_ADMIN) ) {?>
                            <a href="<?=Yii::$app->urlManager->createUrl(['/admin/industry/all-deffect/create', 'id_department'=>$model->id, 'dep_name' => $model->department_id]);?>" class="btn btn-danger">Добавить в дефект</a>
                        <?php }?>

                        <?php if ( ($model->is_defect == 1 || count($defect)) &&  ( Yii::$app->user->identity->is_part == User::ROLE_OTK || Yii::$app->user->identity->role == User::ROLE_ADMIN)) {?>
                            <a href="<?=Yii::$app->urlManager->createUrl(['/admin/industry/all-deffect/view', 'id'=>$defect->id ]);?>" class="btn btn-warning">Посмотреть дефект</a>
                        <?php }?>

                        <?php if ($model->status == 0 && count($buffer->id) == 0  &&  $model->is_ckeck && ((Yii::$app->user->identity->is_part == User::ROLE_UCHET && Yii::$app->user->identity->department_id == $model->department_id) || Yii::$app->user->identity->role == User::ROLE_ADMIN )) {?>
                            <a href="<?=Yii::$app->urlManager->createUrl(['/admin/industry/buffer-zone/create', 'department_id'=>$model->id, 'dep_name' => $model->department_id ]);?>" class="btn btn-primary">Добавить в Буфферную зону</a>
                        <?php }?>

                        <?php if ($model->status == 1 && empty(!$buffer->id) &&  ((Yii::$app->user->identity->is_part == User::ROLE_UCHET && Yii::$app->user->identity->department_id == $model->department_id) || Yii::$app->user->identity->role == User::ROLE_ADMIN  )) {?>
                            <a href="<?=Yii::$app->urlManager->createUrl(['/admin/industry/buffer-zone/view', 'id'=>$buffer->id ]);?>" class="btn btn-primary">Посмотреть в Буфферой зоне</a>
                        <?php }?>
                        <?php if ($model->is_ckeck == 0 &&  (Yii::$app->user->identity->is_part == User::ROLE_OTK || Yii::$app->user->identity->role == User::ROLE_ADMIN)) {?>
                            <a href="<?=Yii::$app->urlManager->createUrl(['/admin/industry/department-electro/check', 'id'=>$model->id]);?>" class="btn btn-success">Проверку прошел</a>
                        <?php }?>


                        <div class="btn-group"><button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                                <span class="fa fa-cog"></span>
                            </button>
                            <ul class="dropdown-menu pull-left">
                                <li><a href="<?=Yii::$app->urlManager->createUrl(['/admin/industry/department-electro/index']);?>" class="dropdown-item">Все операции электро-сборки</a></li>
                                <?php if(Yii::$app->user->identity->is_edit == true  ) { ?>
                                    <li><a href="<?=Yii::$app->urlManager->createUrl(['/admin/industry/department-electro/create', 'id'=>$model->id]);?>" class="dropdown-item">Редактировать</a></li>
                                <?php } ?>
                                <?php if(Yii::$app->user->identity->is_remove == true  ) { ?>
                                    <li><a href="<?=Yii::$app->urlManager->createUrl(['/admin/industry/department-electro/remove', 'id'=>$model->id]);?>" class="dropdown-item" class="remove-object">Удалить</a></li>
                                <?php } ?>

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
                                    <td>С какого отдела прешел поддон:</td>
                                    <td><?=$model->previous_department_id ? $model->previous->name_ru : '-';?></td>
                                </tr>
                                <tr>
                                    <td>Модель:</td>
                                    <td><?=$model->model_id ? $model->model->name_ru : '-';?></td>
                                </tr>
                                <tr>
                                    <td>Артикул:</td>
                                    <td><?=$model->articul ? $model->articul : '-';?></td>

                                </tr>
                                <tr>
                                    <td>Линия:</td>
                                    <td><?=$model->line ? $model->line->name_ru : '-';?></td>
                                </tr>
                                <tr>
                                    <td>Пользатель:</td>
                                    <td><?=$model->user ? $model->user->name : '-';?></td>
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
                                    <td>Кол-во:</td>
                                    <td><?=$model->amount ? ($model->amount) : '-';?></td>
                                </tr>
                                <tr>
                                    <td>Пользователь принявший поддон:</td>
                                    <td><?=$model->to_user ? $model->to_user : '-';?></td>
                                </tr>

                                <tr>
                                    <td>Проверка контроля качества:</td>
                                    <td>
                                        <?php
                                        if ($model->is_ckeck == 1) {
                                            echo '<span class="label label-success">Проверку прошел</span>';
                                        } else {
                                            echo '<span class="label label-warning">Ожидание проверки</span>';
                                        }
                                        ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td>Дефектная продукция:</td>
                                    <td>
                                        <?php
                                        if ($model->is_defect == 1) {
                                            echo '<span class="label label-danger">Да</span>';
                                        } else {
                                            echo '<span class="label label-success">Нет</span>';
                                        }
                                        ?>
                                    </td>
                                </tr>


                                <tr>
                                    <td>Статус:</td>
                                    <td>
                                        <?php
                                        if ($model->status == 1) {
                                            echo '<span class="label label-success">Готов</span>';
                                        } else {
                                            echo '<span class="label label-warning">В ожидании</span>';
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