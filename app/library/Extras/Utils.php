<?php

namespace App\Extras;

use Closure;
use App\Debug\debug;
use \Datetime;
use Exception;
use Phalcon\Mvc\Model\ResultsetInterface;
use Phalcon\Text;

class Utils
{

    /**
     * Metodo utilitário para verificar se determinada chave existe
     * - se sim: devolve o valor do array
     * - se nao: devolve o default
     *
     * => $array[$key] / $default
     *
     *
     * @param array $array
     * @param string $key
     * @param mixed $default
     * @return array|string|int|bool valor do array ou default
     */
    public static function readValue($array, $key, $default = "")
    {
        if (!is_array($array)) {
            return $default;
        } else {
            return array_key_exists($key, $array) && $array[$key] !== "" ? $array[$key] : $default;
        }
    }

    /**
     * Como o readValue mas para arrays com duas profundidades
     * => $array[$key][$key2] / $default
     *
     * @param array $array
     * @param string $key
     * @param string $key2
     * @param string $default
     */
    public static function readChildValue($array, $key, $key2, $default = "")
    {
        if (!is_array($array)) {
            return $default;
        } elseif (array_key_exists($key, $array) && $array[$key] != "") {
            if (array_key_exists($key2, $array[$key]) && $array[$key] != "") {
                return $array[$key][$key2];
            } else {
                return $default;
            }
        } else {
            return $default;
        }
    }

    /**
     * Metodo utilitário para verificar se determinada chave existe
     * - se sim: devolve o valor do array
     * - se nao: devolve o default
     * => a diferenca para o readValue eh q este permite especificar varias chaves q dependendo se tem valor
     * apresentam o resultado respectivo
     *
     * @param array $array
     * @param string $key
     * @param mixed $default
     * @return valor do array ou default
     */
    public static function readAnyValue($array, $keys, $default = "")
    {
        if (!is_array($array)) {
            return $default;
        } else {
            foreach ($keys as $key) {
                if (array_key_exists($key, $array) && $array[$key] != "") {
                    return $array[$key];
                }
            }
            return $default;
        }
    }

