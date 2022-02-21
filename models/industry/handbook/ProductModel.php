<?php

namespace app\models\industry\handbook;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "b_model".
 *
 * @property int $id
 * @property string $name_ru
 * @property string|null $name_en
 * @property string|null $name_uz
 * @property int $status
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property BBufferZone[] $bBufferZones
 * @property BDepartmentElectro[] $bDepartmentElectros
 * @property BDepartmentGp[] $bDepartmentGps
 * @property BDepartmentMechanical[] $bDepartmentMechanicals
 * @property BDepartmentPaiting[] $bDepartmentPaitings
 * @property BDepartmentSizing[] $bDepartmentSizings
 * @property BDepartmentStamping[] $bDepartmentStampings
 * @property BDepartmentTest[] $bDepartmentTests
 */
class ProductModel extends \yii\db\ActiveRecord
{


    public static function tableName()
    {
        return 'b_model';
    }


    public function rules()
    {
        return [
            [['name_ru', 'articul'], 'required'],
            [['status', 'created_at', 'updated_at', 'sort', 'department_id'], 'integer'],
            [['name_ru', 'name_en', 'name_uz'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name_ru' => Yii::t('app', 'Название Ru'),
            'name_en' => Yii::t('app', 'Название En'),
            'name_uz' => Yii::t('app', 'Название Uz'),
            'sort' => Yii::t('app', 'Сортировка'),
            'status' => Yii::t('app', 'Статус'),
            'created_at' => Yii::t('app', 'Дата создания'),
            'updated_at' => Yii::t('app', 'Дата изменения'),
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

    public function getBBufferZones()
    {
        return $this->hasMany(BBufferZone::className(), ['model_id' => 'id']);
    }

    public function removeObject(){
        return $this->delete();
    }

    public function getBDepartmentElectros()
    {
        return $this->hasMany(BDepartmentElectro::className(), ['model_id' => 'id']);
    }


    public function getBDepartmentGps()
    {
        return $this->hasMany(BDepartmentGp::className(), ['model_id' => 'id']);
    }


    public function getBDepartmentMechanicals()
    {
        return $this->hasMany(BDepartmentMechanical::className(), ['model_id' => 'id']);
    }


    public function getBDepartmentPaitings()
    {
        return $this->hasMany(BDepartmentPaiting::className(), ['model_id' => 'id']);
    }


    public function getBDepartmentSizings()
    {
        return $this->hasMany(BDepartmentSizing::className(), ['model_id' => 'id']);
    }


    public function getBDepartmentStampings()
    {
        return $this->hasMany(BDepartmentStamping::className(), ['model_id' => 'id']);
    }


    public function getBDepartmentTests()
    {
        return $this->hasMany(BDepartmentTest::className(), ['model_id' => 'id']);
    }

    public function getDepartment()
    {
        return $this->hasOne(BDepartment::className(), ['id' => 'department_id']);
    }
}
