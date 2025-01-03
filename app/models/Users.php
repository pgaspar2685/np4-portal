<?php
namespace App\Models;

use Phalcon\Mvc\Model;
use Phalcon\Validation;
use Phalcon\Validation\Validator\Uniqueness;

/**
 * App\Models\Users
 * All the users registered in the application
 */
class Users extends Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
     */
    public $ds_admin_name;

    /**
     *
     * @var string
     */
    public $email;

    /**
     *
     * @var string
     */
    public $password;

    /**
     *
     * @var string
     */
    public $cd_type;

    /**
     *
     * @var string
     */
    public $id_profile;

    /**
     *
     * @var integer
     */
    public $id_team;

    /**
     *
     * @var string
     */
    public $ds_admin_owner;

    /**
     *
     * @var integer
     */
    public $fl_active;
    
    /**
     *
     * @var datetime
     */
    public $dt_lastlogin;

    public function initialize()
    {

        $this->setSource('admin');

        $this->belongsTo('id_profile', __NAMESPACE__ . '\Profiles', 'id', [
            'alias' => 'profile',
            'reusable' => true
        ]);

        // $this->hasMany('id', __NAMESPACE__ . '\SuccessLogins', 'id_user', [
        //     'alias' => 'successLogins',
        //     'foreignKey' => [
        //         'message' => 'User cannot be deleted because he/she has activity in the system'
        //     ]
        // ]);

        // $this->hasMany('id', __NAMESPACE__ . '\PasswordChanges', 'id_user', [
        //     'alias' => 'passwordChanges',
        //     'foreignKey' => [
        //         'message' => 'User cannot be deleted because he/she has activity in the system'
        //     ]
        // ]);

        // $this->hasMany('id', __NAMESPACE__ . '\ResetPasswords', 'id_user', [
        //     'alias' => 'resetPasswords',
        //     'foreignKey' => [
        //         'message' => 'User cannot be deleted because he/she has activity in the system'
        //     ]
        // ]);
        
    }
}
