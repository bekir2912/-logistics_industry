<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AdminAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        '/admin_files/bower_components/bootstrap/dist/css/bootstrap.min.css',
        '/admin_files/bower_components/font-awesome/css/font-awesome.min.css',
        '/admin_files/bower_components/Ionicons/css/ionicons.min.css',
        '/admin_files/css/skins/_all-skins.min.css',
        '/admin_files/css/AdminLTE.min.css',
        '/admin_files/css/AdminLTE.css',
        '/admin_files/plugins/iCheck/all.css',
        '/admin_files/plugins/iCheck/square/blue.css',
        '/admin_files/css/nestable.css',
        '/admin_files/css/btn.css',
        '/admin_files/css/style.css',
        '/admin_files/plugins/bootstrap-slider/slider.css',
        '/plugins/file-uploader/css/jquery.fileuploader.css',
        '/plugins/file-uploader/css/jquery.fileuploader-theme-thumbnails.css',
        '/plugins/datepicker/jquery.datetimepicker.css',
        '/plugins/select2/select2.min.css',
    ];
    public $js = [
        '/admin_files/bower_components/bootstrap/dist/js/bootstrap.min.js',
        '/admin_files/bower_components/fastclick/lib/fastclick.js',
        '/admin_files/bower_components/chart.js/Chart.js',
        '/admin_files/plugins/bootstrap-slider/bootstrap-slider.js',
        '/admin_files/js/adminlte.min.js',
        '/admin_files/js/demo.js',
        '/admin_files/js/nestable.js',
        '/admin_files/js/main.js',
        '/admin_files/plugins/iCheck/icheck.min.js',
        '/admin_files/bower_components/jquery-knob/js/jquery.knob.js',
        '/admin_files/bower_components/jquery-sparkline/dist/jquery.sparkline.min.js',
        '/admin_files/js/custom.js',
        '/plugins/file-uploader/js/jquery.fileuploader.min.js',
        '/plugins/file-uploader/js/custom.js',
        '/plugins/datepicker/jquery.datetimepicker.full.js',
        '/plugins/select2/select2.min.js',
        '/assets_files/js/input.mask.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}