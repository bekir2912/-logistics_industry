<?php

namespace app\models\shop;

use yii\base\Model;
use yii\data\ActiveDataProvider;

use app\models\shop\ShopStackShelving;

/**
 * ShopStackShelvingSearch represents the model behind the search form of `app\models\ShopStackShelving`.
 */
class ShopStackShelvingSearch extends ShopStackShelving
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'shop_stack_id', 'status', 'sort'], 'integer'],
            [['shelf_number', 'date', 'ip'], 'safe'],
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
        $query = ShopStackShelving::find();

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
            'shop_stack_id' => $this->shop_stack_id,
            'status' => $this->status,
            'sort' => $this->sort,
            'date' => $this->date,
        ]);

        $query->andFilterWhere(['like', 'shelf_number', $this->shelf_number])
            ->andFilterWhere(['like', 'ip', $this->ip]);

        return $dataProvider;
    }
}
