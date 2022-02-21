<?php

use bsadnu\googlecharts\ColumnChart;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;
use app\models\user\User;

$this->title = 'Админ панель';


$plans = \app\models\industry\BPlan::find()->orderBy('`date_plan` DESC')->limit(365)->all();

$connection = Yii::$app->getDb();
$command = $connection->createCommand('SELECT dates, sum(amount) as \'amount\'
                                            FROM b_department_gp
                                            Where status = 1
                                            GROUP BY dates');
$result = $command->queryAll();



//Преаращения в читабельный массив для Pie Chart План
$plan_array = [];
//Преаращения в читабельный массив для Pie Chart Факт
$plan_array2 = [];

if(!empty($plans)) {
    foreach ($plans as $plan) {
        $plan_array[] = array($plan->date_plan, $plan->value);
    }
}
if(!empty($result)) {
    foreach ($result as $res) {
        $plan_array2[] = array($res['dates'], $res['amount']);
    }
}

?>
<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>

<script src="/admin_files/bower_components/jquery/dist/jquery.min.js"></script>

<div class="content-wrapper">
    <section class="content-header">
        <h1><?=$this->title;?></h1>

        <ol class="breadcrumb">
            <li><a href="<?=Yii::$app->urlManager->createUrl(['/admin/'])?>"><i class="fa fa-dashboard"></i> Главная</a></li>
            <li class="active"><?=$this->title;?></li>
        </ol>
    </section>
    <section class="content">
        <?php if (Yii::$app->user->identity->role == User::ROLE_ADMIN) {?>
            <div class="row">
                <div class="col-lg-3 col-xs-6">
                    <div class="small-box bg-aqua">
                        <div class="inner">
                            <h3><?=($departments && ($departments > 0)) ? $departments : 0;?></h3>
                            <p>Отделы</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-grid"></i>
                        </div>
                        <a href="<?=Yii::$app->urlManager->createUrl(['/admin/industry/department'])?>" class="small-box-footer">Посмотреть <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-xs-6">
                    <div class="small-box bg-yellow">
                        <div class="inner">
                            <h3><?=($products && ($products > 0)) ? $products : 0;?></h3>
                            <p>Буферная зона</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-clock"></i>
                        </div>
                        <a href="<?=Yii::$app->urlManager->createUrl(['/admin/industry/buffer-zone'])?>" class="small-box-footer">Посмотреть <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-xs-6">
                    <div class="small-box bg-green">
                        <div class="inner">
                            <h3><?= ($gps && ($gps > 0)) ? $gps : 0; ?></h3>
                            <p>Отдел ГП</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-happy"></i>
                        </div>
                        <a href="<?=Yii::$app->urlManager->createUrl(['/admin/industry/department-gp'])?>" class="small-box-footer">Посмотреть <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-xs-6">
                    <div class="small-box bg-red">
                        <div class="inner">
                            <h3><?= ($defects && ($defects > 0)) ? $defects : 0; ?></h3>
                            <p>Дефектная продукция</p>
                        </div>
                        <div class="icon">
                            <i class="ion-close"></i>
                        </div>
                        <a href="<?=Yii::$app->urlManager->createUrl(['/admin/industry/all-deffect'])?>" class="small-box-footer">Посмотреть <i class="ion ion-happy"></i></a>
                    </div>
                </div>
            </div>


            <br><br>



            <div class="row">


                <div class="col-md-5">
                    <form action="" method="get">
                        <div class="row">
                            <div class="col-md-5">
                                <input type="date" class="form-control" name="start" value="<?=$start?>">
                            </div>
                            <div class="col-md-5">
                                <input type="date" class="form-control" name="end" value="<?=$end?>">
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-success">Показать</button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="col-md-7">
                    <div class="row">
                        <div class="col-md-12 text-right">
                            <form action="" method="get" style="display: inline-block;">
                                <input type="hidden" class="form-control" name="start" value="<?=date('Y-m-d')?>">
                                <input type="hidden" class="form-control" name="end" value="<?=date('Y-m-d')?>">
                                <button class="btn btn-info ">Сегодня</button>
                            </form>
                            <form action="" method="get" style="display: inline-block;">
                                <input type="hidden" class="form-control" name="start" value="<?=date('Y-m-d', strtotime('-6 days'))?>">
                                <input type="hidden" class="form-control" name="end" value="<?=date('Y-m-d')?>">
                                <button class="btn btn-info">Неделя</button>
                            </form>
                            <form action="" method="get" style="display: inline-block;">
                                <input type="hidden" class="form-control" name="start" value="<?=date('Y-m-d', strtotime('-30 days'))?>">
                                <input type="hidden" class="form-control" name="end" value="<?=date('Y-m-d')?>">
                                <button class="btn btn-info">Месяц</button>
                            </form>
                            <form action="" method="get" style="display: inline-block;">
                                <input type="hidden" class="form-control" name="start" value="2021-04-01">
                                <input type="hidden" class="form-control" name="end" value="<?=date('Y-m-d')?>">
                                <button class="btn btn-info">За все время</button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
            <br><br>

       <div class="row">

           <?= ColumnChart::widget([
               'id' => 'my-column-trendlines-chart-id',
               'data' => [
                   ['Week', 'ГП', 'Дефект'],
                   ["Штамповка  ($stamping_gp/$stamping_defect)", (int)$stamping_gp_percent, (int)$stamping_defect_percent],
                   ["Покраска ($paiting_gp/$paiting_defect)", (int)$paiting_gp_percent, (int)$paiting_defect_percent],
                   ["Механическая сборка ($mechanical_gp/$mechanical_defect)", (int)$mechanical_gp_percent, (int)$mechanical_defect_percent],
                   ["Тест на утечку ($test_gp/$test_defect)", (int)$test_gp_percent, (int)$test_defect_percent],
                   ["Калибровка ($sizing_gp/$sizing_defect)", (int)$sizing_gp_percent, (int)$sizing_defect_percent ],
                   ["Электро-сборка ($electro_gp/$electro_defect)", (int)$electro_gp_percent , (int)$electro_defect_percent],
                   ["ГП ($gp_gp/$gp_defect)", (int)$gp_gp_percent, (int)$gp_defect_percent],
                   ["Отливка пластиковых деталей ($plastic_gp/$plastic_defect)", (int)$plastic_gp_percent , (int)$plastic_defect_percent ],
                   ["Сборка газового регулятора ($regulator_gp/$regulator_defect)", (int)$regulator_gp_percent, (int)$regulator_defect_percent],
                   ["Печать надписей на переднюю панель ($printing_gp/$printing_defect)", (int)$printing_gp_percent, (int)$printing_defect_percent],
                   ["Формовка AUQ-G6 ($forming_gp/$forming_defect)", (int)$forming_gp_percent, (int)$forming_defect_percent],
                   ["Проверка узлов на утечку ($checking_gp/$checking_defect)", (int)$checking_gp_percent, (int)$checking_defect_percent]
               ],
               'options' => [
                   'fontName' => 'Verdana',
                   'height' => 450,
                   'curveType' => 'function',
                   'fontSize' => 12,
                     'tooltip' => [
                         'textStyle' => [
                            'fontName' => 'Verdana',
                             'fontSize' => 13
                         ]
                     ],
                   'chartArea' => [
                       'left' => 50,
                       'width' => '92%',
                       'height' => 350
                   ],
                    "amount"=>array(
            "label"=>"Amount",
            "type"=>"number",
            "prefix"=>'$',
            "config"=>array(
                "borderColor"=>"#aaaaaa",
                "backgroundColor"=>"#dddddd",
                "borderWidth"=>2,
            )
        ),
                   'hAxis' => [
                       'format' => '#',
                       'viewWindow' => [
                           'min' => 0,
                           'max' => 12
                       ],
                       'gridlines' => [
                           'count' => 10
                       ]
                   ],
                   'vAxis' => [
                       'title' => 'Кол-во',
                       'titleTextStyle' => [
                           'fontSize' => 13,
                           'italic' => false
                       ],
                       'gridlines' => [
                           'color' => '#e5e5e5',
                           'count' => 10
                       ],
                       'minValue' => 0
                   ],
                   'colors' => [
                       '#438c4c',
                       '#FB8C00'
                   ],

                       'ticks' => [
          'max' =>  10,
          'display' => false,
          'beginAtZero' => true
        ],
                   'legend' => [
                       'position' => 'top',
                       'alignment' => 'end',
                       'textStyle' => [
                           'fontSize' => 12
                       ]
                   ]
               ]
           ]) ?>
       </div>

            <style>
                .boxes{
                    display: flex;
                }
                .box-gp {
                    display: block;
                    color: white;
                    background-color: green;
                }
                .box-defect {
                    display: block;
                    color: white;
                    background-color: red;


                }
            </style>

            <div class="row" style="display: flex; justify-content: space-between; position: relative; margin-right: 0px !important;">
                <div class="col-1">
                    <div class="box-gp" >
                        <span class="box-gp"><?=(int)$stamping_gp_percent?>%</span>
                    </div>
                    <div class="box-defect">
                        <span class="box-defect"><?=(int)$stamping_defect_percent?>%</span>
                    </div>
                </div>
                <div class="col-1">
                    <div class="box-gp">
                        <span class="box-gp"><?=(int)$paiting_gp_percent?>%</span>
                    </div>
                    <div class="box-defect">
                        <span class="box-defect"><?=(int)$paiting_defect_percent?>%</span>
                    </div>
                </div>
                <div class="col-1">
                    <div class="box-gp">
                        <span class="box-gp"><?=(int)$mechanical_gp_percent?>%</span>
                    </div>
                    <div class="box-defect">
                        <span class="box-defect"><?=(int)$mechanical_defect_percent?>%</span>
                    </div>
                </div>
                <div class="col-1">
                    <div class="box-gp">
                        <span class="box-gp"><?=(int)$test_gp_percent?>%</span>
                    </div>
                    <div class="box-defect">
                        <span class="box-defect"><?=(int)$test_defect_percent?>%</span>
                    </div>
                </div>
                <div class="col-1">
                    <div class="box-gp">
                        <span class="box-gp"><?=(int)$sizing_gp_percent?>%</span>
                    </div>
                    <div class="box-defect">
                        <span class="box-defect"><?=(int)$sizing_defect_percent?>%</span>
                    </div>
                </div>
                <div class="col-1">
                    <div class="box-gp">
                        <span class="box-gp"><?=(int)$electro_gp_percent?>%</span>
                    </div>
                    <div class="box-defect">
                        <span class="box-defect"><?=(int)$electro_defect_percent?>%</span>
                    </div>
                </div>
                <div class="col-1">
                    <div class="box-gp">
                        <span class="box-gp"><?=(int)$gp_gp_percent?>%</span>
                    </div>
                    <div class="box-defect">
                        <span class="box-defect"><?=(int)$gp_defect_percent?>%</span>
                    </div>
                </div>
                <div class="col-1">
                    <div class="box-gp">
                        <span class="box-gp"><?=(int)$plastic_gp_percent?>%</span>
                    </div>
                    <div class="box-defect">
                        <span class="box-defect"><?=(int)$plastic_defect_percent?>%</span>
                    </div>
                </div>
                <div class="col-1">
                    <div class="box-gp">
                        <span class="box-gp"><?=(int)$regulator_gp_percent?>%</span>
                    </div>
                    <div class="box-defect">
                        <span class="box-defect"><?=(int)$regulator_defect_percent?>%</span>
                    </div>
                </div>
                <div class="col-1">
                    <div class="box-gp">
                        <span class="box-gp"><?=(int)$printing_gp_percent?>%</span>
                    </div>
                    <div class="box-defect">
                        <span class="box-defect"><?=(int)$printing_defect_percent?>%</span>
                    </div>
                </div>
                <div class="col-1">
                    <div class="box-gp">
                        <span class="box-gp"><?=(int)$forming_gp_percent?>%</span>
                    </div>
                    <div class="box-defect">
                        <span class="box-defect"><?=(int)$forming_defect_percent?>%</span>
                    </div>
                </div>
                <div class="col-1">
                    <div class="box-gp">
                        <span class="box-gp"><?=(int)$checking_gp_percent?>%</span>
                    </div>
                    <div class="box-defect">
                        <span class="box-defect"><?=(int)$checking_defect_percent?>%</span>
                    </div>
                </div>
            </div>


            <script !src="">
                $( window ).on( "load", function() {
                    let elem = document.querySelector("#my-column-trendlines-chart-id > div > div:nth-child(1) > div > svg > g:nth-child(4) > g:nth-child(2) > g:nth-child(2)");
                    console.log(elem);
                });

            </script>

            <br><br>

            <div class="row">
                <div class="col-md-12">
                    <h3>
                        Инфомация о готовой продукции
                    </h3>
                </div>
            </div>

            <div id="recent-transactions" class="col-12">
                <div class="card">
                    <div class="card-header">
                        <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                    </div>
                    <div class="card-content">
                        <div class="table-responsive">
                            <table id="recent-orders" class="table table-hover table-xl mb-0">
                                <thead>
                                <tr>
                                    <th class="border-top-0">ID</th>
                                    <th class="border-top-0">Модель</th>
                                    <th class="border-top-0">Сотрудник</th>
                                    <th class="border-top-0">Операция</th>
                                    <th class="border-top-0">Номер поддона</th>
                                    <th class="border-top-0">Кол-во</th>
                                    <th class="border-top-0">Добавлено</th>
                                    <th class="border-top-0">Статус</th>
                                </tr>
                                </thead>
                                <tbody>

                                <?php foreach ($gps_list as $gp_ls) { ?>

                                    <tr>
                                        <td class="text-truncate"><i class="la la-dot-circle-o success font-medium-1 mr-1"></i> <?=$gp_ls->id?></td>
                                        <td class="text-truncate"><?=$gp_ls->model->name_ru?></td>
                                        <td class="text-truncate"><?=$gp_ls->user->name?></a></td>
                                        <td class="text-truncate"><?=$gp_ls->current_operation?></a></td>
                                        <td class="text-truncate"><?=$gp_ls->number_poddon?></a></td>
                                        <td class="text-truncate"><?=$gp_ls->amount?></a></td>
                                        <td><?=date('d.m.Y', $gp_ls->created_at)?></td>
                                        <td>
                                            <?php
                                            if ($gp_ls->status == 1) {
                                                echo '<small class="label label-success">Готов</small>';
                                            }elseif ($gp_ls->status == 2){
                                                echo '<small class="label label-primary">На проверке</small>';
                                            }elseif($gp_ls->status == 0) {
                                                echo '<small class="label label-warning">В ожидании </small>';
                                            }elseif($gp_ls->status == 3) {
                                                echo '<small class="label label-success">На складе </small>';
                                            }
                                            ?>
                                        </td>

                                        <td><a href="<?=Url::to(['admin/gp/create', 'id' => $gp_ls->id])?>"><i class="fa fa-arrow-right"></i></a></td>

                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>




<!--        Деффекная продукция-->
            <div class="row">
                <div class="col-md-12">
                    <h3>
                        Инфомация о деффектной продукции
                    </h3>
                </div>

            </div>


            <div id="recent-transactions" class="col-12">
                <div class="card">
                    <div class="card-header">
                        <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                    </div>
                    <div class="card-content">
                        <div class="table-responsive">
                            <table id="recent-orders" class="table table-hover table-xl mb-0">
                                <thead>
                                <tr>
                                    <th class="border-top-0">ID</th>
                                    <th class="border-top-0">Вид дефекта</th>
                                    <th class="border-top-0">Отдел</th>
                                    <th class="border-top-0">Номер поддона</th>
                                    <th class="border-top-0">Сотрудник</th>
                                    <th class="border-top-0">Кол-во</th>
                                    <th class="border-top-0">Добавлено</th>
                                    <th class="border-top-0">Статус</th>
                                </tr>
                                </thead>
                                <tbody>

                                <?php if (!empty($deffects_list)) {
                                    foreach ($deffects_list as $deffect) { ?>

                                        <tr>
                                            <td class="text-truncate"><i class="la la-dot-circle-o success font-medium-1 mr-1"></i> <?=$deffect->id?></td>
                                            <td class="text-truncate"><?=$deffect->deffect_id  ? $deffect->deffect->name_ru : '-';?></td>
                                            <td class="text-truncate"><?=$deffect->department_id  ? $deffect->department->name_ru : '-';?></td>
                                            <td class="text-truncate"><?=$deffect->number_poddon  ? $deffect->number_poddon : '-';?></td>
                                            <td class="text-truncate"><?=$deffect->user_id  ? $deffect->user->name : '-';?></td>
                                            <td class="text-truncate"><?=$deffect->count_deffect  ? $deffect->count_deffect : '-';?></td>
                                            <td><?=$deffect->created_at ? date('d.m.Y H:i',$deffect->created_at) : '-';?></td>
                                            <td>
                                                <?php
                                                if ($deffect->status == 1) {
                                                    echo '<span class="label label-warning">В ожидании</span>';
                                                } else {
                                                    echo '<span class="label label-success">Закрыт</span>';
                                                }
                                                ?>
                                            </td>
                                            <td><a href="<?=Url::to(['industry/all-deffect/create', 'id' => $deffect->id])?>"> <i class="fa fa-arrow-right"></i></a></td>

                                        </tr>
                                    <?php }
                                } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        <?php }?>
    </section>
</div>


<style>
    .box-gp{
        margin: 0 0 10px 0;
    }
</style>
