<?php

namespace app\models\industry;

use app\models\industry\handbook\BDepartment;
use app\models\industry\handbook\ProductModel;
use app\models\user\User;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;


class DepartmentPaiting extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'b_department_paiting';
    }

    public function rules()
    {
        return [
            [['model_id', 'user_id', 'number_poddon', 'amount', 'part_model'], 'required'],
            [[ 'model_id', 'department_id',  'user_id', 'amount', 'is_ckeck', 'part_model', 'is_defect', 'status', 'created_at', 'updated_at'], 'integer'],
            [['current_operation', 'number_poddon'], 'string', 'max' => 255],
            ['current_operation', 'unique'],
            [['model_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProductModel::className(), 'targetAttribute' => ['model_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['department_id'], 'exist', 'skipOnError' => true, 'targetClass' => BDepartment::className(), 'targetAttribute' => ['department_id' => 'id']],
        ];
    }



    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'model_id' => Yii::t('app', 'Модель'),
            'department_id' => Yii::t('app', 'Отдел'),
            'user_id' => Yii::t('app', 'Пользователь'),
            'current_operation' => Yii::t('app', 'Операция'),
            'number_poddon' => Yii::t('app', 'Номер поддона'),
            'amount' => Yii::t('app', 'Кол-во'),
            'part_model' => Yii::t('app', 'Часть'),
            'is_defect' => Yii::t('app', 'Наличие деф. прод.'),
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



    public function getModel()
    {
        return $this->hasOne(ProductModel::className(), ['id' => 'model_id']);
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
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
