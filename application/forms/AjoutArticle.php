<?php
class Application_Form_AjoutArticle extends Zend_Form
{
	public function init()
	{
		// Créer un objet formulaire
		$FormAjoutArticle = new Zend_Form();
		 
		// Parametrer le formulaire
		$this->setMethod('post')->setAction('/articles/index');
		$this->setAttrib('id', 'FormAjoutArticle');
		
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
		
		$this->addElement($NewTitre);
		$this->addElement($NewArticle);
		$this->addElement($boutonSubmit);
		 
		//Envoi du formulaire à la vue
		$this->view->FormAjoutArticle = $FormAjoutArticle;
	}
	
	
} 