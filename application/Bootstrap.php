<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

	public function run()
	{
		parent::run();
	}
	
	protected function _initView() 
	{
		$view = new Zend_View();
		//... code de paramétrage de votre vue : titre, doctype ...
		$view->addHelperPath('ZendX/JQuery/View/Helper', 'ZendX_JQuery_View_Helper');
		//... paramètres optionnels pour les helpeurs jQuery ....
		$viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('ViewRenderer');
		$viewRenderer->setView($view);
		return $view;
	}
	
	protected function _initDoctype() {
		$this->bootstrap('view');
		$view = $this->getResource('view');
		$view->doctype('HTML5');
	}
	
	protected function _initEncoding() {
		mb_internal_encoding("UTF-8");
	}
	
	protected function _initConfig()
	{
		Zend_Registry::set('configs', new Zend_Config($this->getOptions()));		
	}
	
	protected function _initSession()
	{
		$session = new Zend_Session_Namespace('blog', true);
		return $session;
	}
	
	protected function _initDb()
	{
		$db = Zend_Db::factory(Zend_Registry::get('configs')->database);
		Zend_Db_Table_Abstract::setDefaultAdapter($db);
		Zend_Registry::set('db', $db);
	}

}

