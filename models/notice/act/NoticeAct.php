<?php

namespace app\models\notice\act;

use Yii;
use app\models\user\User;
use app\models\product\Product;
use app\models\Category;
use app\models\notice\control\NoticeControl;
use app\models\Notification;

/**
 * This is the model class for table "notice_act".
 *
 * @property int $id
 * @property int|null $user_id
 * @property int|null $notice_control_id
 * @property string|null $date_notice
 * @property string|null $description
 * @property string $date
 * @property int $sort
 * @property int $status
 *
 * @property NoticeControl $noticeControl
 * @property User $user
 * @property NoticeActProduct[] $noticeActProducts
 */
class NoticeAct extends \yii\db\ActiveRecord
{
    public $products = [];
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'notice_act';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'notice_control_id', 'sort', 'status'], 'integer'],
            [['description'], 'string'],
            [['date', 'products'], 'safe'],
            [['date_notice'], 'string', 'max' => 255],
            [['notice_control_id'], 'exist', 'skipOnError' => true, 'targetClass' => NoticeControl::className(), 'targetAttribute' => ['notice_control_id' => 'id']],
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
            'notice_control_id' => 'Notice Control ID',
            'date_notice' => 'Date Notice',
            'description' => 'Description',
            'date' => 'Date',
            'sort' => 'Sort',
            'status' => 'Status',
        ];
    }

    public function saveObject() {
        $post = Yii::$app->request->post();

        $this->user_id = Yii::$app->user->identity->id;
        $this->status = 1;

        if ($this->save()) {
            $notification = new Notification;
            $notification->user_id = Yii::$app->user->identity->id;
            $notification->object_id = $this->id;
            $notification->status = 0;
            $notification->status_admin = 0;
            $notification->message = 'Новая заявка от раздела: акт приемки';
            $notification->type = 'notice_finish';
            $notification->save();

            NoticeActProduct::deleteAll(['notice_act_id'=>$this->id]);
            
            $keys = array('notice_act_id', 'product_id', 'unit_id', 'amount', 'description', 'status', 'stock_id', 'stack_id', 'shelf_id', 'weight');
            $vals = array();

            foreach ($this->products['product'] as $k => $product) {
                if ($this->products['amount'][$k]) {
                    $vals[] = [
                        'notice_act_id' => $this->id,
                        'product_id' => $this->products['product'][$k],
                        'unit_id' => $this->products['unit'][$k],
                        'amount' => $this->products['amount'][$k],
                        'description' => $this->products['description'][$k],
                        'status' => 1,
                        'stock_id' => $this->products['stock_id'][$k],
                        'stack_id' => $this->products['stack_id'][$k],
                        'shelf_id' => $this->products['shelf_id'][$k],
                        'weight' => $this->products['weight'][$k]
                    ];

                    $product = Product::findOne($this->products['product'][$k]);

                    if (!$product) {
                        $product = new Product;
                    }

                    $product->amount = $product->amount+$this->products['amount'][$k];
                    $product->stock_id = $this->products['stock_id'][$k];
                    $product->stack_id = $this->products['stack_id'][$k];
                    $product->shelf_id = $this->products['shelf_id'][$k];
                    $product->save(false);
                }
            }
            
            Yii::$app->db->createCommand()->batchInsert('notice_act_product', $keys, $vals)->execute();

            return true;
        }

        return false;
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
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * Gets query for [[NoticeActProducts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getNoticeActProducts()
    {
        return $this->hasMany(NoticeActProduct::className(), ['notice_act_id' => 'id']);
    }

    public function getProvider()
    {
        return $this->hasOne(Category::className(), ['id' => 'provider_id']);
    }
}
