<?php

namespace app\models\industry\handbook;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "b_defect".
 *
 * @property int $id
 * @property int $department_id
 * @property string $name_ru
 * @property string|null $name_en
 * @property string|null $name_uz
 * @property int $status
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property BDepartment $department
 * @property BDeffects[] $bDeffects
 */
class BDeffect extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'b_defect';
    }

    public function rules()
    {
        return [
            [['department_id', 'name_ru'], 'required'],
            [['department_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['name_ru', 'name_en', 'name_uz'], 'string', 'max' => 255],
            [['department_id'], 'exist', 'skipOnError' => true, 'targetClass' => BDepartment::className(), 'targetAttribute' => ['department_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'department_id' => Yii::t('app', 'Отдел'),
            'name_ru' => Yii::t('app', 'Название Ru'),
            'name_en' => Yii::t('app', 'Название En'),
            'name_uz' => Yii::t('app', 'Название UZ'),
            'status' => Yii::t('app', 'Статус'),
            'created_at' => Yii::t('app', 'Дата создания'),
            'updated_at' => Yii::t('app', 'Дата обновления'),
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ],
        ];
    }

    public function getDepartment()
    {
        return $this->hasOne(BDepartment::className(), ['id' => 'department_id']);
    }

    public function removeObject(){
        return $this->delete();
    }

    public function getBDeffects()
    {
        return $this->hasMany(BDeffects::className(), ['deffect_id' => 'id']);
    }
}
