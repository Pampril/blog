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
    	
    	if(isset($_POST['Envoyer']))
    	{
    		echo'rentre dans le if';
    		$article = new Article;
    	
    		$addarticle = $article->createRow();
    		$addarticle->id = '';
    		$addarticle->date = '';
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
    	
    	//affiche le formulaire
    	echo $FormAjoutArticle;
    }
    
    public function afficherAction()
    {
    	 
    }
    
    public function supprimerAction()
    {
    	
    }
    
    public function modifierAction()
    {
    	
    }


}

