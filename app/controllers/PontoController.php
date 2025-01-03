<?php

namespace CRM\Controllers;

use \App\Models\EntradasTipos;
use \App\Extras\Debug;
use \App\Models\Users;
use \App\Models\EntradasRegistos;

class PontoController extends BaseController
{
    private $terminal = 0;

    public function initialize()
    {
        $this->view->setTemplateAfter('templates/ponto');
        $this->view->setRenderLevel(\Phalcon\Mvc\View::LEVEL_AFTER_TEMPLATE);

        if ($this->cookies->has("terminal")) {
            $this->terminal = 1;
        }
    }

    public function indexAction($token = "")
    {
        $data = $this->db->query("SELECT
            distinct on (username) username ,
            hora_inicio,
            hora_fim
        FROM
            ca_registos 
        WHERE data_dia=now()::DATE 
        AND id_tipo = '" . EntradasTipos::TIPO_TRABALHO_LOCAL . "' 
        AND deleted = '0'
        ORDER BY
            username,
            id desc");
        $data->setFetchMode(\Phalcon\Db::FETCH_ASSOC);
        $results = $data->fetchAll();

        $data = [];
        foreach ($results as $row) {
            $data[$row['username']] = [
                'hora_inicio' => $row['hora_inicio'],
                'hora_fim' => $row['hora_fim'],
            ];
        }

        if ($token == 'YAC355D9221') {
            $this->terminal = 1;
            $this->cookies->set("terminal", 1, (time() * 1000 * 60 * 60 * 24 * 365));
        }

        $this->view->setVar("last_entry", $data);
        $this->view->setVar("users", Users::find(["controlar_acessos='1' AND dispensa_entrada = '0' AND activo='1'", "order" => "nome"]));

        $this->setTerminalIdBar();
    }

    private function setTerminalIdBar()
    {
        $this->view->setVar("id_bar", ($this->terminal ? "terminal" : "guest") . " :: " . $_SERVER['REMOTE_ADDR']);
    }

    public function serverAction($sec = "")
    {
        if ($_SERVER['REMOTE_ADDR'] == '127.0.0.1' || substr($_SERVER['REMOTE_ADDR'], 0, 5) == '10.10') {
            debug::dump($_SERVER);
        }
    }

    /**
     * Controla a acção de dar entrada ou saída (segundo passo)
     * @param $username
     * @param false $accao
     * PGaspar - 2025-01-03
     */
    public function marcarAction($username, $accao = false)
    {
        if ($accao) {

            $registo = EntradasRegistos::findFirst([
                "username = :username: AND data_dia = :data_dia: AND id_tipo = :id_tipo: AND deleted = '0'",
                "bind" => [
                    'username' => $username,
                    'data_dia' => date('Y-m-d'),
                    'id_tipo' => EntradasTipos::TIPO_TRABALHO_LOCAL
                ],
                "order" => "id DESC"
            ]);

            if ($accao == 'in') {
                //debug::dump($registo->toArray());
                if ($registo) {

                    if ($registo->hora_fim && $registo->hora_inicio == "") {
                        $this->flashSession->warning("Já foi dada entrada. Tem a certeza que pretende marcar novamente a entrada?");
                        $this->outputJSON([
                            'success' => true,
                            'redirect_url' => "/ponto/confirmar/" . $username . "/" . $accao
                        ]);
                    } elseif ($registo->hora_fim == "" && $registo->hora_inicio) {
                        $this->flashSession->warning("Já foi dada entrada. Tem a certeza que pretende marcar novamente a entrada?");
                        $this->outputJSON([
                            'success' => true,
                            'redirect_url' => "/ponto/confirmar/" . $username . "/" . $accao
                        ]);
                    }


                }

                $registo = new EntradasRegistos();
                $registo->username = $username;
                $registo->id_tipo = 1;
                $registo->data_dia = date('Y-m-d');
                $registo->hora_inicio = 'now()';
                $registo->terminal = $this->terminal ? '1' : '0';

            } else {


                if (!$registo || $registo->hora_fim != "") {
                    $this->flashSession->warning("Não existe entrada data. Tem a certeza que pretende marcar a saída?");
                    //return $this->response->redirect("/ponto/confirmar/" . $username . "/" . $accao);
                    $this->outputJSON([
                        'success' => true,
                        'redirect_url' => "/ponto/confirmar/" . $username . "/" . $accao
                    ]);
                }

                $registo->hora_fim = "now()";
            }

            if (!$registo->save()) {
                die("ERRO!");
            }
            //$this->response->redirect("/ponto/index");
            $this->outputJSON([
                'success' => true,
                'redirect_url' => "/ponto"
            ]);
        }
        $user = Users::findFirstByUsername($username);
        $this->view->setVar("user", $user);
        $this->view->setVar("username", $user->username);
    }

    /**
     * Controla a acção de dar entrada ou saída (segundo passo)
     * @param $username
     * @param false $accao
     * PGaspar - 2025-01-03
     */
    public function confirmarAction($username, $accao = false, $confirm = false)
    {
        if ($confirm) {
            $registo = new EntradasRegistos();
            $registo->id_tipo = 1;
            $registo->username = $username;
            $registo->terminal = $this->terminal ? '1' : '0';
            $registo->data_dia = date('Y-m-d');

            if ($accao == 'in') {
                $registo->hora_inicio = 'now()';
            } else {
                $registo->hora_fim = 'now()';
            }

            if (!$registo->save()) {
                die("ERRO!");
            }
            $this->outputJSON([
                'success' => true,
                'redirect_url' => "/ponto"
            ]);
            //$this->response->redirect("/ponto/index");
        }
        $user = Users::findFirstByUsername($username);

        $this->view->setVar("accao", $accao);
        $this->view->setVar("user", $user);
        $this->view->setVar("username", $user->username);
    }
}