<?php

namespace app\models\notice_shop_stock;

use Yii;
use app\models\user\User;
use app\models\Notification;

/**
 * This is the model class for table "notice_shop_stock".
 *
 * @property int $id
 * @property int|null $user_id
 * @property string|null $description
 * @property int $status
 * @property int $sort
 * @property string $date
 *
 * @property User $user
 * @property NoticeShopStockProduct[] $noticeShopStockProducts
 */
class NoticeShopStock extends \yii\db\ActiveRecord
{
    public $products = [];
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'notice_shop_stock';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['description'], 'required', 'message' => 'Заполните поле'],
            [['user_id', 'status', 'sort'], 'integer'],
            [['description'], 'string'],
            [['date', 'products'], 'safe'],
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
            'description' => 'Description',
            'status' => 'Status',
            'sort' => 'Sort',
            'date' => 'Date',
        ];
    }

    public function saveObject() {
        $post = Yii::$app->request->post();

        $this->user_id = Yii::$app->user->identity->id;
        $this->status = 0;

        if ($this->save()) {
            $notification = new Notification;
            $notification->user_id = Yii::$app->user->identity->id;
            $notification->object_id = $this->id;
            $notification->status = 0;
            $notification->status_admin = 0;
            $notification->message = 'Новая заявка на пополнение склада';
            $notification->type = 'shop_stock';
            $notification->save();

            $keys = array('notice_shop_stock_id', 'product_id', 'unit_id', 'amount', 'description', 'status');
            $vals = array();

            foreach ($this->products['product'] as $k => $product) {
                if ($this->products['amount'][$k]) {
                    $vals[] = [
                        'notice_shop_stock_id' => $this->id,
                        'product_id' => $this->products['product'][$k],
                        'unit_id' => $this->products['unit'][$k],
                        'amount' => $this->products['amount'][$k],
                        'description' => $this->products['description'][$k],
                        'status' => 1
                    ];
                }
            }
            
            Yii::$app->db->createCommand()->batchInsert('notice_shop_stock_product', $keys, $vals)->execute();

            return true;
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

    /**
     * Gets query for [[NoticeShopStockProducts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getNoticeShopStockProducts()
    {
        return $this->hasMany(NoticeShopStockProduct::className(), ['notice_shop_stock_id' => 'id']);
    }
}
