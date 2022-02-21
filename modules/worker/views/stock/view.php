<?php

use app\widgets\admin_language_tab\AdminLanguageTab;
use app\widgets\admin_stock_menu\AdminStockMenu;

$this->title = 'Склад';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="content-wrapper">
    <section class="content-header">
        <h1><?=$this->title;?></h1>

        <ol class="breadcrumb">
            <li><a href="<?=Yii::$app->urlManager->createUrl(['/worker/'])?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active"><?=$this->title;?></li>
        </ol>
    </section>
    <section class="content">
        <?php if (Yii::$app->session->hasFlash('stock_saved')) {?>
            <div class="alert alert-success text-center">
                <?=Yii::$app->session->getFlash('stock_saved');?>
            </div>
        <?php }?>
        <div class="row">
            <div class="col-sm-3">
                <?=AdminStockMenu::widget();?>
            </div>
            <div class="col-sm-9">
                <?php if ($model) {?>
                    <div class="box">
                        <div class="box-header">
                            <div class="btn-group">
                                <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                                    <span class="fa fa-cog"></span>
                                </button>
                                <ul class="dropdown-menu pull-left">
                                    <li><a href="<?=Yii::$app->urlManager->createUrl(['/worker/stock/create']);?>" class="dropdown-item">Добавить склад</a></li>
                                    <li><a href="<?=Yii::$app->urlManager->createUrl(['/worker/stock/create', 'id'=>$model->id]);?>" class="dropdown-item">Редактировать</a></li>
                                    <li><a href="<?=Yii::$app->urlManager->createUrl(['/worker/stock/remove', 'id'=>$model->id]);?>" class="dropdown-item" class="remove-object">Удалить</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="box-body">
                            <table class="table table-striped">
                                <tr>
                                    <td>ID:</td>
                                    <td><?=$model->id ? $model->id : '-';?></td>
                                </tr>
                                <tr>
                                    <td>Название:</td>
                                    <td><?=$model->name_ru ? $model->name_ru : '-';?></td>
                                </tr>
                                <tr>
                                    <td>Логин:</td>
                                    <td><?=$model->user ? $model->user->login : '-';?></td>
                                </tr>
                                <tr>
                                    <td>Код:</td>
                                    <td><?=$model->code ? $model->code : '-';?></td>
                                </tr>
                                <tr>
                                    <td>Статус:</td>
                                    <td>
                                        <?php
                                            if ($model->status == 1) {
                                                echo '<small class="label label-success">Активный</small>';
                                            } else {
                                                echo '<small class="label label-danger">Заблокирован</small>';
                                            }
                                        ?>
                                    </td>
                                </tr>
                                <!-- <tr>
                                    <td>Адрес:</td>
                                    <td><?=$model->address ? $model->address : '-';?></td>
                                </tr> -->
                            </table>
                            <br/>
                        </div>
                    </div>
                    <div class="box">
                        <div class="box-header">
                            Описание
                        </div>
                        <div class="box-body">
                            <?php if ($model->description_ru) {?>
                                <?=$model->description_ru;?>
                            <?php } else {?>
                                <div class="alert alert-warning text-center">Описания нет</div>
                            <?php }?>
                        </div>
                    </div>
                    <!-- <div class="box">
                        <div class="box-header">
                            Местоположение
                        </div>
                        <div class="box-body">
                            <?php if ($model && $model->lng && $model->lat) {?>
                                <div id="map"></div>
                            <?php } else {?>
                                <div class="alert alert-warning text-center">Местоположение не указано</div>
                            <?php }?>
                        </div>
                    </div> -->
                <?php } else {?>
                    <div class="alert alert-warning text-center">Данных нет</div>
                <?php }?>
            </div>
        </div>
    </section>
</div>


<?php if ($model && $model->lng && $model->lat) {?>
    <script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>
    <script type="text/javascript">

    ymaps.ready(init);

    var myMap, 
        myPlacemark;

    function init() {
        var myMap = new ymaps.Map("map", {
                center: [<?=$model->lng;?>, <?=$model->lat;?>],
                zoom: 13
            }, {
                searchControlProvider: 'yandex#search'
            }),

        // Создаем геообъект с типом геометрии "Точка".
            myGeoObject = new ymaps.GeoObject({
                // Описание геометрии.
                geometry: {
                    type: "Point",
                    coordinates: [55.8, 37.8]
                },
                // Свойства.
                properties: {
                    // Контент метки.
                    // iconContent: 'Я тащусь',
                    // hintContent: 'Ну давай уже тащи'
                }
            }, {
                // Опции.
                // Иконка метки будет растягиваться под размер ее содержимого.
                preset: 'islands#blackStretchyIcon',
                // Метку можно перемещать.
                draggable: true
            }),
            myPieChart = new ymaps.Placemark([
                55.847, 37.6
            ], {
                // Данные для построения диаграммы.
                data: [
                    {weight: 8, color: '#0E4779'},
                    {weight: 6, color: '#1E98FF'},
                    {weight: 4, color: '#82CDFF'}
                ],
                iconCaption: "Диаграмма"
            }, {
                // Зададим произвольный макет метки.
                iconLayout: 'default#pieChart',
                // Радиус диаграммы в пикселях.
                iconPieChartRadius: 30,
                // Радиус центральной части макета.
                iconPieChartCoreRadius: 10,
                // Стиль заливки центральной части.
                iconPieChartCoreFillStyle: '#ffffff',
                // Cтиль линий-разделителей секторов и внешней обводки диаграммы.
                iconPieChartStrokeStyle: '#ffffff',
                // Ширина линий-разделителей секторов и внешней обводки диаграммы.
                iconPieChartStrokeWidth: 3,
                // Максимальная ширина подписи метки.
                iconPieChartCaptionMaxWidth: 200
            });

        myMap.geoObjects
            .add(myGeoObject)
            .add(myPieChart)
            .add(new ymaps.Placemark([<?=$model->lng;?>, <?=$model->lat;?>]));
    }

    </script>
<?php }?>