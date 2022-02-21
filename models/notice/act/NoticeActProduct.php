<?php

namespace app\models\notice\act;

use Yii;
use app\models\product\Product;
use app\models\Category;

/**
 * This is the model class for table "notice_act_product".
 *
 * @property int $id
 * @property int|null $notice_act_id
 * @property int|null $product_id
 * @property float|null $amount
 * @property string $date
 * @property int $sort
 * @property int $status
 *
 * @property NoticeAct $noticeAct
 * @property Product $product
 */
class NoticeActProduct extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'notice_act_product';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['notice_act_id', 'product_id', 'unit_id', 'sort', 'status', 'stock_id', 'stack_id', 'shelf_if'], 'integer'],
            [['description'], 'string'],
            [['amount', 'weight', 'percentage', 'amount_passed', 'amount_defect'], 'number'],
            [['date'], 'safe'],
            [['notice_act_id'], 'exist', 'skipOnError' => true, 'targetClass' => NoticeAct::className(), 'targetAttribute' => ['notice_act_id' => 'id']],
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
            'notice_act_id' => 'Notice Act ID',
            'product_id' => 'Product ID',
            'amount' => 'Amount',
            'date' => 'Date',
            'sort' => 'Sort',
            'status' => 'Status',
        ];
    }

    /**
     * Gets query for [[NoticeAct]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getNoticeAct()
    {
        return $this->hasOne(NoticeAct::className(), ['id' => 'notice_act_id']);
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
