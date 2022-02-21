<?php

namespace app\models\industry;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\industry\DepartmentSizing;

class DepartmentSizingSearch extends DepartmentSizing
{
    public function rules()
    {
        return [
            [['id', 'user_id', 'department_id', 'new_number_poddon', 'is_ckeck', 'previous_department_id', 'model_id', 'amount', 'time_expire', 'is_defect', 'status', 'created_at', 'updated_at'], 'integer'],
            [['current_operation', 'articul', 'new_number_poddon', 'number_poddon'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = DepartmentSizing::find();

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
            'user_id' => $this->user_id,
            'department_id' => $this->department_id,
            'number_poddon' => $this->number_poddon,
            'model_id' => $this->model_id,
            'amount' => $this->amount,
            'previous_department_id' => $this->previous_department_id,
            'time_expire' => $this->time_expire,
            'new_number_poddon' => $this->new_number_poddon,
            'is_defect' => $this->is_defect,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'current_operation', $this->current_operation])
            ->andFilterWhere(['like', 'current_operation', $this->number_poddon])
            ->andFilterWhere(['like', 'articul', $this->articul]);

        return $dataProvider;
    }
}
