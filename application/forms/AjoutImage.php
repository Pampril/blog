<?php 
class AjoutImage extends Zend_Form
{

    public function init()
    {
        // Champ upload "nomFichier"
		//$nomFichier = new Zend_Form_Element_File('nomFichier');
    	//Img
    	$nomFichier = $this->createElement('file', 'nomFichier');
    	
    	
    	 
		$nomFichier->setDestination(PUBLIC_PATH. '/img/uploads/');
		$nomFichier->setLabel('Fichier');
		$nomFichier->setRequired(true);
		
		$this->setMethod('POST');
		$this->setAction('/images/ajoutimages');
		$this->setEnctype(Zend_Form::ENCTYPE_MULTIPART);
				
		$this->addElement($nomFichier)->addElement('submit', 'ajouter', array('label' => 'Ajouter'));
    }
}