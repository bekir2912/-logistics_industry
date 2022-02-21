<?php

namespace app\models\moderator;

use Yii;

/**
 * This is the model class for table "moderator_url".
 *
 * @property int $id
 * @property string $name
 * @property string $url
 *
 * @property ModeratorAccess[] $moderatorAccesses
 */
class ModeratorUrl extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'b_moderator_url';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'url', 'type'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'url' => 'Url',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModeratorAccesses()
    {
        return $this->hasMany(ModeratorAccess::className(), ['moderator_id' => 'id']);
    }

    public function getModeratorAccessUser()
    {
        return $this->hasOne(ModeratorAccess::className(), ['moderator_id' => 'id'])->andOnCondition(['user_id'=>Yii::$app->request->get('id')]);
    }
}
