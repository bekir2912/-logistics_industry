<?php

namespace app\models\transaction;

use yii\base\Model;
use yii\data\ActiveDataProvider;

use app\models\transaction\Transaction;

/**
 * TransactionSearch represents the model behind the search form of `app\models\Transaction`.
 */
class TransactionSearch extends Transaction
{
    public $user_id, $service_id, $region_id;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'click_trans_id', 'amount', 'date'], 'integer'],
            [['account', 'status', 'error', 'user_id', 'service_id', 'region_id'], 'safe'],
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
        $query = Transaction::find();

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
            'click_trans_id' => $this->click_trans_id,
            'amount' => $this->amount,
            'user_id' => $this->user_id,
            'service_id' => $this->service_id,
            'region_id' => $this->region_id,
            'date' => $this->date,
        ]);

        $query->andFilterWhere(['like', 'account', $this->account])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'error', $this->error]);

        return $dataProvider;
    }
}
