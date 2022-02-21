<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\widgets\LinkPager;
use yii\bootstrap\ActiveForm;

use app\widgets\admin_stock_menu\AdminStockMenu;

$this->title = 'Этажи';
$this->params['breadcrumbs'][] = $this->title;

$id = Yii::$app->request->get('id');
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
        <?php if (Yii::$app->session->hasFlash('stack_removed')) {?>
            <div class="alert alert-success text-center">
                <?=Yii::$app->session->getFlash('stack_removed');?>
            </div>
        <?php }?>
        <div class="row">
            <div class="col-sm-3">
                <?=AdminStockMenu::widget();?>
            </div>
            <div class="col-sm-9">
                <?php if ($stacks) {?>
                    <div class="row">
                        <?php foreach ($stacks as $stack) {?>
                            <div class="col-sm-3">
                                <div class="box box-warning color-palette-box">
                                    <div class="box-header with-border">
                                        <?=$stock->name_ru;?>
                                    </div>
                                    <div class="box-body text-center">
                                        <div class="sparkline" data-type="pie" data-offset="90" data-width="200px" data-height="200px">
                                            <?php if ($stack->stackShelvings) {?>
                                                <?php foreach ($stack->stackShelvings as $shelf) {?>
                                                    <?=count($shelf->products).',';?>
                                                <?php }?>
                                            <?php } ?>
                                            <?php $taken = 100 - count($stack->products);?>
                                            <?php $taken = round($taken);?>
                                        </div>
                                        <hr/>
                                        <table class="table">
                                            <tr>
                                                <td align="left">Свободно:</td>
                                                <td><span class="label label-success"><?=round($taken);?>%</span></td>
                                            </tr>
                                            <tr>
                                                <td align="left">Занято:</td>
                                                <td><span class="label label-success"><?=100 - $taken;?>%</span></td>
                                            </tr>
                                            <tr>
                                                <td align="left">Кол-во ячеек:</td>
                                                <td><span class="label label-success"><?=count($stack->stackShelvings);?></span></td>
                                            </tr>
                                            <tr>
                                                <td align="left">Кол-во продуктов:</td>
                                                <td><span class="label label-success"><?=count($stack->products);?></span></td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="box-footer">
                                        <a href="<?=Yii::$app->urlManager->createUrl(['/worker/stock/stack-view', 'id'=>$id, 'stack_id'=>$stack->id]);?>" class="btn btn-info" style="width:100%">Посмотреть</a>
                                    </div>
                                </div>
                            </div>
                        <?php }?>
                    </div>
                <?php }?>
                <div class="box">
                    <!-- <div class="box-header">
                        <div class="box-title">
                            <a href="<?=Yii::$app->urlManager->createUrl(['/worker/stock/stack-create', 'id'=>$id])?>" class="btn btn-primary">
                                <i class="fa fa-plus"></i>
                                Добавить этаж
                            </a>
                        </div>
                        <div id="action-links" style="display:none">
                            <a href="javascript:;" class="btn btn-danger" data-value="remove"><i class="fa fa-trash"></i> Удалить</a>
                            <a href="javascript:;" class="btn btn-warning" data-value="disable"><i class="fa fa-lock"></i> Заблокировать</a>
                            <a href="javascript:;" class="btn btn-success" data-value="enable"><i class="fa fa-unlock"></i> Разблокировать</a>
                        </div>
                    </div> -->
                    <div class="box-body" id="item-block">
                        <?php Pjax::begin(); ?>
                            <?= GridView::widget([
                                'dataProvider' => $dataProvider,
                                'filterModel' => $searchModel,
                                'summary' => "Страница {begin} - {end} из {totalCount} этажей<br/><br/>",
                                'emptyText' => 'Этажей нет',
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
                                        'attribute'=>'stack_number',
                                        'label'=>'<i class="fa fa-sort"></i> Номер этажа',
                                        'encodeLabel' => false,
                                    ],
                                    [
                                        'attribute'=>'shelfs_count',
                                        'label'=>'<i class="fa fa-sort"></i> Кол-во полок',
                                        'encodeLabel' => false,
                                        'value' => function ($model, $key, $index, $column) {
                                            return $model->stackShelvings ? count($model->stackShelvings) : '-';
                                        },
                                    ],
                                    [
                                        'attribute'=>'status',
                                        'label'=>'<i class="fa fa-sort"></i> Статус',
                                        'encodeLabel' => false,
                                        'format' => 'html',
                                        'contentOptions' => [
                                            'style' => 'width:100px'
                                        ],
                                        'value' => function ($model, $key, $index, $column) {
                                            if ($model->status == 1) {
                                                return '<small class="label label-success">Активный</small>';
                                            } else {
                                                return '<small class="label label-danger">Заблокирован</small>';
                                            }
                                        },
                                    ],
                                    [
                                        'attribute'=>'date',
                                        'label'=>'<i class="fa fa-sort"></i> Дата',
                                        'encodeLabel' => false,
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
                                                            <li><a href="'.Yii::$app->urlManager->createUrl(['/worker/stock/stack-view', 'id'=>$model->stock_id, 'stack_id'=>$model->id]).'" class="dropdown-item">Посмотреть</a></li>
                                                        </ul>';
                                            }
                                        ],
                                    ]
                                ],
                            ]); ?>
                        <?php Pjax::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script src="/admin_files/bower_components/jquery/dist/jquery.min.js"></script>

