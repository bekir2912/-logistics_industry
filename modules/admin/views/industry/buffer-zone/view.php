<?php

use app\models\industry\DepartmentPaiting;
use app\models\industry\DepartmentStamping;
use \app\models\industry\DepartmentMechanical;
use \app\models\industry\DepartmentTest;
use \app\models\industry\DepartmentSizing;
use \app\models\industry\DepartmentElectro;
use app\models\user\User;
use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = 'Операция № ' . ' ' . $model->current_operation ;
$this->params['breadcrumbs'][] = $this->title  ;

$stamping = DepartmentStamping::findOne($model->dep_id);
$paiting = DepartmentPaiting::findOne($model->dep_id);
$mechanical = DepartmentMechanical::findOne($model->dep_id);
$test = DepartmentTest::findOne($model->dep_id);
$sizing = DepartmentSizing::findOne($model->dep_id);
$electro  = DepartmentElectro::findOne($model->dep_id);
$checking  = \app\models\industry\DepartmentChecking::findOne($model->dep_id);
$printing = \app\models\industry\DepartmentPrinting::findOne($model->dep_id);


?>



<div class="content-wrapper">
    <section class="content-header">
        <h1><?= /** @var TYPE_NAME $model */
            $this->title;?></h1>

        <ol class="breadcrumb">
            <li><a href="<?=Yii::$app->urlManager->createUrl(['/admin/'])?>"><i class="fa fa-dashboard"></i> Главная</a></li>
            <li class="active"><?=$this->title;?></li>
        </ol>
    </section>
    <section class="content">
        <?php if (Yii::$app->session->hasFlash('buffer_saved')) {?>
            <div class="alert alert-success text-center">
                <?=Yii::$app->session->getFlash('buffer_saved');?>
            </div>
        <?php } ?>
        <div class="row">
            <div class="col-sm-12">
                <?php if ($model) {?>
                <div class="box box-info color-palette-box">
                    <div class="box-header">



                        <?php if ($model->from_department_id == 1 && empty($model->time_expire) && (Yii::$app->user->identity->department_id == User::ROLE_WORKER_PAITING || Yii::$app->user->identity->role == User::ROLE_ADMIN )) {?>
                            <a href="<?=Yii::$app->urlManager->createUrl(['/admin/industry/department-paiting/create', 'department_id'=>$stamping->id, 'buffer_id' => $model->id ]);?>" class="btn btn-primary">Отправить в отдел Покраски</a>
                        <?php }?>

                        <?php if ($model->from_department_id == 2 && empty($model->time_expire) && (Yii::$app->user->identity->department_id == User::ROLE_WORKER_MECHANICA || Yii::$app->user->identity->role == User::ROLE_ADMIN )) {?>
                            <a href="<?=Yii::$app->urlManager->createUrl(['/admin/industry/department-mechanical/create', 'department_id'=>$paiting->id, 'buffer_id' => $model->id , 'from_department' =>  $model->from_department_id]);?>" class="btn btn-primary">Отправить в отдел Мех. сборки</a>
                        <?php }?>
                        <?php if ($model->from_department_id == 3 && time() >=$model->time_expire  && (Yii::$app->user->identity->department_id == User::ROLE_WORKER_TEST || Yii::$app->user->identity->role == User::ROLE_ADMIN )) {?>
                            <a href="<?=Yii::$app->urlManager->createUrl(['/admin/industry/department-test/create', 'department_id'=>$mechanical->id, 'buffer_id' => $model->id ]);?>" class="btn btn-primary">Отправить в отдел Тест на утечку</a>
                        <?php }?>
                        <?php if ($model->from_department_id == 4 && (Yii::$app->user->identity->department_id == User::ROLE_WORKER_SIZING || Yii::$app->user->identity->role == User::ROLE_ADMIN )) {?>
                            <a href="<?=Yii::$app->urlManager->createUrl(['/admin/industry/department-sizing/create', 'department_id'=>$test->id, 'buffer_id' => $model->id ]);?>" class="btn btn-primary">Отправить в отдел Калибровки</a>
                        <?php }?>
                        <?php if ($model->from_department_id == 5 && (Yii::$app->user->identity->department_id == User::ROLE_WORKER_ELECTRO || Yii::$app->user->identity->role == User::ROLE_ADMIN )) {?>
                            <a href="<?=Yii::$app->urlManager->createUrl(['/admin/industry/department-electro/create', 'department_id'=>$sizing->id, 'buffer_id' => $model->id, 'from_department' =>  $model->from_department_id ]);?>" class="btn btn-primary">Отправить в отдел Электро сборки</a>
                        <?php }?>
                        <?php if ($model->from_department_id == 6 && (Yii::$app->user->identity->department_id == User::ROLE_WORKER_GP || Yii::$app->user->identity->role == User::ROLE_ADMIN )) {?>
                            <a href="<?=Yii::$app->urlManager->createUrl(['/admin/industry/department-gp/create', 'department_id'=>$electro->id, 'buffer_id' => $model->id ]);?>" class="btn btn-primary">Отправить в отдел ГП</a>
                        <?php }?>

                        <?php if ($model->from_department_id == 12 && (Yii::$app->user->identity->department_id == User::ROLE_WORKER_MECHANICA || Yii::$app->user->identity->role == User::ROLE_ADMIN )) {?>
                            <a href="<?=Yii::$app->urlManager->createUrl(['/admin/industry/department-mechanical/create', 'department_id'=>$checking->id, 'buffer_id' => $model->id, 'from_department' =>  $model->from_department_id]);?>" class="btn btn-primary">Отправить в отдел Мех. сборки</a>
                        <?php }?>

                        <?php if ($model->from_department_id == 10 &&  (Yii::$app->user->identity->department_id == User::ROLE_WORKER_ELECTRO || Yii::$app->user->identity->role == User::ROLE_ADMIN )) {?>
                            <a href="<?=Yii::$app->urlManager->createUrl(['/admin/industry/department-electro/create', 'department_id'=>$printing->id, 'buffer_id' => $model->id, 'from_department' =>  $model->from_department_id]);?>" class="btn btn-primary">Отправить в отдел Электро сборки</a>
                        <?php }?>

<!--                        --><?//=debug($model->from_department_id )?>
<!--                        --><?//=debug($electro->department->id)?>

                        <div class="btn-group"><button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                                <span class="fa fa-cog"></span>
                            </button>
                            <ul class="dropdown-menu pull-left">
                                <li><a href="<?=Yii::$app->urlManager->createUrl(['/admin/industry/buffer-zone/']);?>" class="dropdown-item">Буферная зона</a></li>
                                <li><a href="<?=Yii::$app->urlManager->createUrl(['/admin/industry/buffer-zone/create', 'id'=>$model->id]);?>" class="dropdown-item">Редактировать</a></li>
                                <li><a href="<?=Yii::$app->urlManager->createUrl(['/admin/industry/buffer-zone/remove', 'id'=>$model->id, 'dep_name' => $model->dep_id, 'department_id' => $model->from_department_id]);?>" class="dropdown-item" class="remove-object">Удалить</a></li>
                            </ul>
                        </div>
                        <div class="box-body">
                            <table class="table table-striped">
                                <tr>
                                    <td>ID:</td>
                                    <td><?=$model->id ? $model->id : '-';?></td>
                                </tr>
                                <tr>
                                    <td>Откуда:</td>
                                    <td><?=$model->fromDepartment  ? $model->fromDepartment->name_ru : '-';?></td>
                                </tr>
                                <tr>
                                    <td>Куда:</td>
                                    <td><?=$model->toDepartment   ? $model->toDepartment->name_ru : '-';?></td>
                                </tr>
                                <tr>
                                    <td>Пользатель:</td>
                                    <td><?=$model->user ? $model->user->name : '-';?></td>
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
                                    <td>Текущая операция:</td>
                                    <td><?=$model->current_operation ? $model->current_operation : '-';?></td>
                                </tr>

                                <tr>
                                    <td>Номер поддона:</td>
                                    <td><?=$model->number_poddon ? $model->number_poddon : '-';?></td>
                                </tr>

                                <tr>
                                    <td>Кол-во:</td>
                                    <td><?=$model->amount ? $model->amount : '-';?></td>
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

                                <tr>
                                    <td>Дата Окончания:</td>
                                    <td><?=$model->time_expire ? date_format($time_expire, 'd.m.Y H:i') : '-';?></td>
                                </tr>


                                <tr>
                                    <td>Оставшее время:</td>
                                    <td><?=$model->time_expire > time() ? $interval->h .' ч. '. $interval->i . ' мин.'  : '-';?></td>
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
