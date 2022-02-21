<?php

namespace app\models\notice_shop_stock;

use yii\base\Model;
use yii\data\ActiveDataProvider;

use app\models\notice_shop_stock\NoticeShopStockProduct;

/**
 * NoticeShopStockProductSearch represents the model behind the search form of `app\models\NoticeShopStockProduct`.
 */
class NoticeShopStockProductSearch extends NoticeShopStockProduct
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'notice_shop_stock_id', 'product_id', 'unit_id', 'status', 'sort'], 'integer'],
            [['article', 'date', 'description'], 'safe'],
            [['amount'], 'number'],
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
        $query = NoticeShopStockProduct::find();

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
            'notice_shop_stock_id' => $this->notice_shop_stock_id,
            'product_id' => $this->product_id,
            'unit_id' => $this->unit_id,
            'amount' => $this->amount,
            'status' => $this->status,
            'sort' => $this->sort,
            'date' => $this->date,
        ]);

        $query->andFilterWhere(['like', 'article', $this->article])->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
