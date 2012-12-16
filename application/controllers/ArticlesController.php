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
    			case "ajoutcommentaire":
    				$this->_helper->actionStack('ajoutcommentaire', 'articles', 'default', array());
    				break;
    			case "affichercommentaire":
    				$this->_helper->actionStack('affichercommentaire', 'articles', 'default', array());
    				break;
    		}    		
    	}
    	
    	//Envoi les données de l'article à la base de donnée
    	if(isset($_POST['EnvoyerArt']))
    	{
    		$article = new Articles;
    	
    		$addarticle = $article->createRow();
    		$addarticle->id = '';
    		$addarticle->date = date('Y-m-d');
    		$addarticle->corps =$_POST['NewArticle'];
    		$addarticle->titre =$_POST['NewTitre'];
    		
    	
    		if(!empty($addarticle->corps))
    		{
    			$addarticle->save();
    			echo "Article ajouté";
    		}
    		else
    		{
    			echo"Erreur d'ajout";
    		}
    	}
    	
    	//Envoi des commentaires à la base de donnée
    	if(isset($_POST['EnvoyerCom']))
    	{
    		$commentaire = new Commentaire;
    	
    		$addcommentaire = $commentaire->createRow();
    		$addcommentaire->id= '';
    		$addcommentaire->date = date('Y-m-d');
    		$addcommentaire->commentaire = $_POST['NewCom'];
    		$addcommentaire->idArticle = '7';
    	
    	
    		if(!empty($addcommentaire->commentaire))
    		{
    			$addcommentaire->save();
    			echo "Commentaire enregistré";
    		}
    		else
    		{
    			echo"Erreur d'ajout";
    		}
    	}
    }    
    
    //Formulaire d'ajout d'article
    public function ajoutarticleAction()
    {
    	// Créer un objet formulaire
    	$FormAjoutArticle = new Zend_Form();
    	
    	// Parametrer le formulaire
    	$FormAjoutArticle->setMethod('post')->setAction('/articles/index');
    	$FormAjoutArticle->setAttrib('id', 'FormAjoutArticle');
    		
    	// Creer de l'elements de formulaire
    	$NewArticle= new Zend_Form_Element_Textarea('NewArticle');
    	$NewArticle ->setLabel('Taper votre article');
    	$NewArticle->setAttrib('id', 'formarticle');
    	$NewArticle ->setRequired(TRUE);
    	
    	$NewTitre= new Zend_Form_Element_Text('NewTitre');
    	$NewTitre ->setLabel('Taper votre titre');
    	$NewTitre->setAttrib('id', 'formarticle');
    	$NewTitre ->setRequired(TRUE);
    	
    	$boutonSubmit = new Zend_Form_Element_Submit('EnvoyerArt');   
    		
    	$FormAjoutArticle->addElement($NewTitre);
    	$FormAjoutArticle->addElement($NewArticle);
    	$FormAjoutArticle->addElement($boutonSubmit);    
    	
    	//Envoi du formulaire à la vue
		$this->view->FormAjoutArticle = $FormAjoutArticle;
    }
    
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
    
    public function afficherlesarticlesAction()
    {
    	//affiche les articles 
    	$article = new Articles;
    	$lesArticles = $article->fetchAll();
    	   		
    	$this->view->lesArticles=$lesArticles;
    		
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
    	// Créer un objet formulaire
    	$FormAjoutCommentaire = new Zend_Form();
    
    	// Parametrer le formulaire
    	$FormAjoutCommentaire->setMethod('post')->setAction('/articles/index');
    	$FormAjoutCommentaire->setAttrib('id', 'FormAjoutCommentaire');
    
    
    	// Creer de l'elements de formulaire
    	$NewCommentaire= new Zend_Form_Element_Textarea('NewCom');
    	$NewCommentaire ->setLabel('Taper votre commentaire');
    	$NewCommentaire->setAttrib('id', 'formcommentaire');
    	$NewCommentaire->setAttrib('COLS', '50');
    	$NewCommentaire->setAttrib('ROWS', '10');
    	$NewCommentaire ->setRequired(TRUE);
    
    
    	$boutonSubmit = new Zend_Form_Element_Submit('EnvoyerCom');
    
    	$FormAjoutCommentaire->addElement($NewCommentaire);
    	$FormAjoutCommentaire->addElement($boutonSubmit);
    
    	//Envoi du formulaire à la vue
    	$this->view->FormAjoutCommentaire = $FormAjoutCommentaire;
    
    }
    
    public function supprimerarticleAction()
    {
    	//supprime un article
    }
    
    public function modifierarticleAction()
    {
    	//modifie le contenu d'un article
    }


}

