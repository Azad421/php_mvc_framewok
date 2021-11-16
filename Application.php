<?php

namespace azadkh\mvcframework;

use azadkh\mvcframework\db\Database;
use azadkh\mvcframework\db\DbModel;

class Application
{
    public $layout = 'main';
    public string $userClass;
    public Router $router;
    public Request $request;
    public Response $response;
    public Session $session;
    public ?UserModel $user;
    public View $view;

    public Database $db;
    public static string $root_dir;
    public static Application $app;
    public ?Controller $controller = null;
    public function __construct($rootPath, array $config)
    {
        $this->userClass = $config['userClass'];
        self::$root_dir = $rootPath;
        $this->response = new Response;
        $this->request = new Request;
        $this->session = new Session;
        $this->view = new View;
        $this->db = new Database($config['db']);
        $this->router = new Router($this->request, $this->response);
        self::$app = $this;
        $primaryValue = $this->session->get("user");
        if ($primaryValue) {
            $primaryKey = $this->userClass::primaryKey();
            $this->user = $this->userClass::findOne([$primaryKey => $primaryValue]);
        } else {
            unset($this->user);
            // $this->response->redirect('./login/');
        }
    }

    public static function isGuest()
    {
        return !isset(self::$app->user);
    }

    public function run()
    {
        try {
            echo $this->router->resolve();
        } catch (\Exception $e) {
            $this->response->setStatusCode($e->getCode());
            echo $this->view->renderView('_error', ['exception' => $e]);
        }
    }

    public function getController(): \azadkh\mvcframework\Controller
    {
        return $this->controller;
    }


    public function setController(\azadkh\mvcframework\Controller $controller): void
    {
        $this->controller = $controller;
    }

    public function login(UserModel $user)
    {
        $this->user = $user;
        $primaryKey = $user->primaryKey();
        $primaryValue = $user->{$primaryKey};
        $this->session->set('user', $primaryValue);
        return true;
    }

    public function logout()
    {
        unset($this->user);
        $this->session->remove('user');
    }
}