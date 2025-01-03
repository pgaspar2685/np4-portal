<?php
namespace App\Extras;

use Phalcon\Session\Adapter\Files as SessionAdapter;

class Debug
{

    public $dumps = [];

    /**
     * Debug dump
     *
     * @param unknown_type $var            
     */
    public static function dump($var, $silent = true)
    {
        echo "<div style='-webkit-border-radius: 5px;-moz-border-radius: 5px;border-radius: 5px; background:#EFE299; color:#000; padding:10px; margin:10px 5px; font:14px arial; line-height: 20px;'>";
        if (is_array($var)) {
            echo "<pre>" . print_r($var, true) . "</pre>";
        } else {
            print_r($var);
        }
        
        if ($silent) {
            $info = "";
        } else {
            $debug = debug_backtrace(false, 3);
            $info = "<div style='font:10px verdana; text-align:center; padding: 0 10px; color:gray;'>" . $debug[0]['file'] . " (" . $debug[0]['line'] . ")</div>";
        }
        die("</div>" . $info);
    }

    /**
     * Debug show
     * Agora o segundo parametro funciona ah semelhança do print_r
     * em q controla o return ou echo
     *
     * @param mixed $var
     *            - string ou array para mostrar
     * @param bool $return
     *            - true:retorna o output false:echo
     * @return mixed
     */
    public static function show($var, $return = false)
    {
        $output = "<div style='-webkit-border-radius: 5px;-moz-border-radius: 5px;border-radius: 5px; background:#EFE299; color:#000; padding:10px; margin:10px 5px; font:14px arial; line-height: 20px;'>";
        if (is_array($var)) {
            $output .= "<pre>" . print_r($var, true) . "</pre>";
        } else {
            $output .= $var . "";
        }
        $output .= "</div>";
        
        if ($return === true)
            return $output;
        else
            echo $output;
    }

    /**
     * Debug by ajax+session
     *
     * @param unknown $var   Variavel a fazer debug
     * @param string $name Titulo do dump         
     * @param bool $backtrace true or false consoante queremos ver o backtrace
     */
    public function ajax($var, $name = false, $backtrace = false)
    {
        if (getenv('APPLICATION_ENV') == 'development') {
            
            $session = new SessionAdapter();
            
            $all_debug = $session->get('crm_debug');
            
            if ($all_debug) {
                $this->dumps = $all_debug;
            }
            
            $var_export = str_replace(" ", '&nbsp;', var_export($var, true));
            
            $var_export = str_replace(array(
                "\n",
                "\r",
                "\n\r",
                "\r\n"
            ), '<br>', $var_export);
            
            $this->dumps[md5($var_export)]['dump'] = $var_export;
            
            if ($backtrace) {
                $this->dumps[md5($var_export)]['backtrace'] = self::backtrace(false, 10, true);
            }
            
            if ($name)
                $this->dumps[md5($var_export)]['name'] = $name;
            
            $session->set('crm_debug', $this->dumps);
        }
    }

    /**
     * Backtrace
     *
     * @param string $options            
     * @param number $limit            
     * @param string $html            
     * @return array|string
     */
    public static function backtrace($options = false, $limit = 10, $html = false)
    {
        $backtrace = debug_backtrace($options, $limit);
        
        if (! $html) {
            return $backtrace;
        } else {
            
            $html = '<table>';
            
            foreach ($backtrace as $step) {
                if (is_array($step)) {
                    $html .= '<tr><td>';
                    $html .= "<table style='background-color:#ffffff;'>";
                    foreach ($step as $chave => $value) {
                        if (! is_array($value))
                            $html .= '<tr style="width:250px;"><td><strong>' . $chave . '</strong></td>' . '<td style="width:650px;">' . $value . '</td></tr>';
                    }
                    $html .= "</table>";
                }
                $html .= '<tr><td colspan="2"><hr></td></tr>';
                $html .= '</td></tr>';
            }
            
            $html .= '</table>';
            
            return $html;
        }
    }
}