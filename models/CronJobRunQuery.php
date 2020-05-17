<?php
/**
 * Created by Model Generator.
 */

namespace vasadibt\cron\models;

use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[CronJobRun]].
 *
 * @see CronJobRun
 */
class CronJobRunQuery extends ActiveQuery
{
    /**
     * {@inheritdoc}
     *
     * @return CronJobRun[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     *
     * @return CronJobRun|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
