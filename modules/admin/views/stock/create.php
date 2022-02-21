<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

use kartik\select2\Select2;
use mihaildev\ckeditor\CKEditor;

$this->title = 'Сохранить ряд';
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="content-wrapper">
    <section class="content-header">
        <h1><?=$this->title;?></h1>

        <ol class="breadcrumb">
            <li><a href="<?=Yii::$app->urlManager->createUrl(['/admin/'])?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active"><?=$this->title;?></li>
        </ol>
    </section>
    <section class="content">

        <?php if (Yii::$app->session->hasFlash('photo_removed')) {?>
            <div class="alert alert-success text-center alert-bottom"><?=Yii::$app->session->getFlash('photo_removed');?></div>
        <?php }?>
        <?php $form = ActiveForm::begin(); ?>
            <div class="box box-info color-palette-box">
                <div class="box-header">
                    Данные склада
                </div>
            
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <?=$form->field($model, 'name_ru')->textInput()->input('text', ['placeholder'=>'Введите название', 'class'=>'form-control'])->label('Название <span class="required-field">*</span>');?>
                        </div>
                        <div class="col-sm-6">
                            <?=$form->field($model, 'code')->textInput()->input('text', ['placeholder'=>'Введите код', 'class'=>'form-control'])->label('Код');?>
                        </div>
                    </div>
                    <div class="row">
                        
                        <div class="col-sm-12">
                            <?=$form->field($model, 'description_ru')->textarea(['rows' => '6'])->label('Описание');?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="box box-info color-palette-box">
                <div class="box-header">
                    Доступ
                </div>
            
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <?=$form->field($model, 'login')->textInput()->input('text', ['placeholder'=>'Введите логин', 'class'=>'form-control'])->label('Логин <span class="required-field">*</span>');?>
                        </div>
                        <div class="col-sm-6">
                            <?=$form->field($model, 'password')->textInput()->input('password', ['placeholder'=>'Придумайте пароль', 'class'=>'form-control'])->label('Пароль');?>
                        </div>
                    </div>
                    
                </div>
                <div class="box-footer">
                    <?=Html::submitButton('<i class="fa fa-check"></i> Сохранить', ['class'=>'btn btn-primary']);?>
                </div>
            </div>

            <!-- <div class="box">
                <div class="box-header">
                    Местоположение на карте
                </div>
                <div class="box-body">
                    <div id="map"></div>
                    <?=$form->field($model, 'lng')->textInput()->input('hidden', ['class'=>'object-lng'])->label(false);?>
                    <?=$form->field($model, 'lat')->textInput()->input('hidden', ['class'=>'object-lat'])->label(false);?>
                </div>
                <div class="box-footer">
                    <?=Html::submitButton('<i class="fa fa-check"></i> Сохранить', ['class'=>'btn btn-primary']);?>
                </div>
            </div> -->
        <?php ActiveForm::end();?>
    </section>
</div>

<script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>
<script type="text/javascript">

ymaps.ready(init);

var myMap, 
    myPlacemark;

function init(){ 
    myMap = new ymaps.Map("map", {
        center: [41.31688073, 69.24690049],
        zoom: 12
    }); 
    
    // Слушаем клик на карте.
    myMap.events.add('click', function (e) {
        var coords = e.get('coords');

        $('.object-lng').val(coords[0]);
        $('.object-lat').val(coords[1]);

        // Если метка уже создана – просто передвигаем ее.
        if (myPlacemark) {
            myPlacemark.geometry.setCoordinates(coords);
        }
        // Если нет – создаем.
        else {
            myPlacemark = createPlacemark(coords);
            myMap.geoObjects.add(myPlacemark);
            // Слушаем событие окончания перетаскивания на метке.
            myPlacemark.events.add('dragend', function () {
                getAddress(myPlacemark.geometry.getCoordinates());
            });
        }
        getAddress(coords);
    });

    // Создание метки.
    function createPlacemark(coords) {
        return new ymaps.Placemark(coords, {
            iconCaption: 'поиск...'
        }, {
            preset: 'islands#violetDotIconWithCaption',
            draggable: true
        });
    }

    // Определяем адрес по координатам (обратное геокодирование).
    function getAddress(coords) {
        myPlacemark.properties.set('iconCaption', 'поиск...');
        ymaps.geocode(coords).then(function (res) {
            var firstGeoObject = res.geoObjects.get(0);

            myPlacemark.properties
                .set({
                    // Формируем строку с данными об объекте.
                    iconCaption: [
                        // Название населенного пункта или вышестоящее административно-территориальное образование.
                        firstGeoObject.getLocalities().length ? firstGeoObject.getLocalities() : firstGeoObject.getAdministrativeAreas(),
                        // Получаем путь до топонима, если метод вернул null, запрашиваем наименование здания.
                        firstGeoObject.getThoroughfare() || firstGeoObject.getPremise()
                    ].filter(Boolean).join(', '),
                    // В качестве контента балуна задаем строку с адресом объекта.
                    balloonContent: firstGeoObject.getAddressLine()
                });
        });
    }
}
</script>