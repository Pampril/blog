<?php

class Connexion extends Zend_Form 
{			
	public function init()
	{		
		// Décorateur pour les inputs de login et mdp
		$decorators_input = array(
				array('ViewHelper'),
				array('Errors'),
				array('Label', array(
						'requiredSuffix'=>' *',
						'separator'=>' :'
				)),
				array('HtmlTag', array('tag'=>"div"))
		);
		
		// Décorateur pour le formulaire en général
		$decorators_form = array(
				'FormElements',
				'Form'
		);
				
		// Parametrage du formulaire
		$this->setMethod('post');
		//$this->setAction(Zend_Registry::get('baseUrl'));
		$this->setAttrib('id', 'form_connexion');
		
		//Desactivation des décorateurs par défaut et ajout du notre
		$this->clearDecorators();
		$this->addDecorators($decorators_form);
		
		// Création de l'input et label pour le login
		$input_login = new Zend_Form_Element_Text('login');
		$input_login->setName("login");
		$input_login->setLabel('Identifiant');
		$input_login->setRequired(true);
		$input_login->setDecorators($decorators_input);
		
		// Création de l'input et label pour le mot de passe
		$input_mdp = new Zend_Form_Element_Password('mdp');
		$input_mdp->setName("mdp");
		$input_mdp->setLabel('Mot de passe');
		$input_mdp->setRequired(true);
		$input_mdp->setDecorators($decorators_input);
		
		// Création du bouton d'envoie du formulaire
		$input_submit = new Zend_Form_Element_Submit('Connexion');
		$input_submit->removeDecorator('DtDdWrapper');
		
		// Ajout des éléments au formulaire
		$this->addElement($input_login);
		$this->addElement($input_mdp);
		$this->addElement($input_submit);
		
	}
	
}


?>