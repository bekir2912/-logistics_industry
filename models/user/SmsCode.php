<?php

namespace app\models\user;

use Yii;

/**
 * This is the model class for table "sms_code".
 *
 * @property int $id
 * @property int|null $user_id
 * @property string|null $phone
 * @property string|null $code
 * @property int|null $sms_expire
 * @property string $date
 *
 * @property User $user
 */
class SmsCode extends \yii\db\ActiveRecord
{
    protected $user;

    const ACCEPT_CODE = 'accept_code';

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
            [['code'], 'required', 'message'=>'Заполните поле'],
            ['code', 'checkCode', 'on'=>self::ACCEPT_CODE],
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

    public function checkCode($attribute, $params) {
        if (!$this->hasErrors()) {
            $this->user = User::findOne(['token'=>Yii::$app->request->get('key')]);

            if ($this->user) {
                $code = self::findOne(['code'=>$this->code, 'phone'=>$this->user->phone]);
            }

            if ($code) {
                if ($code->sms_expire < time()) {
                    return $this->addError($attribute, 'Время смс кода истекло');
                }
            } else {
                return $this->addError($attribute, 'Не верный код подтверждения');
            }
        }

        return false;
    }

    public function saveObject() {
        if ($this->user) {
            $this->user->status = 1;
            
            if ($this->user->save(false)) {
                SmsCode::deleteAll(['user_id'=>$this->user->id]);
                return $this->user->login();
            }
        }

        return false;
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
}
