<?php

namespace ser6io\yii2user\migrations;

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_log}}`.
 */
class m231110_161544_create_user_log_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // https://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user_log}}', [
            'id' => $this->primaryKey(),
            'level' => $this->integer(),
            'category' => $this->string(),
            'log_time' => $this->double(),
            'ip' => $this->string(),
            'user_id' => $this->integer(),
            'session_id' => $this->string(),
            'message' => $this->text(),
        ], $tableOptions);

        $this->createIndex('idx_log_level', '{{%user_log}}', 'level');
        $this->createIndex('idx_log_category', '{{%user_log}}', 'category');
        $this->createIndex('idx_log_time', '{{%user_log}}', 'log_time');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx_log_level', '{{%user_log}}');
        $this->dropIndex('idx_log_category', '{{%user_log}}');
        $this->dropIndex('idx_log_time', '{{%user_log}}');
        $this->dropTable('{{%user_log}}');
    }
}    
