<?php

namespace app\models\stack;

use Yii;
use app\models\product\Product;

/**
 * This is the model class for table "stack_shelving".
 *
 * @property int $id
 * @property int|null $stack_id
 * @property string|null $shelf_count
 * @property int $status
 * @property int $sort
 * @property string $date
 *
 * @property Stack $stack
 */
class StackShelving extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'stack_shelving';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['stack_id', 'status', 'sort'], 'integer'],
            [['date'], 'safe'],
            [['shelf_number'], 'string', 'max' => 255],
            [['stack_id'], 'exist', 'skipOnError' => true, 'targetClass' => Stack::className(), 'targetAttribute' => ['stack_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'stack_id' => 'Stack ID',
            'shelf_number' => 'Shelf Number',
            'status' => 'Status',
            'sort' => 'Sort',
            'date' => 'Date',
        ];
    }

    /**
     * Gets query for [[Stack]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStack()
    {
        return $this->hasOne(Stack::className(), ['id' => 'stack_id']);
    }

    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['shelf_id' => 'id'])->andOnCondition(['stack_id'=>$this->stack_id]);
    }
}
