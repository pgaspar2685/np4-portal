<?php

namespace App\Controllers;

use App\Forms\LoginForm;
use App\Forms\SignUpForm;
use App\Forms\ForgotPasswordForm;
use App\Auth\Exception as AuthException;
use App\Models\Users;
use App\Models\ResetPasswords;

/**
 * Controller used handle non-authenticated session actions like login/logout, user signup, and forgotten passwords
 */
class SessionController extends ControllerBase
{

    /**
     * Default action. Set the public layout (layouts/public.volt)
     */
    public function initialize()
    {
        $this->view->setTemplateBefore('login'); 
    }

    /**
     * Starts a session in the admin backend
     */
    public function loginAction()
    {
        $form = new LoginForm();

        $auth = $this->di->get('auth')->getIdentity();
        if (isset($auth["id"])) {
            return $this->response->redirect('backoffice');      
        }

        try {
            if (!$this->request->isPost()) {
                if ($this->auth->hasRememberMe()) {
                    return $this->auth->loginWithRememberMe();
                }
            } else {

                if ($form->isValid($this->request->getPost()) == false) {
                    
                    foreach ($form->getMessages() as $message) {
                        $this->flash->error($message);
                    }
                } else {

                    $this->auth->check([
                        'email' => trim($this->request->getPost('email')),
                        'password' => $this->request->getPost('password'),
                        'remember' => $this->request->getPost('remember')
                    ]);

                    return $this->response->redirect('backoffice'); 
                }
            }
        } catch (AuthException $e) {
            $this->flash->error($e->getMessage());
        }

        
        $email = "";
        if($this->request->getPost('email') != "") {
            $email = $this->request->getPost('email');
        }
        
        $this->view->setVar("email", $email);
        $this->view->form = $form;
    }

    /**
     * Closes the session
     */
    public function logoutAction()
    {
        $this->auth->remove();

        return $this->response->redirect('index');
    }
    
    public function nocacheAction($redirect = false)
    {
        $cache_dirs = [
            'metaData',
            'acl',
            'volt'
        ];
        
        foreach ($cache_dirs as $dir) {
            
            $path = rtrim($this->config->application->cacheDir, "/") . "/" . $dir;
            
            $files = glob($path . '/*'); // get all file names
            $deleted = 0;
            
            foreach ($files as $file) { // iterate files
                
                if (is_file($file)) {
                    unlink($file); // delete file
                    $deleted ++;
                }
            }
            
            echo ("cache delete [" . $dir . "] - files:" . count($files) . " deleted:" . $deleted . "<br>");
        }
        
        // $this->modelsCache->flush();
        // $this->viewCache->flush();
        echo ("ok deleted <br>");
    }
}
