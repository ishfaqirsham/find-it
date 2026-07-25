<?php
// ==========================================
// Front Controller
// EVERY request goes through this one file. It figures out which
// "page" and "action" were requested and hands off to the Router,
// which loads the right Controller and calls the right method.
//
// Examples:
//   index.php                                -> ItemController::home()
//   index.php?page=auth&action=showLogin      -> AuthController::showLogin()
//   index.php?page=item&action=search&type=lost
//   index.php?page=admin&action=manageUsers
// ==========================================
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/core/Router.php';

// Default to the home page if nothing is specified
$page = $_GET['page'] ?? 'item';
$action = $_GET['action'] ?? 'home';

$router = new Router();
$router->dispatch($page, $action);
