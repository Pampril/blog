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
		$Date->setValue (date("Y-m-d H:m:s"));
		$Date->setRequired(true);
		
		//Champ pour le choix de publication ou non
		$Publier = new Zend_Form_Element_Checkbox(array(
			'name'		=> 'publication',
			'value'		=> 0,
			'checked'	=> false,
			'label'		=> 'Publication :'));

		$Publier->setRequired(true);
		$Publier->setDecorators($decorators_input);	

		//Instancie un element type button
		 $button = new Zend_Form_Element_Button('valider', array('onclick' => 'javascript:save();redirect();'));
        $button->setValue('Envoyer');

		//Ajout des élément dans le formulaire
 		$this->addElement($Date);
		$this->addElement($Publier);
		$this->addElement($button);
			
		//Instancie class article
		$ligneInstance = new Articles;
		
	}
}