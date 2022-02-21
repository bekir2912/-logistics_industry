<?php

namespace app\modules\worker_shop;

use Yii;

/**
 * admin module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\worker_shop\controllers';
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->layout = '/worker_shop';
        parent::init();

        // custom initialization code goes here
    }
}
