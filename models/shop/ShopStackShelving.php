<?php

namespace app\models\shop;

use Yii;

/**
 * This is the model class for table "shop_stack_shelving".
 *
 * @property int $id
 * @property int|null $shop_stack_id
 * @property string|null $row
 * @property string|null $cell
 * @property int $status
 * @property int $sort
 * @property string $date
 * @property string|null $ip
 *
 * @property ShopStack $shopStack
 */
class ShopStackShelving extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'shop_stack_shelving';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['shop_stack_id', 'status', 'sort'], 'integer'],
            [['date'], 'safe'],
            [['shelf_number', 'ip'], 'string', 'max' => 255],
            [['shop_stack_id'], 'exist', 'skipOnError' => true, 'targetClass' => ShopStack::className(), 'targetAttribute' => ['shop_stack_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'shop_stack_id' => 'Shop Stack ID',
            'shelf_number' => 'Shelf Number',
            'status' => 'Status',
            'sort' => 'Sort',
            'date' => 'Date',
            'ip' => 'Ip',
        ];
    }

    /**
     * Gets query for [[ShopStack]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getShopStack()
    {
        return $this->hasOne(ShopStack::className(), ['id' => 'shop_stack_id']);
    }

    public function getProducts()
    {
        return $this->hasMany(ShopProduct::className(), ['shop_stack_shelving_id' => 'id'])->andOnCondition(['shop_stack_id'=>$this->shop_stack_id]);
    }
}
