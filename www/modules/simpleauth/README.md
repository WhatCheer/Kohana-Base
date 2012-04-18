K3-SimpleAuth Module
==============

A Ko3 Module by [**John Hobbs**](http://twitter.com/jmhobbs)

Introduction
------------

This module provides super simple role based auth for controllers. It requires the Auth module.

Installation
------------

K3-SimpleAuth is a simple, standard module.

1. Drop the source in your MODPATH folder.
2. Add the module to Kohana::modules in your bootstrap.php

Usage
-----

To use K3-SimpleAuth you need to extend your controllers from either the "Controller_Auth"
or "Controller_Auth_Template" classes.

These classes will then control access to actions based on the user's roles and the $auth member.

You can block actions off as needed, rules are processed in descending order.

The character '*' is used as a wildcard.

Examples:

    $auth = array(
      // Only roles 'admin' can reach this
      'one' => 'admin',
      // Only roles 'manager' _and_ 'admin' can reach this
      'two' => array( 'manager', 'admin'  ),
      // Anyone logged in can reach this
      'three' => '*',
      // Anyone can access this action, logged in or out
      'four' => false,
      // Any other action can only be reached by those with the 'view' role
      '*' => 'view',
      // This rule is never reached, because of the wildcard rule directly above
      'five' => 'admin', 
    );

To change the default actions taken, simply override these two methods:

    protected function _not_authorized () {
      throw new HTTP_Exception_403();
    }
		
    protected function _not_logged_in () {
      $this->session->set( 'return-to', Request::$current->url() );
      throw new HTTP_Exception_401();
    }

