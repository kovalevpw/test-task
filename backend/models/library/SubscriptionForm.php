<?php

namespace app\models\library;

use app\models\ModelException;
use Throwable;
use Yii;
use yii\base\Model;

class SubscriptionForm extends Model
{
    /**
     * @var int
     */
    public mixed $phone_number = null;

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
    public function rules(): array
    {
        return [
            [['phone_number'], 'required'],
            [['phone_number'], 'filter', 'filter' => [$this, 'phoneFilter'], 'skipOnArray' => true],
            [['phone_number'], 'integer', 'min' => 89000000000, 'max' => 89999999999],
        ];
    }

    /**
     * @param string $value
     * @return string
     */
    public function phoneFilter(string $value): string
    {
        $value = preg_replace('/^[+]7/', '8', $value);
        $value = preg_replace('/[^0-9]+/', '', $value);

        return $value;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels(): array
    {
        return (new Subscription)->attributeLabels();
    }

    /**
     * @return void
     * @throws ModelException
     * @throws Throwable
     */
    public function save(): void
    {
        Yii::$app->db->transaction(function (): void {
            if (!$this->validate()) {
                throw ModelException::modelSaveError($this);
            }

            $subscription = Subscription::findOne(['phone_number' => $this->phone_number])
                ?: new Subscription(['phone_number' => $this->phone_number]);

            if (!$subscription->save()) {
                throw ModelException::modelSaveError($subscription);
            }

            $link = SubscriptionLink::findOne(['subscription_id' => $subscription->id, 'author_id' => $this->author->id])
                ?: new SubscriptionLink(['subscription_id' => $subscription->id, 'author_id' => $this->author->id]);

            if (!$link->save()) {
                throw ModelException::modelSaveError($link);
            }
        });
    }
}
