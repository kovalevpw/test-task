<?php

namespace app\models\library;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * @property-read int $id
 * @property string $isbn
 * @property string $title
 * @property string $description
 * @property string $publication_year
 * @property string $photo_file
 * @property-read BookLink[] $bookLinks
 * @property-read Author[] $authors
 */
class Book extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName(): string
    {
        return '{{%library_book}}';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels(): array
    {
        return [
            'isbn' => Yii::t('yii', 'ISBN'),
            'title' => Yii::t('yii', 'Заголовок'),
            'description' => Yii::t('yii', 'Описание'),
            'publication_year' => Yii::t('yii', 'Год выпуска'),
            'photo_file' => Yii::t('yii', 'Фото главной страницы'),
            'authors' => Yii::t('yii', 'Авторы')
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getBookLinks(): ActiveQuery
    {
        return $this->hasMany(BookLink::class, ['book_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getAuthors(): ActiveQuery
    {
        return $this->hasMany(Author::class, ['id' => 'author_id'])->via('bookLinks');
    }
}
