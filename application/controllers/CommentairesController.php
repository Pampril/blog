<?php

class CommentairesController extends Zend_Controller_Action
{	
	public function indexAction()
	{
		$valeur = $this->_getParam('valeur');
		 
		if(isset($valeur))
		{
			switch ($valeur)
			{				
				case "afficher":
					$this->_helper->actionStack('afficher', 'commentaires', 'default', array());
					break;
				case "ajout":
					$this->_helper->actionStack('ajout', 'commentaires', 'default', array());
					break;
			}
		}
		 
  	if(isset($_POST['Envoyer']))
    	{
    		$commentaire = new Commentaires;
		 
    		$addcommentaire = $commentaire->createRow();
    		$addcommentaire->id= '';
    		$addcommentaire->date = '2010-10-12';
    		$addcommentaire->commentaire = $_POST['NewCom'];
    		$addcommentaire->idArticle = '3';

	 
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
	
	public function afficherAction()
	{
		//affiche les commentaires
		$commentaire = new Commentaires;
		$lesCommentaires = $commentaire->fetchAll();
			
		foreach($lesCommentaires as $unCommentaire)
		{
			$affichage = $unCommentaire->id;
		}
			
		$this->view->lesCommentaires=$lesCommentaires;
			
	}
	
	public function ajoutAction()
	{
		// Créer un objet formulaire
		$FormAjoutCommentaire = new Zend_Form();
		
		// Parametrer le formulaire
		$FormAjoutCommentaire->setMethod('post')->setAction('/commentaires/index');
		$FormAjoutCommentaire->setAttrib('id', 'FormAjoutCommentaire');
		
		
		// Creer de l'elements de formulaire
		$NewCommentaire= new Zend_Form_Element_Textarea('NewCom');
		$NewCommentaire ->setLabel('Taper votre commentaire');
		$NewCommentaire->setAttrib('id', 'formcommentaire');
		$NewCommentaire->setAttrib('COLS', '50');
		$NewCommentaire->setAttrib('ROWS', '10');
		$NewCommentaire ->setRequired(TRUE);
						
				
		$boutonSubmit = new Zend_Form_Element_Submit('Envoyer');			
		
		$FormAjoutCommentaire->addElement($NewCommentaire);
		$FormAjoutCommentaire->addElement($boutonSubmit);
		
		//Envoi du formulaire à la vue
		$this->view->FormAjoutCommentaire = $FormAjoutCommentaire;
 
	}	
	
	public function supprimerAction()
	{
		 
	}
	
	public function modifierAction()
	{
		 
	}
}
