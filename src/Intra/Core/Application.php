<?php
namespace Intra\Core;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Application
{
    /**
     * @var $view View
     */
    public static $view;

    public static function run($control_dir, $view_dir, $request = null)
    {
        self::$view = new View($view_dir);

        if ($request === null) {
            $request = Request::createFromGlobals();
            $request->enableHttpMethodParameterOverride();
        }
        $query = $request->getPathInfo();
        $response = new TwigResponse;

        $control = new QueryProcessor($control_dir, $query, $request, $response);
        $return_by_controler = $control->__act();
        if ($return_by_controler === false) {
            return false;
            #throw new Exception('control action error');
        }
        if ($return_by_controler instanceof Response) {
            $return_by_controler->prepare($request);
            $return_by_controler->send();
            exit;
        }
        if (!is_array($return_by_controler)) {
            exit((string)$return_by_controler);
        }
        $return_by_controler = array_merge($response->get(), $return_by_controler);

        $query = $control->getRoutedQuery();
        if (!self::$view->isExist($query)) {
            throw new \Exception('no view');
        }
        echo self::$view->render($query, $return_by_controler);

        return true;
    }
}
