<?php

namespace app\models\industry;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\industry\DepartmentMechanical;

/**
 * DepartmentMechanicalSearch represents the model behind the search form of `app\models\industry\DepartmentMechanical`.
 */
class DepartmentMechanicalSearch extends DepartmentMechanical
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'model_id', 'line_id', 'department_id', 'is_ckeck', 'user_id', 'amount', 'is_defect', 'status', 'created_at', 'previous_department_id', 'updated_at'], 'integer'],
            [['current_operation', 'articul', 'number_poddon', 'new_number_poddon'], 'safe'],
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
        $query = DepartmentMechanical::find();

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
            'model_id' => $this->model_id,
            'line_id' => $this->line_id,
            'department_id' => $this->department_id,
            'previous_department_id' => $this->previous_department_id,
            'user_id' => $this->user_id,
            'new_number_poddon' => $this->new_number_poddon,
            'amount' => $this->amount,
            'is_defect' => $this->is_defect,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'current_operation', $this->current_operation])
            ->andFilterWhere(['like', 'number_poddon', $this->number_poddon])
            ->andFilterWhere(['like', 'new_number_poddon', $this->new_number_poddon])
            ->andFilterWhere(['like', 'articul', $this->articul]);

        return $dataProvider;
    }
}
