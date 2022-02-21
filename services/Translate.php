<?php

namespace yii\services;

use Yii;
use yii\helpers\ArrayHelper;

use app\models\Category;
use app\models\Words;

class Translate {
    public function get() {
        $languages = ArrayHelper::map(Category::find()->where(['type'=>'language'])->all(), 'id', 'name_mini');
        $data_translate = [];

        $session = Yii::$app->session;

        $language = Category::find()->with('image');
        $session['language'] ? $language->where(['id'=>$session['language']]) : $language->where(['type'=>'language', 'main'=>1]);
        $language = $language->one();

        $data_translate['language'] = $language;

        $model_words = Words::find()->all();

        foreach ($model_words as $k => $v) {
            $name = $v->{'name_'.$language->name_mini};
            $data_translate['words'][$v->name_ru] = $name ? $name : $name_ru;
        }

        return $data_translate;
    }
}