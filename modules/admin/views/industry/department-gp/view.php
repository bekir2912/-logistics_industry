<?php

use app\models\industry\AllDeffect;
use app\models\industry\BufferZone;
use app\models\user\User;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\db\Expression;

$total_defects = \app\models\industry\AllDeffect::find()->where(['like', 'current_operation', $model->current_operation])->all();
$bad_defects = \app\models\industry\AllDeffect::find()->where(['is_save'=>0])->andWhere(['like', 'current_operation', $model->current_operation])->all();
$edit_defects = \app\models\industry\AllDeffect::find()->where(['is_save'=>1])->andWhere(['like', 'current_operation', $model->current_operation])->all();
$close_defects = \app\models\industry\AllDeffect::find()->where(['status'=>0])->andWhere(['like', 'current_operation', $model->current_operation])->all();



if($model->id){
    $defect = AllDeffect::find()->where(['dep_id' =>$model->id])->andWhere(['department_id' => 7])->one();
    $buffer = BufferZone::find()->where(['dep_id' =>$model->id])->andWhere(['from_department_id' => 7])->one();
}



$this->title = 'Готовая продукция';
$this->params['breadcrumbs'][] = $this->title;

?>



<div class="content-wrapper">
    <section class="content-header">

        <h1><?=$this->title . ' ' . $model->current_operation;?></h1>

        <ol class="breadcrumb">
            <li><a href="<?=Yii::$app->urlManager->createUrl(['/admin/'])?>"><i class="fa fa-dashboard"></i> Главная</a></li>
            <li class="active"><?=$this->title;?></li>
        </ol>
    </section>

    <section class="content-header">
        <div class="row" >

            <div class="col-md-3 col-sm-6 col-xs-12">
                <a href="<?=Yii::$app->urlManager->createUrl(['/admin/industry/all-deffect/index', 'current_operation' => $model->current_operation]);?>">
                    <div class="info-box">
                        <span class="info-box-icon bg-gray"><i class="fa fa-cog"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text" style="white-space: pre-wrap;">Общее кол-во деф.прод</span>
                            <span class="info-box-number"><?= count($total_defects);?></span>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-3 col-sm-6 col-xs-12">
                <a href="<?=Yii::$app->urlManager->createUrl(['/admin/industry/all-deffect/index', 'status' => 0, 'current_operation' => $model->current_operation]);?>">
                    <div class="info-box">
                        <span class="info-box-icon bg-green" ><i class="fa fa-cog"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text" style="white-space: pre-wrap;">Кол-во закрытой деф.прод.</span>
                            <span class="info-box-number"><?= $close_defects ? $close_defects : 0;?></span>
                        </div>
                    </div>
                </a>
            </div>


            <div class="col-md-3 col-sm-6 col-xs-12">
                <a href="<?=Yii::$app->urlManager->createUrl(['/admin/industry/all-deffect/index', 'current_operation' => $model->current_operation, 'is_save' => 0 ]);?>">
                    <div class="info-box">
                        <span class="info-box-icon bg-red" ><i class="fa fa-cog"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text" style="white-space: pre-wrap;">Кол-во неисправной деф.прод.</span>
                            <span class="info-box-number"><?= $bad_defects ? $bad_defects : 0;?></span>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-3 col-sm-6 col-xs-12">
                <a href="<?=Yii::$app->urlManager->createUrl(['/admin/industry/all-deffect/index', 'current_operation' => $model->current_operation, 'is_save' => 1 ]);?>">
                    <div class="info-box">
                        <span class="info-box-icon bg-yellow" ><i class="fa fa-cog"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text" style="white-space: pre-wrap;">Кол-во исправной деф.прод.</span>
                            <span class="info-box-number"><?= $edit_defects ? $edit_defects : 0;?></span>
                        </div>
                    </div>
                </a>
            </div>



            <!-- fix for small devices only -->
            <div class="clearfix visible-sm-block"></div>
        </div>
    </section>
    <section class="content">
        <?php if (Yii::$app->session->hasFlash('gp_saved')) {?>
            <div class="alert alert-success text-center">
                <?=Yii::$app->session->getFlash('gp_saved');?>
            </div>
        <?php } ?>
        <?php if (Yii::$app->session->hasFlash('gp_setted')) {?>
            <div class="alert alert-success text-center">
                <?=Yii::$app->session->getFlash('gp_setted');?>
            </div>
        <?php }?>
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
                        <?php if ($model->status == 1 || $model->status == 2 ||$model->status == 0) { ?>
                            <a href="<?=Yii::$app->urlManager->createUrl(['/admin/industry/department-gp/stock', 'id'=>$model->id]);?>" class="btn btn-warning">Добавить на склад</a>
                        <?php }?>
                        <?php if ($model->is_ckeck == 0 &&  (Yii::$app->user->identity->is_part == User::ROLE_OTK || Yii::$app->user->identity->role == User::ROLE_ADMIN)) {?>
                            <a href="<?=Yii::$app->urlManager->createUrl(['/admin/industry/department-gp/check', 'id'=>$model->id]);?>" class="btn btn-success">Проверку прошел</a>
                        <?php }?>
                        <div class="btn-group"><button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                                <span class="fa fa-cog"></span>
                            </button>
                            <ul class="dropdown-menu pull-left">
                                <li><a href="<?=Yii::$app->urlManager->createUrl(['/admin/industry/department-gp/index']);?>" class="dropdown-item">Все операции</a></li>
                                <?php if(Yii::$app->user->identity->is_edit == true  ) { ?>
                                    <li><a href="<?=Yii::$app->urlManager->createUrl(['/admin/industry/department-gp/create', 'id'=>$model->id]);?>" class="dropdown-item">Редактировать</a></li>
                                <?php } ?>
                                <?php if(Yii::$app->user->identity->is_remove == true  ) { ?>
                                    <li><a href="<?=Yii::$app->urlManager->createUrl(['/admin/industry/department-gp/remove', 'id'=>$model->id]);?>" class="dropdown-item" class="remove-object">Удалить</a></li>
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
                                    <td>Модель:</td>
                                    <td><?=$model->model ? $model->model->name_ru : '-';?></td>
                                </tr>
                                <tr>
                                    <td>Артикул:</td>
                                    <td><?=$model->articul ? $model->articul : '-';?></td>

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
                                    <td>Текущая оперция:</td>
                                    <td><?=$model->current_operation ? $model->current_operation : '-';?></td>
                                </tr>
<!--                                <tr>-->
<!--                                    <td>Артикул:</td>-->
<!--                                    <td>--><?//=$model->article ? $model->article : '-';?><!--</td>-->
<!--                                </tr>-->
                                <tr>
                                    <td>Название:</td>
                                    <td><?=$model->name_ru ? $model->name_ru : '-';?></td>
                                </tr>
                                <tr>
                                    <td>Кол-во:</td>
                                    <td><?=$model->amount ? ($model->amount . ' ' . $model->unit->name_ru) : '-';?></td>
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
<!--                                <tr>-->
<!--                                    <td>Процесс проверки (от 0 до 10):</td>-->
<!--                                    <td>--><?//=$model->value_range != NULL ? $model->value_range : '-';?><!--</td>-->
<!--                                </tr>-->

                                <tr>
                                    <td>Статус:</td>
                                    <td>
                                        <?php
                                        if ($model->status == 1) {
                                            echo '<small class="label label-success">Готов</small>';
                                        }elseif ($model->status == 2){
                                            echo '<small class="label label-primary">На проверке</small>';
                                        }elseif($model->status == 0) {
                                            echo '<small class="label label-warning">В ожидании </small>';
                                        }elseif($model->status == 3) {
                                            echo '<small class="label label-success">На складе </small>';
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
