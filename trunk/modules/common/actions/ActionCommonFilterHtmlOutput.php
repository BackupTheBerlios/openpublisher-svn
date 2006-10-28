<?php
// ----------------------------------------------------------------------
// Japa PHP Framework
// Copyright (c)  Armand Turpel < armand.turpel@open-publisher.net >2004, 2005


// ----------------------------------------------------------------------
// LICENSE GPL
// To read the license please visit http://www.gnu.org/copyleft/gpl.html
// ----------------------------------------------------------------------

/**
 * ActionCommonFilterHtmlOutput
 *
 * USAGE:
 * $model->action( 'common', 'filterHtmlOutput', 
 *                 array('str'     => & (string) ,
 *                       'filters' => array('stripComments',
 *                                          'trimOutput') );
 *
 */

class ActionCommonFilterHtmlOutput extends JapaAction
{
    /**
     * apply filters
     *
     * @param mixed $data
     */
    public function perform( $data = false )
    {
        foreach($data['filters'] as $filter)
        {
            $this->$filter( $data['str'] );
        }
    }
    /**
     * validate array data
     *
     * @param mixed $data
     */
    public function validate( $data = false )
    {
        // this var must be defined
        // it contains the string to filter
        //
        if( !isset($data['str']) )
        {
            throw new JapaModelException('"str" var isnt defined!');      
        }
        
        // this var must be defined
        // it contains the filter names
        if( !isset($data['filters']) )
        {
            throw new JapaModelException('"filters" var isnt defined!');    
        }
        
        if(!is_array($data['filters']))
        {
            throw new JapaModelException('"filters" var isnt from type array!');  
        }
        
        // check if the filter names are valide
        foreach($data['filters'] as $filter)
        {
            if(!preg_match("/stripComments|trimOutput/", $filter ))
            {
                throw new JapaModelException('In array "filters" unknown value: ' . $filter); 
            }
        }
        
        // if every thing is fine this methode must return true
        // else the perform methode isnt executed
        return true;
    }   
    
    /**
     * strip html comments from a string 
     *
     * @param string $str
     */
    private function stripComments( & $str )
    {
        $str = preg_replace("/<!--([^-]*([^-]|-([^-]|-[^>])))*-->/", "", $str );    
    }
    
    /**
     * trim a string (remove empty spaces before and after a string) 
     *
     * @param string $str
     */
    private function trimOutput( & $str )
    {
        $str = trim( $str );     
    }
}

?>