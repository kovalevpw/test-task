<?php

use yii\db\Migration;

class m250927_091145_library_book_link_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp(): void
    {
        $this->createTable('{{%library_book_link}}', [
            'id' => $this->bigPrimaryKey(),
            'book_id' => $this->bigInteger(),
            'author_id' => $this->bigInteger(),
        ]);

        $this->createIndex('ck1__library_book_link', '{{%library_book_link}}', ['book_id']);
        $this->createIndex('ck2__library_book_link', '{{%library_book_link}}', ['author_id']);

        $this->addForeignKey('fk1__library_book', '{{%library_book_link}}', ['author_id'], '{{%library_author}}', 'id');
        $this->addForeignKey('fk2__library_book', '{{%library_book_link}}', ['book_id'], '{{%library_book}}', 'id');

        $this->createIndex('uk2__library_book', '{{%library_book_link}}', ['book_id', 'author_id'], true);
    }

    /**
     * @inheritdoc
     */
    public function safeDown(): void
    {
        $this->dropTable('{{%library_book_link}}');
    }
}
