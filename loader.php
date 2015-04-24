<?php
//	ini_set('display_errors',1);
		
	function __autoload($class_name) {
		$array_paths = array(
	        'classes/', 
	        'controller/',
	        'view/'
	    );
	    
		# Count the total item in the array.
	    $total_paths = count($array_paths);
	
	    # Set the class file name.
	    $file_name = $class_name.'.class.php';
	
	    # Loop the array.
	    $app_root = realpath( dirname(__FILE__) ) . '/';
	    for ($i = 0; $i < $total_paths; $i++) 
	    {
	        if(file_exists($app_root.$array_paths[$i].$file_name)) 
	        {
	            require_once $app_root.$array_paths[$i].$file_name;
	        } 
	    }
	}
?>