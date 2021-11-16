<?php

namespace azadkh\mvcframework\form;

use azadkh\mvcframework\Model;

class InputField extends BaseField
{
    public const TYPE_TEXT = 'text';
    public const TYPE_PASSWORD = 'password';
    public const TYPE_NUMBER = 'number';

    public string $type;
    public Model $model;
    public string $attribute;

    public function __construct(Model $model, $attribute)
    {
        $this->type = self::TYPE_TEXT;
        parent::__construct($model, $attribute);
    }



    public function passwrodField()
    {
        $this->type = self::TYPE_PASSWORD;
        return $this;
    }
    public function renderInput(): string
    {
        return sprintf(
            '<input type="%s" name="%s" class="form-control %s" value="%s" name="%s" >',
            $this->type,
            $this->attribute,
            $this->model->hasError($this->attribute) ? 'is-invalid' : '',
            $this->model->{$this->attribute},
            $this->attribute,
        );
    }
}
