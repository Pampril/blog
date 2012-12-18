<?php


class Application_Form_SupprimerArticle extends Zend_Form
{

	public function init()
	{

		//decorateur des cases a cocher
		$decorateurCase = array(
				array('ViewHelper'),
				array('Errors'),
				array('HtmlTag', array('tag'=>'td')),
				array(
						array('tr' => 'HtmlTag'),
						array('tag'=> 'tr')
				)
		);
		//decorateur du bouton submit
		$decorateurBoutonEnvoyer = array(
				array('ViewHelper'),
				array('Errors'),
				array('HtmlTag', array('tag'=>'td', 'class'=>'boutonEnvoyer')),
				array(
						array('tr' => 'HtmlTag'),
						array('tag'=> 'tr'),
				)
		);
		//decorateur du bouton formulaire complet
		$decorateurTableau = array(
				array('FormElements'),
				array('HtmlTag', array('tag'=>'table', 'id'=>'tableauCaseACocherVol'))
		);
		
				
		//Paramétre le formulaire
		$this->setMethod('post');
		$this->setAction(Zend_Registry::get('baseUrl'));
		$this->setAttrib('id', 'form_supprimer_article');
		//$this->addDecorators($decorators_form);
		
		
		$lesArticles = $this->lesArticles;
		
		foreach ($lesArticles as $unArticle ){
			
			//Champ pour le choix de suppression
			$caseACocher = new Zend_Form_Element_Checkbox(array(
					'name'		=> 'idArticle',
					'value'		=> 0,
					'checked'	=> false));
			
			$caseACocher->setRequired(true);
			//$caseACocher->setDecorators($decorators_input);
			$this -> addElement($caseACocher);

			//on récupère dans des tableaux pour chaque article:
			//l'id de l'article
			$larticle = $lesArticles->getRecuperIdArticle($unArticle->titreArticle);
			$titreArticle[$unArticle->idArticle] = $larticle['titreArticle'];
			//le titre de l'article
			$larticle = $lesArticles->getRecuperIdArticle($unArticle->idArticle);
			$titreArticle[$unArticle->titreArticle] = $larticle['idArticle'];
			//la date de l'article
			$larticle = $lesArticles->getRecuperDateArticle($unArticle->dateArticle);
			$dateArticle[$unArticle->dateArticle] = $larticle['dateArticle'];
			//le corps de l'article
			$larticle = $lesArticles->getRecuperCorps($unArticle->corps);
			$corps[$unArticle->corps] = $larticle['corps'];
			//la publication (oui/non)
			$larticle = $lesArticles->getRecuperPublication($unArticle->publication);
			$publication[$unArticle->publication] = $larticle['publication'];
			//l'id de l'utilisateur qui a créé l'article
			$larticle = $lesArticles->getRecuperIdUtilisateur($unArticle->Utilisateur_idUtilisateur);
			$idUtilisateur[$unArticle->Utilisateur_idUtilisateur] = $larticle['Utilisateur_idUtilisateur'];
			//l'id de l'image sur l'article
			$larticle = $lesArticles->getRecuperIdImage($unArticle->Image_idImage);
			$idImage[$unArticle->Image_idImage] = $larticle['Image_idImage'];
		
		}
	

		//on crée le bouton submit
		//Instancie un element type submit
		$btSubmit = new Zend_Form_Element_Submit('boutonSubmitSupprimerArticle');
		$btSubmit-> setLabel('Supprimer');
		$btSubmit -> setDecorators($decorateurBoutonEnvoyer);

		
		//Ajout des élément dans le formulaire
		$this->view->lesIdArticles = $idArticle;
		$this->view->lesTitreArticles = $titreArticle;
		$this->view->lesDateArticle = $dateArticle;
		$this->view->lesCorps = $corps;
		$this->view->lesPublications = $publication;
		$this->view->lesIdUtilisateur = $idUtilisateur;
		$this->view->lesIdImage = $idImage;


		$this->addElement($btSubmit);
			
		//Instancie class article
		$ligneInstance = new Application_Model_MArticle();


	}
}





