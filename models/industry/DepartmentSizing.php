<?php

namespace app\models\industry;

use app\models\industry\handbook\BDepartment;
use app\models\industry\handbook\BLine;
use app\models\industry\handbook\ProductModel;
use app\models\user\User;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class DepartmentSizing extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'b_department_sizing';
    }


    public function rules()
    {
        return [
            [['user_id', 'department_id','current_operation', 'amount'], 'required'],
            [['user_id', 'department_id', 'model_id', 'is_ckeck', 'amount', 'previous_department_id', 'time_expire', 'is_defect', 'status', 'created_at', 'updated_at'], 'integer'],
            [['current_operation', 'number_poddon', 'new_number_poddon'], 'string', 'max' => 255],
            ['current_operation', 'unique'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['model_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProductModel::className(), 'targetAttribute' => ['model_id' => 'id']],
            [['line_id'], 'exist', 'skipOnError' => true, 'targetClass' => BLine::className(), 'targetAttribute' => ['line_id' => 'id']],
            [['department_id'], 'exist', 'skipOnError' => true, 'targetClass' => BDepartment::className(), 'targetAttribute' => ['department_id' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'Пользователь'),
            'previous_department_id' => Yii::t('app', 'Преведущий отдел'),
            'department_id' => Yii::t('app', 'Отдел'),
            'model_id' => Yii::t('app', 'Модель'),
            'line_id' => Yii::t('app', 'Линия'),
            'current_operation' => Yii::t('app', 'Текущая операция'),
            'number_poddon' => Yii::t('app', 'Номер поддона'),
            'new_number_poddon' => Yii::t('app', 'Новый номер поддона'),
            'amount' => Yii::t('app', 'Кол-во'),
            'time_expire' => Yii::t('app', 'Остаток времи'),
            'is_defect' => Yii::t('app', 'Деффект'),
            'status' => Yii::t('app', 'Статус'),
            'created_at' => Yii::t('app', 'Дата создания'),
            'updated_at' => Yii::t('app', 'Дата обновления'),
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if($insert){
                $this->dates = date('Y-m-d H:i');
                $this->articul = $this->model->articul;

            }
            return true;
        } else {
            return false;
        }
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
        if($this->buffer){
            foreach ($this->buffer as $k => $buffer)
                $buffer->removeObject();
        }
        if($this->deffect){
            foreach ($this->deffect as $k => $defect)
            $defect->removeObject();
        }
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
    public function getLine()
    {
        return $this->hasOne(BLine::className(), ['id' => 'line_id']);
    }

    public function getPrevious()
    {
        return $this->hasOne(BDepartment::className(), ['id' => 'previous_department_id']);
    }


    public  function nameModel($model_id){
        $model_name = ProductModel::findOne($model_id);
        return $model_name->name_ru;
    }

    public function getDepartment()
    {
        return $this->hasOne(BDepartment::className(), ['id' => 'department_id']);
    }


    public function getDeffect()
    {
        return $this->hasMany(AllDeffect::className(), ['dep_id' => 'id']);
    }
    public function getBuffer()
    {
        return $this->hasMany(BufferZone::className(), ['dep_id' => 'id']);
    }


}
