<?php

namespace app\models\industry;

use app\models\industry\handbook\BDepartment;
use app\models\industry\handbook\BLine;
use app\models\industry\handbook\ProductModel;
use app\models\user\User;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class DepartmentElectro extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'b_department_electro';
    }

    public function rules()
    {
        return [
            [['model_id', 'department_id', 'line_id', 'user_id', 'amount'], 'required'],
            ['current_operation', 'unique'],
            [['model_id', 'department_id', 'line_id', 'user_id', 'is_ckeck', 'is_defect', 'amount', 'status', 'created_at', 'updated_at', 'previous_department_id'], 'integer'],
            [['current_operation', 'number_poddon', 'to_user'], 'string', 'max' => 255],
            [['model_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProductModel::className(), 'targetAttribute' => ['model_id' => 'id']],
            [['line_id'], 'exist', 'skipOnError' => true, 'targetClass' => BLine::className(), 'targetAttribute' => ['line_id' => 'id']],
            [['department_id'], 'exist', 'skipOnError' => true, 'targetClass' => BDepartment::className(), 'targetAttribute' => ['department_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'model_id' => Yii::t('app', 'Модель'),
            'department_id' => Yii::t('app', 'Отделы'),
            'to_user' => Yii::t('app', 'Пользователь принявший поддон'),
            'line_id' => Yii::t('app', 'Линия'),
            'user_id' => Yii::t('app', 'Пользователь'),
            'current_operation' => Yii::t('app', 'Текущая операция'),
            'previous_department_id' => Yii::t('app', 'Предыдущий отдел'),
            'number_poddon' => Yii::t('app', 'Номер поддона'),
            'is_defect' => Yii::t('app', 'Деффект'),
            'amount' => Yii::t('app', 'Кол-во'),
            'status' => Yii::t('app', 'Статус'),
            'created_at' => Yii::t('app', 'Дата создания'),
            'updated_at' => Yii::t('app', 'Дата изменения'),
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


    public  function nameModel($model_id){
        $model_name = ProductModel::findOne($model_id);
        return $model_name->name_ru;
    }

    public function getModel()
    {
        return $this->hasOne(ProductModel::className(), ['id' => 'model_id']);
    }
    public function getModelName($id)
    {
        $model  = ProductModel::findOne($id);

        return $model->name_ru;
    }

    public function getLine()
    {
        return $this->hasOne(BLine::className(), ['id' => 'line_id']);
    }

    public function getDepartment()
    {
        return $this->hasOne(BDepartment::className(), ['id' => 'department_id']);
    }

    public function getPrevious()
    {
        return $this->hasOne(BDepartment::className(), ['id' => 'previous_department_id']);
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
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
