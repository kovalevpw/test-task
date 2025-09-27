<?php

use yii\db\Migration;

/**
 * @property-read int $id
 * @property string $isbn
 * @property string $title
 * @property string $description
 * @property string $publication_year
 * @property string $photo_file
 */
class m250927_091036_library_book_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp(): void
    {
        $this->createTable('{{%library_book}}', [
            'id' => $this->bigPrimaryKey(),
            'isbn' => $this->string()->notNull(),
            'title' => $this->string()->notNull(),
            'description' => $this->text()->null(),
            'publication_year' => $this->smallInteger()->notNull(),
            'photo_file' => $this->string()->null(),
        ]);

        $this->createIndex('ck1__library_book', '{{%library_book}}', ['publication_year']);
        $this->createIndex('uk1__library_book', '{{%library_book}}', ['isbn'], true);
    }

    /**
     * @inheritdoc
     */
    public function safeDown(): void
    {
        $this->dropTable('{{%library_book}}');
    }
}
