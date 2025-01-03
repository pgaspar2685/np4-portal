<?php

namespace CRM\Models;

use CRM\Extras\Blameable;

class EntradasFerias extends BaseModel
{
    public function initialize()
    {
        $this->setSource('employee_ferias');
        $this->belongsTo("username", __NAMESPACE__ . '\Users', "username", [
            'alias' => 'Users',
            'reusable' => true
        ]);
    }
}