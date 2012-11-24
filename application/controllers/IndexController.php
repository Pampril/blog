<?php

class IndexController extends Zend_Controller_Action
{

	public function indexAction()
    {
    	$this->_helper->actionStack('menu', 'index', 'default', array());
    	$this->_helper->actionStack('login', 'index', 'default', array());    	
    }
    
    public function menuAction()
    {
    	$this->_helper->viewRenderer->setResponseSegment('menu');
    }
    
    public function loginAction(){
    	$this->_helper->viewRenderer->setResponseSegment('login');
    }
}

