<?php

/********************************************************\
 * File: 	CvModule.php								*
 * Author: 	Andreas Göransson							*
 * Date: 	2011-12-04									*
 * Organization: Andreas Göransson						*
 *														*
 * Project: 	phpPortfolio.							*
 *														*
 * Description:	Admin module core.						*
\********************************************************/

class CvModule{
	
	protected $error;
	
	function __construct() {
	}

	function __destruct() {
	}
   
	function Name(){
		return get_class($this);
	}
	
	// Caution!! Don't override this method, your menu might get b0rked! 
	function Menu($selected = false){
		if( Login::LoggedIn() )
			return '<li class="modulelistitem"><a class="moduleitem' . ($selected == true ? ' moduleselected':'') . '" href="admin.php?mod=' . get_class($this) . '">' . get_class($this) . '</a></li>';
	}
	
	// Overload this function for adding custom module description or tutorial.
	function Description(){
		if( Login::LoggedIn() )
			return '<table><tr><td>This is the default description.</td></tr><tr><td>of the default module.</td></tr></table>';
	}
	
	// This is the content space, fill this with whatever content you want.
	// Custom CSS rules should be loaded within ./modules/ClassName.css, your rules will then be loaded automatically
	// When submitting forms it's important that we place the Class Name (f.ex. using $this->Name()) as the name attribute
	function Content(){
		return '<table><tr><td>This is the default content.</td></tr><tr><td>of the default module.</td></tr></table>';
	}
	
	// You must override this method when using HTTP POST
	function POST(){
		print_r($_POST);
	}
	
	// You must override this method when using HTTP GET
	function GET(){
		print_r($_GET);
	}
	
	// Caution, don't override this function, lest your site be filled with errors!
	function Error(){
		if( is_array($this->error) )
			return (strlen(implode($this->error)) > 0 ? implode("<br />",$this->error) : false );
		else
			return (strlen($this->error) > 0 ? $this->error : false );
	}
}
?>