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
 * ActionCommonRssOutput
 *
 * USAGE:
 *    $this->model->action('common','rssBuilderOutput',
                           array('format'  => (string) output|save|get,
                                 'version' => (string) 2.0|1.0|0.91,
                                 'path'    => (string)  ));
 *
 */
 
/**
 * 
 */
class ActionCommonRssBuilderOutput extends SmartAction
{
    /**
     * Perform on the action call
     *
     * @param mixed $data Data passed to this action
     */
    public function perform( $data = FALSE )
    {
        switch( $data['format'] )
        {
            case 'output':
              $data['rssObject']->outputRSS( $data['version'] );
              exit;
            case 'save':
              return $data['rssObject']->saveRSS( $data['version'], $data['path'] );
            case 'get':
              return $data['rssObject']->getRSSOutput( $data['version'] );
        }
    }
    /**
     */
    public function validate( & $data )
    {
        if(!isset($data['rssObject']))
        {
            throw new SmartModelException("Missing 'rssObject' object var"); 
        } 
        if(!is_object($data['rssObject']))
        {
            throw new SmartModelException("Var 'rssObject' isnt from type object"); 
        }     
        
        if(!isset( $data['format'] ))
        {
            throw new SmartModelException("No RSS 'format' defined");  
        }
        else
        {
            if(!preg_match("/output|save|get/", $data['format']))
            {
                throw new SmartModelException("Wrong RSS 'format' definition");  
            }   
            
            if($data['format'] == 'save')
            {
                if(!isset($data['path']))
                {
                    throw new SmartModelException("No RSS 'path' defined"); 
                }
                if(!is_string($data['path']))
                {
                    throw new SmartModelException("No RSS 'path' isnt from type string"); 
                }                
            }
        }
        
        if(!isset( $data['version'] ))
        {
            throw new SmartModelException("No RSS 'version' defined");  
        }
        else
        {
            if(!is_string($data['version']))
            {
                throw new SmartModelException("RSS 'version' isnt from type string");  
            }         
            if(!preg_match("/2\.0|1\.0|0\.91/", $data['version']))
            {
                throw new SmartModelException("Wrong RSS 'version': " . $data['version']);  
            }
        }

        return TRUE;
    }    
}

?>