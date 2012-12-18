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
    	$this->_helper->actionStack('afficherunarticle','articles','default',array());
    }
    
    public function menuAction()
    {
    	$this->_helper->viewRenderer->setResponseSegment('menu');
    }
    
    public function connexionAction()
    {
    	// Instanciation de Zend_Auth
		$auth = Zend_Auth::getInstance();
	
		// On récupère les données de GET
		$data_get = $this->getRequest()->getQuery();
	
		// url pour l'action du formulaire
		$url = $this->view->baseUrl('index/connexion');
	
		if(isset($data_get['need_action']) && isset($data_get['need_controller']))
		{
			$url .= "?need_action=".$data_get['need_action']."&need_controller=".$data_get['need_controller']."&need_connexion=1";
		}
	
		// instance du formulaire de connexion
		$form_connexion = new Connexion(array('url'=>$url));

		// Si on vient d'une redirection
		if(isset($data_get['need_connexion']))
		{
			$this->view->assign('need_connexion', true);
		}
						
		// Si on est déjà connecté, on est directement réenvoyé sur une page d'accueil
		if($auth->hasIdentity())
		{
			$this->_helper->getHelper('Redirector')->gotoSimple('index', 'index');
		}
				
		// affichage du formulaire lors de la première visite
		if(!$this->getRequest()->getPost())
		{
			$this->view->assign('form_connexion',$form_connexion);
		}
		else
		{
			// on récupère les données du formulaire
			$data = $this->getRequest()->getPost();

			// on récupère login et mot de passe pour tester la connexion
			if(isset($data['login']) && isset($data['mdp']))
			{
				$identifiant = trim((string)$data['login']);
				$mdp = trim((string)$data['mdp']);
			}
			else
			{
				$identifiant = "";
				$mdp = "";
			}

			// variable de test renseignant si la connexion à échouée ou non
			$test_connexion = false;

			// variable signalant si on doit afficher message erreur ou pas
			$error_connexion = false;

			// on effectue la connexion
			if($identifiant!="" && $mdp!="")
			{
				// Parametrage de l'adapteur
				$dbAdapter = new Zend_Auth_Adapter_DbTable(null, 'Utilisateur', 'login', 'mdp' );
					
				// Chargement identifiant et mdp a tester
				$dbAdapter->setIdentity($identifiant);
				$dbAdapter->setCredential(md5($mdp));
					
				// Récupère l'authentification en passant en parametre l'adaptateur
				$resultat_connexion = $auth->authenticate($dbAdapter);
					
				// Si la connexion est réussie
				if($resultat_connexion->isValid())
				{
					$test_connexion = true;

					// Récupère l'id et login
					$data = $dbAdapter->getResultRowObject(null, 'mdp');

					// Stocke id et login dans zend_auth
					$auth->getStorage()->write($data);

					// Si l'utilisateur vient d'une autre page, on redirige une fois co a la page qu'il désirait
					if(isset($data_get['need_action']) && isset($data_get['need_controller']))
					{
						$this->_helper->getHelper('Redirector')->gotoSimple($data_get['need_action'], $data_get['need_controller']);
					}
					else
					{
						// si non, on le redirige vers une page par défaut
						$this->_helper->getHelper('Redirector')->gotoSimple('index', 'index');
					}
				}
				else
				{
					$test_connexion = false;
				}
			}	
				// Si l'identifiant, mdp sont vides ou bien que la connexion a échoué, on réaffiche le formulaire pré-remplit
				if($identifiant=="" || $mdp=="" || !$test_connexion)
				{					
					$error_connexion = true;
					$form_connexion->populate($data);
					$this->view->assign('form_connexion',$form_connexion);
				}
	
				// permet d'afficher un message d'erreur
				$this->view->assign('error_connexion', $error_connexion);
			}				
		}
        
    public function ajaxAction()
    {
    	$this->view->msg = "bwa";
    }
        
}

