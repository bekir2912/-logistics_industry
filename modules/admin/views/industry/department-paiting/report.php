<?php

use app\models\industry\AllDeffect;
use app\models\industry\BufferZone;
use yii\helpers\Html;
use yii\widgets\DetailView;


$this->title = 'Отчетность по отделу Покраски';
$this->params['breadcrumbs'][] = $this->title;


?>


<div class="content-wrapper">
    <section class="content-header">

        <h1><?= $this->title ?></h1>

        <ol class="breadcrumb">
            <li><a href="<?= Yii::$app->urlManager->createUrl(['/admin/']) ?>"><i class="fa fa-dashboard"></i>
                    Главная</a></li>
            <li class="active"><?= $this->title; ?></li>
        </ol>
    </section>
    <section class="content">

        <!--        --><?php //if (Yii::$app->session->hasFlash('stamping_saved')) {?>
        <!--            <div class="alert alert-success text-center">-->
        <!--                --><? //=Yii::$app->session->getFlash('stamping_saved');?>
        <!--            </div>-->
        <!--        --><?php //} ?>
        <div class="row">

            <div class="col-sm-12"><h3 style="margin-bottom: 20px">Отчет по выпуску кол-ва продукции с <?=$month .' по '. $now_date?></h3>

                    <div class="col-lg-3 col-xs-6">
                        <div class="small-box bg-aqua">
                            <div class="inner">
                                <h3><?= ($total_now && (count((int)($total_now[0]['amount'])) > 0)) ?$total_now[0]['amount'] : 0; ?></h3>
                                <p>Сегодня изготовленно</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-grid"></i>
                            </div>
<!--                            <a href="--><?//= Yii::$app->urlManager->createUrl(['/admin/industry/department']) ?><!--"-->
<!--                               class="small-box-footer">Посмотреть <i class="fa fa-arrow-circle-right"></i></a>-->
                        </div>
                    </div>
                    <div class="col-lg-3 col-xs-6">
                        <div class="small-box bg-yellow">
                            <div class="inner">
                                <h3><?= ($total_plan_between && (count((int)($total_plan_between[0]['amount'])) > 0)) ? $total_plan_between[0]['amount'] : 0; ?> /
                                <?= ($total_plans && (count((int)($total_plans[0]['amount'])) > 0)) ? $total_plans[0]['amount'] : 0; ?></h3>
                                <p>План на сегоня / месяц</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-rocket"></i>
                            </div>
<!--                            <a href="--><?//= Yii::$app->urlManager->createUrl(['/admin/industry/buffer-zone']) ?><!--"-->
<!--                               class="small-box-footer">Посмотреть <i class="fa fa-arrow-circle-right"></i></a>-->
                        </div>
                    </div>
                    <div class="col-lg-3 col-xs-6">
                        <div class="small-box bg-green">
                            <div class="inner">
                                <h3><?= ($total_ready_between && (count((int)($total_ready_between[0]['amount'])) > 0)) ? $total_ready_between[0]['amount'] : 0; ?></h3>
                                <p>ГП </p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-check"></i>
                            </div>
<!--                            <a href="--><?//= Yii::$app->urlManager->createUrl(['/admin/industry/department-gp']) ?><!--"-->
<!--                               class="small-box-footer">Посмотреть <i class="fa fa-arrow-circle-right"></i></a>-->
                        </div>
                    </div>
                    <div class="col-lg-3 col-xs-6">
                        <div class="small-box bg-red">
                            <div class="inner">
                                <h3><?= ($defect_between && (count((int)($defect_between[0]['amount'])) > 0)) ? $defect_between[0]['amount'] : 0; ?></h3>
                                <p>Дефекты</p>
                            </div>
                            <div class="icon">
                                <i class="ion-close"></i>
                            </div>
<!--                            <a href="--><?//= Yii::$app->urlManager->createUrl(['/admin/industry/all-deffect']) ?><!--"-->
<!--                               class="small-box-footer">Посмотреть <i class="fa fa-arrow-circle-right"></i></a>-->
                        </div>
                    </div>
            </div>

        </div>

        <br>

        <div class="row">
            <div class="col-sm-12">
                <h3>Диаграмма, плана и факта на каждый день</h3>

                <div class="row">
                    <div class="col-md-5">
                        <form action="" method="get">
                            <div class="row">
                                <div class="col-md-5" style="margin: 15px 0;">
                                    <input type="date" class="form-control" name="start" value="<?=$start?>">
                                </div>
                                <div class="col-md-5" style="margin: 15px 0;">
                                    <input type="date" class="form-control" name="end" value="<?=$end?>">
                                </div>
                                <div class="col-md-2" style="margin: 15px 0;">
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
                <br>     <br>
                <div class="box box-info color-palette-box">
                    <?php

                    $series = [
                        [
                            'name' => 'План',
                            'data' => $plan_array,
                        ],
                        [
                            'name' => 'Факт',
                            'data' => $plan_array2,
                        ],
                    ];

                    echo \onmotion\apexcharts\ApexchartsWidget::widget([
                        'type' => 'area', // default area
                        'height' => '400', // default 350
                        'width' => '100%', // default 100%
                        'chartOptions' => [
                            'colors' => ['#059400', '#051aff'],
                            'chart' => [
                                'toolbar' => [
                                    'show' => true,
                                    'autoSelected' => 'zoom'
                                ],
                            ],

                            'plotOptions' => [
                                'bar' => [
                                    'horizontal' => false,
                                    'endingShape' => 'rounded'
                                ],
                            ],
                            'dataLabels' => [
                                'enabled' => false
                            ],
                            'xaxis' => [
                                'type' => 'datetime',
                                // 'categories' => $categories,
                            ],
                            'stroke' => [
                                'show' => true,
                                'colors' => ['transparent']
                            ],
                            'legend' => [
                                'verticalAlign' => 'bottom',
                                'horizontalAlign' => 'left',
                            ],
                        ],
                        'series' => $series
                    ]);

                    ?>
                </div>
            </div>
        </div>
    </section>
</div>
