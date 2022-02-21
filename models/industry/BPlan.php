<?php

namespace app\models\industry;

use app\models\industry\handbook\BDepartment;
use app\models\shipment\ShipmentProduct;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "b_plans".
 *
 * @property int $id
 * @property int $value
 * @property string $date_plan
 * @property string|null $name
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class BPlan extends \yii\db\ActiveRecord
{

    public $products = [];


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'b_plans';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[ 'department_id'], 'required'],
            [[ 'value', 'created_at', 'status', 'updated_at'], 'integer'],
            [['date_plan', 'date_start', 'products', 'date_end'], 'safe'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'value' => 'Значение',
            'date_plan' => 'Дата',
            'date_start' => 'Дата начала',
            'date_end' => 'Дата конца',
            'department_id' => 'Отдел',
            'name' => 'Наименование',
            'status' =>  'Статус',
            'created_at' => 'Дата добавления',
            'updated_at' =>  'Дата изменения',
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

    public function saveObject() {
        if ($this->save()) {


            $dates = [];
            foreach ($this->products['date'] as $k => $date) {
                $dates[$k] =  $date.  ' ' . '08:00:00';
            }
            $plans = BPlansDates::find()->where(['department_id' => $this->department_id])->andWhere(['date' =>  $dates])->all();
            if($plans){
                $this->removeObject();
                Yii::$app->session->setFlash('dublicate_saved', 'Дата не должна повторяться');
                return $this->refresh();
            }
            else{
                $keys = array('plan_id', 'department_id','status', 'value', 'date');
                $vals = array();

                foreach ($this->products['value'] as $k => $product) {
                    $vals[] = [
                        'plan_id' => $this->id,
                        'department_id' => $this->department_id,
                        'status' => $this->status,
                        'value' => $this->products['value'][$k],
                        'date' =>  $this->products['date'][$k] .  ' ' . '08:00:00',
                    ];
                }
                Yii::$app->db->createCommand()->batchInsert('b_plans_dates', $keys, $vals)->execute();

                return true;
            }

        }

        return false;
    }



    public function removeObject(){
        foreach ($this->plansDates as $k => $plans)
            $plans->removeObject();
        return $this->delete();
    }


    public function getPlansDates()
    {
        return $this->hasMany(BPlansDates::className(), ['plan_id' => 'id']);
    }

    public function getDepartment()
    {
        return $this->hasOne(BDepartment::className(), ['id' => 'department_id']);
    }

}
