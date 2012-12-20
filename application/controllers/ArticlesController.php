<?php

class ArticlesController extends Zend_Controller_Action
{
	public function indexAction()
    {
    	$valeur = $this->_getParam('valeur');
    	
    	if(isset($valeur))
    	{
    		switch ($valeur)
    		{
    			case "ajoutarticle":
    				$this->_helper->actionStack('ajoutarticle', 'articles', 'default', array());
    				break;
    			case "afficherarticle":
    				$this->_helper->actionStack('afficherlesarticles', 'articles', 'default', array());
    				break;    			
    		}    		
    	}    	
    }    
    //Formulaire d'ajout d'article
    public function ajoutarticleAction()
    {
    	//Instancie le formulaire
		$formAjoutArticle = new AjoutArticle;
		
		//Instancie le form créer
		$article = new Articles;
		
		if(!$this->getRequest()->getPost())
		{
			//Envoie a la vue le form
			$this->view->assign('form_ajout_article',$formAjoutArticle);
		}
		else
		{
			// on récupère les données du formulaire
			$data = $this->getRequest()->getPost();			
			
			//Verif des donnée du formulaire
			if($formAjoutArticle->isValid($data)==true)
			{
				// on récupère les infos du formulaire
				if(isset($data['titre']) && isset($data['date']) && isset($data['corps']))//&& isset($data['publication']))  
				{
					$dateArticle = $data['date'];
					$titreArticle = $data['titre'];					
					$corps = $data['corps'];
					//$publication = $data['publication'];									
				}
				else
				{
					$dateArticle = "";
					$titreArticle = "";					
					$corps = "";
					//$publication = "";
				}
				if($titreArticle != "" && $dateArticle != "" && $corps != "")//&& $publication != ""					
				{					
					//AJOUT DANS LA BD
					$ajoutArticle = $article->createRow($data);
					$ajoutArticle->save();
					//Envoie a la vue le form
					$this->view->assign('form_ajout_article',$formAjoutArticle);
					$Valide = true;
					$this->view->Done = $Valide;
				}
			}
			else
			{
				$formAjoutArticle->populate($data);
				//Envoie a la vue le form
				$this->view->assign('form_ajout_article',$formAjoutArticle);
		
			}
		}
    }
    //Affichage du dernier article
    public function afficherunarticleAction()
    {   
    	// Selectionne le dernier article	
    	$sql = 'select id, titre, corps
    			from articles 
    			WHERE id IN
    			(SELECT max(id) FROM articles)
    			GROUP BY titre';
    	
    	$db = Zend_Db_Table::getDefaultAdapter();
    	$datas = $db->query($sql)->fetchAll();
	    
	    foreach ($datas as $data )
	    {
    	    	$listeArticle[1][0] = $data['titre'];
    	    	$listeArticle[1][1] = $data['corps'];
    	    	$listeArticle[1][3] = $data['id'];
    	    	
	    }
	    $this->view->listeArticle = $listeArticle;
	    
	    //Recupere les commentaires de l'article
	    $sql2 = '
	    			select idArticle, commentaire, date
    				from commentaires
    				WHERE idArticle  = \''.$listeArticle[1][3].'\'';
	    
	    $datas2 = $db->query($sql2)->fetchAll();
	    $compteur2 =0;
 	    foreach ($datas2 as $data2 )
 	    {
	    	$compteur2 = $compteur2 + 1;
	    	$listeCom[$compteur2][0] = $data2['date'];
	    	$listeCom[$compteur2][1] = $data2['commentaire'];
 	    	$this->view->listeCom = $listeCom;
 	    }
    }
    //affichage de la liste des articles
    public function afficherlesarticlesAction()
    {
    	//affiche les articles 
    	$article = new Articles;
    	$lesArticles = $article->fetchAll();
    	   		
    	$this->view->lesArticles=$lesArticles;
    		
    }    
    //Suppression d'un article   
    public function supprimerarticleAction()
    {
    	//Instancie le form créer
		$formSupprimerArticle = new SupprimerArticle;
		//Instancie le form créer
		$article = new Article;
		
		//affiche les articles
		$lesArticles = $article->fetchAll();
		
		$this->view->lesArticles=$lesArticles;
		$this->form->lesArticles=$lesArticles;
		
		if(!$this->getRequest()->getPost())
		{
			//Envoie a la vue le form
			$this->view->assign('form_supprimer_article',$formSupprimerArticle);
		}
		if(isset($_POST['boutonSubmitSupprimerVol']))
		{
			//supprimer un vol
			foreach($lesArticles as $unArticle)
			{
				if(isset($_POST[$unArticle->idArticle]))
				{
					if($_POST[$unArticle->idArticle] == 1)
					{
						$Article->find($unArticle->idArticle)->current()->delete();
						$tableau[] = $unArticle->idArticle;
					}
				}
			}

			if(isset($tableau))
			{
				$this->view->tableau = $tableau;
			}
		}
		
    }
    //Modification d'un article
    public function modifierarticleAction()
    {
    	//modifie le contenu d'un article
    }

}