    public static function isValidDate($date, $format = 'Y-m-d')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }

    public static function isValidDateTime($date, $format = 'Y-m-d H:i')
    {
        $date = substr($date, 0, 16);

        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }

    public static function arrayToParams($array)
    {
        $str = "";
        if (is_array($array) && count($array)) {
            foreach ($array as $key => $val) {
                $str .= " " . $key . "=\"" . $val . "\"";
            }
        }
        return $str;
    }

    /**
     * Operacao de adicionar o valor do IVA
     *
     * @param float $valor
     * @param float $percentagem
     * @return float
     */
    public static function calcAplicaIVA($valor, $percentagem)
    {
        return $valor * (1 + ($percentagem / 100));
    }

    /**
     * Operacao de retirar o valor do IVA
     *
     * @param float $valor
     * @param float $percentagem
     * @return float
     */
    public static function calcRetiraIVA($valor, $percentagem)
    {
        return $valor / (1 + ($percentagem / 100));
    }

    public static function base64_url_encode($input)
    {
        return strtr(base64_encode($input), '+/=', '._-');
    }

    public static function base64_url_decode($input)
    {
        return base64_decode(strtr($input, '._-', '+/='));
    }

    /**
     * Permite fazer append de uma condição
     *
     * @param array $parameters
     * @param string $condition
     * @return string|unknown
     */
    public static function appendCondition(&$parameters, $condition)
    {
        if (!isset($parameters['conditions']) || trim($parameters['conditions']) == "") {
            $parameters['conditions'] = $condition;
        } else {
            $parameters['conditions'] .= " AND " . $condition;
        }

        return $parameters;
    }

    /**
     * Truncar texto (remove line breaks e strip_tags)
     *
     * @param string $text
     * @param int $chars
     *            default 255
     * @return string
     */
    public static function truncate($text, $chars = 255)
    {
        $text = strip_tags(str_replace(array(
            "\n",
            "\r"
        ), '', $text));

        if (mb_strlen($text) <= $chars) {
            return $text;
        }

        return mb_strimwidth($text, 0, $chars, "...");
    }

    /**
     * php delete function that deals with directories recursively
     *
     * @todo isolar a directoria do argumento $target !!!!!!!!!!!!!!!!!!!!!!!!!!!!!
     *
     * https://paulund.co.uk/php-delete-directory-and-files-in-directory
     */
    public static function deleteDirWithFiles($target)
    {
        if (is_dir($target)) {
            $files = glob($target . '*', GLOB_MARK); // GLOB_MARK adds a slash to directories returned

            foreach ($files as $file) {
                self::deleteDirWithFiles($file);
            }

            rmdir($target);
        } elseif (is_file($target)) {
            unlink($target);
        }
    }

    public static function getStringBetween($string, $start, $end)
    {
        $string = ' ' . $string;
        $ini = strpos($string, $start);
        if ($ini == 0) {
            return '';
        }
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
        return substr($string, $ini, $len);
    }

    /**
     * Retorna o numero de casas decimais
     * @param $value
     * Hugo - 28/10/2020
     * @return int
     */
    public static function getDecimalPlaces($value)
    {
        return strlen(substr(strrchr((float)$value, "."), 1));
    }

    /**
     * Converte um intervalo em segundos
     * @param $time
     * Hugo - 28/10/2021
     * @return float|int|mixed|string
     */
    public static function timeToSeconds($time)
    {
        list($horas, $minutos, $segundos) = explode(":", $time);

        if ($horas[0] == '-') {
            $op = -1;
            $horas = ltrim($horas, '-');
        } else {
            $op = 1;
        }

        return (($horas * 60 * 60) + ($minutos * 60) + $segundos) * $op;
    }

    /**
     * Converte segundos em intervalo
     * @param $ss - segundos
     * Hugo - 02/11/2021
     * @return string
     */
    public static function secondsToTime($ss)
    {
        if ($ss < 0) {
            $op = "-";
            $ss *= -1;
        } else {
            $op = "";
        }

        $s = $ss % 60;
        $m = floor(($ss % 3600) / 60);
        $h = floor(($ss % 86400) / 3600);

        return $op . sprintf("%02d:%02d:%02d", $h, $m, $s);
    }

    /**
     * Function to generate color
     * @param  mixed $param
     * @return string|array
     */
    public static function generateColor($param)
    {
        if (is_array($param)) {
            return Helpers::mapArray(function($innerItem){
                return static::generateColor($innerItem);
            }, $param);
        }
        return "#" . substr(md5($param), 0, 6);
    }

    /**
     * Function to change a given array into a mapped one
     * @param array|ResultsetInterface $array
     * @param Closure $callback
     * @return array
     * @deprecated
     */
    public static function mapArray($array, Closure $callback) : array
    {
        $result = [];
        foreach ($array as $key => $value) {
            $mapArray = call_user_func($callback, $value, $key);
            if (!isset($mapArray["value"])) {
                throw new Exception("Invalid array response : You need to set at least value key on your array");
            }
            $result[$mapArray["key"] ?? $key] = $mapArray['value'];
        }
        return $result;
    }
    /**
     * Function to humanize string, this means we will transform a raw string like this
     * SELLER_JOAO (T_SS) -> Seller Joao (T SS)
     * @param string $string
     * @param array $replaceMap Map to allow the translation of character
     * @return string
     */
    public static function humanizeString(?string $string, ?array $replaceMap = []) : string {
        $words = Text::humanize($string);
        return strtr(preg_replace_callback('/\b(?<!\()([^() ]+)\b(?!\))/', function($matches){
            [$word] = $matches;
            if (strlen($word) <= 2) {
                return $word;
            }
            return ucwords(mb_strtolower($word));
        }, $words), $replaceMap);
    }
    /**
     * Output CSV
     *
     * Aceita um array de rows (com key=>val)
     * na primeira linha apresenta as keys do array
     *
     * @param string $filename - nome do csv to download
     * @param array $array - dados
     * Hugo - 10/02/2022
     * @return void|null
     */
    public static function downloadArrayToCSV(string $filename, array $array)
    {
        // disable caching
        $now = gmdate("D, d M Y H:i:s");
        header("Expires: Tue, 03 Jul 2001 06:00:00 GMT"); // <-- ??? @fixme
        header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
        header("Last-Modified: {$now} GMT");

        header("Accept-Charset: UTF-8");
        header('Content-Encoding: UTF-8');

        // force download
        header("Content-Type: application/force-download; charset=UTF-8");
        header("Content-Type: application/octet-stream; charset=UTF-8");
        header("Content-Type: application/download; charset=UTF-8");

        // disposition / encoding on response body
        header("Content-Disposition: attachment;filename={$filename}");
        header("Content-Transfer-Encoding: binary");
        echo "\xEF\xBB\xBF";

        if (count($array) == 0) {
            return null;
        }

        ob_start();
        $df = fopen("php://output", 'w');
        fputcsv($df, array_keys(reset($array)), ";", "\"");

        foreach ($array as $row) {

            // remover new lines
            foreach ($row as $field => $value) {
                $row[$field] = str_replace(";", " -- ", trim(preg_replace('/\s+/', ' ', $value)));
            }

            fputcsv($df, $row, ";", "\"");
        }
        fclose($df);
        echo ob_get_clean();
    }

    /**
     * Parse dos parameters para ter em conta campos do tipo array
     * @param $parameters
     * @param $field
     * @param $alias
     * @author Hugo Costa
     * @date 07/07/2022 15:16
     */
    public static function processParametersWithArrayField(&$parameters, $field, $alias = '')
    {
        if (stripos($parameters['conditions'], $field) !== false) {
            $parameters['conditions'] = str_replace("[$field] = :$field:",
                "cardinality (array_intersect(" . self::wrapText($alias, '', '.') . "$field, :$field: ) ) > 0", $parameters['conditions']);
            $parameters['bind']['tags'] = '{' . implode(',', $parameters['bind']['tags']) . '}';

        } else {
            unset($parameters['bind'][$field]);
        }
    }

    /**
     * Método utilitario para colocar texto antes e depois, no caso deste n ser vazio
     * senao colocar o default
     *
     * @param unknown_type $text
     * @param unknown_type $antes
     * @param unknown_type $depois
     * @param unknown_type $default
     */
    public static function wrapText($text, $htmlantes = "", $htmldepois = "", $default = "")
    {
        return trim($text) ? $htmlantes . $text . $htmldepois : $default;
    }

    /**
     * Removes the preceeding or proceeding portion of a string
     * relative to the last occurrence of the specified character.
     * The character selected may be retained or discarded.
     *
     * Example usage:
     * <code>
     * $example = 'http://example.com/path/file.php';
     * $cwd_relative[] = cut_string_using_last('/', $example, 'left', true);
     * $cwd_relative[] = cut_string_using_last('/', $example, 'left', false);
     * $cwd_relative[] = cut_string_using_last('/', $example, 'right', true);
     * $cwd_relative[] = cut_string_using_last('/', $example, 'right', false);
     * foreach($cwd_relative as $string) {
     *     echo "$string <br>".PHP_EOL;
     * }
     * </code>
     *
     * Outputs:
     * <code>
     * http://example.com/path/
     * http://example.com/path
     * /file.php
     * file.php
     * </code>
     *
     * @param string $character the character to search for.
     * @param string $string the string to search through.
     * @param string $side determines whether text to the left or the right of the character is returned.
     * Options are: left, or right.
     * @param bool $keep_character determines whether or not to keep the character.
     * Options are: true, or false.
     * @return string
     */
    function cutStringUsingLast($character, $string, $side, $keep_character = true)
    {
        $offset = ($keep_character ? 1 : 0);
        $whole_length = strlen($string);
        $right_length = (strlen(strrchr($string, $character)) - 1);
        $left_length = ($whole_length - $right_length - 1);
        switch ($side) {
            case 'left':
                $piece = substr($string, 0, ($left_length + $offset));
                break;
            case 'right':
                $start = (0 - ($right_length + $offset));
                $piece = substr($string, $start);
                break;
            default:
                $piece = false;
                break;
        }
        return ($piece);
    }

    /**
     * Calculo simples da percentagem
     * @param $current
     * @param $total
     * @author Hugo Costa
     * @date 22/07/2022 10:01
     * @return float|int
     */
    public static function percent($current, $total)
    {
        return $total > 0 ? round(($current * 100) / $total) : 0;
    }

    /**
     * Remover lixo do filename
     * @param string $filename
     * @return array|string|string[]|null
     * @author Hugo Costa
     * @date 29/09/2022 17:29
     */
    public static function cleanFilename(string $filename) : string
    {
        return preg_replace('/[^0-9A-Z_.]/i', '', str_ireplace(' ', '_', $filename));
    }

    /**
     * Retorna o ID na forma de diretoria folder split, exemplo: 1 -> 000/00/01
     * @param $id
     * @return string
     * @author Hugo Costa
     * @date 06/10/2022 10:38
     */
    public static function folderSplit($id) : string
    {
        $id = sprintf("%07d", $id);
        $id_a = substr($id, 0, 3);
        $id_b = substr($id, 3, 2);
        $id_c = substr($id, 5, 2);

        return '/' . $id_a . '/' . $id_b . '/' . $id_c;
    }

    public static function jsonResponse($code = 200, $message = null)
    {
        // clear the old headers
        header_remove();
        // set the actual code
        http_response_code($code);
        // set the header to make sure cache is forced
        header("Cache-Control: no-transform,public,max-age=300,s-maxage=900");
        // treat this as json
        header('Content-Type: application/json');
        $status = array(
            200 => '200 OK',
            400 => '400 Bad Request',
            422 => 'Unprocessable Entity',
            500 => '500 Internal Server Error'
        );
        // ok, validation error, or failure
        header('Status: ' . $status[$code]);
        // return the encoded json
        return json_encode(array(
            'status' => $code < 300, // success or not?
            'message' => $message
        ));
    }
}