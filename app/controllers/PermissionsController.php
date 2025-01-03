<?php
namespace App\Controllers;

use App\Models\Profiles;
use App\Models\Permissions;

/**
 * View and define permissions for the various profile levels.
 */
class PermissionsController extends ControllerBase
{

    /**
     * View the permissions for a profile level, and change them if we have a POST.
     */
    public function indexAction()
    {

        // die("aaa");
        $this->view->setTemplateBefore('private');

        if ($this->request->isPost()) {

            // Validate the profile
            $profile = Profiles::findFirstById($this->request->getPost('profileId'));

            if ($profile) {

                if ($this->request->hasPost('permissions') && $this->request->hasPost('submit')) {

                    // Deletes the current permissions
                    $profile->getPermissions()->delete();

                    // Save the new permissions
                    foreach ($this->request->getPost('permissions') as $permission) {

                        $parts = explode('.', $permission);

                        $permission = new Permissions();
                        $permission->id_profile = $profile->id;
                        $permission->resource = $parts[0];
                        $permission->action = $parts[1];

                        $permission->save();
                    }

                    $this->flash->success('Permissions were updated with success');
                }

                // Rebuild the ACL with
                $this->acl->rebuild();

                // Pass the current permissions to the view
                $this->view->permissions = $this->acl->getPermissions($profile);
            }

            $this->view->profile = $profile;
        }

        // Pass all the active profiles
        $this->view->profiles = Profiles::find([
            'fl_active = :fl_active:',
            'bind' => [
                'fl_active' => '1'
            ]
        ]);
    }
}
