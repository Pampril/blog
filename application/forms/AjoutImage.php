<?php 
class AjoutImage extends Zend_Form
{

    public function init()
    {
       	$image = new Zend_Form_Element_File('image');
		$image->setLabel('Image')
				->addValidator('Size', false, '10MB')
				->addValidator('Extension', false,'jpg,png,gif')
				->setDecorators(
						array('File', 'Description', 'Errors',
							array(array('data'=>'HtmlTag'), array('tag' => 'td')),
							array('Label', array('tag' => 'th')),
							array(array('row'=>'HtmlTag'),array('tag'=>'tr'))
						)
					)
				->setAttribs(array(  'id' => 'id_form_image' 
				, 'enctype' => 'multipart/form-data'
				))
				->setValueDisabled(true)
                ->setDestination(PUBLIC_PATH.'/img/uploads');
				
		
		
		$this->addElement($image, 'image');	
		$this->addElement('submit', 'ajouter', array('label' => 'Ajouter'));
    }
}