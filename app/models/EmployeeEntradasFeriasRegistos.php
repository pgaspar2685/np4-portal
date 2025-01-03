<?php

namespace CRM\Models;

use CRM\Debug\debug;

/**
 * Esta classe vai servir como registo individual de todas as mudanças
 */
class EntradasFeriasRegistos extends \Phalcon\Mvc\Model
{
    /** @var int */
    public $id;

    /** @var string */
    public $username;

    /** @var string */
    public $changes;

    /** @var string */
    public $ferias;

    /** @var int */
    public $ano;

    public function initialize()
    {
        $this->setSource('employee_ferias_registos');
        //$this->keepSnapshots(true);
    }

    public function beforeValidationOnUpdate()
    {
        $this->datam = date('Y-m-d H:i:s');
        $this->userm = $this->getDI()
            ->get('auth')
            ->getUsername();
    }
public function beforeValidationOnCreate()
    {
        $this->datam = date('Y-m-d H:i:s');
        $this->userm = $this->getDI()
            ->get('auth')
            ->getUsername();
    }

    public function prepareSave()
    {

//        $ferias = EntradasRegistos::find([
//            'columns' => 'data_dia',
//            "date_part('year', data_dia) = :ano: AND username = :username: AND id_tipo = :id_tipo:",
//            "bind" => [
//                "ano" => $this->ano,
//                "username" => $this->username,
//                "id_tipo" => EntradasTipos::TIPO_FERIAS
//            ],
//            "order" => "data_dia",
//        ]);

        $ferias = EntradasRegistos::query()
            ->columns([
                "data_dia"
            ])
            ->where("date_part('year', data_dia) = :ano: AND username = :username: AND id_tipo = :id_tipo:", [
                "ano" => $this->ano,
                "username" => $this->username,
                "id_tipo" => EntradasTipos::TIPO_FERIAS
            ])
            ->orderBy('data_dia')
            ->columnResults("data_dia");

        $ferias_parcial = EntradasRegistos::query()
            ->columns([
                "data_dia"
            ])
            ->where("date_part('year', data_dia) = :ano: AND username = :username: AND id_tipo = :id_tipo:", [
                "ano" => $this->ano,
                "username" => $this->username,
                "id_tipo" => EntradasTipos::TIPO_FERIAS_PARCIAL
            ])
            ->orderBy('data_dia')
            ->columnResults("data_dia");

        $this->ferias = json_encode([
            'ferias' => $ferias,
            'ferias_parcial' => $ferias_parcial,
        ]);


    }
}