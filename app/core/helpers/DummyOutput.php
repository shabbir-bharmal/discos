<?php

/*
 * 
 */
class DummyOutput implements OutputInterface
{
    private $contentType;    
    
    public function __construct()
    {
    }
        
    public function write($buffer, $filename)
    {        
        echo "\n\nfilename: $filename \n";
        echo "writing out buffer \n";
        echo str_replace(",", "\n", $buffer);
    }    
}

?>
