<?php

class ArticlesController extends Zend_Controller_Action
{
	public function indexAction()
    {    	
    		
    }    
    //Formulaire d'ajout d'article
    public function ajoutarticleAction()
    {
    	//Instancie le formulaire
		$formAjoutArticle = new AjoutArticle;
		
		//Instancie le model
		$article = new Articles;
		
		if(!$this->getRequest()->getPost())
		{
			//Envoie le formulaire à la vue
			$this->view->assign('formAjoutArticle',$formAjoutArticle);			
		}
		else
		{							
			//Sauvegarde des données dans la base
			$post = $this->getRequest()->getPost();
			$data = $article->createRow();
			$data -> titre = $post['titre'];
			$data -> date = $post['date'];
			$data -> corps = $post['corps'];
			$data -> publication = $post['publication'];
			$data -> save();
	
			////Envoie le formulaire à la vue
			$this->view->assign('formAjoutArticle',$formAjoutArticle);					
			$Valide = true;
			$this->view->Valide = $Valide;						
		}
		//Affiche la liste des images
		$this->_helper->actionStack('listeimages', 'images');
    }
    
    //Affichage du dernier article publié
    public function afficherunarticleAction()
    {   
    	// Selectionne le dernier article publié	
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
	    
    	//remplie un tableau avec les datas
	    foreach ($datas as $data )
	    {
	    		$listeArticle[1][2] = $data['date'];
    	    	$listeArticle[1][0] = $data['titre'];
    	    	$listeArticle[1][1] = $data['corps'];
    	    	$listeArticle[1][3] = $data['id'];
    	    	
	    }
	    
	    if(!empty($datas))
	    {
	    	//envoi le tableau à la vue
	    	$this->view->listeArticle = $listeArticle;
	    
	    
		    //Recupere les commentaires de l'article
		    $sql2 = '	select idArticle, commentaire, date, auteur
	    				from commentaires
	    				WHERE idArticle  = \''.$listeArticle[1][3].'\'';
	   
		    $datas2 = $db->query($sql2)->fetchAll();
		    $compteur2 =0;
		    
		    //remplie un tableau avec les datas
	 	    foreach ($datas2 as $data2 )
	 	    {
		    	$compteur2 = $compteur2 + 1;
		    	$listeCom[$compteur2][0] = $data2['date'];
		    	$listeCom[$compteur2][1] = $data2['auteur'];
		    	$listeCom[$compteur2][2] = $data2['commentaire'];
	 	    	$this->view->listeCom = $listeCom;
	 	    }
	 	    //affiche le formulaire d'ajout de commentaire
	 	    $this->_helper->actionStack('ajoutcommentaire', 'commentaire');
      	}
     }
    
    //affichage de la liste des articles
    public function afficherlesarticlesAction()
    {
    	//affiche les articles 
    	$article = new Articles;
    	$lesArticles = $article->fetchAll();
    	//envoi le résultat à la vue   		
    	$this->view->lesArticles=$lesArticles;
    		
    } 

    //affiche l'article cliké
    public function afficherarticleselectAction()
    {   
    	// Récupère l'IdArticle passé en parametre dans l'url
    	$idArticle = $this->_getParam('idArticle');   
    
    
    	// Selectionne l'article qui a été cliqué
    	$sql = "select id, titre, corps, date
    	from articles
    	WHERE id = $idArticle ;";
    
    
    	$db = Zend_Db_Table::getDefaultAdapter();
    	$datas = $db->query($sql)->fetchAll();
    	
    	//remplie un tableau avec les datas
    	foreach ($datas as $data )
    	{
    		$listeArticle[1][0] = $data['titre'];
    		$listeArticle[1][1] = $data['corps'];
    		$listeArticle[1][2] = $data['date'];
    		$listeArticle[1][3] = $data['id'];
    
    	}
    	//envoi le tableau à la vue
    	$this->view->listeArticle = $listeArticle;
    
    	//Recupere les commentaires de l'article
    	$sql2 = '
		select idArticle, commentaire, date, auteur
		from commentaires
		WHERE idArticle = \''.$listeArticle[1][3].'\'';
    
    	$datas2 = $db->query($sql2)->fetchAll();
    	$compteur2 =0;
    	
    	//remplie un tableau avec les datas
    	foreach ($datas2 as $data2 )
    	{
    		$compteur2 = $compteur2 + 1;
    		$listeCom[$compteur2][0] = $data2['date'];
    		$listeCom[$compteur2][1] = $data2['auteur'];
    		$listeCom[$compteur2][2] = $data2['commentaire'];
    		//envoi le tableau à la vue
    		$this->view->listeCom = $listeCom;    		
    	}
    	
    }
    
    //Suppression d'un article et de ses commentaires
    public function supprimerarticleAction()
    {        	
    	//on instancie le model article
    	$class_article = new Articles();
    	//on instancie le model commentaire
    	$class_commentaire = new Commentaire();
    	
    	//affiche les articles
    	$lesArticles = $class_article->fetchAll();
    	$idArticle = $this->_getParam('idArticle');
    	//envoi les données à la vue
    	$this->view->lesArticles=$lesArticles;    	
    	
    	//supprimer un article
    	foreach($lesArticles as $unArticle)
    	{    				
    		if($idArticle ==$unArticle->id)
    		{
    			$class_article->find($unArticle->id)->current()->delete();    			
    			
    			//selectionne l'article correpondand a l'id passé dans l'url
    			$sql='select * from commentaires where idArticle='.$idArticle.';';
    			
    			$db = Zend_Db_Table::getDefaultAdapter();
    			$suppressionCommentaire = $db->query($sql)->fetchAll();
    			
    			Zend_Debug::dump($suppressionCommentaire);
    			
    			//supprime le commentaire
    			foreach($suppressionCommentaire as $unCommentaire)
    			{
    				$class_commentaire->find($unCommentaire['id'])->current()->delete();    				   				
    			}
    			//renvoi à l'index
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
    			
    			//récupére les données du post   			
    			$data=array('titre'=>$this->_request->getPost('titre'),
    					'date'=>$this->_request->getPost('date'),
    					'corps'=>$this->_request->getPost('corps'),
    					'publication'=>$this->_request->getPost('publication'));
    			
    			$db = Zend_Db_Table::getDefaultAdapter();
    			$db->update('articles',$data,'id ='.$_GET['idArticle']);
    			$this->_redirect('/index/index');
    		}
    		else 
    		{
    			// Selectionne l'article qui a été cliké
    			$sql = 'select * from articles WHERE id = '.$_GET['idArticle'].' ;';    			
    			$db = Zend_Db_Table::getDefaultAdapter();
    			$datas = $db->query($sql)->fetch();
    			 
    			//Instancie le formulaire créé
    			$form = new AjoutArticle;
    			//remplie le formulaire avec les données existante
    			$form->populate($datas);
    			echo $form;
    		}
    		
    	}
    }

}

