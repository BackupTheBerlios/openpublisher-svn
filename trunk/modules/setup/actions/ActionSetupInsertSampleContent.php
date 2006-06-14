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
        if(is_readable(SMART_BASE_DIR."modules/setup/sample_content/example.sql"))
        {
            $file_c = file(SMART_BASE_DIR."modules/setup/sample_content/example.sql");
            foreach($file_c as $line)
            {
                if(preg_match("/^INSERT/",$line))
                {
                    if("smart_" != $data['prefix'])
                    {
                        $line = str_replace("INSERT INTO `op_","INSERT INTO `".$data['prefix'],$line);
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
