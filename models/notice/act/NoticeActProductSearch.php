<?php

namespace app\models\notice\act;

use yii\base\Model;
use yii\data\ActiveDataProvider;

use app\models\notice\act\NoticeActProduct;

/**
 * NoticeActProductSearch represents the model behind the search form of `app\models\NoticeActProduct`.
 */
class NoticeActProductSearch extends NoticeActProduct
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'notice_act_id', 'unit_id', 'product_id', 'sort', 'status'], 'integer'],
            [['amount', 'percentage', 'amount_passed', 'amount_defect'], 'number'],
            [['date'], 'safe'],
            [['description'], 'string'],
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
        $query = NoticeActProduct::find();

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
            'notice_act_id' => $this->notice_act_id,
            'product_id' => $this->product_id,
            'amount' => $this->amount,
            'percentage' => $this->percentage,
            'amount_passed' => $this->amount_passed,
            'date' => $this->date,
            'sort' => $this->sort,
            'status' => $this->status,
        ]);

        return $dataProvider;
    }
}
