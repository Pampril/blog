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
		echo "id de l'article :".$idArticle;
		
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
}  
	   

