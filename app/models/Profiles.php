<?php
namespace App\Models;

use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Relation;

/**
 * App\Models\Profiles
 * All the profile levels in the application. Used in conjenction with ACL lists
 */
class Profiles extends Model
{

    /**
     * ID
     * @var integer
     */
    public $id;

    /**
     * Name
     * @var string
     */
    public $name;

    /**
     * Define relationships to Users and Permissions
     */
    public function initialize()
    {

        $this->setSource('admin_profiles');

        $this->hasMany('id', __NAMESPACE__ . '\Users', 'id_profile', [
            'alias' => 'users',
            'foreignKey' => [
                'message' => 'Profile cannot be deleted because it\'s used on Users'
            ]
        ]);

        $this->hasMany('id', __NAMESPACE__ . '\Permissions', 'id_profile', [
            'alias' => 'permissions',
            'foreignKey' => [
                'action' => Relation::ACTION_CASCADE
            ]
        ]);
    }
}
