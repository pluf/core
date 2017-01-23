<?php

class Pluf_HTTP_Response_File extends Pluf_HTTP_Response
{

    public $delete_file = false;

    function __construct ($filepath, $mimetype = null, $delete_file = false)
    {
        parent::__construct($filepath, $mimetype);
        $this->delete_file = $delete_file;
    }

    /**
     * Render a response object.
     *
     * در صورتی که منبع مورد نظر وجود نداشته باشید خطای عدم وجود منبع تولید
     * خواهد شد.
     */
    function render ($output_body = true)
    {
        if (! file_exists($this->content)) {
            throw new Pluf_Exception_DoesNotExist('Requested resource not found');
        }
        $this->headers['Content-Length'] = (string) filesize($this->content);
        $this->outputHeaders();
        if ($output_body) {
            $fp = fopen($this->content, 'rb');
            while (! feof($fp)) {
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
