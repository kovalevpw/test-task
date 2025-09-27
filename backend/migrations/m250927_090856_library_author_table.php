<?php

use yii\db\Migration;

class m250927_090856_library_author_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp(): void
    {
        $this->createTable('{{%library_author}}', [
            'id' => $this->bigPrimaryKey(),
            'full_name' => $this->string()->notNull(),
        ]);

        $this->createIndex('uk1__library_author', '{{%library_author}}', ['full_name'], true);
    }

    /**
     * @inheritdoc
     */
    public function safeDown(): void
    {
        $this->dropTable('{{%library_author}}');
    }
}
