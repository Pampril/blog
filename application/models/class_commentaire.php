<?php

class Commentaires extends Zend_Db_Table_Abstract
{
	protected $_name='commentaires';
	protected $_primary=array('id');
	
	protected $_referenceMap = array(
			'idArticle' => array(
					'columns' => 'id',
					'refTableClass' =>'articles'));
	
	
}
?>