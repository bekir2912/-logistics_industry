<?php

namespace app\models\moderator;

use app\models\industry\DepartmentStamping;
use Yii;
use app\models\user\User;

/**
 * This is the model class for table "moderator_access".
 *
 * @property int $id
 * @property int $moderator_id
 * @property int $user_id
 *
 * @property ModeratorUrl $moderator
 * @property User $user
 */
class ModeratorAccess extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'b_moderator_access';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['moderator_id', 'user_id'], 'integer'],
            [['moderator_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModeratorUrl::className(), 'targetAttribute' => ['moderator_id' => 'id']],
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
            'moderator_id' => 'Moderator ID',
            'user_id' => 'User ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModerator()
    {
        return $this->hasOne(ModeratorUrl::className(), ['id' => 'moderator_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
    public function getDepartment()
    {
        return $this->hasOne(DepartmentStamping::className(), ['id' => 'dep_id']);
    }
}
