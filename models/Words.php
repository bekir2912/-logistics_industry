<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "words".
 *
 * @property int $id
 * @property string $name_ru
 * @property string $name_uz
 * @property string $name_en
 * @property string $date
 */
class Words extends \yii\db\ActiveRecord
{
    public $names_ru = [];
    public $names_uz = [];
    public $names_en = [];
    public $ids = [];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'words';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name_ru', 'name_uz', 'name_en'], 'string'],
            [['date', 'ids', 'names_ru', 'names_uz', 'names_en'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name_ru' => 'Name Ru',
            'name_uz' => 'Name Uz',
            'name_en' => 'Name En',
            'date' => 'Date',
        ];
    }

    public function saveWord($type = 'insert') {
        if ($type == 'update') {
            if ($this->names_ru) {
                foreach ($this->names_ru as $k => $v) {
                    Yii::$app->db->createCommand()->update('words', ['name_ru'=>$this->names_ru[$k], 'name_uz'=>$this->names_uz[$k], 'name_en'=>$this->names_en[$k]], ['id'=>$this->ids[$k]])->execute();
                }
            }
        } else {
            if ($this->names_ru) {
                $keys = array('name_ru', 'name_uz', 'name_en');
                $vals = [];
                foreach ($this->names_ru as $k => $v) {
                    $vals[$k]['name_ru'] = trim($this->names_ru[$k]);
                    $vals[$k]['name_uz'] = trim($this->names_uz[$k]);
                    $vals[$k]['name_en'] = trim($this->names_en[$k]);
                }

                Yii::$app->db->createCommand()->batchInsert('words', $keys, $vals)->execute();
            }
        }

        return true;
    }
}
