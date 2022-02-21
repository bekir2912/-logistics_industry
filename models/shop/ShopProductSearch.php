<?php

namespace app\models\shop;

use yii\base\Model;
use yii\data\ActiveDataProvider;

use app\models\shop\ShopProduct;

/**
 * ShopProductSearch represents the model behind the search form of `app\models\ShopProduct`.
 */
class ShopProductSearch extends ShopProduct
{
    public $name_ru, $article, $shop_stack_id, $shop_stack_shelving_id, $total_amount, $datepicker;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'shop_id', 'product_id', 'shop_stack_id', 'shop_stack_shelving_id', 'status', 'sort'], 'integer'],
            [['amount'], 'number'],
            [['datepicker', 'date', 'name_ru', 'article', 'shop_stack_id', 'shop_stack_shelving_id', 'total_amount'], 'safe'],
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
        $query = ShopProduct::find();

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
            'shop_id' => $this->shop_id,
            'product_id' => $this->product_id,
            'shop_stack_id' => $this->shop_stack_id,
            'shop_stack_shelving_id' => $this->shop_stack_shelving_id,
            'amount' => $this->amount,
            'status' => $this->status,
            'sort' => $this->sort,
            'date' => $this->date,
        ]);

        if ($this->datepicker) {
            $daterange = explode(' - ', $this->datepicker);
            $start = $daterange[0];
            $end = $daterange[1];

            $query->andFilterWhere(['between', 'shop_product.date', $start, $end]);
        }

        return $dataProvider;
    }
}
