<?php
namespace App\Models;

use Phalcon\Mvc\Model;

/**
 * PasswordChanges
 * Register when a user changes his/her password
 */
class PasswordChanges extends Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var integer
     */
    public $id_user;

    /**
     *
     * @var string
     */
    public $ip_address;

    /**
     *
     * @var string
     */
    public $useragent;

    /**
     *
     * @var integer
     */
    public $dt_create;

    /**
     * Before create the user assign a password
     */
    public function beforeValidationOnCreate()
    {
        // Timestamp the confirmaton
        $this->dt_create = time();
    }

    public function initialize()
    {

        $this->setSource('admin_password_changes');

        $this->belongsTo('id_user', __NAMESPACE__ . '\Users', 'id', [
            'alias' => 'user'
        ]);
    }
}
