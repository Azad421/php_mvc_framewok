<?php

namespace azadkh\mvcframework;

class View
{
    public string $title = '';

    public function renderView($view, $data = [])
    {
        // return Application::$app->view->renderView($view, $data);
        $viewContent = $this->renderOnlyView($view, $data);
        $layoutContent = $this->layoutContent();
        return str_replace('{{content}}', $viewContent, $layoutContent);
    }

    public function renderContent($viewContent)
    {
        $layoutContent = $this->layoutContent();
        return str_replace('{{content}}', $viewContent, $layoutContent);
    }

    public function layoutContent()
    {
        $layout = Application::$app->layout;
        if (Application::$app->controller) {
            $layout =  Application::$app->controller->layout;
        }
        ob_start();
        require_once Application::$root_dir . "/views/layouts/$layout.php";
        return ob_get_clean();
    }

    protected function renderOnlyView($view, $data)
    {
        foreach ($data as $key => $value) {
            $$key = $value;
        }
        ob_start();
        require_once Application::$root_dir . "/views/$view.php";
        return ob_get_clean();
    }
}