<?php

namespace app\models;

use RuntimeException;
use yii\base\Model;

final class ModelException extends RuntimeException
{
    /**
     * @param Model $model
     * @param string $message
     */
    private function __construct(private Model $model, string $message)
    {
        parent::__construct($message);
    }

    /**
     * @param Model $model
     * @return self
     */
    public static function modelSaveError(Model $model): self
    {
        return new self($model, 'Model save error.');
    }

    /**
     * @param Model $model
     * @return self
     */
    public static function modelValidationError(Model $model): self
    {
        return new self($model, 'Model validation error.');
    }

    /**
     * @return Model
     */
    public function getModel(): Model
    {
        return $this->model;
    }
}
