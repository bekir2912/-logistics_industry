<?php

namespace app\models;

use Yii;
use app\models\user\User;

/**
 * This is the model class for table "sms_code".
 *
 * @property int $id
 * @property int $user_id
 * @property string $phone
 * @property string $code
 * @property int $sms_expire
 * @property string $date
 *
 * @property User $user
 */
class SmsCode extends \yii\db\ActiveRecord
{
    // consts
    const NEW_PASSWORD = 'new_password';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sms_code';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['code'], 'required', 'message'=>'Заполните поле', 'on'=>self::NEW_PASSWORD],
            [['user_id', 'sms_expire'], 'integer'],
            [['date'], 'safe'],
            [['phone', 'code'], 'string', 'max' => 255],
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
            'phone' => 'Phone',
            'code' => 'Code',
            'sms_expire' => 'Sms Expire',
            'date' => 'Date',
        ];
    }

    public function fields() {
        return ['token'=>function(){return $this->user->token;}, 'code'];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
