<?php 

class ImagesController extends Zend_Controller_Action
{
	public function indexAction()
	{
		
	}
	
	public function ajoutimagesAction()
	{
		$image= new Images();    	
    	$addImage = $image->createRow();
    	
		// Création du formulaire et déclaration des paramètres généraux
		$form = new AjoutImage();
		
	
		        
		//
		if($this->getRequest()->isPost() && $form->isValid($_POST))
		{
		    // Retrait des informations depuis les données en post
		    // et ajout dans le modèle
// 		    $values = $form->getValues();		    
// 		    $addImage->setFromArray(array_intersect_key($values, $addImage->toArray()));
		    
// 		    var_dump($values);   
		   
// 		    $addImage->save();		

			echo $_POST['nomFichier'];
        }
        
       echo $form;
	}

}