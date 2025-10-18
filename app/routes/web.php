<?php
use App\core\Request;
use App\core\Router;
use App\controllers\AuthController;
use App\controllers\CandidateController;
use App\core\Auth;

$router = new Router();
$auth = new AuthController();
$c = new CandidateController();

// Rutas públicas
$router->add('GET', '/login', fn(Request $r) => $auth->showLogin($r));
$router->add('POST','/login', fn(Request $r) => $auth->login($r));

// Rutas privadas
$router->add('GET', '/', function(Request $r) use ($c) {
  Auth::requireLogin();
  return $c->index($r);
});

$router->add('POST', '/buscar', function(Request $r) use ($c) {
  Auth::requireLogin();
  return $c->search($r);
});

$router->add('GET', '/api/buscar', function(Request $r) use ($c) {
  Auth::requireLogin();
  return $c->searchApi($r);
});

$router->add('POST', '/api/buscar', function(Request $r) use ($c) {
  Auth::requireLogin();
  return $c->searchApi($r);
});

$router->add('GET', '/cv/{id}', function(Request $r, $p) use ($c) {
  Auth::requireLogin();
  return $c->show($r, $p);
});

// Logout
$router->add('GET', '/logout', fn(Request $r) => $auth->logout());

return $router;
