<?php

namespace app\controllers;

use InvalidArgumentException;
use Yii;
use yii\db\ActiveRecord;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;

trait ModelHelperTrait
{
    /**
     * @param mixed $id
     * @param string $modelClass
     * @return ActiveRecord
     * @throws BadRequestHttpException
     * @throws NotFoundHttpException
     */
    private function getOrCreate(mixed $id, string $modelClass): ActiveRecord
    {
        /** @var ActiveRecord|string $modelClass */
        if (!is_a($modelClass, ActiveRecord::class, true)) {
            throw new InvalidArgumentException(sprintf('Invalid modelClass %s', $modelClass));
        }

        if ($id === null) {
            return Yii::createObject($modelClass);
        }

        if (!is_numeric($id)) {
            throw new BadRequestHttpException();
        }

        $model = $modelClass::findOne(['id' => $id]);

        if ($model === null) {
            throw new NotFoundHttpException();
        }

        return $model;
    }

    /**
     * @param mixed $id
     * @param string $modelClass
     * @return ActiveRecord
     * @throws BadRequestHttpException
     * @throws NotFoundHttpException
     */
    private function getOrError(mixed $id, string $modelClass): ActiveRecord
    {
        $model = $this->getOrCreate($id, $modelClass);

        if ($model->isNewRecord) {
            throw new NotFoundHttpException();
        }

        return $model;
    }
}
