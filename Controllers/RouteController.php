<?php
namespace Controllers;
use    model\Manager\RouteManager;


$router = new RouteManager($twig, $db);

// Register routes
// GENERAL ROUTES


$router->registerRoute('home', ConnectionController::class, 'index');
$router->registerRoute("logout", ConnectionController::class, "logout");
$router->registerRoute('404', ErrorController::class, 'error404');
$router->registerRoute("viewAll", GeneralController::class, "viewAll");
$router->registerRoute('login', ConnectionController::class, 'login');
$router->registerRoute("findDoku", GeneralController::class, "findDoku");
$router->registerRoute("searchResults", GeneralController::class, "searchResults");
$router->registerRoute('showDetails', GeneralController::class, 'showDetails');
$router->registerRoute('getVariant', GeneralController::class, 'getVariant');
$router->registerRoute('showVariantRules', GeneralController::class, 'showVariantRules');
$router->registerRoute('searchByStars', GeneralController::class, 'searchByStars');
$router->registerRoute("getPrevious", GeneralController::class, "getPrevious");
$router->registerRoute("getRandomPuzzle", GeneralController::class, "getRandomPuzzle");
// ADMIN ROUTES

$router->registerRoute("admin", AdminController::class, "admin");
$router->registerRoute("addSetter", AdminController::class, "addSetter");
$router->registerRoute("addVariant", AdminController::class, "addVariant");
$router->registerRoute("addSudoku", AdminController::class, "addSudoku");
$router->registerRoute("viewLogs", AdminController::class, "viewLogs");
$router->registerRoute("editVariant", AdminController::class, "editVariant");

$router->registerRoute("logLinkConnect", ConnectionController::class, "logLinkConnect");
// Handle request
$route = $_GET['route'] ?? 'home';
$router->handleRequest($route);