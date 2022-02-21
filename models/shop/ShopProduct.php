<?php

namespace app\models\shop;

use Yii;
use app\models\shop\Shop;
use app\models\product\Product;

/**
 * This is the model class for table "shop_product".
 *
 * @property int $id
 * @property int|null $shop_id
 * @property int|null $product_id
 * @property float|null $amount
 * @property int $status
 * @property int $sort
 * @property string $date
 *
 * @property Product $product
 * @property Shop $shop
 */
class ShopProduct extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'shop_product';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['shop_id', 'product_id', 'shop_stack_id', 'shop_stack_shelving_id', 'status', 'sort'], 'integer'],
            [['amount'], 'number'],
            [['date'], 'safe'],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['product_id' => 'id']],
            [['shop_id'], 'exist', 'skipOnError' => true, 'targetClass' => Shop::className(), 'targetAttribute' => ['shop_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'shop_id' => 'Shop ID',
            'product_id' => 'Product ID',
            'amount' => 'Amount',
            'status' => 'Status',
            'sort' => 'Sort',
            'date' => 'Date',
        ];
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

    /**
     * Gets query for [[Shop]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getShop()
    {
        return $this->hasOne(Shop::className(), ['id' => 'shop_id']);
    }

    public function getShopStack()
    {
        return $this->hasOne(ShopStack::className(), ['id' => 'shop_stack_id']);
    }

    public function getShopStackShelving()
    {
        return $this->hasOne(ShopStackShelving::className(), ['id' => 'shop_stack_shelving_id']);
    }
}
