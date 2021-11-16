<?php

namespace azadkh\mvcframework\form;

class TextArea extends BaseField
{
    public function renderInput(): string
    {
        return sprintf(
            '<textarea name="%s" id="" rows="3" class="form-control %s">%s</textarea>',
            $this->attribute,
            $this->model->hasError($this->attribute) ? 'is-invalid' : '',
            $this->model->{$this->attribute},
        );
    }
}
