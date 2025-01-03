<?php
namespace App\Controllers;

use App\Extras\Utils;

/**
 * Api Controller - rountines
 */
class ApiController extends ControllerBase
{

    public function apiAuth(){
        if (!isset($_SERVER['HTTP_APIKEY'])) {
            header('WWW-Authenticate: Basic realm="Np4GamePrd"');
            header('HTTP/1.0 401 Unauthorized');
            echo Utils::jsonResponse(400, "Unauthorized");
            exit;
        } else {
            if ($_SERVER['HTTP_APIKEY'] == md5("Np4apiX#24")) {
            } else {
                echo Utils::jsonResponse(400, "INVALID APIKEY");
                exit;
            }
        }
    }
}
