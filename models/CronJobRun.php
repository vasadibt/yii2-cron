<?php
/**
 * Created by Model Generator.
 */

namespace vasadibt\cron\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveQueryInterface;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "cron_job_run".
 *
 * @property integer $id
 * @property integer $job_id
 * @property string $pid
 * @property boolean $in_progress
 * @property string $start
 * @property string $finish
 * @property double $runtime
 * @property integer $exit_code
 * @property string $output
 * @property string $error_output
 *
 * @property CronJob $job
 */
class CronJobRun extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cron_job_run';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['job_id', 'pid', 'in_progress', 'start'], 'required'],
            [['job_id', 'exit_code'], 'integer'],
            [['in_progress'], 'boolean'],
            [['start', 'finish'], 'safe'],
            [['runtime'], 'number'],
            [['output', 'error_output'], 'string'],
            [['pid'], 'string', 'max' => 255],
            [['job_id'], 'exist', 'targetRelation' => 'Job'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('vbt-cron', 'ID'),
            'job_id' => Yii::t('vbt-cron', 'Job'),
            'pid' => Yii::t('vbt-cron', 'Pid'),
            'in_progress' => Yii::t('vbt-cron', 'In Progress'),
            'start' => Yii::t('vbt-cron', 'Start'),
            'finish' => Yii::t('vbt-cron', 'Finish'),
            'runtime' => Yii::t('vbt-cron', 'Runtime'),
            'exit_code' => Yii::t('vbt-cron', 'Exit Code'),
            'output' => Yii::t('vbt-cron', 'Output'),
            'error_output' => Yii::t('vbt-cron', 'Error Output'),
        ];
    }

    /**
     * @return ActiveQuery|CronJobRunQuery|ActiveQueryInterface
     */
    public function getJob()
    {
        return $this->hasOne(CronJob::class, ['id' => 'job_id']);
    }

    /**
     * {@inheritdoc}
     * @return CronJobRunQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CronJobRunQuery(get_called_class());
    }
}
