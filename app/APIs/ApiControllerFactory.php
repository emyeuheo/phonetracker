<?php
/**
 * Created by PhpStorm.
 * User: macintosh
 * Date: 8/5/15
 * Time: 2:07 PM
 */

namespace App\APIs;


use Illuminate\Http\Request;
use Mockery\CountValidator\Exception;

class ApiControllerFactory
{
    const API_NOT_FOUND = 1;
    const API_ACTION_NOT_FOUND = 2;
    private static $error_msg = array(
        self::API_NOT_FOUND => 'Api not found',
    );

    private $apiClassObject = null;
    private $httpMethod = 'get';

    private function getErrorMsg($code) {
        if(!isset(self::$error_msg[$code])) {
            return 'Unknown error';
        }
        return self::$error_msg[$code];
    }

    private function getAction($action) {
        $listMethod = get_class_methods($this->apiClassObject);
        foreach($listMethod as $method) {
            if(strtolower($method) == strtolower($this->httpMethod.$action)) {
                return $method;
            }
        }
        return null;
    }

    public function __construct($apiName = null, Request $request) {
        if($apiName == null || !class_exists(__NAMESPACE__.'\\'.ucfirst($apiName).'API')) {
            throw new Exception(self::getErrorMsg(ApiControllerFactory::API_NOT_FOUND), ApiControllerFactory::API_NOT_FOUND);
        }
        $className = __NAMESPACE__.'\\'.ucfirst($apiName).'API';
        $this->apiClassObject = new $className();

        if(get_class($this->apiClassObject) == 'BaseAPI' || $this->apiClassObject->isPublic == false) {
            throw new Exception(self::getErrorMsg(ApiControllerFactory::API_NOT_FOUND), ApiControllerFactory::API_NOT_FOUND);
        }

        $this->apiClassObject->setData($request->all());
        $this->httpMethod = $request->getMethod();
    }

    public function doAction($action = null, $parameters = array()) {
        if($action == null || ($apiAction = $this->getAction($action)) == null) {
            echo $apiAction;
            throw new Exception(self::getErrorMsg(ApiControllerFactory::API_NOT_FOUND), ApiControllerFactory::API_NOT_FOUND);
        }

        return call_user_func_array(array($this->apiClassObject, $apiAction), $parameters);
    }
}