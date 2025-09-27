<?php

namespace app\models\library;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * @property-read int $id
 * @property int $subscription_id
 * @property int $author_id
 * @property-read Subscription $subscription
 * @property-read Author $author
 */
class SubscriptionLink extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName(): string
    {
        return '{{%library_subscription_link}}';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels(): array
    {
        return [
            'subscription_id' => Yii::t('yii', 'Автор'),
            'author_id' => Yii::t('yii', 'Книга'),
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getSubscription(): ActiveQuery
    {
        return $this->hasOne(Subscription::class, ['id' => 'subscription_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getAuthor(): ActiveQuery
    {
        return $this->hasOne(Author::class, ['id' => 'author_id']);
    }
}
