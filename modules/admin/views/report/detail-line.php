<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\widgets\LinkPager;
use yii\bootstrap\ActiveForm;

use kartik\select2\Select2;
use kartik\daterange\DateRangePicker;

use app\widgets\admin_report_menu\AdminReportMenu;

$this->title = 'Отчетность';
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
        <?php if (Yii::$app->session->hasFlash('shipment_removed')) {?>
            <div class="alert alert-success text-center">
                <?=Yii::$app->session->getFlash('shipment_removed');?>
            </div>
        <?php }?>
        <div class="row">
            <div class="col-sm-3">
                <?=AdminReportMenu::widget();?>
            </div>
            <div class="col-sm-9">
                <div class="box box-info color-palette-box">
                    <div class="box-body">
                        <div class="chart">
                            <canvas id="barChart" style="height:230px"></canvas>
                        </div>
                    </div>
                </div>
                <div class="box box-info color-palette-box">
                    <div class="box-header">
                        Отчетность об отданных деталях на производственную линию
                        <div class="pull-right">
                            <a href="<?=Yii::$app->urlManager->createUrl(['/admin/report/detail-line', 'type'=>'week']);?>" class="btn btn-<?=(Yii::$app->request->get('type') == 'week') ? 'danger' : 'primary';?>">Неделя</a>
                            <a href="<?=Yii::$app->urlManager->createUrl(['/admin/report/detail-line', 'type'=>'month']);?>" class="btn btn-<?=(Yii::$app->request->get('type') == 'month') ? 'danger' : 'primary';?>">Месяц</a>
                            <a href="<?=Yii::$app->urlManager->createUrl(['/admin/report/detail-line', 'type'=>'tmonth']);?>" class="btn btn-<?=(Yii::$app->request->get('type') == 'tmonth') ? 'danger' : 'primary';?>">3 месяца</a>
                            <a href="<?=Yii::$app->urlManager->createUrl(['/admin/report/detail-line', 'type'=>'hyear']);?>" class="btn btn-<?=(Yii::$app->request->get('type') == 'hyear') ? 'danger' : 'primary';?>">Пол года</a>
                            <a href="<?=Yii::$app->urlManager->createUrl(['/admin/report/detail-line', 'type'=>'year']);?>" class="btn btn-<?=(Yii::$app->request->get('type') == 'year') ? 'danger' : 'primary';?>">Год</a>
                        </div>
                    </div>
                    <div class="box-body" id="item-block">
                        <?= GridView::widget([
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'summary' => "Страница {begin} - {end} из {totalCount} данных<br/><br/>",
                            'emptyText' => 'Данных нет',
                            'pager' => [
                                'options'=>['class'=>'pagination'],
                                'pageCssClass' => 'page-item',
                                'prevPageLabel' => 'Назад',
                                'nextPageLabel' => 'Вперед',
                                'maxButtonCount'=>10,
                                'linkOptions' => [
                                    'class' => 'page-link'
                                ]
                                ],
                            'tableOptions' => [
                                'class'=>'table table-striped'
                            ],
                            'columns' => [
                                ['class' => 'yii\grid\SerialColumn'],
                                [
                                    'class' => 'yii\grid\CheckboxColumn'
                                ],
                                [
                                    'attribute'=>'id',
                                    'label'=>'<i class="fa fa-sort"></i> ID',
                                    'encodeLabel' => false,
                                    'contentOptions' => [
                                        'style' => 'width:70px'
                                    ],
                                ],
                                [
                                    'attribute'=>'name_ru',
                                    'label'=>'<i class="fa fa-sort"></i> Название',
                                    'encodeLabel' => false,
                                    'format' => 'html',
                                    'contentOptions' => [
                                        'style' => 'width:150px'
                                    ],
                                    'value' => function ($model, $key, $index, $column) {
                                        return '<a href="'.Yii::$app->urlManager->createUrl(['/stock/product/view', 'id'=>$model->product->id]).'">'.$model->product->name_ru.'</a>';
                                    },
                                ],
                                [
                                    'attribute'=>'article',
                                    'label'=>'<i class="fa fa-sort"></i> Артикул',
                                    'encodeLabel' => false,
                                    'format' => 'html',
                                    'value' => function ($model, $key, $index, $column) {
                                        return $model->product->article;
                                    },
                                ],
                                [
                                    'attribute'=>'shop_stack_id',
                                    'label'=>'<i class="fa fa-sort"></i>  Этаж',
                                    'encodeLabel' => false,
                                    'contentOptions' => [
                                        'style' => 'width:70px'
                                    ],
                                    'value' => function ($model, $key, $index, $column) {
                                        return $model->shopStack ? $model->shopStack->stack_number : '-';
                                    },
                                ],
                                [
                                    'attribute'=>'shop_stack_shelving_id',
                                    'label'=>'<i class="fa fa-sort"></i>  Ячека',
                                    'encodeLabel' => false,
                                    'contentOptions' => [
                                        'style' => 'width:100px'
                                    ],
                                    'value' => function ($model, $key, $index, $column) {
                                        return $model->shopStackShelving ? $model->shopStackShelving->shelf_number : '-';
                                    },
                                ],
                                [
                                    'attribute'=>'amount',
                                    'label'=>'<i class="fa fa-sort"></i> Кол-во',
                                    'encodeLabel' => false,
                                    'value' => function ($model, $key, $index, $column) {
                                        return $model->amount ? $model->amount : '-';
                                    },
                                ],
                                [
                                    'attribute'=>'date',
                                    'label'=>'<i class="fa fa-sort"></i> Дата',
                                    'encodeLabel' => false,
                                    'filter' => DateRangePicker::widget([
                                        'model'=>$searchModel,
                                        'attribute'=>'datepicker',
                                        'convertFormat'=>true,
                                        'pluginOptions'=>[
                                            'timePicker'=>true,
                                            'timePickerIncrement'=>30,
                                            'locale'=>[
                                                'format'=>'Y-m-d'
                                            ]
                                        ],
                                    ]),
                                    'value' => function ($model, $key, $index, $column) {
                                        return $model->date;
                                    },
                                ],
                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'template' => '{view}',
                                    'buttons' => [
                                        'view' => function ($url, $model) {
                                            return '<div class="btn-group"><button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                                                        <span class="fa fa-cog"></span>
                                                    </button>
                                                    <ul class="dropdown-menu pull-left">
                                                        <li><a href="'.Yii::$app->urlManager->createUrl(['/admin/product/view', 'id'=>$model->product->id]).'" class="dropdown-item">Посмотреть</a></li>
                                                    </ul>';
                                        }
                                    ],
                                ]
                            ],
                        ]); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script src="/admin_files/bower_components/jquery/dist/jquery.min.js"></script>

