<?php

namespace app\models\library;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * @property-read int $id
 * @property int $author_id
 * @property int $book_id
 * @property-read Author $author
 * @property-read Book $book
 */
class BookLink extends ActiveRecord
{
    /**
     * @var int
     */
    public int $book_count = 0;

    /**
     * @inheritdoc
     */
    public static function tableName(): string
    {
        return '{{%library_book_link}}';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels(): array
    {
        return [
            'author_id' => Yii::t('yii', 'Автор'),
            'book_id' => Yii::t('yii', 'Книга'),
            'book_count' => Yii::t('yii', 'Количество книг'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['author_id'], 'required'],
            [['author_id'], 'integer'],
            [['author_id'], 'exist', 'targetClass' => Author::class, 'targetAttribute' => 'id'],
            [['book_id'], 'required'],
            [['book_id'], 'integer'],
            [['book_id'], 'exist', 'targetClass' => Book::class, 'targetAttribute' => 'id'],
            [['author_id', 'book_id'], 'unique', 'targetAttribute' => ['author_id', 'book_id']],
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getAuthor(): ActiveQuery
    {
        return $this->hasOne(Author::class, ['id' => 'author_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getBook(): ActiveQuery
    {
        return $this->hasOne(Book::class, ['id' => 'book_id']);
    }
}
