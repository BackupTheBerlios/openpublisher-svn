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
 * ActionSetupInsertSampleContent class 
 *
 * USAGE:
 * $model->action('setup','insertSampleContent',
 *                array('prefix' => string))     // DBtablePrefix
 *
 */
class ActionSetupInsertSampleContent extends SmartAction
{                            
    /**
     * insert sample data
     *
     * @param array $data
     */
    function perform( $data = FALSE )
    { 
        $dir1 = SMART_BASE_DIR . 'data/article/918413323';
        $dir2 = SMART_BASE_DIR . 'data/article/1725715675';
        
        if(!is_dir($dir1))
        {
            if(!mkdir($dir1))
            {
                trigger_error("cant make dir: " . $dir1, E_USER_ERROR);
            }
            else
            {
                if(!copy(SMART_BASE_DIR.'modules/setup/sample_content/smart3.gif', $dir1.'/smart3.gif'))
                {
                    trigger_error("cant copy file: " . $dir1.'/smart3.gif', E_USER_ERROR);
                }
            }
            if(!mkdir($dir1.'/thumb'))
            {
                trigger_error("cant make dir: " . $dir1.'/thumb', E_USER_ERROR);
            }
            else
            {
                if(!copy(SMART_BASE_DIR.'modules/setup/sample_content/smart3thumb.gif', $dir1.'/thumb/smart3.gif'))
                {
                    trigger_error("cant copy file: " . $dir1.'/thumb/smart3.gif', E_USER_ERROR);
                }
            }            
        }
        
        if(!is_dir($dir2))
        {
            if(!mkdir($dir2))
            {
                trigger_error("cant make dir: " . $dir2, E_USER_ERROR);
            }
            else
            {
                if(!copy(SMART_BASE_DIR.'modules/setup/sample_content/smart3.gif', $dir2.'/smart3.gif'))
                {
                    trigger_error("cant copy file: " . $dir2.'/smart3.gif', E_USER_ERROR);
                }
            }
            if(!mkdir($dir2.'/thumb'))
            {
                trigger_error("cant make dir: " . $dir2.'/thumb', E_USER_ERROR);
            }
            else
            {
                if(!copy(SMART_BASE_DIR.'modules/setup/sample_content/smart3thumb.gif', $dir2.'/thumb/smart3.gif'))
                {
                    trigger_error("cant copy file: " . $dir2.'/thumb/smart3.gif', E_USER_ERROR);
                }
            }              
        } 

        if(is_readable(SMART_BASE_DIR."modules/setup/sample_content/smart3.sql"))
        {
            $file_c = file(SMART_BASE_DIR."modules/setup/sample_content/smart3.sql");
            foreach($file_c as $line)
            {
                if(preg_match("/^INSERT/",$line))
                {
                    if("smart_" != $data['prefix'])
                    {
                        $line = str_replace("INSERT INTO `smart_","INSERT INTO `".$data['prefix'],$line);
                    }
                    $sql = preg_replace("/;\r\n$/","",$line);
                    $this->model->dba->query($sql);   
                }
            }
        }
    }
    
    public function validate( $data = FALSE )
    {
        if(!isset($data['prefix']))
        {
            throw new SmartModelException("data 'prefix' key is required");
        }
        
        if(!is_string($data['prefix']))
        {
            throw new SmartModelException("data 'prefix' key value isnt from type string");
        }        
        
        return TRUE;
    }
}

?>
