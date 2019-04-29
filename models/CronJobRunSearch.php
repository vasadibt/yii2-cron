<?php

namespace vasadibt\cron\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * CronJobRunSearch represents the model behind the search form of `\vasadibt\cron\models\CronJobRun`.
 */
class CronJobRunSearch extends CronJobRun
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'job_id', 'in_progress', 'exit_code'], 'integer'],
            [['pid', 'start', 'finish', 'output', 'error_output'], 'safe'],
            [['runtime'], 'number'],
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
        $query = CronJobRun::find();

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

        $query->andFilterWhere(['AND',
            ['id' => $this->id],
            ['job_id' => $this->job_id],
            ['like', 'pid', $this->pid],
            ['like', 'in_progress', $this->in_progress],
            ['start' => $this->start],
            ['finish' => $this->finish],
            ['runtime' => $this->runtime],
            ['exit_code' => $this->exit_code],
            ['like', 'output', $this->output],
            ['like', 'error_output', $this->error_output],
        ]);

        return $dataProvider;
    }

}
