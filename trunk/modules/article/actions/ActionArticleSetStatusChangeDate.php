<?php
// ---------------------------------------------
// Open Publisher CMS
// Copyright (c) 2006
// by Armand Turpel < cms@open-publisher.net >
// http://www.open-publisher.net/
// ---------------------------------------------
// LICENSE LGPL
// http://www.gnu.org/licenses/lgpl.html
// ---------------------------------------------

/**
 * ActionArticleUpdateArticle class 
 *
 * USAGE:
 * $model->action('article','setStatusChangeDate',
 *                array('id_article' => int,
 *                      'remove'     => bool,
 *                      'status'     => int,
 *                      'date'       => string)))
 */
 
class ActionArticleSetStatusChangeDate extends SmartAction
{
    /**
     * update/set article status date
     *
     * @param array $data
     */
    function perform( $data = FALSE )
    {
        if(isset($data['remove']) && ($data['remove'] == true))
    	{
        	$sql = "DELETE FROM {$this->config['dbTablePrefix']}article_changedate
                           WHERE `id_article` = {$data['id_article']}";

            $this->model->dba->query($sql);              
        }
        else
    	{
        	// update article changed status
            $sql = "REPLACE INTO {$this->config['dbTablePrefix']}article_changedate
                        SET `id_article` = {$data['id_article']},
                               `changedate` = DATE_SUB('{$this->model->dba->escape($data['date'])}',INTERVAL {$this->model->action('common', 'getGmtOffset')}  HOUR),
                               `status`     = {$data['status']}";

            $this->model->dba->query($sql);
        }        
    } 
    /**
     * validate data array
     *
     * @param array $data
     * @return bool true or false on error
     */    
    public function validate( $data = FALSE )
    {
        if(!isset($data['id_article']))
        {
            throw new SmartModelException('"id_article" isnt set');        
        }
        
        if(!is_int($data['id_article']))
        {
            throw new SmartModelException('"id_article" isnt from type int');        
        }

		if(isset($data['remove']) && ($data['remove'] == true))
		{
		  	return true;
		}

        if(!isset($data['status']))
        {
            throw new SmartModelException('"status" isnt set');        
        }
        
        if(!is_int($data['status']))
        {
            throw new SmartModelException('"status" isnt from type int');        
        }

        if(!isset($data['date']))
        {
            throw new SmartModelException('"date" isnt set');        
        }
        
        if(!preg_match("/[0-9]{4}-[0-9]{1,2}-[0-9]{1,2} [0-9]{1,2}:[0-9]{1,2}:[0-9]{1,2}/",$data['date']))
        {
            throw new SmartModelException('"date" has wrong format');        
        }

        return TRUE;
    }
}

?>
