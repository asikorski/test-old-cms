<?php

/*
 * @Author: Arnold Sikorski
 *
 * Plugin kontroli dostepu do zasobów
 * 
 */

class MyAcl {

    private $acl = null;

    function __construct() {
        $this->acl = new Zend_Acl();
        $this->_initRoles();
        $this->_initResources();
        $this->_initPrivileges();
    }

    function isAllowed($some_role, $some_resource, $some_privilege) {
        return $this->acl->isAllowed($some_user, $some_resource, $some_action);
    }

    private function _initRoles() {
        $roles = AclRole::all();
        foreach ($roles as $role) {
            if ($role->parent) {
                $parent = $this->acl->getRole($role->parent->name);
                if (is_null($parent)) {
// if parent hasn't been created in memory, do so
                    $parent = new Zend_Acl_Role($role->parent->name);
                    $this->acl->addRole($parent);
                }
                $this->acl->addRole(new Zend_Acl_Role($role->name), $parent);
            } else {
// only needs to be done if it doesn't exist
                if (!$this->acl->hasRole($role->name)) {
                    $this->acl->addRole(new Zend_Acl_Role($role->name));
                }
            }
        }
    }

    private function _initResources() {
        $resources = AclResource::all();
        foreach ($resources as $resource) {
            if ($resource->parent) {
                $parent = $this->acl->get($resource->parent->name);
                if (is_null($parent)) {
                    $parent = new Zend_Acl_Resource($resource->parent->name);
                    $this->acl->addResource($parent);
                }
                $this->acl->addResource(new Zend_Acl_Resource($resource->name), $parent);
            } else {
                if (!$this->acl->has($resource->name)) {
                    $this->acl->addResource(new Zend_Acl_Resource($resource->name));
                }
            }
        }
    }

    private function _initPrivileges() {
        $privileges = AclRolesResource::all();
        foreach ($privileges as $privilege) {
// make sure role and resource are valid
            if ($privilege->acl_role && $privilege->acl_resource) {
                $this->acl->allow($privilege->acl_role->name, $privilege->acl_resource->name, $privilege->privilege);
            } else {
                echo 'WARNING: unable to create privilege';
            }
        }
    }

}
