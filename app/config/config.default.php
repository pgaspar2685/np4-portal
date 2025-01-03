<?php

use Phalcon\Config;
use Phalcon\Logger;

return new Config([
    'database' => [
        'adapter' => 'Mysql',
        'host' => 'xxxxxx',
        'username' => 'xxxxx',
        'password' => 'xxxxx',
        'dbname' => 'xxxxx'
    ],
    'application' => [
        'controllersDir' => APP_PATH . '/controllers/',
        'modelsDir'      => APP_PATH . '/models/',
        'formsDir'       => APP_PATH . '/forms/',
        'viewsDir'       => APP_PATH . '/views/',
        'libraryDir'     => APP_PATH . '/library/',
        'pluginsDir'     => APP_PATH . '/plugins/',
        'cacheDir'       => BASE_PATH . '/cache/',
        'baseUri'        => '/',
        'publicUrl'      => 'http://dominio.com',
        'cryptSalt'      => 'eEAfR|_&G&f,+vU]:jFr!!A&+71w1Ms9~8_4L!<@[N@DyaIP_2My|:+.u>/6m,$D',
        'filedirPath'    => '...'
    ],
    'mail' => [
        'fromName' => 'AA - Backoffice',
        'fromEmail' => 'geral@dominio.com',
        'smtp' => [
            'server' => 'mail.dominio.com',
            'port' => 587,
            'security' => 'tls',
            'username' => 'geral@dominio.com',
            'password' => 'xxxxxx'
        ]
    ],
    'logger' => [
        'path'     => BASE_PATH . '/logs/',
        'format'   => '%date% [%type%] %message%',
        'date'     => 'D j H:i:s',
        'logLevel' => Logger::DEBUG,
        'filename' => 'application.log',
    ],
    // Set to false to disable sending emails (for use in test environment)
    'useMail' => false
]);
