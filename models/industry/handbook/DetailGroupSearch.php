<?php

namespace app\models\industry\handbook;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\industry\handbook\Detail;

/**
 * DetailSearch represents the model behind the search form of `app\models\industry\handbook\Detail`.
 */
class DetailGroupSearch extends Detail
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'department_id', 'product_id', 'sort', 'count', 'status', 'created_at', 'updated_at'], 'integer'],
            [['name_ru', 'name_en', 'name_uz'], 'safe'],
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
        $query = Detail::find()
            ->select(['SUM(count) AS amount', 'department_id', 'unit_id', 'name_ru', 'count', 'status', 'created_at', 'id'])
            ->where('status = 1')
            ->groupBy(['department_id', 'product_id','unit_id', 'name_ru','count', 'status', 'created_at', 'id']);

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
            'department_id' => $this->department_id,
            'sort' => $this->sort,
            'count' => $this->count,
            'product_id' => $this->product_id,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'name_ru', $this->name_ru])
            ->andFilterWhere(['like', 'name_en', $this->name_en])
            ->andFilterWhere(['like', 'name_uz', $this->name_uz]);

        return $dataProvider;
    }
}
