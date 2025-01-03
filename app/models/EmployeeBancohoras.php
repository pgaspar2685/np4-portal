<?php
namespace CRM\Models;

class Bancohoras extends BaseModel
{
    public function initialize()
    {
        $this->setSource('employee_bh');

        $this->belongsTo("username", __NAMESPACE__ . '\Users', "username", [
            'alias' => 'Users',
            'reusable' => true
        ]);
    }
}