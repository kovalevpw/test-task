<?php

namespace app\models\library;

use Yii;
use yii\db\ActiveRecord;

/**
 * @property-read int $id
 * @property string $full_name
 */
class Author extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName(): string
    {
        return '{{%library_author}}';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels(): array
    {
        return [
            'full_name' => Yii::t('yii', 'ФИО'),
        ];
    }
}
