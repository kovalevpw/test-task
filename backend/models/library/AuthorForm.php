<?php

namespace app\models\library;

use app\models\ModelException;
use Throwable;
use Yii;
use yii\base\Model;
use yii\db\Query;

class AuthorForm extends Model
{
    /**
     * @var string
     */
    public mixed $full_name = null;

    /**
     * @param Author $author
     */
    public function __construct(public Author $author)
    {
        parent::__construct([]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels(): array
    {
        return $this->author->attributeLabels();
    }

    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['full_name'], 'required'],
            [['full_name'], 'trim'],
            [['full_name'], 'string', 'min' => 1, 'max' => 255],
            [
                ['full_name'],
                'unique',
                'targetClass' => Author::class,
                'targetAttribute' => 'full_name',
                'filter' => fn(Query $query) => $query->andWhere(['not', ['id' => $this->author->id]]),
            ],
        ];
    }

    /**
     * @return void
     * @throws ModelException
     * @throws Throwable
     */
    public function save(): void
    {
        Yii::$app->db->transaction(function(): void {
            $this->author->setAttributes($this->getAttributes(), false);

            if (!$this->author->save()) {
                throw ModelException::modelSaveError($this->author);
            }
        });
    }
}
