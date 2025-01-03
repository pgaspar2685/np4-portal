<?php
/*
 * Define custom routes. File gets included in the router service definition.
 */
$router = new Phalcon\Mvc\Router();

$router->add('/confirm/{code}/{email}', [
    'controller' => 'user_control',
    'action' => 'confirmEmail'
]);

// /{email}
$router->add('/reset-password/{code}', [
    'controller' => 'user_control',
    'action' => 'resetPassword'
]);

$router->add('/login', [
    'controller' => 'session',
    'action' => 'login'
]);

$router->add('/logout', [
    'controller' => 'session',
    'action' => 'logout'
]);

$router->add('/logout', [
    'controller' => 'session',
    'action' => 'logout'
]);

$router->add('/politica-de-privacidade', [
    'controller' => 'index',
    'action' => 'politicaPrivacidade'
]);

$router->add('/agendamento', [
    'controller' => 'index',
    'action' => 'agendamento'
]);

$router->add('/nocache', [
    'controller' => 'session',
    'action' => 'deletecached'
]);

return $router;
