<?php

namespace app\models\product;

use yii\base\Model;
use yii\data\ActiveDataProvider;

use app\models\product\Product;

/**
 * ProductSearch represents the model behind the search form of `app\models\Product`.
 */
class ProductSearch extends Product
{
    public $datepicker;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'stock_id', 'amount', 'status', 'sort', 'manufacturer_id', 'unit_id', 'region_id', 'category_id', 'stack_id', 'shelf_id'], 'integer'],
            [['datepicker', 'name_ru', 'name_en', 'name_uz', 'description_ru', 'description_en', 'description_uz', 'article', 'model', 'qr', 'date', 'ip'], 'safe'],
            [['price_buy', 'price_sale', 'amount_limit'], 'number'],
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
        $query = Product::find();

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
            'stack_id' => $this->stack_id,
            'price_buy' => $this->price_buy,
            'price_sale' => $this->price_sale,
            'manufacturer_id' => $this->manufacturer_id,
            'unit_id' => $this->unit_id,
            'category_id' => $this->category_id,
            'amount' => $this->amount,
            'shelf_id' => $this->shelf_id,
            'status' => $this->status,
            'sort' => $this->sort,
            'date' => $this->date,
        ]);

        $query->andFilterWhere(['like', 'name_ru', $this->name_ru])
            ->andFilterWhere(['like', 'name_en', $this->name_en])
            ->andFilterWhere(['like', 'name_uz', $this->name_uz])
            ->andFilterWhere(['like', 'description_ru', $this->description_ru])
            ->andFilterWhere(['like', 'description_en', $this->description_en])
            ->andFilterWhere(['like', 'description_uz', $this->description_uz])
            ->andFilterWhere(['like', 'article', $this->article])
            ->andFilterWhere(['like', 'model', $this->model])
            ->andFilterWhere(['like', 'qr', $this->qr])
            ->andFilterWhere(['like', 'ip', $this->ip]);

        if ($this->datepicker) {
            $daterange = explode(' - ', $this->datepicker);
            $start = $daterange[0];
            $end = $daterange[1];

            $query->andFilterWhere(['between', 'product.date', $start, $end]);
        }

        return $dataProvider;
    }
}
