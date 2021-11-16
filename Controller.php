<?php

namespace azadkh\mvcframework;

use azadkh\mvcframework\middlewares\BaseMiddleware;

class Controller
{
    public string $layout = 'main';
    public string $action = '';
    /**
     * @var \azadkh\mvcframework\middlewares\BaseMiddleware[]
     */
    protected array $middlewares = [];

    public function setLayout($layout)
    {
        $this->layout = $layout;
    }
    public function render($view, $data = [])
    {
        return Application::$app->view->renderView($view, $data);
    }
    public function registerMiddleware(BaseMiddleware $middleware)
    {
        $this->middlewares[] = $middleware;
    }

    /**
     * Get the value of middlewares
     *
     * @return  \azadkh\mvcframework\middlewares\BaseMiddleware[]
     */
    public function getMiddlewares()
    {
        return $this->middlewares;
    }
}