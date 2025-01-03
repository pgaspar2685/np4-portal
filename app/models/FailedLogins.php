<?php
namespace App\Models;

use Phalcon\Mvc\Model;

/**
 * FailedLogins
 * This model registers unsuccessfull logins registered and non-registered users have made
 */
class FailedLogins extends Model
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
    public $usersId;

    /**
     *
     * @var string
     */
    public $ip_address;

    /**
     *
     * @var integer
     */
    public $attempted;

    public function initialize()
    {
        $this->setSource('admin_failed_logins');

        $this->belongsTo('id_user', __NAMESPACE__ . '\Users', 'id', [
            'alias' => 'user'
        ]);
    }
}
