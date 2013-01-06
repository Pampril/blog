<?php 
class AjoutImage extends Zend_Form
{

    public function init()
    {
       	$image = new Zend_Form_Element_File("image");
		$image->setLabel("Fichier");
		$image->setRequired(true);
		$image->addValidator('Size', false, '10MB')
			  ->addValidator('Extension', false,'jpg,png,gif');
		$image->setDecorators(
						array('File', 'Description', 'Errors',
						array(array('data'=>'HtmlTag'), array('tag' => 'td')),
						array('Label', array('tag' => 'th')),
						array(array('row'=>'HtmlTag'),array('tag'=>'tr'))
				));
		$image->setAttribs(array( 'enctype' => 'multipart/form-data'));
		
		$image->setDestination(PUBLIC_PATH. '/img/uploads/');
		$this->addElement($image)->addElement('submit', 'ajouter', array('label' => 'Ajouter'));
		
    }
}