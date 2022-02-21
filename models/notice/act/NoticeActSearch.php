<?php

namespace app\models\notice\act;

use yii\base\Model;
use yii\data\ActiveDataProvider;

use app\models\notice\act\NoticeAct;

/**
 * NoticeActSearch represents the model behind the search form of `app\models\NoticeAct`.
 */
class NoticeActSearch extends NoticeAct
{
    public $truck_number, $truck_number_reg, $invoice_number, $notice_number, $provider_id, $article, $description, $date_notice;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'notice_control_id', 'sort', 'status'], 'integer'],
            [['date_notice', 'description', 'date', 'notice_number', 'description', 'date', 'date_notice', 'truck_number', 'truck_number_reg', 'invoice_number', 'provider_id', 'article'], 'safe'],
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
        $query = NoticeAct::find();

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
            'notice_control_id' => $this->notice_control_id,
            'date' => $this->date,
            'sort' => $this->sort,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'date_notice', $this->date_notice])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'date_notice', $this->date_notice])
            ->andFilterWhere(['like', 'truck_number', $this->truck_number])
            ->andFilterWhere(['like', 'truck_number_reg', $this->truck_number_reg])
            ->andFilterWhere(['like', 'invoice_number', $this->invoice_number])
            ->andFilterWhere(['like', 'article', $this->article]);

        return $dataProvider;
    }
}
