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
 * ControllerArticleOptions
 *
 */
 
class ControllerArticleOptions extends JapaControllerAbstractPage
{
    /**
     * this child controller return the view in order to echo
     * @var bool $returnView
     */
    public $returnView = true;
    
    /**
     * Submited config options data array
     */
    private $fields = array();

    /**
     * prepend filter chain
     *
     */
    public function prependFilterChain()
    {
        // if no rights for the logged user, show error template
        // Only administrators 
        if($this->controllerVar['loggedUserRole'] > 20)
        {
            // reload admin
            $this->router->redirect( $this->controllerVar['adminWebController'] ); 
        }
    } 
    
   /**
    * Perform on the main view
    *
    */
    public function perform()
    {   
        if(isset($this->dontPerform))
        {
            return;
        }
        
        $this->viewVar['error'] = array();
        $this->fields  = array();

        $updateOptions = $this->httpRequest->getParameter('updateOptions', 'post', 'alnum');
        
        if(!empty($updateOptions))
        {
            if(true == $this->validatePostData())
            {
                $this->model->action( 'common','setConfigVar',
                                      array('data'   => $this->fields,
                                            'module' => 'article')); 
                
                // reload                        
                $this->router->redirect( $this->controllerVar['adminWebController'].'/mod/article/cntr/options' );
            }
        }

        // assign view vars of options
        $this->viewVar['option'] = $this->config->getModuleArray( 'article' );
    }   
    
