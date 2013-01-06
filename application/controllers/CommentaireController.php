<?php

class CommentaireController extends Zend_Controller_Action
{

	public function indexAction()
	{
		
	       
	}
	
	public function affichercommentaireAction()
	{
		//récupére les commentaires
		$commentaire = new Commentaire;
		$lesCommentaires = $commentaire->fetchAll();
		//envoi les résultat à la vue
		$this->view->lesCommentaires=$lesCommentaires;
	
	}	
	
	public function ajoutcommentaireAction()
	{		
		//Instancie le formulaire AjoutCommentaire
		$formAjoutCommentaire= new AjoutCommentaire;
		//Instancie le model Commentaire
		$class_commentaire= new Commentaire;
		//récupére l'id article passé dans l'url
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
			foreach ($datas as $data )
			{
				$idArticle = $data['id'];
			}
		}	
		
		if(!$this->getRequest()->getPost())
		{
			//Envoie le formulaire à la vue
			$this->view->assign('formAjoutCommentaire',$formAjoutCommentaire);
		}
		else
		{
			// on récupère les données du formulaire
			$data = $this->getRequest()->getPost();		
			$data["idArticle"] = $idArticle;
			//Verifie les donnée du formulaire
			if($formAjoutCommentaire->isValid($data)==true)
			{
				//Récupère les infos du formulaire
				if(isset($data['auteur']) && isset($data['date'])	&& isset($data['commentaire'])	&& isset($data["idArticle"]))					 
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
				}
				if($auteur!= ""	&& $dateCommentaire!= "" && $commentaire != "" && $idArticle != "")
				{						
					//AJOUT DANS LA BD
					$ajoutCommentaire= $class_commentaire->createRow($data);
					$ajoutCommentaire->save();
					//Envoie a la vue le form
					$this->view->assign('formAjoutCommentaire',$formAjoutCommentaire);
					$Valide = true;
					$this->view->Valide = $Valide;
				}
			}
			else
			{
				//remplie le formulaire avec les données existante
				$formAjoutCommentaire->populate($data);
				//Envoie a la vue le form
				$this->view->assign('formAjoutCommentaire',$formAjoutCommentaire);
		
			}
		}		
	}
	
	//affiche le commentaire cliké dans la liste
	public function afficherlecommentaireAction()
	{
		// Récupère l'IdCommentaire passé en parametre dans l'url
		$idCommentaire = $this->_getParam('idCommentaire');
	
	
		// Selectionne le commentaire qui a été cliqué
		$sql = "select id, auteur, commentaire, date
		from commentaires
		WHERE id = $idCommentaire ;";
	
	
		$db = Zend_Db_Table::getDefaultAdapter();
		//récupére les données
		$datas = $db->query($sql)->fetchAll();
		
		//rempli un tableau avec les données
		foreach ($datas as $data )
		{
			$listeCommentaire[1][0] = $data['auteur'];
			$listeCommentaire[1][1] = $data['date'];
			$listeCommentaire[1][2] = $data['commentaire'];
			$listeCommentaire[1][3] = $data['id'];
	
		}
		//envoi le tableau à la vue
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
				// Selectionne le commentaire qui a été cliké
				$sql = 'select *
    			from commentaires
    			WHERE id = '.$_GET['idCommentaire'].' ;';
				 
				$db = Zend_Db_Table::getDefaultAdapter();
				$datas = $db->query($sql)->fetch();
	
				//Instancie le formulaire créé
				$form = new AjoutCommentaire;
				//remplie le formulaire avec les données déjà existantes
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
    	//on récupére l'id du commentaire passé dans l'url
    	$idCommentaire = $this->_getParam('idCommentaire');
    	
    	$this->view->lesCommentaires=$lesCommentaires;    	
    	
    	//supprimer un commentaire
    	foreach($lesCommentaires as $unCommentaire)
    	{    				
    		if($idCommentaire ==$unCommentaire->id)
    		{
    			//supprime le commentaire selectionné
    			$class_commentaire->find($unCommentaire->id)->current()->delete();    			
    			//renvoi sur l'index
    			$this->_redirect('/index/index');
    		} 			  
    	}    	
	}
	
}  
	   

