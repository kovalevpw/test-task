<?php

namespace app\models\library;

use Yii;
use yii\db\ActiveRecord;

/**
 * @property-read int $id
 * @property int $phone_number
 */
class Subscription extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName(): string
    {
        return '{{%library_subscription}}';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels(): array
    {
        return [
            'phone_number' => Yii::t('yii', 'Номер телефона'),
        ];
    }
}
