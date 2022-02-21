<?php

use app\models\industry\AllDeffect;
use app\models\industry\BufferZone;
use yii\helpers\Html;
use yii\widgets\DetailView;



$this->title = 'План продукции с  ';
$this->params['breadcrumbs'][] = $this->title;

?>



<div class="content-wrapper">
    <section class="content-header">

        <h1><?=$this->title . ' ' . $model->date_start . ' по ' .$model->date_end;?></h1>

        <ol class="breadcrumb">
            <li><a href="<?=Yii::$app->urlManager->createUrl(['/admin/'])?>"><i class="fa fa-dashboard"></i> Главная</a></li>
            <li class="active"><?=$this->title;?></li>
        </ol>
    </section>
    <section class="content">
        <?php if (Yii::$app->session->hasFlash('plan_saved')) {?>
            <div class="alert alert-success text-center">
                <?=Yii::$app->session->getFlash('plan_saved');?>
            </div>
        <?php } ?>
        <?php if (Yii::$app->session->hasFlash('plan_setted')) {?>
            <div class="alert alert-success text-center">
                <?=Yii::$app->session->getFlash('plan_setted');?>
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
                                <li><a href="<?=Yii::$app->urlManager->createUrl(['/admin/industry/b-plan/create']);?>" class="dropdown-item">Добавить план</a></li>
                                <li><a href="<?=Yii::$app->urlManager->createUrl(['/admin/industry/b-plan/create', 'id'=>$model->id]);?>" class="dropdown-item">Редактировать</a></li>
                                <li><a href="<?=Yii::$app->urlManager->createUrl(['/admin/industry/b-plan/remove', 'id'=>$model->id]);?>" class="dropdown-item" class="remove-object">Удалить</a></li>
                            </ul>
                        </div>

                        <div class="box-body">
                            <table class="table table-striped">
                                <tr>
                                    <td>ID:</td>
                                    <td><?=$model->id ? $model->id : '-';?></td>
                                </tr>
                                <tr>
                                    <td>Дата начала плана:</td>
                                    <td><?=$model->date_start ? $model->date_start : '-';?></td>
                                </tr>
                                <tr>
                                    <td>Дата конца плана:</td>
                                    <td><?=$model->date_end ? $model->date_end : '-';?></td>
                                </tr>
<!--                                <tr>-->
<!--                                    <td>Значение:</td>-->
<!--                                    <td>--><?//=$model->value ? $model->value : '-';?><!--</td>-->
<!--                                </tr>-->
<!--                                <tr>-->
<!--                                    <td>Дата:</td>-->
<!--                                    <td>--><?//=$model->date_plan ? $model->date_plan : '-';?><!--</td>-->
<!--                                </tr>-->
<!--                                <tr>-->
<!--                                    <td>Наименование:</td>-->
<!--                                    <td>--><?//=$model->name ? $model->name : '-';?><!--</td>-->
<!--                                </tr>-->

                                <tr>
                                    <td>Статус:</td>
                                    <td>
                                        <?php
                                        if ($model->status == 1) {
                                            echo '<span class="label label-success">Готов</span>';
                                        } else {
                                            echo '<span class="label label-danger">Заблокирован</span>';
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


                <div class="col-sm-12">
                        <?php if ($model->plansDates) {?>
                            <?php foreach ($model->plansDates as $k => $plans) {?>
                        <div class="box box-info color-palette-box">
                            <div class="box-header">

                                <div class="btn-group"><button type="button" class="btn btn-primary dropdown-toggle " data-toggle="dropdown">
                                        <span class="fa fa-cog"></span>
                                    </button>
                                    <ul class="dropdown-menu pull-left">
<!--                                        <li><a href="--><?//=Yii::$app->urlManager->createUrl(['/admin/industry/plans-dates/create']);?><!--" class="dropdown-item">Добавить план</a></li>-->
                                        <li><a href="<?=Yii::$app->urlManager->createUrl(['/admin/industry/plans-dates/create', 'id'=>$plans->id]);?>" class="dropdown-item">Редактировать</a></li>
                                        <li><a href="<?=Yii::$app->urlManager->createUrl(['/admin/industry/plans-dates/remove', 'id'=>$plans->id]);?>" class="dropdown-item" class="remove-object">Удалить</a></li>
                                    </ul>
                                </div>

                                <div class="box-body">
                                    <table class="table table-striped">
                                        <tr>
                                            <td>План ID:</td>
                                            <td><?=$plans->plan_id ? $plans->plan->id : '-';?></td>
                                        </tr>
                                        <tr>
                                            <td>Отдел:</td>
                                            <td><?=$plans->department_id ? $plans->department->name_ru : '-';?></td>
                                        </tr>
                                        <tr>
                                            <td>Дата плана:</td>
                                            <td><?=$plans->date ? $plans->date : '-';?></td>
                                        </tr>

                                        <tr>
                                            <td>Кол-во:</td>
                                            <td><?=$plans->value ? $plans->value : '-';?></td>
                                        </tr>

                                    </table>
                                    <br/>
                                </div>
                            </div>

                            <?php }?>
                        <?php } else {?>
                        <div class="alert alert-warning text-center">Данных нет</div>
                        <?php }?>

                        <div class="box-header">
                        </div>
                </div>

    </section>
</div>