<script>
  $(function () {
    var areaChartData = {
      labels  : ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
      datasets: [
        {
          label               : 'Electronics',
          fillColor           : 'rgba(210, 214, 222, 1)',
          strokeColor         : 'rgba(210, 214, 222, 1)',
          pointColor          : 'rgba(210, 214, 222, 1)',
          pointStrokeColor    : '#c1c7d1',
          pointHighlightFill  : '#fff',
          pointHighlightStroke: 'rgba(220,220,220,1)',
          data                : [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
        },
        {
          label               : 'Digital Goods',
          fillColor           : 'rgba(60,141,188,0.9)',
          strokeColor         : 'rgba(60,141,188,0.8)',
          pointColor          : '#3b8bba',
          pointStrokeColor    : 'rgba(60,141,188,1)',
          pointHighlightFill  : '#fff',
          pointHighlightStroke: 'rgba(60,141,188,1)',
          data                : [<?php foreach ($data_products as $k => $product) { echo $product.','; }?>]
        }
      ]
    }

    var barChartCanvas                   = $('#barChart').get(0).getContext('2d')
    var barChart                         = new Chart(barChartCanvas)
    var barChartData                     = areaChartData
    barChartData.datasets[1].fillColor   = '#00a65a'
    barChartData.datasets[1].strokeColor = '#00a65a'
    barChartData.datasets[1].pointColor  = '#00a65a'
    var barChartOptions                  = {
      scaleBeginAtZero        : true,
      scaleShowGridLines      : true,
      scaleGridLineColor      : 'rgba(0,0,0,.05)',
      scaleGridLineWidth      : 1,
      scaleShowHorizontalLines: true,
      scaleShowVerticalLines  : true,
      barShowStroke           : true,
      barStrokeWidth          : 2,
      barValueSpacing         : 5,
      barDatasetSpacing       : 1,
      legendTemplate          : '<ul class="<%=name.toLowerCase()%>-legend"><% for (var i=0; i<datasets.length; i++){%><li><span style="background-color:<%=datasets[i].fillColor%>"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>',
      responsive              : true,
      maintainAspectRatio     : true
    }

    barChartOptions.datasetFill = false
    barChart.Bar(barChartData, barChartOptions)
  })
</script>