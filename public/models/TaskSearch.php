<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Expression;

/**
 * TaskSearch represents the model behind the search form of `app\models\Task`.
 */
class TaskSearch extends Task
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id'], 'integer'],
            [['name', 'start', 'end', 'stat'], 'safe'],
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
        $query = Task::find();

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
        $query->select('*');
        $query->addSelect(['stat' => new Expression('CASE WHEN end IS NULL THEN 1 ELSE 2 END')]);
        $query->addSelect(['timeD' => new Expression('TIMESTAMPDIFF(day,start,CASE WHEN end IS NULL THEN NOW() ELSE end END)')]);
        $query->addSelect(['timeH' => new Expression('LPAD(MOD(TIMESTAMPDIFF(hour,start,CASE WHEN end IS NULL THEN NOW() ELSE end END),24), 2, 0)')]);
        $query->addSelect(['timeM' => new Expression('LPAD(MOD(TIMESTAMPDIFF(minute,start,CASE WHEN end IS NULL THEN NOW() ELSE end END),60), 2, 0)')]);
        $query->addSelect(['timeS' => new Expression('LPAD(MOD(TIMESTAMPDIFF(second,start,CASE WHEN end IS NULL THEN NOW() ELSE end END),60), 2, 0)')]);
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'user_id' => $this->user_id,
            'start' => $this->start,
            'end' => $this->end,
        ]);
        $query->andFilterWhere(['like', 'name', $this->name]);
        if ($this->stat == 1) {
            $query->andWhere(['end' => null]);
        } elseif ($this->stat == 2) {
            $query->andWhere(['NOT', ['end' => null]]);
        }


        return $dataProvider;
    }
}
