<?php

namespace app\components\smspilot;

use Yii;
use yii\queue\JobInterface;

class SmsClientJob implements JobInterface
{
    /**
     * @param string $phoneNumber
     * @param string $message
     */
    public function __construct(
        private string $phoneNumber,
        private string $message,
    ) {
    }

    /**
     * @inheritdoc
     */
    public function execute($queue): void
    {
        /** @var SmsClient $client */
        $client = Yii::createObject(SmsClient::class);
        $client->sendMessage($this->phoneNumber, $this->message);
    }
}
