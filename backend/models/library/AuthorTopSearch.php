<?php

namespace app\models\library;

use app\models\ModelException;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class AuthorTopSearch extends Model
{
    /**
     * @var int|null
     */
    public mixed $publication_year = null;

    /**
     * @var int
     */
    public int $limit = 10;

    /**
     * @inheritdoc
     */
    public function formName(): string
    {
        return '';
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [['publication_year'], 'integer', 'min' => 0, 'max' => date('Y')],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels(): array
    {
        return (new Book)->attributeLabels();
    }

    /**
     * @return ActiveDataProvider
     */
    public function search(): ActiveDataProvider
    {
        if (!$this->validate()) {
            throw ModelException::modelValidationError($this);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => BookLink::find()
                ->with(['author'])
                ->joinWith(['book'], false)
                ->select([
                    'author_id',
                    'COUNT(book_id) as book_count',
                ])
                ->andHaving('COUNT(book_id) > 0')
                ->groupBy(['author_id'])
                ->orderBy([
                    'book_count' => SORT_DESC,
                    'author_id' => SORT_ASC,
                ])
                ->limit($this->limit),
            'pagination' => false,
        ]);

        $dataProvider->query->andFilterWhere([Book::tableName() . '.publication_year' => $this->publication_year]);

        return $dataProvider;
    }

    /**
     * @return string[]
     */
    public function getPublicationYearOptions(): array
    {
        return Book::find()
            ->select(['publication_year'])
            ->distinct()
            ->orderBy(['publication_year' => SORT_DESC])
            ->indexBy('publication_year')
            ->column();
    }
}
