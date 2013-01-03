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
		
		//Instancie le formulaire créé
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
					$publication = $data['publication'];									
				}
				else
				{
					$dateArticle = "";
					$titreArticle = "";					
					$corps = "";
					$publication = "";
				}
				if($titreArticle != "" && $dateArticle != "" && $corps != ""&& $publication != "")					
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
    	$sql = 'select id, titre, corps, date
		from articles 
		WHERE id IN (select id
							from articles 
							where id IN (select MAX( id ) 
												from articles 
												WHERE `publication` != 0))
												GROUP BY `id`;';
    	
    	$db = Zend_Db_Table::getDefaultAdapter();
    	$datas = $db->query($sql)->fetchAll();
	    
	    foreach ($datas as $data )
	    {
	    		$listeArticle[1][2] = $data['date'];
    	    	$listeArticle[1][0] = $data['titre'];
    	    	$listeArticle[1][1] = $data['corps'];
    	    	$listeArticle[1][3] = $data['id'];
    	    	
	    }
	    
	    if(!empty($datas))
	    {
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
	 	    $this->_helper->actionStack('ajoutcommentaire', 'commentaire');
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

    //affiche l'article selectionné dans la liste
    public function afficherlarticleAction()
    {   
    	// Récupère l'IdArticle passé en parametre dans l'url
    	$idArticle = $this->_getParam('idArticle');   
    
    
    	// Selectionne l'article qui a été cliqué
    	$sql = "select id, titre, corps, date
    	from articles
    	WHERE id = $idArticle ;";
    
    
    	$db = Zend_Db_Table::getDefaultAdapter();
    	$datas = $db->query($sql)->fetchAll();
    	
    	foreach ($datas as $data ){
    		$listeArticle[1][0] = $data['titre'];
    		$listeArticle[1][1] = $data['corps'];
    		$listeArticle[1][2] = $data['date'];
    		$listeArticle[1][3] = $data['id'];
    
    	}
    	$this->view->listeArticle = $listeArticle;
    
    	//Recupere les commentaires de l'article
    	$sql2 = '
		select idArticle, commentaire, date, auteur
		from commentaires
		WHERE idArticle = \''.$listeArticle[1][3].'\'';
    
    	$datas2 = $db->query($sql2)->fetchAll();
    	$compteur2 =0;
    	foreach ($datas2 as $data2 ){
    		$compteur2 = $compteur2 + 1;
    		$listeCom[$compteur2][0] = $data2['date'];
    		$listeCom[$compteur2][1] = $data2['auteur'];
    		$listeCom[$compteur2][2] = $data2['commentaire'];
    		$this->view->listeCom = $listeCom;
    	}
    }
    
    //Suppression d'un article   
    public function supprimerarticleAction()
    {        	
    	//on instancie le model article
    	$class_article = new Articles();
    	//on instancie le model commentaire
    	$class_commentaire = new Commentaire();
    	
    	//affiche les articles
    	$lesArticles = $class_article->fetchAll();
    	$idArticle = $this->_getParam('idArticle');
    	
    	$this->view->lesArticles=$lesArticles;    	
    	
    	//supprimer un article
    	foreach($lesArticles as $unArticle)
    	{    				
    		if($idArticle ==$unArticle->id)
    		{
    			$class_article->find($unArticle->id)->current()->delete();    			
    			
    			
    			$sql='select * from commentaires where idArticle='.$idArticle.';';
    			
    			$db = Zend_Db_Table::getDefaultAdapter();
    			$suppressionCommentaire = $db->query($sql)->fetchAll();
    			
    			Zend_Debug::dump($suppressionCommentaire);
    			
    			
    			foreach($suppressionCommentaire as $unCommentaire)
    			{
    				$class_commentaire->find($unCommentaire['id'])->current()->delete();
    				   				
    			}
    			$this->_redirect('/index/index');
    		} 			  
    	}    	
    }
    
    //Modification d'un article
    public function modifierarticleAction()
    {
    	if(isset($_GET['idArticle']))
    	{
    		
    		if($this->_request->isPost())
    		{
    			//on instancie le model article
    			$class_article = new Articles;    			
    			$data=array('titre'=>$this->_request->getPost('titre'),
    					'date'=>$this->_request->getPost('date'),
    					'corps'=>$this->_request->getPost('corps'),
    					'publication'=>$this->_request->getPost('publication'));
    			
    			$db = Zend_Db_Table::getDefaultAdapter();
    			$db->update('articles',$data,'id ='.$_GET['idArticle']);
    		
    		}
    		else 
    		{
    			// Selectionne l'article qui a été cliqué
    			$sql = 'select *
    			from articles
    			WHERE id = '.$_GET['idArticle'].' ;';
    			
    			$db = Zend_Db_Table::getDefaultAdapter();
    			$datas = $db->query($sql)->fetch();
    			 
    			//Instancie le formulaire créé
    			$form = new AjoutArticle;
    			$form->populate($datas);
    			echo $form;
    		}
    		
    	}
    }

}

