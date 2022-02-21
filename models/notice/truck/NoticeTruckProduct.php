<?php

namespace app\models\notice\truck;

use Yii;
use app\models\product\Product;
use app\models\Category;

/**
 * This is the model class for table "notice_truck_product".
 *
 * @property int $id
 * @property int|null $notice_truck_id
 * @property int|null $product_id
 * @property float|null $amount
 * @property string $date
 * @property int $sort
 * @property int $status
 *
 * @property NoticeTruck $noticeTruck
 * @property Product $product
 */
class NoticeTruckProduct extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'notice_truck_product';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['notice_truck_id', 'product_id', 'unit_id', 'sort', 'status'], 'integer'],
            [['description', 'description_fact'], 'string'],
            [['amount', 'amount_fact'], 'number'],
            [['date'], 'safe'],
            [['notice_truck_id'], 'exist', 'skipOnError' => true, 'targetClass' => NoticeTruck::className(), 'targetAttribute' => ['notice_truck_id' => 'id']],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['product_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'notice_truck_id' => 'Notice Truck ID',
            'product_id' => 'Product ID',
            'amount' => 'Amount',
            'date' => 'Date',
            'sort' => 'Sort',
            'status' => 'Status',
        ];
    }

    /**
     * Gets query for [[NoticeTruck]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getNoticeTruck()
    {
        return $this->hasOne(NoticeTruck::className(), ['id' => 'notice_truck_id']);
    }

    /**
     * Gets query for [[Product]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }

    public function getUnit()
    {
        return $this->hasOne(Category::className(), ['id' => 'unit_id']);
    }
}
