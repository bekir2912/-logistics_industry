<?php

namespace app\models\industry;

use app\models\Category;
use app\models\industry\handbook\BDepartment;
use app\models\industry\handbook\ProductModel;
use app\models\user\User;

use DateTime;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class BufferZone extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'b_buffer_zone';
    }

    public function rules()
    {
        return [
            [['to_department_id', 'from_department_id', 'dep_id', 'user_id', 'model_id', 'amount', 'status', 'created_at', 'updated_at'], 'integer'],
            [['time_expire'], 'safe'],
            [['user_id', 'model_id', 'number_poddon'], 'required'],
            [['current_operation', 'number_poddon'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['model_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProductModel::className(), 'targetAttribute' => ['model_id' => 'id']],
            [['from_department_id'], 'exist', 'skipOnError' => true, 'targetClass' => BDepartment::className(), 'targetAttribute' => ['from_department_id' => 'id']],
            [['to_department_id'], 'exist', 'skipOnError' => true, 'targetClass' => BDepartment::className(), 'targetAttribute' => ['to_department_id' => 'id']],
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if($insert){
                $this->articul = $this->model->articul;
            }
            return true;
        } else {
            return false;
        }
    }


    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'from_department_id' => Yii::t('app', 'Откуда'),
            'to_department_id' => Yii::t('app', 'Куда'),
            'dep_id' => Yii::t('app', 'ID отдела'),
            'user_id' => Yii::t('app', 'Пользователь'),
            'model_id' => Yii::t('app', 'Модель'),
            'current_operation' => Yii::t('app', 'Текущий оператор'),
            'number_poddon' => Yii::t('app', 'Номер поддона'),
            'amount' => Yii::t('app', 'Кол-во'),
            'status' => Yii::t('app', 'Статус'),
            'time_expire' => Yii::t('app', 'Время истечения'),
            'created_at' => Yii::t('app', 'Дата создания'),
            'updated_at' => Yii::t('app', 'Даа обновления'),
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

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getModel()
    {
        return $this->hasOne(ProductModel::className(), ['id' => 'model_id']);
    }

    public function getFromDepartment()
    {
        return $this->hasOne(BDepartment::className(), ['id' => 'from_department_id']);
    }
    public function getDepartment()
    {
        return $this->hasOne(DepartmentStamping::className(), ['id' => 'dep_id']);
    }




    public function getToDepartment()
    {
        return $this->hasOne(BDepartment::className(), ['id' => 'to_department_id']);
    }

    public function getUnit()
    {
        return $this->hasOne(Category::className(), ['id' => 'unit_id']);
    }

}
