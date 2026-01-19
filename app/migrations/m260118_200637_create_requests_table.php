<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%requests}}`.
 */
class m260118_200637_create_requests_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%requests}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'amount' => $this->integer()->notNull(),
            'term' => $this->integer()->notNull(),
            'status' => $this->string(16)->notNull()->defaultValue('pending'),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('NOW()'),
            'updated_at' => $this->timestamp()->notNull()->defaultExpression('NOW()'),
        ]);
        $this->createIndex('idx_requests_user_id', 'requests', ['user_id']);
        $this->execute("CREATE UNIQUE INDEX uq_requests_approved_user_id ON requests(user_id) WHERE status='approved'");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->execute("DROP INDEX IF EXISTS uq_requests_approved_user_id");
        $this->dropTable('{{%requests}}');
    }
}
