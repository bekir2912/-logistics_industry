<?php

namespace app\models\stock;

use Yii;

/**
 * This is the model class for table "stock_shelving".
 *
 * @property int $id
 * @property int|null $stock_id
 * @property string|null $row
 * @property string|null $cell
 * @property int $status
 * @property int $sort
 * @property string $date
 * @property string|null $ip
 *
 * @property Stock $stock
 */
class StockShelving extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'stock_shelving';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['stock_id', 'status', 'sort'], 'integer'],
            [['date'], 'safe'],
            [['row', 'cell', 'ip'], 'string', 'max' => 255],
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
            'row' => 'Row',
            'cell' => 'Cell',
            'status' => 'Status',
            'sort' => 'Sort',
            'date' => 'Date',
            'ip' => 'Ip',
        ];
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
}
