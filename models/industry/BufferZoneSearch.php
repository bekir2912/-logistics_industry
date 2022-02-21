<?php

namespace app\models\industry;

use app\models\industry\BufferZone;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * BufferZoneSearch represents the model behind the search form of `app\models\industry\BufferZone`.
 */
class BufferZoneSearch extends BufferZone
{

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'to_department_id', 'from_department_id', 'user_id', 'model_id', 'amount', 'status', 'time_expire', 'created_at', 'updated_at'], 'integer'],
            [['current_operation', 'number_poddon'], 'safe'],
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

    public function search($params)
    {
        if(Yii::$app->user->identity->id == 1){
            $query = BufferZone::find();
        }
        else {
            $query = BufferZone::find()->where(['user_id' => Yii::$app->user->identity->id])->orWhere(['from_department_id' => Yii::$app->user->identity->department_id])->orWhere(['to_department_id' => Yii::$app->user->identity->department_id]) ;
        }

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
            'to_department_id' => $this->to_department_id,
            'from_department_id' => $this->from_department_id,
            'user_id' => $this->user_id,
            'model_id' => $this->model_id,
            'amount' => $this->amount,
            'status' => $this->status,
            'status' => $this->status,
            'time_expire' => $this->time_expire,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'current_operation', $this->current_operation])
            ->andFilterWhere(['like', 'number_poddon', $this->number_poddon]);

        return $dataProvider;
    }
}
