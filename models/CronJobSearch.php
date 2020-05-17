<?php

namespace vasadibt\cron\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * CronJobSearch represents the model behind the search form of `\vasadibt\cron\models\CronJob`.
 */
class CronJobSearch extends CronJob
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'last_id', 'max_execution_time', 'active'], 'integer'],
            [['name', 'schedule', 'command'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
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
        $query = CronJob::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            "query" => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere(['AND',
            ['id' => $this->id],
            ['last_id' => $this->last_id],
            ['like', 'name', $this->name],
            ['like', 'schedule', $this->schedule],
            ['like', 'command', $this->command],
            ['max_execution_time' => $this->max_execution_time],
        ]);

        if (is_numeric($this->active) || is_bool($this->active)) {
            $query->where(['active' => (bool)$this->active]);
        }

        return $dataProvider;
    }
}
