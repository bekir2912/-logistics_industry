<?php

namespace app\models\notice\truck;

use Yii;
use app\models\user\User;
use app\models\product\Product;
use app\models\notice\waybill\NoticeWaybill;
use app\models\notice\control\NoticeControl;
use app\models\notice\truck\NoticeTruckProduct;
use app\models\Category;
use app\models\Notification;

/**
 * This is the model class for table "notice_truck".
 *
 * @property int $id
 * @property int|null $user_id
 * @property int|null $notice_waybill_id
 * @property string|null $notice_number
 * @property string|null $description
 * @property string $date
 * @property int $sort
 * @property int $status
 *
 * @property NoticeControl[] $noticeControls
 * @property NoticeWaybill $noticeWaybill
 * @property User $user
 * @property NoticeTruckProduct[] $noticeTruckProducts
 */
class NoticeTruck extends \yii\db\ActiveRecord
{
    public $truck_number, $truck_number_reg, $invoice_number, $provider_id, $article, $description, $date_notice;
    public $fact;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'notice_truck';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['notice_number'], 'required', 'message' => 'Заполните поле'],
            [['user_id', 'notice_waybill_id', 'sort', 'status'], 'integer'],
            [['description'], 'string'],
            [['date', 'fact'], 'safe'],
            [['notice_number'], 'string', 'max' => 255],
            [['notice_waybill_id'], 'exist', 'skipOnError' => true, 'targetClass' => NoticeWaybill::className(), 'targetAttribute' => ['notice_waybill_id' => 'id']],
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
            'notice_waybill_id' => 'Notice Waybill ID',
            'notice_number' => 'Notice Number',
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
            if ($this->fact) {
                foreach ($this->fact['amount_fact'] as $id => $fact) {
                    $pr = NoticeTruckProduct::findOne($id);
                    $pr->amount_fact = $fact;
                    $pr->description_fact = $this->fact['description_fact'][$id];
                    $pr->save(false);
                }
            }
            $products = NoticeTruckProduct::find()->where(['notice_truck_id'=>$this->id])->all();

            $control = new NoticeControl;
            $control->date_notice = $this->noticeWaybill->date_notice;
            $control->status = 0;
            $control->notice_truck_id = $this->id;

            if ($control->save(false)) {
                $notification = new Notification;
                $notification->user_id = Yii::$app->user->identity->id;
                $notification->object_id = $control->id;
                $notification->status = 0;
                $notification->status_admin = 0;
                $notification->message = 'Новая заявка от раздела: прием грузовика';
                $notification->type = 'control';
                $notification->save();
                $keys = array('notice_control_id', 'product_id', 'unit_id', 'amount', 'description', 'status');
                $vals = array();
                foreach ($products as $k => $product) {
                    $vals[] = [
                        'notice_control_id' => $control->id,
                        'product_id' => $product->product_id,
                        'unit_id' => $product->unit_id,
                        'amount' => $product->amount_fact,
                        'description' => $product->description,
                        'status' => 1
                    ];
                }

                Yii::$app->db->createCommand()->batchInsert('notice_control_product', $keys, $vals)->execute();
            }

            return true;
        }

        return false;
    }

    /**
     * Gets query for [[NoticeControls]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getNoticeControls()
    {
        return $this->hasMany(NoticeControl::className(), ['notice_truck_id' => 'id']);
    }

    /**
     * Gets query for [[NoticeWaybill]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getNoticeWaybill()
    {
        return $this->hasOne(NoticeWaybill::className(), ['id' => 'notice_waybill_id']);
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
     * Gets query for [[NoticeTruckProducts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getNoticeTruckProducts()
    {
        return $this->hasMany(NoticeTruckProduct::className(), ['notice_truck_id' => 'id']);
    }

    public function getProvider()
    {
        return $this->hasOne(Category::className(), ['id' => 'provider_id']);
    }

    public function getNoticeControl() {
        return $this->hasOne(NoticeControl::className(), ['notice_truck_id' => 'id']);
    }
}
