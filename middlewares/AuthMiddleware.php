<?php

namespace azadkh\mvcframework\middlewares;

use azadkh\mvcframework\Application;
use azadkh\mvcframework\exception\ForbiddenException;

class AuthMiddleware extends BaseMiddleware
{
    public $actions = [];

    public function __construct(array $actions = [])
    {
        $this->actions = $actions;
    }

    public function execute()
    {
        if (Application::isGuest()) {
            if (empty($this->actions) || in_array(Application::$app->controller->action, $this->actions)) {
                throw new ForbiddenException();
            }
        }
    }
}
