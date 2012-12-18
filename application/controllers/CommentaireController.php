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
		
		//$idArticle = $this->_getParam('idArticle');
		
		
		// Selectionne le dernier article qui peut etre publié
// 		$sql = 'select idArticle
// 		from Article
// 		WHERE idArticle IN (select idArticle
// 		from Article
// 		where idArticle IN (select MAX( idArticle )
// 		from Article
// 		WHERE `publication` != 0))
// 		GROUP BY `idArticle`;';
		
// 		if ($idArticle == "")
// 		{			
// 			$db = Zend_Db_Table::getDefaultAdapter();
// 			$datas = $db->query($sql)->fetchAll();
// 			foreach ($datas as $data ){
// 				$idArticle = $data['idArticle'];
// 			}
// 		}
		
		if(!$this->getRequest()->getPost())
		{
			//Envoie a la vue le form
			$this->view->assign('form_ajout_commentaire',$formAjoutCommentaire);
		}
		else
		{
			// on récupère les données du formulaire
			$data = $this->getRequest()->getPost();
			
			//$data["Article_idArticle"] = $idArticle;
			
			
			//Verif des donnée du formulaire
			if($formAjoutCommentaire->isValid($data)==true)
			{
				// on récupère les infos du formulaire
				if(isset($data['pseudo'])
						&& isset($data['dateCommentaire'])
						&& isset($data['commentaire']))
						//&& isset($data["Article_idArticle"]))  
				{
					$pseudo= $data['pseudo'];
					$dateCommentaire= $data['dateCommentaire'];
					$commentaire = $data['commentaire'];
					//$idArticle = $data["Article_idArticle"];
				}
				else
				{
					$pseudo= "";
					$dateCommentaire= "";
					$commentaire = "";
					//$idArticle = "";
				}
				if($pseudo!= "" 
					&& $dateCommentaire!= ""
					&& $commentaire != "")
					//&& $idArticle != "")
				{						
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
}  
	   

