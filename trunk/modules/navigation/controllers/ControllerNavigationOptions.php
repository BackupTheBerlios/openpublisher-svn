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
 * ControllerNavigationOptions
 *
 */
 
class ControllerNavigationOptions extends JapaControllerAbstractPage
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
    
    public function prependFilterChain()
    {
        // if no rights for the logged user, show error template
        // Only administrators 
        if($this->controllerVar['loggedUserRole'] > 20)
        {
            // reload admin
            @header('Location: '.$this->controllerVar['url_base'].'/'.$this->viewVar['adminWebController']);
            exit;  
        }
    } 
    
   /**
    * Perform on the main view
    *
    */
    public function perform()
    {   
        $this->viewVar['error'] = FALSE;

        $updateOptions = $this->httpRequest->getParameter('updateOptions', 'post', 'alnum');
        
        if(!empty($updateOptions))
        {
            if(TRUE == $this->validatePostData())
            {
                $this->model->action( 'navigation','updateConfigOptions',
                                      array('fields' => &$this->fields)); 
            }
        }

        // get all available public views
        $this->viewVar['option'] = array();
        $this->model->action( 'navigation','getAllConfigOptions',
                              array('result' => &$this->viewVar['option']) );   
                                           
        return TRUE;
    }   
   /**
    * Validate form data
    *
    */    
    private function validatePostData()
    {
        $this->viewVar['error'] = array();
        $this->fields  = array();

        $thumb_width   = $this->httpRequest->getParameter('thumb_width', 'post', 'digits');
        $img_size_max  = $this->httpRequest->getParameter('img_size_max', 'post', 'digits');
        $file_size_max = $this->httpRequest->getParameter('file_size_max', 'post', 'digits');
        $force_format  = $this->httpRequest->getParameter('force_format', 'post', 'digits');
        $use_short_text  = $this->httpRequest->getParameter('use_short_text', 'post', 'digits');
        $use_body  = $this->httpRequest->getParameter('use_body', 'post', 'digits');
        $use_logo  = $this->httpRequest->getParameter('use_logo', 'post', 'digits');
        $use_images  = $this->httpRequest->getParameter('use_images', 'post', 'digits');
        $use_files  = $this->httpRequest->getParameter('use_files', 'post', 'digits');
        $use_keywords  = $this->httpRequest->getParameter('use_keywords', 'post', 'digits');
        
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
        
        if(!empty($use_short_text) && ($use_short_text == '1'))
        {
            $this->fields['use_short_text'] = (int)$use_short_text;
        }
        else
        {
            $this->fields['use_short_text'] = 0;
        }
        
        if(!empty($use_body) && ($use_body == '1'))
        {
            $this->fields['use_body'] = (int)$use_body;
        } 
        else
        {
            $this->fields['use_body'] = 0;
        }
        
        if(!empty($use_logo) && ($use_logo == '1'))
        {
            $this->fields['use_logo'] = (int)$use_logo;
        } 
        else
        {
            $this->fields['use_logo'] = 0;
        }        
        
        if(!empty($use_images) && ($use_images == '1'))
        {
            $this->fields['use_images'] = (int)$use_images;
        } 
        else
        {
            $this->fields['use_images'] = 0;
        }
        
        if(!empty($use_files) && ($use_files == '1'))
        {
            $this->fields['use_files'] = (int)$use_files;
        } 
        else
        {
            $this->fields['use_files'] = 0;
        }

        if(!empty($use_keywords) && ($use_keywords == '1'))
        {
            $this->fields['use_keywords'] = (int)$use_keywords;
        } 
        else
        {
            $this->fields['use_keywords'] = 0;
        }
        
        if(count($this->viewVar['error']) > 0)
        {
            return false;
        }
        $this->viewVar['error'] = false;
        return true;
    }
}

?>
