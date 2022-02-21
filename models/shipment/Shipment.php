<?php

namespace app\models\shipment;

use app\models\industry\handbook\BDepartment;
use Yii;
use moonland\phpexcel\Excel;

use app\models\Category;

/**
 * This is the model class for table "shipment".
 *
 * @property int $id
 * @property string|null $date_shipment
 * @property string|null $fio
 * @property int $status
 * @property int $sort
 * @property string $date
 *
 * @property ShipmentProduct[] $shipmentProducts
 */
class Shipment extends \yii\db\ActiveRecord
{
    public $products = [];
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'shipment';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status', 'sort', 'department_id'], 'integer'],
            [['date', 'products'], 'safe'],
            [['date_shipment', 'fio'], 'string', 'max' => 255],
            [['comment'], 'string']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'date_shipment' => 'Date Shipment',
            'department_id' => 'Departments ID',
            'fio' => 'Fio',
            'status' => 'Status',
            'sort' => 'Sort',
            'date' => 'Date',
        ];
    }

    public function saveObject() {
        $this->status = 0;
        if ($this->save()) {
            $products = ShipmentProduct::deleteAll(['shipment_id'=>$this->id]);

            $keys = array('shipment_id', 'product_id', 'amount', 'article');
            $vals = array();

            foreach ($this->products['product'] as $k => $product) {
                $vals[] = [
                    'shipment_id' => $this->id,
                    'product_id' => $product,
                    'amount' => $this->products['amount'][$k],
                    'article' => $this->products['article'][$k],
                ];
            }
            Yii::$app->db->createCommand()->batchInsert('shipment_product', $keys, $vals)->execute();

            return true;
        }

        return false;
    }

    /**
     * Gets query for [[ShipmentProducts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getShipmentProducts()
    {
        return $this->hasMany(ShipmentProduct::className(), ['shipment_id' => 'id']);
    }

    public function getShipmentType() {
        return $this->hasOne(Category::className(), ['id' => 'type_id']);
    }

    public function getDepartment()
    {
        return $this->hasOne(BDepartment::className(), ['id' => 'department_id']);
    }
}
