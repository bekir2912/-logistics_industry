<?php

namespace app\models\notice_shop_stock;

use Yii;
use app\models\product\Product;
use app\models\Category;

/**
 * This is the model class for table "notice_shop_stock_product".
 *
 * @property int $id
 * @property int|null $notice_shop_stock_id
 * @property int|null $product_id
 * @property string|null $article
 * @property float|null $amount
 * @property int $status
 * @property int $sort
 * @property string $date
 *
 * @property Product $product
 * @property NoticeShopStock $noticeShopStock
 */
class NoticeShopStockProduct extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'notice_shop_stock_product';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['notice_shop_stock_id', 'product_id', 'unit_id', 'status', 'sort'], 'integer'],
            [['amount'], 'number'],
            [['date', 'description'], 'safe'],
            [['article'], 'string', 'max' => 255],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['product_id' => 'id']],
            [['notice_shop_stock_id'], 'exist', 'skipOnError' => true, 'targetClass' => NoticeShopStock::className(), 'targetAttribute' => ['notice_shop_stock_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'notice_shop_stock_id' => 'Notice Shop Stock ID',
            'product_id' => 'Product ID',
            'article' => 'Article',
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
     * Gets query for [[NoticeShopStock]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getNoticeShopStock()
    {
        return $this->hasOne(NoticeShopStock::className(), ['id' => 'notice_shop_stock_id']);
    }

    public function getUnit()
    {
        return $this->hasOne(Category::className(), ['id' => 'unit_id']);
    }
}
