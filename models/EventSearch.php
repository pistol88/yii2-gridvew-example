<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use app\models\Distance;

/**
 * EventSearch represents the model behind the search form of `app\models\Event`.
 */
class EventSearch extends Event
{
    public $date_from;
    public $date_to;
    public $distance_from;
    public $distance_to;
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'distance_from', 'distance_to'], 'integer'],
            [['name', 'date_from', 'date_to'], 'safe'],
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
        $query = Event::find();

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
        ]);
        
        if ($this->date_from) {
            $query->andWhere(['>=', 'date', $this->date_from]);
        }
       
        if ($this->date_to) {
            $query->andWhere(['<=', 'date', $this->date_to]);
        }
        
        if ($this->distance_from | $this->distance_from) {
            $distances = Distance::find();
                    
            if ($this->distance_from) {
                $distances->andWhere(['>=', 'value', $this->distance_from]);
            }
            
            if ($this->distance_to) {
                $distances->andWhere(['<=', 'value', $this->distance_to]);
            }
            
            $distanceIds = ArrayHelper::map($distances->asArray()->all(), 'id', 'id');

            if(empty($distanceIds)) {
                $distanceIds = -1;
            }
            
            $query->leftJoin('event_distances', 'event_distances.event_id = events.id')->andWhere(['event_distances.distance_id' => $distanceIds]);
        }
        
        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}
