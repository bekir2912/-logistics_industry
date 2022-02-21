<?php

namespace app\models\industry;

use app\models\Category;
use app\models\industry\handbook\BDepartment;
use app\models\industry\handbook\ProductModel;
use app\models\Notification;
use app\models\user\User;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "b_department_gp".
 *
 * @property int $id
 * @property int $department_id
 * @property int|null $model_id
 * @property int $user_id
 * @property int $unit_id
 * @property string|null $current_operation
 * @property string|null $number_poddon
 * @property int $value_range
 * @property int|null $amount
 * @property string|null $article
 * @property string|null $name_ru
 * @property string|null $name_en
 * @property string|null $name_uz
 * @property string|null $description_ru
 * @property string|null $description_uz
 * @property string|null $description_en
 * @property int $status
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property BDepartments $department
 * @property User $user
 * @property Category $unit
 * @property BModel $model
 */
class DepartmentGp extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'b_department_gp';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['department_id', 'user_id', 'unit_id',  'status', 'current_operation','number_poddon'], 'required'],
            [['department_id', 'model_id', 'user_id', 'unit_id', 'is_ckeck', 'value_range', 'amount', 'status', 'created_at', 'updated_at'], 'integer'],
            [['description_ru', 'description_uz', 'description_en'], 'string'],
            [['dates'], 'safe'],
            ['current_operation', 'unique'],
            [['current_operation', 'number_poddon', 'article', 'name_ru', 'name_en', 'name_uz'], 'string', 'max' => 255],
            [['department_id'], 'exist', 'skipOnError' => true, 'targetClass' => BDepartment::className(), 'targetAttribute' => ['department_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['unit_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['unit_id' => 'id']],
            [['model_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProductModel::className(), 'targetAttribute' => ['model_id' => 'id']],
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
                $this->articul = $this->model->articul;
            }
            return true;
        } else {
            return false;
        }
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



    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'model_id' => 'Модель',
            'user_id' => 'Пользователь',
            'department_id' => 'Отдел',
            'unit_id' => 'Ед.измерения',
            'current_operation' => 'Текущая операция',
            'number_poddon' => 'Номер поддона',
            'value_range' => 'Статус проверки',
            'amount' => 'Кол-во',
            'article' => 'Актикул',
            'name_ru' => 'Наименование Ru',
            'name_en' => 'Наименование En',
            'name_uz' => 'Наименование Uz',
            'description_ru' => 'Описание Ru',
            'description_uz' => 'Описание Uz',
            'description_en' => 'Описание En',
            'status' => 'Статус',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
    }


    public function getDepartment()
    {
        return $this->hasOne(BDepartment::className(), ['id' => 'department_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public  function nameModel($model_id){
        $model_name = ProductModel::findOne($model_id);
        return $model_name->name_ru;
    }

    /**
     * Gets query for [[Unit]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUnit()
    {
        return $this->hasOne(Category::className(), ['id' => 'unit_id']);
    }

    /**
     * Gets query for [[Model]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getModel()
    {
        return $this->hasOne(ProductModel::className(), ['id' => 'model_id']);
    }

    public function getBuffer()
    {
        return $this->hasMany(BufferZone::className(), ['dep_id' => 'id']);
    }
    public function getDeffect()
    {
        return $this->hasMany(AllDeffect::className(), ['dep_id' => 'id']);
    }
}
