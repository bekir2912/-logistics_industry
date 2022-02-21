<?php

namespace app\models\notice\control;

use yii\base\Model;
use yii\data\ActiveDataProvider;

use app\models\notice\control\NoticeControlProduct;

/**
 * NoticeControlProductSearch represents the model behind the search form of `app\models\NoticeControlProduct`.
 */
class NoticeControlProductSearch extends NoticeControlProduct
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'notice_control_id', 'product_id', 'unit_id', 'sort', 'status'], 'integer'],
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
        $query = NoticeControlProduct::find();

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
            'notice_control_id' => $this->notice_control_id,
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
