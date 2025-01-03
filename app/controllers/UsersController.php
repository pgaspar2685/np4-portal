<?php
namespace App\Controllers;

use Phalcon\Tag;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use App\Forms\ChangePasswordForm;
use App\Forms\UsersForm;
use App\Forms\UserConfigForm;
use App\Models\Users;
use App\Models\PasswordChanges;
use App\Models\UserPayment;
use App\Models\UserOfertas;
use App\Models\TelegramChannel;

/**
 * App\Controllers\UsersController
 * CRUD to manage users
 */
class UsersController extends ControllerBase
{

    public function initialize()
    {
        parent::initialize();
        //die("ola");
        $this->view->setTemplateBefore('private');
    }

    /**
     * Default action, shows the search form
     */
    public function indexAction()
    {
        $this->persistent->conditions = null;
        $this->view->form = new UsersForm();
    }

    /**
     * Searches for users
     */
    public function searchAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'App\Models\Users', $this->request->getPost());
            $this->persistent->searchParams = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = [];
        if ($this->persistent->searchParams) {
            $parameters = $this->persistent->searchParams;
        }

        $users = Users::find($parameters);
        if (count($users) == 0) {
            $this->flash->notice("The search did not find any users");
            return $this->dispatcher->forward([
                "action" => "index"
            ]);
        }

        $paginator = new Paginator([
            "data" => $users,
            "limit" => 10,
            "page" => $numberPage
        ]);

        $this->view->page = $paginator->getPaginate();
    }

    /**
     * Creates a User
     */
    public function createAction()
    {
        $form = new UsersForm(null);

        if ($this->request->isPost()) {

            if ($form->isValid($this->request->getPost()) == false) {
                
                foreach ($form->getMessages() as $message) {
                    $this->flash->error($message);
                }
                
            } else {

                $user = new Users([
                    'name' => $this->request->getPost('name', 'striptags'),
                    'profilesId' => $this->request->getPost('profilesId', 'int'),
                    'email' => $this->request->getPost('email', 'email')
                ]);

                if (!$user->save()) {
                    $this->flash->error($user->getMessages());
                } else {

                    $this->flash->success("User was created successfully");

                    // Tag::resetInput();
                }
            }
        }

        $this->view->form = $form;
    }

    /**
     * Saves the user from the 'edit' action
     */
    public function editAction($id)
    {
        $user = Users::findFirstById($id);

        // \App\Extras\Debug::dump($user->toArray());

        if (!$user) {
            $this->flash->error("User was not found");
            return $this->dispatcher->forward([
                'action' => 'index'
            ]);
        }

        if ($this->request->isPost()) {

            $user->assign([
                'name' => $this->request->getPost('name', 'striptags'),
                'id_profile' => $this->request->getPost('profilesId', 'int'),
                'email' => $this->request->getPost('email', 'email'),
                'fl_banned' => $this->request->getPost('banned'),
                'fl_suspended' => $this->request->getPost('suspended'),
                'fl_active' => $this->request->getPost('active')
            ]);

            $form = new UsersForm($user, [
                'edit' => true
            ]);

            if ($form->isValid($this->request->getPost()) == false) {
                
                foreach ($form->getMessages() as $message) {
                    $this->flash->error($message);
                }
                
            } else {

                if (!$user->save()) {
                    $this->flash->error($user->getMessages());
                } else {

                    $this->flash->success("User was updated successfully");

                    Tag::resetInput();
                }
            }
        }

        $this->view->user = $user;

        $this->view->form = new UsersForm($user, [
            'edit' => true
        ]);
        
        $this->view->setVar('id', $id);
    }

    /**
     * Deletes a User
     *
     * @param int $id
     */
    public function deleteAction($id)
    {
        $user = Users::findFirstById($id);
        if (!$user) {
            $this->flash->error("User was not found");
            return $this->dispatcher->forward([
                'action' => 'index'
            ]);
        }

        if (!$user->delete()) {
            $this->flash->error($user->getMessages());
        } else {
            $this->flash->success("User was deleted");
        }

        return $this->dispatcher->forward([
            'action' => 'index'
        ]);
    }

    /**
     * Users must use this action to change its password
     */
    public function changePasswordAction()
    {
        $form = new ChangePasswordForm();

        if ($this->request->isPost()) {

            if (!$form->isValid($this->request->getPost())) {

                foreach ($form->getMessages() as $message) {
                    $this->flash->error($message);
                }
            } else {

                $user = $this->auth->getUser();

                $user->password = $this->security->hash($this->request->getPost('password'));
                $user->mustChangePassword = 'N';
                $user->save();

                $passwordChange = new PasswordChanges();
                $passwordChange->id_user = $user->id;
                $passwordChange->ip_address = $this->request->getClientAddress();
                $passwordChange->useragent = $this->request->getUserAgent();

                if (!$passwordChange->save()) {
                    $this->flash->error($passwordChange->getMessages());
                } else {

                    $this->flash->success('Your password was successfully changed');
                    $this->view->setvar("password_changed",true);
                    // Tag::resetInput();
                }
            }
        }

        $this->view->form = $form;
    }
    
}
