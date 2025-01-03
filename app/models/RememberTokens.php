<?php
namespace App\Models;

use Phalcon\Mvc\Model;

/**
 * RememberTokens
 * Stores the remember me tokens
 */
class RememberTokens extends Model
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
    public $token;

    /**
     *
     * @var string
     */
    public $userAgent;

    /**
     *
     * @var integer
     */
    public $createdAt;

    /**
     * Before create the user assign a password
     */
    public function beforeValidationOnCreate()
    {
        // Timestamp the confirmaton
        $this->createdAt = time();
    }

    public function initialize()
    {

        $this->setSource('admin_remember_tokens');

        $this->belongsTo('id_user', __NAMESPACE__ . '\Users', 'id', [
            'alias' => 'user'
        ]);
    }
}
