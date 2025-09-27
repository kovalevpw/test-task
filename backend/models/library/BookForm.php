<?php

namespace app\models\library;

use app\models\ModelException;
use Throwable;
use Yii;
use yii\base\Model;
use yii\db\Query;
use yii\web\UploadedFile;

class BookForm extends Model
{
    /**
     * @var string
     */
    public mixed $isbn = null;

    /**
     * @var string
     */
    public mixed $title = null;

    /**
     * @var string
     */
    public mixed $description = null;

    /**
     * @var string
     */
    public mixed $publication_year = null;

    /**
     * @var int[]
     */
    public mixed $author_id = [];

    /**
     * @var UploadedFile
     */
    public mixed $image = null;

    /**
     * @param Book $book
     */
    public function __construct(public Book $book)
    {
        parent::__construct([]);
    }

    /**
     * @return void
     */
    public function init(): void
    {
        $this->author_id[] = array_map(fn (Author $author) => $author->id, $this->book->authors);

        parent::init();
    }

    /**
     * @inheritdoc
     */
    public function load($data, $formName = null): bool
    {
        // TODO: with formName
        $this->image = UploadedFile::getInstanceByName('image');

        return parent::load($data, $formName);
    }

    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['isbn'], 'required'],
            [['isbn'], 'trim'],
            [['isbn'], 'string'],
            [['isbn'], 'validateIsbn'],
            [
                ['isbn'],
                'unique',
                'targetClass' => Book::class,
                'targetAttribute' => 'isbn',
                'filter' => fn(Query $query) => $query->andWhere(['not', ['id' => $this->book->id]]),
            ],
            [['title'], 'required'],
            [['title'], 'trim'],
            [['title'], 'string', 'min' => 1, 'max' => 255],
            [['description'], 'default', 'value' => ''],
            [['description'], 'string', 'max' => 65535],
            [['publication_year'], 'required'],
            [['publication_year'], 'integer', 'min' => 0, 'max' => date('Y')],
            [['author_id'], 'required'],
            [['author_id'], 'each', 'rule' => ['integer', 'skipOnEmpty' => false]],
            [['author_id'], 'each', 'rule' => ['exist', 'targetClass' => Author::class, 'targetAttribute' => 'id']],
            [['author_id'], 'filter', 'filter' => fn (array $value) => array_unique($value), 'skipOnArray' => false],
            [['image'], 'file', 'extensions' => ['png', 'jpg']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels(): array
    {
        return $this->book->attributeLabels();
    }

    /**
     * @param string $attribute
     * @return void
     */
    public function validateIsbn(string $attribute): void
    {
        $value = $this->{$attribute};

        if (!$this->hasErrors($attribute)) {
            return;
        }

        if (preg_match('/^[0-9][-][0-9]{4}[-][0-9]{4}[-][0-9]$/', $value)) {
            return;
        }

        if (preg_match('/^[0-9]{3}[-][0-9][-][0-9]{4}[-][0-9]{4}[-][0-9]$/', $value)) {
            return;
        }

        $this->addError($attribute, Yii::t('yii', 'The format of {attribute} is invalid.', [
            'attribute' => $attribute,
        ]));
    }

    /**
     * @return void
     * @throws ModelException
     * @throws Throwable
     */
    public function save(): void
    {
        Yii::$app->db->transaction(function () {
            if (!$this->validate()) {
                throw ModelException::modelSaveError($this);
            }

            $this->book->setAttributes($this->getAttributes(), false);

            if ($this->image && !$this->image->hasError) {
                $this->book->photo_file = sprintf('@webroot/library/%s.%s', hash('sha256', time()), $this->image->extension);
                $this->image->saveAs($this->book->photo_file);
            }

            if (!$this->book->save()) {
                throw ModelException::modelSaveError($this);
            }

            $links = $this->book->getBookLinks()->indexBy('author_id')->all();

            foreach ($links as $authorId => $link) {
                if (!in_array($authorId, $this->author_id)) {
                    $link->delete();
                }
            }

            foreach ($this->author_id as $authorId) {
                if (!array_key_exists($authorId, $links)) {
                    $link = new BookLink(['author_id' => $authorId, 'book_id' => $this->book->id]);

                    if (!$link->save()) {
                        throw ModelException::modelSaveError($link);
                    }
                }
            }
        });
    }
}
