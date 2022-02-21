<?php

namespace app\models\industry;

use app\models\Category;
use app\models\industry\handbook\BDeffect;
use app\models\industry\handbook\BDepartment;
use app\models\industry\handbook\BLine;
use app\models\industry\handbook\Detail;
use app\models\industry\handbook\ProductModel;
use app\models\user\User;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class AllDeffect extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'b_deffects';
    }





    public function rules()
    {
        return [
            [['department_id', 'deffect_id',  'user_id', 'count_deffect', 'is_save'], 'required'],
            [['department_id', 'deffect_id', 'detail_id', 'user_id', 'line_id', 'is_save', 'count_deffect', 'status', 'created_at', 'updated_at', 'model_id', 'dep_id'], 'integer'],
            [['number_poddon'], 'string', 'max' => 255],
            [['description_en','description_uz','description_ru', 'current_operation'], 'string'],
            [['unit_id'], 'safe'],
            [['line_id'], 'exist', 'skipOnError' => true, 'targetClass' => BLine::className(), 'targetAttribute' => ['line_id' => 'id']],
            [['department_id'], 'exist', 'skipOnError' => true, 'targetClass' => BDepartment::className(), 'targetAttribute' => ['department_id' => 'id']],
            [['detail_id'], 'exist', 'skipOnError' => true, 'targetClass' => Detail::className(), 'targetAttribute' => ['detail_id' => 'id']],
            [['deffect_id'], 'exist', 'skipOnError' => true, 'targetClass' => BDeffect::className(), 'targetAttribute' => ['deffect_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'department_id' => Yii::t('app', 'Отдел'),
            'dep_id' => Yii::t('app', 'ID отдела'),
            'deffect_id' => Yii::t('app', 'Дефект'),
            'detail_id' => Yii::t('app', 'Деталь'),
            'is_save' => Yii::t('app', 'Доработка'),
            'user_id' => Yii::t('app', 'Пользователь'),
            'line_id' => Yii::t('app', 'Линия'),
            'number_poddon' => Yii::t('app', 'Номер поддона'),
            'current_operation' => Yii::t('app', 'Операция'),
            'description_ru' => Yii::t('app', 'Описание'),
            'description_en' => Yii::t('app', 'Описание EN'),
            'model_id' => Yii::t('app', 'Модель'),
            'description_uz' => Yii::t('app', 'Описание UZ'),
            'count_deffect' => Yii::t('app', 'Кол-во дефектов'),
            'unit_id' => Yii::t('app', 'Ед. Измерения'),
            'status' => Yii::t('app', 'Статус'),
            'created_at' => Yii::t('app', 'Дата создания'),
            'updated_at' => Yii::t('app', 'Дата ищменения'),
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

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if($insert){
                $this->dates = date('Y-m-d H:i');
            }
            return true;
        } else {
            return false;
        }
    }

    public function removeObject(){
        return $this->delete();
    }

    public function getLine()
    {
        return $this->hasOne(BLine::className(), ['id' => 'line_id']);
    }


    public function getDepartment()
    {
        return $this->hasOne(BDepartment::className(), ['id' => 'department_id']);
    }


    public function getModel()
    {
        return $this->hasOne(ProductModel::className(), ['id' => 'model_id']);
    }


    public function getDetail()
    {
        return $this->hasOne(Detail::className(), ['id' => 'detail_id']);
    }

    public function getDeffect()
    {
        return $this->hasOne(BDeffect::className(), ['id' => 'deffect_id']);
    }

    public function getCurrentDepartment()
    {
        return $this->hasOne(DepartmentStamping::className(), ['id' => 'dep_id']);
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getUnit()
    {
        return $this->hasOne(Category::className(), ['id' => 'unit_id']);
    }
}
