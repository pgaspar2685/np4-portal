<?php
namespace CRM\Models;

class EntradasTipos extends BaseModel
{
    const TIPO_TRABALHO_LOCAL = 1;
    const TIPO_TELETRABALHO = 2;
    const TIPO_FERIAS = 3;
    const TIPO_FERIAS_PARCIAL = 4;
    const TIPO_FALTA_JUSTIFICADA = 5;
    const TIPO_BANCO_HORAS = 6;
    const TIPO_FALTA_INJUSTIFICADA = 7;
    const TIPO_FALTA_DIA_INJUSTIFICADO = 8;
    const TIPO_FERIADO = 9;
    const TIPO_DIA_JUSTIFICADO = 10;

    public function initialize()
    {
        $this->setSource('employee_tipos');
    }
}