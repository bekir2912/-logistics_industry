<?php

namespace app\models\shipment;

use Yii;
use app\models\product\Product;
use app\models\Category;

/**
 * This is the model class for table "shipment_product".
 *
 * @property int $id
 * @property int|null $type_id
 * @property int|null $shipment_id
 * @property int|null $product_id
 * @property int|null $amount
 * @property int $status
 * @property int $sort
 * @property string $date
 *
 * @property Shipment $shipment
 */
class ShipmentProduct extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'shipment_product';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['shipment_id', 'product_id', 'amount', 'status', 'sort'], 'integer'],
            [['date'], 'safe'],
            [['article', 'qr', 'tnvd_code'], 'string'],
            [['shipment_id'], 'exist', 'skipOnError' => true, 'targetClass' => Shipment::className(), 'targetAttribute' => ['shipment_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type_id' => 'Type ID',
            'shipment_id' => 'Shipment ID',
            'product_id' => 'Product ID',
            'amount' => 'Amount',
            'status' => 'Status',
            'sort' => 'Sort',
            'date' => 'Date',
        ];
    }

    /**
     * Gets query for [[Shipment]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getShipment()
    {
        return $this->hasOne(Shipment::className(), ['id' => 'shipment_id']);
    }

    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }
}
