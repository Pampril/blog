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
				case "ajout":
					$this->_helper->actionStack('ajout', 'commentaires', 'default', array());
					break;
				case "afficher":
					$this->_helper->actionStack('afficher', 'commentaires', 'default', array());
					break;
			}
		}
		 
//   	if(isset($_POST['Envoyer']))
//     	{
//     		$article = new Article;
		 
//     		$addarticle = $commentaire->createRow();
//     		$addarticle->id= '';
//     		$addarticle->date_publication = '';
//     		$addarticle->corps = $_POST['NewArticle'];
//     		$addarticle->titre = $_POST['NewTitre'];

	 
//     		if(!empty($addarticle->corps))
//     		{
//     			$addarticle->save();
//     			echo "Article ajouté";
//     		}
//     		else
//     		{
//     			echo"Erreur d'ajout";
//     		}
//     	}
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
		$NewCommentaire ->setRequired(TRUE);
						
		$NewTitre= new Zend_Form_Element_Text('NewTitre');
		$NewTitre ->setLabel('Taper votre titre');
		$NewTitre->setAttrib('id', 'formcommentaire');
		$NewTitre ->setRequired(TRUE);
		
		$boutonSubmit = new Zend_Form_Element_Submit('Envoyer');
		$boutonReset = new Zend_Form_Element_Reset('Reset');
			
		$FormAjoutCommentaire->addElement($NewTitre);
		$FormAjoutCommentaire->addElement($NewCommentaire);
		
		//affiche le formulaire
		echo $FormAjoutCommentaire;
 
	}
	
	public function supprimerAction()
	{
		 
	}
	
	public function modifierAction()
	{
		 
	}
}
