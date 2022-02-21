<?php

namespace app\models\notice\waybill;

use Yii;
use app\models\product\Product;
use app\models\Category;

/**
 * This is the model class for table "notice_waybill_product".
 *
 * @property int $id
 * @property int|null $notice_waybill_id
 * @property int|null $product_id
 * @property float|null $amount
 * @property string $date
 * @property int $sort
 * @property int $status
 *
 * @property NoticeWaybill $noticeWaybill
 * @property Product $product
 */
class NoticeWaybillProduct extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'notice_waybill_product';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['notice_waybill_id', 'product_id', 'unit_id', 'sort', 'status'], 'integer'],
            [['amount'], 'number'],
            [['description'], 'string'],
            [['date'], 'safe'],
            [['notice_waybill_id'], 'exist', 'skipOnError' => true, 'targetClass' => NoticeWaybill::className(), 'targetAttribute' => ['notice_waybill_id' => 'id']],
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
            'notice_waybill_id' => 'Notice Waybill ID',
            'product_id' => 'Product ID',
            'amount' => 'Amount',
            'date' => 'Date',
            'sort' => 'Sort',
            'status' => 'Status',
        ];
    }

    /**
     * Gets query for [[NoticeWaybill]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getNoticeWaybill()
    {
        return $this->hasOne(NoticeWaybill::className(), ['id' => 'notice_waybill_id']);
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
