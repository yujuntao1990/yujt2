<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Sponsors;

/**
 * SponsorsSearch represents the model behind the search form about `app\models\Sponsors`.
 */
class SponsorsSearch extends Sponsors
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'sports_id', 'money', 'people_num', 'user_id'], 'integer'],
            [['sponsors_name', 'sponsors_about', 'contact', 'photo', 'sponsors_activity', 'begin_time', 'end_time', 'address', 'created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
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
        $query = Sponsors::find();

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
            'sports_id' => $this->sports_id,
            'money' => $this->money,
            'people_num' => $this->people_num,
            'user_id' => $this->user_id,
        ]);

        $query->andFilterWhere(['like', 'sponsors_name', $this->sponsors_name])
            ->andFilterWhere(['like', 'sponsors_about', $this->sponsors_about])
            ->andFilterWhere(['like', 'contact', $this->contact])
            ->andFilterWhere(['like', 'photo', $this->photo])
            ->andFilterWhere(['like', 'sponsors_activity', $this->sponsors_activity])
            ->andFilterWhere(['like', 'begin_time', $this->begin_time])
            ->andFilterWhere(['like', 'end_time', $this->end_time])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'created_at', $this->created_at])
            ->andFilterWhere(['like', 'updated_at', $this->updated_at]);

        return $dataProvider;
    }
}
