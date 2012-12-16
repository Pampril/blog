<?php

class IndexController extends Zend_Controller_Action
{
	
	public function init()
	{
		$this->view->addHelperPath("ZendX/JQuery/View/Helper", "ZendX_JQuery_View_Helper");
	}
	

	public function indexAction()
    {
    	$this->_helper->actionStack('menu', 'index', 'default', array());
    	$this->_helper->actionStack('login', 'index', 'default', array());    	
    	$this->_helper->actionStack('afficherunarticle','articles','default',array());
    }
    
    public function menuAction()
    {
    	$this->_helper->viewRenderer->setResponseSegment('menu');
    }
    
    public function loginAction()
    {
    	$this->_helper->viewRenderer->setResponseSegment('login');
    }
    
    public function ajaxAction()
    {
    	$this->view->msg = "bwa";
    }
    
    
}

