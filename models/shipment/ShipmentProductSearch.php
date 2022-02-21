<?php

namespace app\models\shipment;

use yii\base\Model;
use yii\data\ActiveDataProvider;

use app\models\shipment\ShipmentProduct;

/**
 * ShipmentProductSearch represents the model behind the search form of `app\models\ShipmentProduct`.
 */
class ShipmentProductSearch extends ShipmentProduct
{
    public $datepicker, $amount, $price, $price_total;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'shipment_id', 'product_id', 'amount', 'status', 'sort'], 'integer'],
            [['date', 'article', 'tnvd_code', 'datepicker', 'amount', 'price', 'price_total'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = ShipmentProduct::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'type_id' => $this->type_id,
            'shipment_id' => $this->shipment_id,
            'product_id' => $this->product_id,
            'amount' => $this->amount,
            'status' => $this->status,
            'sort' => $this->sort,
            'date' => $this->date,
        ]);

        if ($this->datepicker) {
            $daterange = explode(' - ', $this->datepicker);
            $start = $daterange[0];
            $end = $daterange[1];

            $query->andFilterWhere(['between', 'coming_product.date', $start, $end]);
        }

        return $dataProvider;
    }
}
