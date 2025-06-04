<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('districts', 'DistrictManagement::index');
$routes->post('district/save', 'DistrictManagement::saveDetails');
$routes->post('district/update', 'DistrictManagement::editDistrict');
$routes->post('district/delete', 'DistrictManagement::deleteDistrict');

//Pole Management Routes
$routes->get('poles', 'PoleManagement::index');
$routes->post('pole-management/store', 'PoleManagement::storePole');
$routes->get('pole-management/edit', 'PoleManagement::editPole/');
$routes->post('pole-management/update', 'PoleManagement::updatePole/');
$routes->get('pole-management/delete', 'PoleManagement::deletePole/');