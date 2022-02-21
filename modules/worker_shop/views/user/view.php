<?php

use app\widgets\admin_user_menu\AdminUserMenu;

$this->title = 'Профиль клиента';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="content-wrapper">
    <section class="content-header">
        <h1><?=$this->title;?></h1>
        
        <ol class="breadcrumb">
            <li><a href="<?=Yii::$app->urlManager->createUrl(['/worker/'])?>"><i class="fa fa-dashboard"></i> Главная</a></li>
            <li class="active"><?=$this->title;?></li>
        </ol>
    </section>
    <section class="content">
        <?php if (Yii::$app->session->hasFlash('user_saved')) {?>
            <div class="alert alert-success text-center">
                <?=Yii::$app->session->getFlash('user_saved');?>
            </div>
        <?php }?>
        <?php if ($model) {?>
            <div class="row">
                <div class="col-sm-3">
                    <?=AdminUserMenu::widget();?>
                </div>
                <div class="col-sm-9">
                    <div class="box">
                        <div class="box-body">
                            <div class="nav-tabs-custom">
                                <ul class="nav nav-tabs">
                                    <li class="active"><a href="#tab_1" data-toggle="tab"><strong>Личные данные</strong></a></li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab_1">
                                        <table class="table table-striped">
                                            <tr>
                                                <td>ID</td>
                                                <td><?=$model->id ? $model->id : 'Не указано';?></td>
                                            </tr>
                                            <tr>
                                                <td>Статус</td>
                                                <td>
                                                    <?php
                                                        if ($model->status == 1) {
                                                            echo '<span class="badge badge-success">Активный</span>';
                                                        }
                                                        if ($model->status == 0) {
                                                            echo '<span class="badge badge-warning">В ожидании</span>';
                                                        }
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Имя</td>
                                                <td><?=$model->name ? $model->name : 'Не указано';?></td>
                                            </tr>
                                            <tr>
                                                <td>Фамилия</td>
                                                <td><?=$model->lastname ? $model->lastname : 'Не указано';?></td>
                                            </tr>
                                            <tr>
                                                <td>Отчество</td>
                                                <td><?=$model->fname ? $model->fname : 'Не указано';?></td>
                                            </tr>
                                            <tr>
                                                <td>Телефон</td>
                                                <td><?=$model->phone ? $model->phone : 'Не указано';?></td>
                                            </tr>
                                            <tr>
                                                <td>E-mail</td>
                                                <td><?=$model->email ? $model->email : 'Не указано';?></td>
                                            </tr>
                                            <tr>
                                                <td>Пол</td>
                                                <td>
                                                    <?php
                                                        if ($model->gender == 1) {
                                                            echo 'Мужской';
                                                        }
                                                        if ($model->gender == 2) {
                                                            echo 'Женский';
                                                        }
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Дата рождения</td>
                                                <td><?=$model->birthday ? $model->birthday : 'Не указано';?></td>
                                            </tr>
                                            <tr>
                                                <td>Дата регистрации</td>
                                                <td><?=$model->date ? $model->date : 'Не указано';?></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } else {?>
            <div class="box">
                <div class="box-body">
                    <div class="alert alert-warning text-center">Пользователя не существует</div>
                </div>
            </div>
        <?php }?>
    </section>
</div>