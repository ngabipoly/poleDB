<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'AppAuth::index');
$routes->post('pole-position/login', 'AppAuth::login');
$routes->get('pole-position/logout', 'AppAuth::logout');

// Dashboard Routes
$routes->get('home', 'Home::index');

$routes->get('districts', 'DistrictManagement::index');
$routes->post('district/save', 'DistrictManagement::saveDetails');
$routes->post('district/update', 'DistrictManagement::editDistrict');
$routes->post('district/delete', 'DistrictManagement::deleteDistrict');

//Pole Management Routes
$routes->get('poles', 'PoleManagement::index');
$routes->post('pole-management/store', 'PoleManagement::storePole');
$routes->post('pole-management/update', 'PoleManagement::updatePole/');
$routes->post('pole-management/delete', 'PoleManagement::deletePole/');
$routes->get('pole-management/pole-types', 'PoleManagement::poleTypes');
$routes->post('pole-management/save-pole-type', 'PoleManagement::savePoleType');
$routes->post('pole-management/delete-pole-type', 'PoleManagement::deletePoleType');

//User Management Routes
$routes->get('administration/usr-admin', 'UserRoleMgr::index');
$routes->post('administration/usr-admin', 'UserRoleMgr::saveUser');
$routes->post('administration/reset-pwd', 'UserRoleMgr::resetPwd');
$routes->post('administration/save-new-pwd', 'UserRoleMgr::savePwdChange');
$routes->get('administration/change-pass', 'UserRoleMgr::changePwd');
$routes->get('administration/usr-roles', 'UserRoleMgr::roles');
$routes->get('administration/fetch-rights', 'UserRoleMgr::loadRightsMenus');
$routes->post('administration/role-save', 'UserRoleMgr::saveRole');
$routes->post('administration/save-role-rights', 'UserRoleMgr::saveRights');