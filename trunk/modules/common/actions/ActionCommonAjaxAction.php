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
 * ActionCommonAjaxAction
 *
 * USAGE:
 *                                                  
    $this->model->action('common','ajaxAction',
                         array('insertAlert'  => (string),
                               'assignAttr'   => array('id'      => (string),
                                                       'attr'    => (string),
                                                       'message' => (string)),
                               'insertScript' => (string),
                               'prependAttr'  => array('id'      => (string),
                                                       'attr'    => (string),
                                                       'message' => (string)),
                               'appendAttr'   => array('id'      => (string),
                                                       'attr'    => (string),
                                                       'message' => (string)),
                               'clearAttr'    => array('id'      => (string),
                                                       'attr'    => (string)),
                               'createNode'   => array('id'      => (string),
                                                       'attr'    => (string),
                                                       'tag'     => (string),
                                                       'type'    => (string)),
                               'removeNode'   => (string),
                               'replaceNode'  => array('id'      => (string),
                                                       'attr'    => (string),
                                                       'tag'     => (string)) ));  

 *
 */

include_once( JAPA_LIBRARY_DIR . 'PEAR/HTML/AJAX/Action.php');

/**

 * 
 */
class ActionCommonAjaxAction extends JapaAction
{
    /**
     * Perform on the action call
     *
     * @param mixed $data Data passed to this action
     */
    public function perform( $data = FALSE )
    {
        // Create a response object
        if(!isset( $this->model->objResponse ))
        {
            $this->model->objResponse = new HTML_AJAX_Action();
        }
        
        if( isset($data['assignAttr']) )
        {
            $this->model->objResponse->assignAttr( $data['assignAttr']['id'], 
                                                   $data['assignAttr']['attr'], 
                                                   $data['assignAttr']['message'] );
        }
        
        if( isset($data['insertScript']) )
        {
            $this->model->objResponse->insertScript( $data['insertScript'] );
        }        

        if( isset($data['prependAttr']) )
        {
            $this->model->objResponse->prependAttr( $data['prependAttr']['id'], 
                                                    $data['prependAttr']['attr'], 
                                                    $data['prependAttr']['message'] );
        }
        
        if( isset($data['appendAttr']) )
        {
            $this->model->objResponse->appendAttr( $data['appendAttr']['id'], 
                                                   $data['appendAttr']['attr'], 
                                                   $data['appendAttr']['message'] );
        }
        
        if( isset($data['clearAttr']) )
        {
            $this->model->objResponse->clearAttr( $data['clearAttr']['id'], 
                                                  $data['clearAttr']['attr'] );
        }
        
        if( isset($data['createNode']) )
        {
            $this->model->objResponse->createNode( $data['createNode']['id'], 
                                                   $data['createNode']['tag'],
                                                   $data['createNode']['attr'], 
                                                   $data['createNode']['type'] );
        }        
        
        if( isset($data['insertAlert']) )
        {
            $this->model->objResponse->insertAlert( $data['insertAlert'] );
        }
        
        if( isset($data['removeNode']) )
        {
            $this->model->objResponse->removeNode( $data['removeNode'] );
        }   
        
        if( isset($data['replaceNode']) )
        {
            $this->model->objResponse->replaceNode( $data['replaceNode']['id'], 
                                                    $data['replaceNode']['tag'],
                                                    $data['replaceNode']['attr'] );
        } 
        
        return $this->model->objResponse;
    }
    
    /**
     */
    public function validate( $data = FALSE )
    {
        if( isset( $data['assignAttr'] ) )
        {
            if( !is_array( $data['assignAttr'] ) )
            {
                throw new JapaModelException("'assignAttr' isnt from type array");            
            }
            if( !isset( $data['assignAttr']['id'] ) )
            {
                throw new JapaModelException("In array 'assignAttr' 'id' is required");            
            }  
            if( !isset( $data['assignAttr']['attr'] ) )
            {
                throw new JapaModelException("In array 'assignAttr' 'attr' is required");            
            }  
            if( !isset( $data['assignAttr']['message'] ) )
            {
                throw new JapaModelException("In array 'assignAttr' 'message' is required");            
            }             
        }
        if( isset( $data['prependAttr'] ) )
        {
            if( !is_array( $data['prependAttr'] ) )
            {
                throw new JapaModelException("'prependAttr' isnt from type array");            
            }
            if( !isset( $data['prependAttr']['id'] ) )
            {
                throw new JapaModelException("In array 'prependAttr' 'id' is required");            
            }  
            if( !isset( $data['prependAttr']['attr'] ) )
            {
                throw new JapaModelException("In array 'prependAttr' 'attr' is required");            
            }  
            if( !isset( $data['prependAttr']['message'] ) )
            {
                throw new JapaModelException("In array 'prependAttr' 'message' is required");            
            }             
        }
        if( isset( $data['appendAttr'] ) )
        {
            if( !is_array( $data['appendAttr'] ) )
            {
                throw new JapaModelException("'appendAttr' isnt from type array");            
            }
            if( !isset( $data['appendAttr']['id'] ) )
            {
                throw new JapaModelException("In array 'appendAttr' 'id' is required");            
            }  
            if( !isset( $data['appendAttr']['attr'] ) )
            {
                throw new JapaModelException("In array 'appendAttr' 'attr' is required");            
            }  
            if( !isset( $data['appendAttr']['message'] ) )
            {
                throw new JapaModelException("In array 'appendAttr' 'message' is required");            
            }             
        }    
        if( isset( $data['clearAttr'] ) )
        {
            if( !is_array( $data['clearAttr'] ) )
            {
                throw new JapaModelException("'clearAttr' isnt from type array");            
            }
            if( !isset( $data['clearAttr']['id'] ) )
            {
                throw new JapaModelException("In array 'clearAttr' 'id' is required");            
            }  
            if( !isset( $data['clearAttr']['attr'] ) )
            {
                throw new JapaModelException("In array 'clearAttr' 'attr' is required");            
            }            
        }   
        if( isset( $data['createNode'] ) )
        {
            if( !is_array( $data['createNode'] ) )
            {
                throw new JapaModelException("'createNode' isnt from type array");            
            }
            if( !isset( $data['createNode']['id'] ) )
            {
                throw new JapaModelException("In array 'createNode' 'id' is required");            
            }  
            if( !isset( $data['createNode']['attr'] ) )
            {
                throw new JapaModelException("In array 'createNode' 'attr' is required");            
            }  
            if( !isset( $data['createNode']['tag'] ) )
            {
                throw new JapaModelException("In array 'createNode' 'tag' is required");            
            } 
            if( !isset( $data['createNode']['type'] ) )
            {
                throw new JapaModelException("In array 'createNode' 'type' is required");            
            }             
        }  
        if( isset( $data['replaceNode'] ) )
        {
            if( !is_array( $data['replaceNode'] ) )
            {
                throw new JapaModelException("'replaceNode' isnt from type array");            
            }
            if( !isset( $data['replaceNode']['id'] ) )
            {
                throw new JapaModelException("In array 'replaceNode' 'id' is required");            
            }  
            if( !isset( $data['replaceNode']['attr'] ) )
            {
                throw new JapaModelException("In array 'replaceNode' 'attr' is required");            
            }  
            if( !isset( $data['replaceNode']['tag'] ) )
            {
                throw new JapaModelException("In array 'replaceNode' 'tag' is required");            
            }             
        }         
        return TRUE;
    }  
}

?>