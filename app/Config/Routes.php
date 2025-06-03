<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('districts', 'DistrictManagement::index');
$routes->get('districts/save', 'DistrictManagement::addDistrict');
$routes->get('districts/edit', 'DistrictManagement::editDistrict/');
$routes->get('districts/delete', 'DistrictManagement::deleteDistrict/');

//Pole Management Routes
$routes->get('pole-management', 'PoleManagement::index');
$routes->get('pole-management/create', 'PoleManagement::createPole');
$routes->post('pole-management/store', 'PoleManagement::storePole');
$routes->get('pole-management/edit', 'PoleManagement::editPole/');
$routes->post('pole-management/update', 'PoleManagement::updatePole/');
$routes->get('pole-management/delete', 'PoleManagement::deletePole/');