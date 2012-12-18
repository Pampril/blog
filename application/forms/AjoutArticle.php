<?php

class AjoutArticle extends Zend_Form
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
		$this->setAttrib('id', 'form_ajout_article');
		$this->addDecorators($decorators_form);
		
		//Champ de la date de l'article
		$Date = new Zend_Form_Element_Text('date');
		$Date->setLabel("Date :");
		$DateJour = new Zend_Date();
		$Date->setValue (date("Y-m-d"));
		$Date->setRequired(true);
		
		//Champ du titre de l'Article
		$Titre = new Zend_Form_Element_Text('titre');
		$Titre->setLabel('Titre :');
		$Titre->setValue("Ceci est le Titre");
		$Titre->setRequired(true);		

		//Champ du corps
		$Corps = new Zend_Form_Element_Textarea('corps');
		$Corps->setLabel("Article :");
		$Corps->setValue("Ceci est le Corps");
		$Corps->setRequired(true);
		
		
			
		//Champ pour le choix de publication ou non
		$Publier = new Zend_Form_Element_Checkbox(array(
			'name'		=> 'publication',
			'value'		=> 0,
			'checked'	=> false,
			'label'		=> 'Publication :'));

		$Publier->setRequired(true);
		$Publier->setDecorators($decorators_input);	

		//Instancie un element type submit
		$Submit = new Zend_Form_Element_Submit('Enregistrer');

		//Ajout des élément dans le formulaire
		$this->addElement($Date);
		$this->addElement($Titre);
		$this->addElement($Corps);
		$this->addElement($Publier);
		$this->addElement($Submit);
			
		//Instancie class article
		$ligneInstance = new Articles;
		
	}
}