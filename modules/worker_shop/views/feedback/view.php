<?php

$this->title = 'Заявка';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="main-content">
    <section class="section">
        <div class="card">
            <?php if ($model) {?>
                <div class="card-header">
                    <div class="pull-right">
                        <a href="<?=Yii::$app->urlManager->createUrl(['/admin/feedback/remove', 'id'=>$model->id]);?>" class="btn btn-danger remove-object">
                            <i class="fas fa-trash"></i> Удалить
                        </a>
                    </div>
                    <?=$this->title;?>
                </div>
            <?php }?>
            <div class="card-body">
                <?php if ($model) {?>
                    <table class="table">
                        <tr>
                            <td>ID</td>
                            <td><?=$model->id;?></td>
                        </tr>
                        <tr>
                            <td>Имя</td>
                            <td><?=$model->name ? $model->name : '-';?></td>
                        </tr>
                        <tr>
                            <td>Пользователь</td>
                            <td><?=$model->user ? '<a href="'.Yii::$app->urlManager->createUrl(['/admin/user/view', 'id'=>$model->user->id]).'">'.$model->user->name.'</a>' : 'Не зарегистрирован';?></td>
                        </tr>
                        <tr>
                            <td>E-mail</td>
                            <td><?=$model->email ? $model->email : '-';?></td>
                        </tr>
                        <tr>
                            <td>Статус</td>
                            <td>
                                <?php if ($model->status == 0) {?>
                                    <span class="label bg-yellow">В ожидании</span>
                                <?php }?>
                                <?php if ($model->status == 1) {?>
                                    <span class="label bg-green">Просмотрен</span>
                                <?php }?>
                            </td>
                        </tr>
                        <tr>
                            <td>Дата</td>
                            <td><?=$model->date ? $model->date : '-';?></td>
                        </tr>
                    </table>
                <?php } else {?>
                    <div class="alert alert-warning text-center">Заявки не найдено</div>
                <?php }?>
            </div>
        </div>
        <?php if ($model->content) {?>
            <div class="card">
                <div class="card-header">
                    Сообщение
                </div>
                <div class="card-body">
                    <?=$model->content;?>
                </div>
            </div>
        <?php }?>
    </section>
</div>