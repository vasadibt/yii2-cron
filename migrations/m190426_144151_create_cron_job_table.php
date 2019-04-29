<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%cron_job}}`.
 */
class m190426_144151_create_cron_job_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%cron_job}}', [
            'id' => $this->primaryKey(),
            'last_id' => $this->integer(),
            'name' => $this->string()->notNull(),
            'schedule' => $this->string()->notNull(),
            'command' => $this->string()->notNull(),
            'active' => $this->boolean()->notNull()->defaultValue(1),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%cron_job}}');
    }
}
