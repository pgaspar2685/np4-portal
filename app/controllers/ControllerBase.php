<?php
namespace App\Controllers;

use Phalcon\Mvc\Controller;
use Phalcon\Mvc\Dispatcher;
use App\Extras\Utils;

/**
 * ControllerBase
 * This is the base controller for all controllers in the application
 *
 * @property \App\Auth\Auth auth
 */
class ControllerBase extends Controller
{

    public $version = 1;

    /**
     * Execute before the router so we can determine if this is a private controller, and must be authenticated, or a
     * public controller that is open to all.
     *
     * @param Dispatcher $dispatcher
     * @return boolean
     */
    
    public function initialize()
    {
        
    }
    
    public function beforeExecuteRoute(Dispatcher $dispatcher)
    {
        date_default_timezone_set('Europe/Lisbon');
        
        $controllerName = $dispatcher->getControllerName();
        $actionName = $dispatcher->getActionName();
        $userId = 0;
        $userProfileId = 0;
        $userProfile = "";
        
        $this->view->setVar('controller', $controllerName);
        $this->view->setVar('action', $actionName);
        $this->view->setVar('title', $controllerName);
        $this->view->setVar('userId', $userId);
        $this->view->setVar('userProfileId', $userProfileId);
        $this->view->setVar('userProfile', $userProfile);
        $this->view->setVar('version', $this->version);
        
        // Only check permissions on private controllers
        if ($this->acl->isPrivate($controllerName)) {

            // Get the current identity
            $identity = $this->auth->getIdentity();
            
            $userProfile = $identity["profile"];
            $userName = $identity["name"];
            $userId = $this->auth->getUser()->id?? 0;
            
            if (isset($this->auth->getUser()->profilesId)) {
                $userProfileId = $this->auth->getUser()->id_profile;
            }

            $this->view->setVar('userId', $userId);
            $this->view->setVar('userName', $userName);
            $this->view->setVar('userProfileId', $userProfileId);
            $this->view->setVar('userProfile', $userProfile);
            
            // If there is no identity available the user is redirected to index/index
            if (!is_array($identity)) {

                // $this->flash->notice('You don\'t have access to this module: private');

                $dispatcher->forward([
                    'controller' => 'session',
                    'action' => 'login'
                ]);
                return false;
            }

            // Check if the user have permission to the current option
            $actionName = $dispatcher->getActionName();
            if (!$this->acl->isAllowed($identity['profile'], $controllerName, $actionName)) {

                $this->flash->notice('You don\'t have access to this module: ' . $controllerName . ':' . $actionName);

                if ($this->acl->isAllowed($identity['profile'], $controllerName, 'index')) {
                    $dispatcher->forward([
                        'controller' => $controllerName,
                        'action' => 'index'
                    ]);
                } else {
                    $dispatcher->forward([
                        'controller' => 'user_control',
                        'action' => 'index'
                    ]);
                }

                return false;
            }
        }
    }

    /**
     *
     * @param Dispatcher $dispatcher
     * @return unknown
     */
    public function afterExecuteRoute(Dispatcher $dispatcher)
    {
        $action = $dispatcher->getActionName();

        //needed to allow the phalcon to ad logs into debug bar
        // basicamente controlo se a accao tiver ajax eh pq n quero o tradicional result
        if (stripos($action, 'ajax') === false && $this->request->isAjax()) {

            $controller = $dispatcher->getControllerName();

            $this->view->setRenderLevel(\Phalcon\Mvc\View::LEVEL_BEFORE_TEMPLATE);
            $this->view->start();
            $this->view->render($controller, $action); // Pass a controller/action as parameters if required
            $this->view->finish();

            $return = array(
                'success' => true,
                'html' => str_replace(array(
                    "\n",
                    "\t",
                    "\r"
                ), "", $this->view->getContent())
            );

            return $dispatcher->getActiveController()->outputJSON($return);
        }
    }

    /**
     * Output de um array em formato JSON
     *
     * @param array $array
     */
    public function outputJSON($array)
    {
        header("Content-type: application/x-json");
        header("ExecTime: " . (microtime(true) - EXEC_TIME_START));
        echo json_encode($array);
        exit();
    }
    
    /**
     * Método Genérico de resposta JSON
     *
     * @param bool $success
     *            : true | false
     * @param array $params
     * @return json
     */
    public function outputResponse($success, $params = null)
    {
        // if (count($this->response_params)) {
        //     $params = array_merge($params, $this->response_params);
        // }

        if (!$success) {
            if ($this->request->isAjax()) {
                return $this->outputJSON([
                    'success' => 'false',
                    'message' => Utils::readValue($params, 'message'),
                    'code' => Utils::readValue($params, 'code'),
                    'errors' => Utils::readValue($params, 'errors')
                ]);
            } else {

                // $_SESSION['e_message'] = Utils::readValue($params, 'message');

                die( Utils::readValue($params, 'message') );
            }
        } else {
            $params['success'] = true;
            if ($this->request->isAjax()) {
                return $this->outputJSON($params);
            } else {
                \App\Extras\Debug::dump($params);
            }
        }
    }

    /**
     * Faz o processamento do erro numa qualquer interacção com o modelo
     *
     * @param object $record
     * @return json response
     */
    protected function errorResponse($record)
    {
        $error_fields = [];
        foreach ($record->getMessages() as $message) {
            $error_fields[$message->getField()] = $message->getMessage();
        }

        $details = [
            'code' => 'save-errors',
            'errors' => $error_fields
        ];
        // $this->logger->error("ERRO", $details);

        return $this->outputResponse(false, $details);
    }
}
