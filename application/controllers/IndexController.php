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
    	
    	$auth = Zend_Auth::getInstance();
    	
    	if ($auth->hasIdentity())
    	{   	    	
    		$this->_helper->actionStack('affichercommentaire','commentaire','default',array());
    	}
    	else
    	{
    		$this->_helper->actionStack('afficherunarticle','articles','default',array());
    	}
    	
    	$this->_helper->actionStack('afficherlesarticles', 'articles');
    }
    
    public function menuAction()
    {
    	$this->_helper->viewRenderer->setResponseSegment('menu');
    }
		
	public function loginAction()
	{
		$this->_helper->viewRenderer->setResponseSegment('login');
		if ($this->_request->isPost())
		{
			try {
				$db =Zend_Registry::get('db');
				// on récupère les données du formulaire de connexion
				//et on applique un filtre dessus qui enleve toutes balises
				//php ou html
				$f = new Zend_Filter_StripTags();
				$login = $f->filter($this->_request->getPost('login'));
				$password = $f->filter($this->_request->getPost('mdp'));
					
				//on instancie Zend_Auth
				$auth = Zend_Auth::getInstance();
					
				//charger et parametrer l'adapteur				
				$dbAdapter = new Zend_Auth_Adapter_DbTable($db,'user','login','mdp');
					
				//charger les logs à vérifier
				$dbAdapter->setIdentity($login);
				$dbAdapter->setCredential($password);
					
				//on test l'autentification
				$resultat = $auth->authenticate($dbAdapter);
					
				if($resultat->isValid())
				{
					$data = $dbAdapter->getResultRowObject(null,'mdp');
					$auth->getStorage()->write($data);		
					$this->_redirect('index/index');
				}
				else
				{
					$formConnexion = new Connexion();
					$this->view->formconnexion = $formConnexion;
				}
			}
			catch(Zend_Exception $e)
			{
				echo $e->getMessage();
			}
		}
		else
		{
			$auth = Zend_Auth::getInstance();
			//on regarde si on est bien authentifié
			if ($auth->hasIdentity())
			{//on affiche le login de celui qui est connecté
				$identity = $auth->getIdentity();
				$this->view->identity = $identity;
			}
			else
			{
				$formConnexion = new Connexion();
				$this->view->formconnexion = $formConnexion;
			}
		}
	}
		
	public function deconnexionAction()
	{
		// Supprime la connexion de l'utilisateur
		Zend_Auth::getInstance()->clearIdentity();
		// Redirige à la page de connexion
		$this->_redirect('index/index');
	}
}