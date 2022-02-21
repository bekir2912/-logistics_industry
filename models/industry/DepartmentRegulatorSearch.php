<?php

namespace app\models\industry;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\industry\DepartmentRegulator;

/**
 * DepartmentRegulatorSearch represents the model behind the search form of `app\models\industry\DepartmentRegulator`.
 */
class DepartmentRegulatorSearch extends DepartmentRegulator
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'department_id', 'model_id', 'user_id', 'is_ckeck', 'part_model', 'is_defect', 'amount', 'status', 'created_at', 'updated_at'], 'integer'],
            [['current_operation', 'articul', 'number_poddon'], 'safe'],
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
        $query = DepartmentRegulator::find();

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
            'model_id' => $this->model_id,
            'user_id' => $this->user_id,
            'part_model' => $this->part_model,
            'is_defect' => $this->is_defect,
            'amount' => $this->amount,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'current_operation', $this->current_operation])
            ->andFilterWhere(['like', 'number_poddon', $this->number_poddon])
            ->andFilterWhere(['like', 'articul', $this->articul]);

        return $dataProvider;
    }
}
