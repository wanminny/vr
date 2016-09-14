<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\hotspots;

/**
 * hotspotsSearch represents the model behind the search form about `app\models\hotspots`.
 */
class hotspotsSearch extends hotspots
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'scene_id'], 'integer'],
            [['ath', 'atv', 'linkedscene', 'hname', 'rotate'], 'safe'],
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
        $query = hotspots::find();

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
            'scene_id' => $this->scene_id,
        ]);

        $query->andFilterWhere(['like', 'ath', $this->ath])
            ->andFilterWhere(['like', 'atv', $this->atv])
            ->andFilterWhere(['like', 'linkedscene', $this->linkedscene])
            ->andFilterWhere(['like', 'hname', $this->hname])
            ->andFilterWhere(['like', 'rotate', $this->rotate]);

        return $dataProvider;
    }
}
