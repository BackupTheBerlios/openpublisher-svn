<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_View
 * @subpackage Helper
 * @copyright  Copyright (c) 2006 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */


/**
 * Abstract class for extension
 */
require_once 'Zend/View/Helper/FormElement.php';


/**
 * Helper to generate a "submit" button
 * 
 * @category   Zend
 * @package    Zend_View
 * @subpackage Helper
 * @copyright  Copyright (c) 2006 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_View_Helper_FormSubmit extends Zend_View_Helper_FormElement {
    
    /**
     * Generates a 'submit' button.
     * 
     * @access public
     * 
     * @param string|array $name If a string, the element name.  If an
     * array, all other parameters are ignored, and the array elements
     * are extracted in place of added parameters.
     * 
     * @param mixed $value The element value.
     * 
     * @param array $attribs Attributes for the element tag.
     * 
     * @return string The element XHTML.
     */
    public function formSubmit($name, $value = null, $attribs = null)
    {
        $info = $this->_getInfo($name, $value, $attribs);
        extract($info); // name, value, attribs, options, listsep, disable
        
        // ignore disable/enable, always show the button.
        $xhtml = '<input type="submit"'
               . ' name="' . htmlspecialchars($name, ENT_COMPAT, 'UTF-8') . '"'
               . ' id="' . htmlspecialchars($id, ENT_COMPAT, 'UTF-8') . '"';
        
        // add a value if one is given
        if (! empty($value)) {
            $xhtml .= ' value="' . htmlspecialchars($value, ENT_COMPAT, 'UTF-8') . '"';
        }
        
        // add attributes, close, and return
        $xhtml .= $this->_htmlAttribs($attribs) . ' />';
        return $xhtml;
    }
}
