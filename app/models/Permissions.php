<?php
namespace App\Models;

use Phalcon\Mvc\Model;

/**
 * Permissions
 * Stores the permissions by profile
 */
class Permissions extends Model
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
    public $id_profile;

    /**
     *
     * @var string
     */
    public $resource;

    /**
     *
     * @var string
     */
    public $action;

    public function initialize()
    {
        $this->setSource('admin_permissions');

        $this->belongsTo('id_profile', __NAMESPACE__ . '\Profiles', 'id', [
            'alias' => 'profile'
        ]);
    }
}
