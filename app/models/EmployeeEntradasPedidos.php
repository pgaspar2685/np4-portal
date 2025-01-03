<?php

namespace CRM\Models;

use CRM\Models\EntradasTipos;

class EntradasPedidos extends BaseModel
{
    const ACCAO_INSERIR = 'i';
    const ACCAO_MODIFICAR = 'u';
    const ACCAO_APAGAR = 'd';

    public function initialize()
    {
        //$this->setConnectionService('databaseEngine');
        $this->setSource('employee_pedidos');

        $this->hasOne("id_tipo", __NAMESPACE__ . '\EntradasTipos', "id", [
            'alias' => 'EntradasTipos',
            'reusable' => true
        ]);
        $this->belongsTo("username", __NAMESPACE__ . '\Users', "username", [
            'alias' => 'Users',
            'reusable' => true
        ]);
        // $this->addBehavior(new Blameable());
    }

    public function afterCreate()
    {
        if ($this->id_tipo == EntradasTipos::TIPO_TELETRABALHO) {

            $body = 'Pedido de teletrabalho para o dia ' . $this->data_dia . ' das ' . $this->hora_inicio . ' ás ' . $this->hora_fim . '.<br>';
            $body .= '@'.$this->username;

            if ($this->obs) {
                $body .= '<br><p>Observações:</p>' . $this->obs;
            }

            $body .= '<br><br><small style="color:gray">(gerado automaticamente via TEAM)</small>';

            // enviar um email
            $this->getDI()->getMail()->send('rh@lvengine.com', 'Pedido de teletrabalho', 'pedido_teletrabalho',
                $this->toArray(), $body);
        }
    }
}