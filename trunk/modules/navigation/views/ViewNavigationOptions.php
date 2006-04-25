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
 * ViewNavigationOptions
 *
 */
 
class ViewNavigationOptions extends SmartView
{
   /**
     * Default template for this view
     * @var string $template
     */
    public  $template = 'options';
    
   /**
     * Default template folder for this view
     * @var string $template_folder
     */    
    public  $templateFolder = 'modules/navigation/templates/';
    
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
        // only administrators can change options
        if($this->viewVar['loggedUserRole'] > 20)
        {
            $this->template       = 'error';
            $this->templateFolder = 'modules/common/templates/';
            $this->tplVar['error'] = 'You have not the rights to change navigation module options!';
            $this->dontPerform = TRUE;
        }
    } 
    
   /**
    * Perform on the main view
    *
    */
    public function perform()
    {   
        $this->tplVar['error'] = FALSE;
        
        if(isset($_POST['updateOptions']))
        {
            if(TRUE == $this->validatePostData())
            {
                $this->model->action( 'navigation','updateConfigOptions',
                                      array('fields' => &$this->fields)); 
            }
        }

        // get all available public views
        $this->tplVar['option'] = array();
        $this->model->action( 'navigation','getAllConfigOptions',
                              array('result' => &$this->tplVar['option']) );   
                                           
        return TRUE;
    }   
   /**
    * Validate form data
    *
    */    
    private function validatePostData()
    {
        $this->tplVar['error'] = array();
        $this->fields  = array();
        
        if(isset($_POST['thumb_width']) && !empty($_POST['thumb_width']))
        {
            if(($_POST['thumb_width'] > 10) && ($_POST['thumb_width'] <= 350))
            {
                $this->fields['thumb_width'] = (int)$_POST['thumb_width'];
            }
            else
            {
                $this->tplVar['error'][] = "Thumbnail width must be between 10 and 250!";
            }
        }
        else
        {
            $this->tplVar['error'][] = "Thumbnail width field is empty!";
        }   
        
        if(isset($_POST['img_size_max']) && !empty($_POST['img_size_max']))
        {
            if(($_POST['img_size_max'] > 0) && ($_POST['img_size_max'] <= 10000000))
            {
                $this->fields['img_size_max'] = (int)$_POST['img_size_max'];
            }
            else
            {
                $this->tplVar['error'][] = "Image file size must be between 100 and 3000000!";
            }
        }
        else
        {
            $this->tplVar['error'][] = "Image file size field is empty!";
        }  
        
        if(isset($_POST['file_size_max']) && !empty($_POST['file_size_max']))
        {
            if(($_POST['file_size_max'] > 0) && ($_POST['file_size_max'] <= 25000000))
            {
                $this->fields['file_size_max'] = (int)$_POST['file_size_max'];
            }
            else
            {
                $this->tplVar['error'][] = "File size must be between 100 and 3000000!";
            }
        }
        else
        {
            $this->tplVar['error'][] = "File size field is empty!";
        }   
        
        if(isset($_POST['force_format']) && !empty($_POST['force_format']))
        {
            if(($_POST['force_format'] >= 0) && ($_POST['force_format'] <= 2))
            {
                $this->fields['force_format'] = (int)$_POST['force_format'];
            }
        } 
        
        if(isset($_POST['default_format']) && !empty($_POST['default_format']))
        {
            if(($_POST['default_format'] >= 0) && ($_POST['default_format'] <= 2))
            {
                $this->fields['default_format'] = (int)$_POST['default_format'];
            }
        }  
        
        if(isset($_POST['use_short_text']) && ($_POST['use_short_text'] == '1'))
        {
            $this->fields['use_short_text'] = (int)$_POST['use_short_text'];
        }
        else
        {
            $this->fields['use_short_text'] = 0;
        }
        
        if(isset($_POST['use_body']) && ($_POST['use_body'] == '1'))
        {
            $this->fields['use_body'] = (int)$_POST['use_body'];
        } 
        else
        {
            $this->fields['use_body'] = 0;
        }
        
        if(isset($_POST['use_logo']) && ($_POST['use_logo'] == '1'))
        {
            $this->fields['use_logo'] = (int)$_POST['use_logo'];
        } 
        else
        {
            $this->fields['use_logo'] = 0;
        }        
        
        if(isset($_POST['use_images']) && ($_POST['use_images'] == '1'))
        {
            $this->fields['use_images'] = (int)$_POST['use_images'];
        } 
        else
        {
            $this->fields['use_images'] = 0;
        }
        
        if(isset($_POST['use_files']) && ($_POST['use_files'] == '1'))
        {
            $this->fields['use_files'] = (int)$_POST['use_files'];
        } 
        else
        {
            $this->fields['use_files'] = 0;
        }

        if(isset($_POST['use_keywords']) && ($_POST['use_keywords'] == '1'))
        {
            $this->fields['use_keywords'] = (int)$_POST['use_keywords'];
        } 
        else
        {
            $this->fields['use_keywords'] = 0;
        }
        
        if(count($this->tplVar['error']) > 0)
        {
            return FALSE;
        }
        $this->tplVar['error'] = FALSE;
        return TRUE;
    }
}

?>
