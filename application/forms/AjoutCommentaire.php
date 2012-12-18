<?php

class AjoutCommentaire extends Zend_Form
{

	public function init()
	{
		// Décorateur pour les inputs de login et mdp
		$decorators_input = array(
				array('ViewHelper'),
				array('Errors'),
				array('Label'),
				array('HtmlTag')
		);

		// Décorateur pour le formulaire en général
		$decorators_form = array(
				'FormElements',
				'Form'
		);

		//Paramétre le formulaire
		$this->setMethod('post');
		//$this->setAction(Zend_Registry::get('baseUrl'));
		$this->setAttrib('id', 'form_ajout_commentaire');
		$this->addDecorators($decorators_form);

		//Champ Auteur
		$Auteur = new Zend_Form_Element_Text('auteur');
		$Auteur->setLabel('Auteur :');
		$Auteur->setValue("");
		$Auteur->setRequired(true);


		//Champ de la date du commentaire
		$Date = new Zend_Form_Element_Text('dateCommentaire');
		$Date->setLabel("Date / heure :");
		$DateJour = new Zend_Date();
		$Date->setValue(date("Y-m-d H:m:s"));
		$Date->setRequired(true);


		//Champ du commentaire
		$Commentaire = new Zend_Form_Element_Textarea('commentaire', array('cols' => 35, 'rows' => 5));
		$Commentaire->setLabel("Commentaire :");
		$Commentaire->setValue("Ceci est le Commentaire");
		$Commentaire->setRequired(true);


		//Instancie un element type submit
		$btSubmit = new Zend_Form_Element_Submit('Ajouter');

		//Ajout des élément dans le formulaire
		//$Auteur->addElement($Auteur);
		$this->addElement($Date);
		$this->addElement($Commentaire);
		$this->addElement($btSubmit);
			
		//Instancie class Commentaire
		$ligneInstance = new Commentaire;

	}
}