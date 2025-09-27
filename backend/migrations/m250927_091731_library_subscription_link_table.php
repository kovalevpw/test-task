<?php

use yii\db\Migration;

class m250927_091731_library_subscription_link_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp(): void
    {
        $this->createTable('{{%library_subscription_link}}', [
            'id' => $this->bigPrimaryKey(),
            'subscription_id' => $this->bigInteger(),
            'author_id' => $this->bigInteger(),
        ]);

        $this->createIndex('ck1__library_subscription_link', '{{%library_subscription_link}}', ['subscription_id']);
        $this->createIndex('ck2__library_subscription_link', '{{%library_subscription_link}}', ['author_id']);

        $this->addForeignKey('fk1__library_subscription_link', '{{%library_subscription_link}}', ['author_id'], '{{%library_author}}', 'id');
        $this->addForeignKey('fk2__library_subscription_link', '{{%library_subscription_link}}', ['subscription_id'], '{{%library_subscription}}', 'id');

        $this->createIndex('uk1__library_subscription_link', '{{%library_subscription_link}}', ['subscription_id', 'author_id'], true);
    }

    /**
     * @inheritdoc
     */
    public function safeDown(): void
    {
        $this->dropTable('{{%library_subscription_link}}');
    }
}
