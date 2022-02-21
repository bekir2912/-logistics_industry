<?php

namespace app\models\industry;

use app\models\industry\handbook\BDepartment;
use app\models\product\Product;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "b_plans_dates".
 *
 * @property int $id
 * @property int|null $plan_id
 * @property string|null $date
 * @property int|null $value
 * @property int|null $created_at
 * @property int|null $update_at
 *
 * @property BPlan $plan
 */
class BPlansDates extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'b_plans_dates';
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

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['date','department_id'], 'unique'],
            [['id', 'department_id', 'plan_id', 'value', 'created_at', 'update_at'], 'integer'],
            [['date'], 'safe'],
            [['plan_id'], 'exist', 'skipOnError' => true, 'targetClass' => BPlan::className(), 'targetAttribute' => ['plan_id' => 'id']],
        ];
    }
    public function removeObject(){
        return $this->delete();
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'plan_id' => 'ID плана',
            'date' => 'Дата',
            'value' => 'Значегие',
            'status' => 'Статус',
            'created_at' => 'Дата создания',
            'update_at' => 'Дата обновения',
        ];
    }

    /**
     * Gets query for [[Plan]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPlan()
    {
        return $this->hasOne(BPlan::className(), ['id' => 'plan_id']);
    }

    public function getDepartment()
    {
        return $this->hasOne(BDepartment::className(), ['id' => 'department_id']);
    }
}
