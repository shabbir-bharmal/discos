<?php
class modules {

	/**
	* Run a module or controller method
	* Output from module is buffered and returned.
	**/
	public static function run($module)
    {
		
		$method = 'index';
		
		if(($pos = strrpos($module, '/')) != FALSE)
        {
			$method = substr($module, $pos + 1);		
			$module = substr($module, 0, $pos);
		}
			
		if (method_exists($module, $method))
        {
			ob_start();
			$args = func_get_args();
			$output = call_user_func_array(array($module, $method), array_slice($args, 1));
			$buffer = ob_get_clean();
			return ($output !== NULL) ? $output : $buffer;
		}
	}
}