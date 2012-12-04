<?php
class Articles extends Zend_Db_Table_Abstract
{
	protected $_name='articles';
	protected $_primary=array('id');
	
	public function selectMax()
	{
		$db = Zend_Db_Table::getDefaultAdapter();
		
		$select = $this->select()->limit(1, 0);
    	return $this->fetchAll($select);
        
	}
}
?>