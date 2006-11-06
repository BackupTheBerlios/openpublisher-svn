<?php
// ----------------------------------------------------------------------
// Japa PHP Framework
// Copyright (c)  Armand Turpel < armand.turpel@open-publisher.net >
// ----------------------------------------------------------------------
// GNU LESSER GENERAL PUBLIC LICENSE
// To read the license please visit http://www.gnu.org/licenses/lgpl.txt
// ----------------------------------------------------------------------

/**
 * Php view (template) engine
 *
 * Here we use php as view language
 */
 
class JapaViewEnginePhp extends JapaViewEngine
{
    /**
     * Tokens found
     */                                    
    private $disallowedItems = array();
                                    
    /**
     * render the template
     *
     */
    public function renderView()
    {
        // get reference of the view variables
        $view = & $this->vars;
        // compatibility with smart3 
        $tpl = & $view;

        // build the whole file path to the view file
        $view_file = $this->viewFolder . $this->view . '.php';

        if($this->config['useCodeAnalyzer'] == true)
        {
            $this->disallowedItems = array();
            
            if(false == $this->analyze($view_file))
            {
                throw new JapaViewException("View php constructs not allowed: <pre>".var_export($this->disallowedItems,true)."<pre>");
            }
        }
        
        if ( !@file_exists( $view_file ) )
        {
            throw new JapaViewException("View dosent exists: ".$view_file);
        }

        ob_start();
        include( $view_file );
        $this->viewBufferContent = ob_get_clean();      
    } 
    
    /**
     * analyze allowed php tokens in view 
     *
     */
    private function analyze( & $view )
    {
        $code_is_valide = true;

        include_once(JAPA_LIBRARY_DIR . "japa/phpca/PHPCodeAnalyzer.php");
        $analyzer = new PHPCodeAnalyzer();
        $analyzer->source = file_get_contents( $view );
        $analyzer->analyze();
        
        foreach($analyzer->calledConstructs as $key => $val)
        {
            if(!in_array($key, $this->config['allowedConstructs']))
            {
                $this->disallowedItems[] = $key;
                $code_is_valide = false;
            }
        }  

        foreach($analyzer->usedVariables as $key => $val)
        {
            if(in_array($key, $this->config['disallowedVariables']))
            {
                $this->disallowedItems[] = $key;
                $code_is_valide = false;
            }
        } 

        return $code_is_valide;
    } 
}

?>