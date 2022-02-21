<?php

use app\widgets\admin_menu\AdminMenu;

$this->title = 'Профиль';
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
        <?php if (Yii::$app->session->hasFlash('profile_saved')) {?>
            <div class="callout callout-success text-center">
                <?=Yii::$app->session->getFlash('profile_saved');?>
            </div>
        <?php }?>
        <?php if ($model) {?>
            <div class="row">
                <div class="col-sm-3">
                    <?=AdminMenu::widget();?>
                </div>
                <div class="col-sm-9">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab_1" data-toggle="tab"><strong>Личные данные</strong></a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab_1">
                                <table class="table table-striped">
                                    <tr>
                                        <td>Логин</td>
                                        <td><?=$model->login ? $model->login : 'Не указано';?></td>
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
        <?php } else {?>
            <div class="box box-default color-palette-box">
                <div class="box-body">
                    <div class="alert alert-warning text-center">Профиля не существует</div>
                </div>
            </div>
        <?php }?>
    </section>
</div>