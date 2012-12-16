<?php 
class AjoutImage extends Zend_Form
{

    public function init()
    {
        // Champ upload "nomFichier"
		$nomFichier = new Zend_Form_Element_File("nomFichier");
		$nomFichier->setLabel("Fichier");
		$nomFichier->setRequired(true);
		$nomFichier->setDestination(PUBLIC_PATH. '/img/uploads/');		
		$this->addElement($nomFichier)->addElement('submit', 'ajouter', array('label' => 'Ajouter'));
    }
}