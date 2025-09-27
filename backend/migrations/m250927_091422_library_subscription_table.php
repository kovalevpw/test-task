<?php

use yii\db\Migration;

class m250927_091422_library_subscription_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp(): void
    {
        $this->createTable('{{%library_subscription}}', [
            'id' => $this->bigPrimaryKey(),
            'phone_number' => $this->string()->notNull(),
        ]);

        $this->createIndex('uk1__library_subscription', '{{%library_subscription}}', ['phone_number'], true);
    }

    /**
     * @inheritdoc
     */
    public function safeDown(): void
    {
        $this->dropTable('{{%library_subscription}}');
    }
}
