<?php

namespace app\models\notice\control;

use Yii;
use app\models\product\Product;
use app\models\Category;

/**
 * This is the model class for table "notice_control_product".
 *
 * @property int $id
 * @property int|null $notice_control_id
 * @property int|null $product_id
 * @property float|null $amount
 * @property string $date
 * @property int $sort
 * @property int $status
 *
 * @property NoticeControl $noticeControl
 * @property Product $product
 */
class NoticeControlProduct extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'notice_control_product';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['notice_control_id', 'product_id', 'unit_id', 'sort', 'status'], 'integer'],
            [['description'], 'string'],
            [['amount', 'percentage', 'amount_passed', 'amount_defect'], 'number'],
            [['date'], 'safe'],
            [['notice_control_id'], 'exist', 'skipOnError' => true, 'targetClass' => NoticeControl::className(), 'targetAttribute' => ['notice_control_id' => 'id']],
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
            'notice_control_id' => 'Notice Control ID',
            'product_id' => 'Product ID',
            'amount' => 'Amount',
            'date' => 'Date',
            'sort' => 'Sort',
            'status' => 'Status',
        ];
    }

    /**
     * Gets query for [[NoticeControl]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getNoticeControl()
    {
        return $this->hasOne(NoticeControl::className(), ['id' => 'notice_control_id']);
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
