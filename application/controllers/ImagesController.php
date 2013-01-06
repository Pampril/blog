<?php 

class ImagesController extends Zend_Controller_Action
{
	public function indexAction()
	{
		
	}
	
	public function ajoutimagesAction()
	{
		$form = new AjoutImage;
		
		if(isset($_POST['ajouter']))
		{	
			//récup les données formulaire			
			$fichier=$form->getValues();
			
			$upload = '/img/uploads/'.$fichier['image'];				
			
			$data['destination'] = $upload ;
			
			$image = new Images;
			$ajoutImage = $image->createRow($data);
			$ajoutImage->save();
		}
		else 
		{
			
			echo $form;
			
		}
	}
	
	//affichage de la liste des images
	public function listeimagesAction()
	{
		//affiche des images
		$image = new Images;
		$lesImages = $image->fetchAll();
	
		$this->view->lesImages=$lesImages;
	
	}

}