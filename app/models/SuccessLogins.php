<?php
namespace App\Models;

use Phalcon\Mvc\Model;

/**
 * SuccessLogins
 * This model registers successfull logins registered users have made
 */
class SuccessLogins extends Model
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
    public $userAgent;

    public function initialize()
    {
        $this->setSource('admin_success_logins');

        $this->belongsTo('id_user', __NAMESPACE__ . '\Users', 'id', [
            'alias' => 'user'
        ]);
    }
}
