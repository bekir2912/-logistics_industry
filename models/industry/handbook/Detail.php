<?php

namespace app\models\industry\handbook;

use app\models\product\Product;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "b_details".
 *
 * @property int $id
 * @property int|null $department_id
 * @property int $sort
 * @property string $name_ru
 * @property string|null $name_en
 * @property string|null $name_uz
 * @property int|null $count
 * @property int $status
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property BDeffects[] $bDeffects
 * @property BDepartments $department
 */
class Detail extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'b_details';
    }


    public function rules()
    {
        return [
            [['department_id', 'product_id', 'sort', 'count', 'status', 'created_at', 'updated_at', 'unit_id'], 'integer'],
            [['name_ru'], 'required'],
            [['name_ru', 'name_en', 'name_uz'], 'string', 'max' => 255],
            [['department_id'], 'exist', 'skipOnError' => true, 'targetClass' => BDepartment::className(), 'targetAttribute' => ['department_id' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'department_id' => Yii::t('app', 'Отдел'),
            'unit_id' => Yii::t('app', 'Ед. измерения'),
            'name_ru' => Yii::t('app', 'Наименование Ru'),
            'name_en' => Yii::t('app', 'Наименование En'),
            'name_uz' => Yii::t('app', 'Наименование Uz'),
            'count' => Yii::t('app', 'Кол-во'),
            'product_id' => Yii::t('app', 'Продукт'),
            'status' => Yii::t('app', 'Статус'),
            'sort' => Yii::t('app', 'Сортировка'),
            'created_at' => Yii::t('app', 'Дата добавления'),
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
        return $this->hasMany(BDeffects::className(), ['detail_id' => 'id']);
    }

    public function getDepartment()
    {
        return $this->hasOne(BDepartment::className(), ['id' => 'department_id']);
    }

    public function getUnit()
    {
        return $this->hasOne(Category::className(), ['id' => 'unit_id']);
    }

    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }


}
