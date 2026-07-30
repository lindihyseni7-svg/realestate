<?php

/**
 * Routing
 */
$router = new Core\Router();

// Add the routes
$router->add('', ['controller' => 'HomeController', 'action' => 'index']);

// Authentication routes
// $router->add('login', ['controller' => 'AuthController', 'action' => 'loginForm']);
// $router->add('login-store', ['controller' => 'AuthController', 'action' => 'loginStore']);
// $router->add('logout', ['controller' => 'AuthController', 'action' => 'logout']);
$router->add('login', ['controller' => 'AuthController', 'action' => 'loginForm']);
$router->add('login-store', ['controller' => 'AuthController', 'action' => 'loginStore']);
$router->add('logout', ['controller' => 'AuthController', 'action' => 'logout']);

// Contact route
$router->add('contact-store', ['controller' => 'ContactController', 'action' => 'store']);

// User routes
$router->add('users', ['controller' => 'UserController', 'action' => 'index']);
$router->add('users-create', ['controller' => 'UserController', 'action' => 'create']);
$router->add('users-store', ['controller' => 'UserController', 'action' => 'store']);
$router->add('users-edit', ['controller' => 'UserController', 'action' => 'edit']);
$router->add('users-update', ['controller' => 'UserController', 'action' => 'update']);
$router->add('users-delete', ['controller' => 'UserController', 'action' => 'destroy']);

// Listings routes
$router->add('buy', ['controller' => 'HomeController', 'action' => 'buy']);
$router->add('listings', ['controller' => 'ListingController', 'action' => 'index']);
$router->add('listings-create', ['controller' => 'ListingController', 'action' => 'create']);
$router->add('listings-store', ['controller' => 'ListingController', 'action' => 'store']);
$router->add('listings-edit', ['controller' => 'ListingController', 'action' => 'edit']);
$router->add('listings-update', ['controller' => 'ListingController', 'action' => 'update']);
$router->add('listings-delete', ['controller' => 'ListingController', 'action' => 'destroy']);

// Property Types routes
$router->add('property_types', ['controller' => 'PropertyTypeController', 'action' => 'index']);
$router->add('property_types-create', ['controller' => 'PropertyTypeController', 'action' => 'create']);
$router->add('property_types-store', ['controller' => 'PropertyTypeController', 'action' => 'store']);
$router->add('property_types-edit', ['controller' => 'PropertyTypeController', 'action' => 'edit']);
$router->add('property_types-update', ['controller' => 'PropertyTypeController', 'action' => 'update']);
$router->add('property_types-delete', ['controller' => 'PropertyTypeController', 'action' => 'destroy']);

// Agents routes
$router->add('agents', ['controller' => 'AgentController', 'action' => 'index']);
$router->add('agents-create', ['controller' => 'AgentController', 'action' => 'create']);
$router->add('agents-store', ['controller' => 'AgentController', 'action' => 'store']);
$router->add('agents-edit', ['controller' => 'AgentController', 'action' => 'edit']);
$router->add('agents-update', ['controller' => 'AgentController', 'action' => 'update']);
$router->add('agents-delete', ['controller' => 'AgentController', 'action' => 'destroy']);

$router->add('locations', ['controller' => 'LocationController', 'action' => 'index']);
$router->add('locations-create', ['controller' => 'LocationController', 'action' => 'create']);
$router->add('locations-store', ['controller' => 'LocationController', 'action' => 'store']);
$router->add('locations-edit', ['controller' => 'LocationController', 'action' => 'edit']);
$router->add('locations-update', ['controller' => 'LocationController', 'action' => 'update']);
$router->add('locations-delete', ['controller' => 'LocationController', 'action' => 'destroy']);

$router->add('amenities', ['controller' => 'AmenityController', 'action' => 'index']);
$router->add('amenities-create', ['controller' => 'AmenityController', 'action' => 'create']);
$router->add('amenities-store', ['controller' => 'AmenityController', 'action' => 'store']);
$router->add('amenities-edit', ['controller' => 'AmenityController', 'action' => 'edit']);
$router->add('amenities-update', ['controller' => 'AmenityController', 'action' => 'update']);
$router->add('amenities-delete', ['controller' => 'AmenityController', 'action' => 'destroy']);




$router->dispatch($_SERVER['QUERY_STRING']);
?>
