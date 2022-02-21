<?php

namespace app\models\stack;

use yii\base\Model;
use yii\data\ActiveDataProvider;

use app\models\stack\Stack;

/**
 * StackSearch represents the model behind the search form of `app\models\Stack`.
 */
class StackSearch extends Stack
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'stock_id', 'status', 'sort'], 'integer'],
            [['stack_number', 'shelfs_count', 'date'], 'safe'],
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
        $query = Stack::find();

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
            'stock_id' => $this->stock_id,
            'status' => $this->status,
            'sort' => $this->sort,
            'date' => $this->date,
        ]);

        $query->andFilterWhere(['like', 'stack_number', $this->stack_number])
            ->andFilterWhere(['like', 'shelfs_count', $this->shelfs_count]);

        return $dataProvider;
    }
}
