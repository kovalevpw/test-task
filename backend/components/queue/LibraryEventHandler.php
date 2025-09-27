<?php

namespace app\components\queue;

use app\components\smspilot\SmsClientJob;
use app\models\library\BookLink;
use app\models\library\SubscriptionLink;
use Yii;
use yii\base\BootstrapInterface;
use yii\base\Event;
use yii\db\AfterSaveEvent;

class LibraryEventHandler implements BootstrapInterface
{
    /**
     * @inheritdoc
     */
    public function bootstrap($app): void
    {
        Event::on(BookLink::class, BookLink::EVENT_AFTER_INSERT, [$this, 'onSaveBookLink']);
    }

    /**
     * @param AfterSaveEvent $event
     * @return void
     */
    public function onSaveBookLink(AfterSaveEvent $event): void
    {
        /** @var BookLink $link */
        $link = $event->sender;

        $this->sendNotification($link);
    }

    /**
     * @param BookLink $bookLink
     * @return void
     */
    public function sendNotification(BookLink $bookLink): void
    {
        $message = sprintf('У автора %s доступна новая книга: "%s"', $bookLink->author->full_name, $bookLink->book->title);

        $query = SubscriptionLink::find()
            ->with(['subscription'])
            ->andWhere(['author_id' => $bookLink->author_id])
            ->orderBy(['id' => SORT_ASC])
        ;

        /** @var SubscriptionLink $subscriptionLink */
        foreach ($query->each() as $subscriptionLink) {
            $job = new SmsClientJob($subscriptionLink->subscription->phone_number, $message);

            Yii::$app->queue->push($job);
        }
    }
}
