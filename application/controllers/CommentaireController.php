<?php

class CommentaireController extends Zend_Controller_Action
{

	public function indexAction()
	{
		
	     $valeur = $this->_getParam('valeur');
	    
	     if(isset($valeur))
	     {
	     	switch ($valeur)
	    	 {
			     case "ajoutcommentaire":
			     $this->_helper->actionStack('ajoutcommentaire', 'commentaire', 'default', array());
			     break;
			     case "affichercommentaire":
			     $this->_helper->actionStack('affichercommentaire', 'commentaire', 'default', array());
			     break;
		     }
	     }	    
	}
	
	public function affichercommentaireAction()
	{
		//affiche les commentaires
		$commentaire = new Commentaire;
		$lesCommentaires = $commentaire->fetchAll();
	
		$this->view->lesCommentaires=$lesCommentaires;
	
	}	
	
	public function ajoutcommentaireAction()
	{		
		//Instancie le form créer
		$formAjoutCommentaire= new AjoutCommentaire;

		$class_commentaire= new Commentaire;
		
		$idArticle = $this->_getParam('idArticle');
		
		
		
		// Selectionne le dernier article qui peut etre publié
		$sql = 'select id
		from articles
		WHERE id IN (select id
		from articles
		where id IN (select MAX( id )
		from articles
		WHERE publication != 0))
		GROUP BY `id`;';
		
		if ($idArticle == "")
		{			
			$db = Zend_Db_Table::getDefaultAdapter();
			$datas = $db->query($sql)->fetchAll();
			foreach ($datas as $data ){
				$idArticle = $data['id'];
			}
		}	
		
		if(!$this->getRequest()->getPost())
		{
			//Envoie a la vue le form
			$this->view->assign('form_ajout_commentaire',$formAjoutCommentaire);
		}
		else
		{
			// on récupère les données du formulaire
			$data = $this->getRequest()->getPost();		
			$data["idArticle"] = $idArticle;
			//Verif des donnée du formulaire
			if($formAjoutCommentaire->isValid($data)==true)
			{
				// on récupère les infos du formulaire
				if(isset($data['auteur'])
						&& isset($data['date'])
						&& isset($data['commentaire'])
						&& isset($data["idArticle"])) 
					 
				{
					$auteur= $data['auteur'];
					$dateCommentaire= $data['date'];
					$commentaire = $data['commentaire'];
					$idArticle = $data["idArticle"];
					
				}
				else
				{
					$auteur= "";
					$dateCommentaire= "";
					$commentaire = "";
					$idArticle = "";
					//echo $idArticle;
				}
				if($auteur!= "" 
					&& $dateCommentaire!= ""
					&& $commentaire != ""
					&& $idArticle != "")
				{	//echo'test3';					
					//AJOUT DANS LA BD
					$ajoutCommentaire= $class_commentaire->createRow($data);
					$ajoutCommentaire->save();
					//Envoie a la vue le form
					$this->view->assign('form_ajout_commentaire',$formAjoutCommentaire);
					$Done = true;
					$this->view->Done = $Done;
				}
			}
			else
			{
				$formAjoutCommentaire->populate($data);
				//Envoie a la vue le form
				$this->view->assign('form_ajout_commentaire',$formAjoutCommentaire);
		
			}
		}		
	}
	
	//affiche l'article selectionné dans la liste
	public function afficherlecommentaireAction()
	{
		// Récupère l'IdArticle passé en parametre dans l'url
		$idCommentaire = $this->_getParam('idCommentaire');
	
	
		// Selectionne l'article qui a été cliqué
		$sql = "select id, auteur, commentaire, date
		from commentaires
		WHERE id = $idCommentaire ;";
	
	
		$db = Zend_Db_Table::getDefaultAdapter();
		$datas = $db->query($sql)->fetchAll();
		 
		foreach ($datas as $data )
		{
			$listeCommentaire[1][0] = $data['auteur'];
			$listeCommentaire[1][1] = $data['date'];
			$listeCommentaire[1][2] = $data['commentaire'];
			$listeCommentaire[1][3] = $data['id'];
	
		}
		$this->view->lesCommentaires = $listeCommentaire;		
		
	}

	public function modifierlecommentaireAction()
	{
		if(isset($_GET['idCommentaire']))
		{
	
			if($this->_request->isPost())
			{
				//on instancie le model commentaire
				$class_commentaire = new Commentaire;
				
				$data=array('auteur'=>$this->_request->getPost('auteur'),
						'date'=>$this->_request->getPost('date'),
						'commentaire'=>$this->_request->getPost('commentaire'));						
				 
				$db = Zend_Db_Table::getDefaultAdapter();
				$db->update('commentaires',$data,'id ='.$_GET['idCommentaire']);
				$this->_redirect('/index/index');
			}
			else
			{
				// Selectionne le commentaire qui a été cliqué
				$sql = 'select *
    			from commentaires
    			WHERE id = '.$_GET['idCommentaire'].' ;';
				 
				$db = Zend_Db_Table::getDefaultAdapter();
				$datas = $db->query($sql)->fetch();
	
				//Instancie le formulaire créé
				$form = new AjoutCommentaire;
				$form->populate($datas);
				echo $form;
			}			
		}
	}
	
	//Suppression d'un commentaire
	public function supprimercommentaireAction()
	{
		//on instancie le model commentaire
    	$class_commentaire = new Commentaire();
    	
    	//récupére les commentaires
    	$lesCommentaires = $class_commentaire->fetchAll();
    	$idCommentaire = $this->_getParam('idCommentaire');
    	
    	$this->view->lesCommentaires=$lesCommentaires;    	
    	
    	//supprimer un article
    	foreach($lesCommentaires as $unCommentaire)
    	{    				
    		if($idCommentaire ==$unCommentaire->id)
    		{
    			$class_commentaire->find($unCommentaire->id)->current()->delete();    			
    			//renvoi sur l'index
    			$this->_redirect('/index/index');
    		} 			  
    	}    	
	}
	
}  
	   

