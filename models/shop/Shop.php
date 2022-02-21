<?php

namespace app\models\shop;

use Yii;
use app\models\user\User;

/**
 * This is the model class for table "shop".
 *
 * @property int $id
 * @property int|null $user_id
 * @property string|null $name_ru
 * @property string|null $description_ru
 * @property int $status
 * @property int $sort
 * @property string $date
 *
 * @property User $user
 * @property ShopStack[] $shopStacks
 */
class Shop extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'shop';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name_ru', 'description_ru'], 'required', 'message' => 'Заполните поле'],
            [['user_id', 'status', 'sort'], 'integer'],
            [['date'], 'safe'],
            [['name_ru', 'description_ru'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'name_ru' => 'Name Ru',
            'description_ru' => 'Description Ru',
            'status' => 'Status',
            'sort' => 'Sort',
            'date' => 'Date',
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * Gets query for [[ShopStacks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getShopStacks()
    {
        return $this->hasMany(ShopStack::className(), ['shop_id' => 'id']);
    }
}
