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
 * Test action (simple example)
 *
 */

/**
 * Every action must extends from the parent class SmartAction
 *
 * The name of an action class must follows the rules:
 * [Action][Module name with first letter uppercase][Action name with first letter uppercase]
 * 
 * The file name of an action must follows the rules:
 * The same as above but with extension ".php"
 *
 * An action file must be located in the specific module directory "/actions"
 *
 * Parent class vars are:
 * $this->model - The model object
 * $this->constructorData - Data passed to the constructor         
 *
 * @todo Should we introduce a permission methode or should permission
 * be done by the validate methode??
 * 
 */
class ActionCommonTest extends JapaAction
{
    /**
     * Perform on the action call
     *
     * @param mixed $data Data passed to this action
     */
    public function perform( $data = FALSE )
    {
        // Here we assign a variable with some content
        // The action caller has to evaluate its content
        //
        $data['result']['message'] = $data['message'];
    }
    
    /**
     * Validate data passed to this action
     * This methode is executed before the perform() methode
     * by the model action runner
     *
     * @param mixed $data 
     * @return bool TRUE if validation was successfull else FALSE.
     *              If FALSE the perform() methode isnt executed.
     *              There are to possibility to react if validation fails:
     *
     *              1) TURE/FALSE - The action caller have to be aware of how to
     *                 react. Ex.: Pass formular data which isnt valide
     *
     *              2) EXCEPTION - It is also possible to throw an exception
     *              if it isnt possible to proceed.
     *              Here the Exception catch have to react
     */
    public function validate( $data = FALSE )
    {
        /*************************************
         * 2 Examples of validation failures *
         *************************************
         *
         
        // If a title, which comes from a formular field
        // is empty. We assign a form error variable that has
        // to be evaluated by the action caller
        //
        if( empty($data['form_title']) )
        {
            $data['form_error'] = 'Form Title is empty';
            return FALSE;
        }
        
        // Check if article_id is an integer
        // Here it is better to throw an exception because we
        // cant procceed at all
        //
        // The Exception catch has to react
        //
        if( !is_int($data['article_id']) )
        {
            throw new SmartModelException("No valide Article ID", JAPA_ACTION_ERROR);
        }
        
        *
        */

        return TRUE;
    }    
}

?>