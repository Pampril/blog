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
    			case "ajout":
    				$this->_helper->actionStack('ajout', 'articles', 'default', array());
    				break;
    			case "afficher":
    				$this->_helper->actionStack('afficher', 'articles', 'default', array());
    				break;
    		}
    	}
    	
    	//Envoi les données de l'article à la base de donnée
    	if(isset($_POST['Envoyer']))
    	{
    		echo'rentre dans le if';
    		
    		$article = new Articles;
    	
    		$addarticle = $article->createRow();
    		$addarticle->id = '';
    		$addarticle->date = '2010-10-12';
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
       
    }
    
    //Formulaire d'ajout d'article
    public function ajoutAction()
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
    	
    	$boutonSubmit = new Zend_Form_Element_Submit('Envoyer');   
    		
    	$FormAjoutArticle->addElement($NewTitre);
    	$FormAjoutArticle->addElement($NewArticle);
    	$FormAjoutArticle->addElement($boutonSubmit);    
    	
    	//Envoi du formulaire à la vue
		$this->view->FormAjoutArticle = $FormAjoutArticle;
    }
    
    public function afficherAction()
    {
    	//affiche les articles 
    	$article = new Articles;
    	$lesArticles = $article->fetchAll();
    	
    	foreach($lesArticles as $unArticle)
    	{
    		$affichage = $unArticle->id;
    	}
    	
    	$this->view->lesArticles=$lesArticles;    	
    	$this->_helper->actionStack('ajout','commentaires','default',array());
    	$this->_helper->actionStack('afficher','commentaires','default',array());    	  	
    }
    
    public function supprimerAction()
    {
    	//supprime un article
    }
    
    public function modifierAction()
    {
    	//modifie le contenu d'un article
    }


}

