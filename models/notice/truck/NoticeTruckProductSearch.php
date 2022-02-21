<?php

namespace app\models\notice\truck;

use yii\base\Model;
use yii\data\ActiveDataProvider;

use app\models\notice\truck\NoticeTruckProduct;

/**
 * NoticeTruckProductSearch represents the model behind the search form of `app\models\NoticeTruckProduct`.
 */
class NoticeTruckProductSearch extends NoticeTruckProduct
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'notice_truck_id', 'unit_id', 'product_id', 'sort', 'status'], 'integer'],
            [['amount', 'amount_fact'], 'number'],
            [['date'], 'safe'],
            [['description', 'description_fact'], 'string'],
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
        $query = NoticeTruckProduct::find();

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
            'notice_truck_id' => $this->notice_truck_id,
            'product_id' => $this->product_id,
            'amount' => $this->amount,
            'amount_fact' => $this->amount_fact,
            'date' => $this->date,
            'sort' => $this->sort,
            'status' => $this->status,
        ]);

        return $dataProvider;
    }
}
