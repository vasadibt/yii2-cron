<?php
/**
 * Created by Model Generator.
 */

namespace vasadibt\cron\models;

use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[CronJob]].
 *
 * @see CronJob
 */
class CronJobQuery extends ActiveQuery
{
    /**
     * {@inheritdoc}
     *
     * @return CronJob[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     *
     * @return CronJob|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
