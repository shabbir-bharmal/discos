<?php

/*
 * 
 */
class DownloadOutput implements OutputInterface
{
    private $contentType;    
    
    public function __construct($contentType = 'text/csv')
    {
        $this->contentType = $contentType;
    }
    
    
    public function write($buffer, $filename)
    {        
        
        $headers = array(
            'Content-Type' => $this->contentType,
            'Content-Disposition' => "attachment; filename='$filename'",
        );

        return LResponse::make($buffer, 200, $headers); 
    }    
}

?>
