<?php

namespace app\components\smspilot;

use yii\httpclient\Client;
use yii\httpclient\CurlTransport;
use yii\httpclient\Exception as ClientException;

class SmsClient
{
    /** @var string */
    private const API_URL = 'https://smspilot.ru/api.php';

    /** @var string */
    private const API_SENDER = 'INFORM';

    /**
     * @param string $apiKey
     */
    public function __construct(
        private string $apiKey = 'XXXXXXXXXXXXYYYYYYYYYYYYZZZZZZZZXXXXXXXXXXXXYYYYYYYYYYYYZZZZZZZZ',
    ) {
    }

    /**
     * @param string $phoneNumber
     * @param string $message
     * @return void
     * @throws SmsClientException
     */
    public function sendMessage(string $phoneNumber, string $message): void
    {
        try {
            $client = new Client(['transport' => CurlTransport::class]);
            $request = $client->createRequest()
                ->setMethod('GET')
                ->setOptions([
                    CURLOPT_FOLLOWLOCATION => false,
                    CURLOPT_CONNECTTIMEOUT => 5,
                    CURLOPT_TIMEOUT => 45,
                ])
                ->setUrl(self::API_URL . '?' . http_build_query([
                        'send' => $message,
                        'to' => $phoneNumber,
                        'from' => self::API_SENDER,
                        'apikey' => $this->apiKey,
                        'format' => 'json',
                    ]));

            $response = $request->send();

            if (isset($response->data['error']['description'])) {
                throw SmsClientException::apiError($response->data['error']['description'], $response->data['error']['code'] ?? '');
            }

            if ($response->statusCode != 200) {
                throw SmsClientException::invalidStatusCode($response->statusCode);
            }

            if (!isset($response->data['send'])) {
                throw SmsClientException::invalidData($response->content);
            }
        } catch (ClientException $exception) {
            throw SmsClientException::clientError($exception->getMessage());
        }
    }
}
