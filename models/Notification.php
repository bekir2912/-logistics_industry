<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "notification".
 *
 * @property int $id
 * @property int $user_id
 * @property int $object_id
 * @property string $type
 * @property int $status
 * @property string $message
 * @property string $date
 */
class Notification extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'notification';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'object_id', 'status', 'status_admin'], 'integer'],
            [['date'], 'safe'],
            [['type', 'message'], 'string', 'max' => 255],
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
            'object_id' => 'Object ID',
            'type' => 'Type',
            'status' => 'Status',
            'message' => 'Message',
            'date' => 'Date',
        ];
    }

    public function saveData($data, $type, $user_id) {
        $this->user_id = $user_id;
        $this->object_id = $data->id;
        $this->type = $type;
        $this->status = 0;
        $this->message = 'Новая заявка на получение полиса';

        return $this->save();
    }

    public function urlAdmin() {
        return Yii::$app->urlManager->createUrl(['/admin/notification/view', 'id'=>$this->id]);
    }

    // relations
    public function getOrder() {
        return $this->hasOne(Order::className(), ['id' => 'object_id']);
    }
}
