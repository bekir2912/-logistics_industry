<?php

namespace app\models\industry\handbook;

use app\models\industry\DepartmentElectro;
use app\models\industry\DepartmentMechanical;
use app\models\industry\DepartmentTest;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "b_line".
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
 * @property BDeffect[] $bDeffects
 * @property DepartmentElectro[] $bDepartmentElectros
 * @property BDepartmentGp[] $bDepartmentGps
 * @property DepartmentMechanical[] $bDepartmentMechanicals
 * @property DepartmentTest[] $bDepartmentTests
 * @property BDepartment $department
 */
class BLine extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'b_line';
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

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'department_id' => Yii::t('app', 'Отдел'),
            'name_ru' => Yii::t('app', 'Название Ru'),
            'name_en' => Yii::t('app', 'Название En'),
            'name_uz' => Yii::t('app', 'Название Uz'),
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
//                 'value' => new Expression('NOW()'),
            ],
        ];
    }

    public function removeObject(){
        return $this->delete();
    }

    public function getBDeffects()
    {
        return $this->hasMany(BDeffect::className(), ['line_id' => 'id']);
    }

    public function getBDepartmentElectros()
    {
        return $this->hasMany(DepartmentElectro::className(), ['line_id' => 'id']);
    }

    public function getBDepartmentGps()
    {
        return $this->hasMany(BDepartmentGP::className(), ['line_id' => 'id']);
    }

    public function getBDepartmentMechanicals()
    {
        return $this->hasMany(DepartmentMechanical::className(), ['line_id' => 'id']);
    }

    public function getBDepartmentTests()
    {
        return $this->hasMany(DepartmentTest::className(), ['line_id' => 'id']);
    }

    public function getDepartment()
    {
        return $this->hasOne(BDepartment::className(), ['id' => 'department_id']);
    }
}
