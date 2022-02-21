<?php

namespace app\modules\worker;

use Yii;

/**
 * admin module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\worker\controllers';
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->layout = '/worker';
        parent::init();

        // custom initialization code goes here
    }
}
