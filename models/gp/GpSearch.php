<?php

namespace app\models\gp;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\gp\Gp;

/**
 * GpSearch represents the model behind the search form of `app\models\Gp`.
 */
class GpSearch extends Gp
{
    public $datepicker;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'stock_id', 'stack_id', 'shelf_id', 'manufacturer_id', 'amount', 'status', 'sort'], 'integer'],
            [['datepicker', 'name_ru', 'name_en', 'name_uz', 'description_ru', 'description_en', 'description_uz', 'article', 'model', 'qr', 'date', 'ip'], 'safe'],
            [['price_buy', 'price_sale'], 'number'],
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
        $query = Gp::find();

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
            'price_buy' => $this->price_buy,
            'price_sale' => $this->price_sale,
            'amount' => $this->amount,
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
            ->andFilterWhere(['like', 'manufacturer', $this->manufacturer])
            ->andFilterWhere(['like', 'qr', $this->qr])
            ->andFilterWhere(['like', 'ip', $this->ip]);

        if ($this->datepicker) {
            $daterange = explode(' - ', $this->datepicker);
            $start = $daterange[0];
            $end = $daterange[1];

            $query->andFilterWhere(['between', 'gp.date', $start, $end]);
        }

        return $dataProvider;
    }
}
