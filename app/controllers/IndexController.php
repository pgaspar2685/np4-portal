<?php
namespace App\Controllers;

use App\Models\Clientes;
use \App\Models\Servicos;
use \App\Models\Users;
use \App\Models\Bookings;
use \App\Models\Folgas;

/**
 * Display the default index page.
 */
class IndexController extends ControllerBase
{

    public function initialize()
    {
        $this->view->setTemplateBefore('public');
    }
    /**
     * Default action. Set the public layout (layouts/public.volt)
     */
    public function indexAction()
    {
        $this->response->redirect('backoffice');
    }
}