<script>
  $(function () {
    /* jQueryKnob */

    $(".knob").knob({
      /*change : function (value) {
       //console.log("change : " + value);
       },
       release : function (value) {
       console.log("release : " + value);
       },
       cancel : function () {
       console.log("cancel : " + this.value);
       },*/
      draw: function () {

        // "tron" case
        if (this.$.data('skin') == 'tron') {

          var a = this.angle(this.cv)  // Angle
              , sa = this.startAngle          // Previous start angle
              , sat = this.startAngle         // Start angle
              , ea                            // Previous end angle
              , eat = sat + a                 // End angle
              , r = true;

          this.g.lineWidth = this.lineWidth;

          this.o.cursor
          && (sat = eat - 0.3)
          && (eat = eat + 0.3);

          if (this.o.displayPrevious) {
            ea = this.startAngle + this.angle(this.value);
            this.o.cursor
            && (sa = ea - 0.3)
            && (ea = ea + 0.3);
            this.g.beginPath();
            this.g.strokeStyle = this.previousColor;
            this.g.arc(this.xy, this.xy, this.radius - this.lineWidth, sa, ea, false);
            this.g.stroke();
          }

          this.g.beginPath();
          this.g.strokeStyle = r ? this.o.fgColor : this.fgColor;
          this.g.arc(this.xy, this.xy, this.radius - this.lineWidth, sat, eat, false);
          this.g.stroke();

          this.g.lineWidth = 2;
          this.g.beginPath();
          this.g.strokeStyle = this.o.fgColor;
          this.g.arc(this.xy, this.xy, this.radius - this.lineWidth + 1 + this.lineWidth * 2 / 3, 0, 2 * Math.PI, false);
          this.g.stroke();

          return false;
        }
      }
    });
    /* END JQUERY KNOB */

    //INITIALIZE SPARKLINE CHARTS
    $(".sparkline").each(function () {
      var $this = $(this);
      $this.sparkline('html', $this.data());
    });

    /* SPARKLINE DOCUMENTATION EXAMPLES http://omnipotent.net/jquery.sparkline/#s-about */
    drawDocSparklines();
    drawMouseSpeedDemo();

  });
  function drawDocSparklines() {

    // Bar + line composite charts
    $('#compositebar').sparkline('html', {type: 'bar', barColor: '#aaf'});
    $('#compositebar').sparkline([4, 1, 5, 7, 9, 9, 8, 7, 6, 6, 4, 7, 8, 4, 3, 2, 2, 5, 6, 7],
        {composite: true, fillColor: false, lineColor: 'red'});


    // Line charts taking their values from the tag
    $('.sparkline-1').sparkline();

    // Larger line charts for the docs
    $('.largeline').sparkline('html',
        {type: 'line', height: '2.5em', width: '4em'});

    // Customized line chart
    $('#linecustom').sparkline('html',
        {
          height: '1.5em', width: '8em', lineColor: '#f00', fillColor: '#ffa',
          minSpotColor: false, maxSpotColor: false, spotColor: '#77f', spotRadius: 3
        });

    // Bar charts using inline values
    $('.sparkbar').sparkline('html', {type: 'bar'});

    $('.barformat').sparkline([1, 3, 5, 3, 8], {
      type: 'bar',
      tooltipFormat: '{{value:levels}} - {{value}}',
      tooltipValueLookups: {
        levels: $.range_map({':2': 'Low', '3:6': 'Medium', '7:': 'High'})
      }
    });

    // Tri-state charts using inline values
    $('.sparktristate').sparkline('html', {type: 'tristate'});
    $('.sparktristatecols').sparkline('html',
        {type: 'tristate', colorMap: {'-2': '#fa7', '2': '#44f'}});

    // Composite line charts, the second using values supplied via javascript
    $('#compositeline').sparkline('html', {fillColor: false, changeRangeMin: 0, chartRangeMax: 10});
    $('#compositeline').sparkline([4, 1, 5, 7, 9, 9, 8, 7, 6, 6, 4, 7, 8, 4, 3, 2, 2, 5, 6, 7],
        {composite: true, fillColor: false, lineColor: 'red', changeRangeMin: 0, chartRangeMax: 10});

    // Line charts with normal range marker
    $('#normalline').sparkline('html',
        {fillColor: false, normalRangeMin: -1, normalRangeMax: 8});
    $('#normalExample').sparkline('html',
        {fillColor: false, normalRangeMin: 80, normalRangeMax: 95, normalRangeColor: '#4f4'});

    // Discrete charts
    $('.discrete1').sparkline('html',
        {type: 'discrete', lineColor: 'blue', xwidth: 18});
    $('#discrete2').sparkline('html',
        {type: 'discrete', lineColor: 'blue', thresholdColor: 'red', thresholdValue: 4});

    // Bullet charts
    $('.sparkbullet').sparkline('html', {type: 'bullet'});

    // Pie charts
    $('.sparkpie').sparkline('html', {type: 'pie', height: '1.0em'});

    // Box plots
    $('.sparkboxplot').sparkline('html', {type: 'box'});
    $('.sparkboxplotraw').sparkline([1, 3, 5, 8, 10, 15, 18],
        {type: 'box', raw: true, showOutliers: true, target: 6});

    // Box plot with specific field order
    $('.boxfieldorder').sparkline('html', {
      type: 'box',
      tooltipFormatFieldlist: ['med', 'lq', 'uq'],
      tooltipFormatFieldlistKey: 'field'
    });

    // click event demo sparkline
    $('.clickdemo').sparkline();
    $('.clickdemo').bind('sparklineClick', function (ev) {
      var sparkline = ev.sparklines[0],
          region = sparkline.getCurrentRegionFields();
      value = region.y;
      alert("Clicked on x=" + region.x + " y=" + region.y);
    });

    // mouseover event demo sparkline
    $('.mouseoverdemo').sparkline();
    $('.mouseoverdemo').bind('sparklineRegionChange', function (ev) {
      var sparkline = ev.sparklines[0],
          region = sparkline.getCurrentRegionFields();
      value = region.y;
      $('.mouseoverregion').text("x=" + region.x + " y=" + region.y);
    }).bind('mouseleave', function () {
      $('.mouseoverregion').text('');
    });
  }

  /**
   ** Draw the little mouse speed animated graph
   ** This just attaches a handler to the mousemove event to see
   ** (roughly) how far the mouse has moved
   ** and then updates the display a couple of times a second via
   ** setTimeout()
   **/
  function drawMouseSpeedDemo() {
    var mrefreshinterval = 500; // update display every 500ms
    var lastmousex = -1;
    var lastmousey = -1;
    var lastmousetime;
    var mousetravel = 0;
    var mpoints = [];
    var mpoints_max = 30;
    $('html').mousemove(function (e) {
      var mousex = e.pageX;
      var mousey = e.pageY;
      if (lastmousex > -1) {
        mousetravel += Math.max(Math.abs(mousex - lastmousex), Math.abs(mousey - lastmousey));
      }
      lastmousex = mousex;
      lastmousey = mousey;
    });
    var mdraw = function () {
      var md = new Date();
      var timenow = md.getTime();
      if (lastmousetime && lastmousetime != timenow) {
        var pps = Math.round(mousetravel / (timenow - lastmousetime) * 1000);
        mpoints.push(pps);
        if (mpoints.length > mpoints_max)
          mpoints.splice(0, 1);
        mousetravel = 0;
        $('#mousespeed').sparkline(mpoints, {width: mpoints.length * 2, tooltipSuffix: ' pixels per second'});
      }
      lastmousetime = timenow;
      setTimeout(mdraw, mrefreshinterval);
    };
    // We could use setInterval instead, but I prefer to do it this way
    setTimeout(mdraw, mrefreshinterval);
  }
</script>