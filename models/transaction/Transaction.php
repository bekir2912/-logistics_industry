<?php

namespace app\models\transaction;

use Yii;

/**
 * This is the model class for table "transaction".
 *
 * @property int $id
 * @property string $account
 * @property int $click_trans_id
 * @property int $amount
 * @property string $status
 * @property string $error
 * @property int $date
 */
class Transaction extends \yii\db\ActiveRecord
{
    public $user_id, $service_id, $region_id;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'transaction';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['click_trans_id', 'amount', 'date'], 'integer'],
            [['account', 'status', 'error'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'account' => 'Account',
            'click_trans_id' => 'Click Trans ID',
            'amount' => 'Amount',
            'status' => 'Status',
            'error' => 'Error',
            'date' => 'Date',
        ];
    }

    public function getOrder() {
        return $this->hasOne(Order::className(), ['id'=>'account']);
    }
}
