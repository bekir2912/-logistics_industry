<?php

namespace app\models\stack;

use Yii;
use app\models\stock\Stock;
use app\models\product\Product;

/**
 * This is the model class for table "stack".
 *
 * @property int $id
 * @property int|null $stock_id
 * @property string|null $stack_number
 * @property string|null $shelfs_count
 * @property int $status
 * @property int $sort
 * @property string $date
 *
 * @property Stock $stock
 * @property StackShelving[] $stackShelvings
 */
class Stack extends \yii\db\ActiveRecord
{
    public $shelfs = [];
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'stack';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['stack_number', 'shelfs_count'], 'required', 'message' => 'Заполните поле'],
            [['stock_id', 'status', 'sort'], 'integer'],
            [['date', 'shelfs'], 'safe'],
            [['stack_number', 'shelfs_count'], 'string', 'max' => 255],
            [['stock_id'], 'exist', 'skipOnError' => true, 'targetClass' => Stock::className(), 'targetAttribute' => ['stock_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'stock_id' => 'Stock ID',
            'stack_number' => 'Stack Number',
            'shelfs_count' => 'Shelfs Count',
            'status' => 'Status',
            'sort' => 'Sort',
            'date' => 'Date',
        ];
    }

    public function saveObject() {
        if ($this->save()) {
            $shelfs = StackShelving::deleteAll(['stack_id'=>$this->id]);
            
            $keys = array('stack_id', 'shelf_number');
            $vals = array();
            foreach ($this->shelfs as $k => $shelf) {
                $vals[] = [
                    'stack_id' => $this->id,
                    'shelf_number' => $shelf
                ];
            }
            Yii::$app->db->createCommand()->batchInsert('stack_shelving', $keys, $vals)->execute();

            return true;
        }

        return false;
    }

    /**
     * Gets query for [[Stock]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStock()
    {
        return $this->hasOne(Stock::className(), ['id' => 'stock_id']);
    }

    /**
     * Gets query for [[StackShelvings]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStackShelvings()
    {
        return $this->hasMany(StackShelving::className(), ['stack_id' => 'id']);
    }

    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['stack_id' => 'id']);
    }
}