   /**
    * Validate form data
    *
    */    
    private function validatePostData()
    {
        $thumb_width   = $this->httpRequest->getParameter('thumb_width', 'post', 'digits');
        $img_size_max  = $this->httpRequest->getParameter('img_size_max', 'post', 'digits');
        $file_size_max = $this->httpRequest->getParameter('file_size_max', 'post', 'digits');
        $use_comment = $this->httpRequest->getParameter('use_comment', 'post', 'digits');
        $default_comment_status = $this->httpRequest->getParameter('default_comment_status', 'post', 'digits');
        $use_article_controller = $this->httpRequest->getParameter('use_article_controller', 'post', 'digits');
        $use_article_controller = $this->httpRequest->getParameter('use_article_controller', 'post', 'digits');
        $force_format  = $this->httpRequest->getParameter('force_format', 'post', 'digits');
        $use_overtitle  = $this->httpRequest->getParameter('use_overtitle', 'post', 'digits');
        $use_subtitle  = $this->httpRequest->getParameter('use_subtitle', 'post', 'digits');
        $use_description  = $this->httpRequest->getParameter('use_description', 'post', 'digits');
        $use_header  = $this->httpRequest->getParameter('use_header', 'post', 'digits');
        $use_ps  = $this->httpRequest->getParameter('use_ps', 'post', 'digits');
        $use_changedate  = $this->httpRequest->getParameter('use_changedate', 'post', 'digits');
        $use_articledate  = $this->httpRequest->getParameter('use_articledate', 'post', 'digits');
        $use_logo  = $this->httpRequest->getParameter('use_logo', 'post', 'digits');
        $use_images  = $this->httpRequest->getParameter('use_images', 'post', 'digits');
        $use_files  = $this->httpRequest->getParameter('use_files', 'post', 'digits');
        $use_keywords  = $this->httpRequest->getParameter('use_keywords', 'post', 'digits');
        $default_order  = $this->httpRequest->getParameter('default_order', 'post', 'raw');
        $default_order_type  = $this->httpRequest->getParameter('default_order_type', 'post', 'alpha');
        
        if(!empty($thumb_width))
        {
            if(($thumb_width > 10) && ($thumb_width <= 350))
            {
                $this->fields['thumb_width'] = (int)$thumb_width;
            }
            else
            {
                $this->viewVar['error'][] = "Thumbnail width must be between 10 and 350!";
            }
        }
        else
        {
            $this->viewVar['error'][] = "Thumbnail width field is empty!";
        }   
        
        if(!empty($img_size_max))
        {
            if(($img_size_max > 0) && ($img_size_max <= 2000000))
            {
                $this->fields['img_size_max'] = (int)$img_size_max;
            }
            else
            {
                $this->viewVar['error'][] = "Image file size must be between 0 and 2000000!";
            }
        }
        else
        {
            $this->viewVar['error'][] = "Image file size field is empty!";
        }  
        
        if(!empty($file_size_max))
        {
            if(($file_size_max > 0) && ($file_size_max <= 25000000))
            {
                $this->fields['file_size_max'] = (int)$file_size_max;
            }
            else
            {
                $this->viewVar['error'][] = "File size must be between 100 and 25000000!";
            }
        }
        else
        {
            $this->viewVar['error'][] = "File size field is empty!";
        }   

        if(!empty($use_comment))
        {
            $this->fields['use_comment'] = 1;
        }
        else
        {
            $this->fields['use_comment'] = 0;
        }  

        if(!empty($default_comment_status))
        {
            $this->fields['default_comment_status'] = (int)$default_comment_status;
        }  

        if(!empty($use_article_controller))
        {
            $this->fields['use_article_controller'] = 1;
        }
        else
        {
            $this->fields['use_article_controller'] = 0;
        }  
        
        if(!empty($force_format))
        {
            if(($force_format >= 0) && ($force_format <= 2))
            {
                $this->fields['force_format'] = (int)$force_format;
            }
        } 
        
        if(!empty($default_format))
        {
            if(($default_format >= 0) && ($default_format <= 2))
            {
                $this->fields['default_format'] = (int)$default_format;
            }
        }  
        
        if(!empty($use_overtitle))
        {
            $this->fields['use_overtitle'] = 1;
        }
        else
        {
            $this->fields['use_overtitle'] = 0;
        }
        
        if(!empty($use_subtitle))
        {
            $this->fields['use_subtitle'] = 1;
        } 
        else
        {
            $this->fields['use_subtitle'] = 0;
        }
        
        if(!empty($use_description))
        {
            $this->fields['use_description'] = 1;
        }
        else
        {
            $this->fields['use_description'] = 0;
        }
        
        if(!empty($use_header))
        {
            $this->fields['use_header'] = 1;
        } 
        else
        {
            $this->fields['use_header'] = 0;
        }  
        
        if(!empty($use_ps))
        {
            $this->fields['use_ps'] = 1;
        } 
        else
        {
            $this->fields['use_ps'] = 0;
        }         

        if(!empty($use_changedate))
        {
            $this->fields['use_changedate'] = 1;
        } 
        else
        {
            $this->fields['use_changedate'] = 0;
        }  
        
        if(!empty($use_articledate))
        {
            $this->fields['use_articledate'] = 1;
        } 
        else
        {
            $this->fields['use_articledate'] = 0;
        }         
        
        if(!empty($use_logo))
        {
            $this->fields['use_logo'] = 1;
        } 
        else
        {
            $this->fields['use_logo'] = 0;
        }        
        
        if(!empty($use_images))
        {
            $this->fields['use_images'] = 1;
        } 
        else
        {
            $this->fields['use_images'] = 0;
        }
        
        if(!empty($use_files))
        {
            $this->fields['use_files'] = 1;
        } 
        else
        {
            $this->fields['use_files'] = 0;
        }

        if(!empty($default_order))
        {
            $this->fields['default_order'] = (string)$default_order;
        } 
        else
        {
            $this->fields['default_order'] = 'rank';
        }
        
        if(!empty($default_ordertype))
        {
            $this->fields['default_ordertype'] = (string)$default_ordertype;
        } 
        else
        {
            $this->fields['default_ordertype'] = 'asc';
        }

        if(!empty($use_keywords))
        {
            $this->fields['use_keywords'] = 1;
        } 
        else
        {
            $this->fields['use_keywords'] = 0;
        }
        
        if(count($this->viewVar['error']) > 0)
        {
            return FALSE;
        }
        
        return TRUE;
    }
}

?>
