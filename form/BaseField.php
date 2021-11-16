<?php

namespace azadkh\mvcframework\form;

use azadkh\mvcframework\Model;

abstract class BaseField
{
    public Model $model;
    public string $attribute;

    public function __construct(Model $model, $attribute)
    {
        $this->model = $model;
        $this->attribute = $attribute;
    }

    abstract public function renderInput(): string;

    public function __toString()
    {
        return sprintf(
            '
        <div class="form-group mb-3">
            <label>%s</label>
            %s
            <div class="invalid-feedback">
               %s
            </div>
        </div>
        ',
            $this->model->getAttr($this->attribute),
            $this->renderInput(),
            $this->model->getFirstError($this->attribute),
        );
    }
}
