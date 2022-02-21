<?php

namespace app\models\industry;

use app\models\industry\AllDeffect;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * AllDeffectSearch represents the model behind the search form of `app\models\industry\AllDeffect`.
 */
class AllDeffectSearch extends AllDeffect
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'department_id', 'is_save', 'deffect_id', 'model_id', 'detail_id', 'user_id', 'line_id', 'count_deffect', 'status', 'created_at', 'updated_at'], 'integer'],
            [['number_poddon', 'current_operation'], 'safe'],
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

        if(Yii::$app->user->identity->id == 1){
            $query = AllDeffect::find();
        }
        else {
            $query = AllDeffect::find()->where(['user_id' => [Yii::$app->user->identity->id]])->orWhere(['department_id'=>Yii::$app->user->identity->department_id]);

        }

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
            'deffect_id' => $this->deffect_id,
            'is_save' => $this->is_save,
            'current_operation' => $this->current_operation,
            'detail_id' => $this->detail_id,
            'model_id' => $this->model_id,
            'user_id' => $this->user_id,
            'line_id' => $this->line_id,
            'count_deffect' => $this->count_deffect,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'number_poddon', $this->number_poddon]);

        return $dataProvider;
    }
}
