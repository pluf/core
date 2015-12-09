<?php

class Pluf_HTTP_Response_File extends Pluf_HTTP_Response
{
    public $delete_file = false;

    function __construct($filepath, $mimetype=null, $delete_file=false)
    {
        parent::__construct($filepath, $mimetype);
        $this->delete_file = $delete_file;
    }

    /**
     * Render a response object.
     */
    function render($output_body=true)
    {
        $this->headers['Content-Length'] = (string) filesize($this->content);
        $this->outputHeaders();
        if ($output_body) {
            $fp = fopen($this->content, 'rb');
            while(!feof($fp)) {
                $buffer = fread($fp, 2048);
                echo $buffer;
            }
            fclose($fp);
        }
        if ($this->delete_file) {
            @unlink($this->content);
        }
    }
}
