<?php
namespace CRM\Models;

class EntradasDiario extends BaseModel
{
    public function initialize()
    {
        //$this->setConnectionService('databaseEngine');
        $this->setSource('employee_diario');

        $this->belongsTo("username", __NAMESPACE__ . '\Users', "username", [
            'alias' => 'Users',
            'reusable' => true
        ]);
        // $this->addBehavior(new Blameable());
    }
}