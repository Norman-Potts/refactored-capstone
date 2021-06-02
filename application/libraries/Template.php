<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 /** Class Template
		Purpose: Used to automatically build views with a header, navigation and footer.
	
 */
class Template
{
    function show($view, $args = NULL)
    {
        $CI =& get_instance();		
        $CI->load->view('baseStructures/header_view',$args);
        $CI->load->view('baseStructures/navBar_view',$args);
        $CI->load->view('BodyStructures/'.$view, $args);
        $CI->load->view('baseStructures/footer_view',$args);
    }
}