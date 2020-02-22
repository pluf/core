<?php

/*
 * This file is part of Pluf Framework, a simple PHP Application Framework.
 * Copyright (C) 2010-2020 Phoinex Scholars Co. (http://dpq.co.ir)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */
namespace Pluf\HTTP\Response;

use Pluf\DoesNotExistException;

/**
 * TODO: maso, 1395: document
 */
class ResumableFile extends \Pluf\HTTP\Response
{

    private $file;

    private $name;

    private $boundary;

    private $delay = 0;

    private $totalSize = 0;

    private $httpRange;

    function __construct($filepath, $httpRange, $fileName, $mimetype = null, $delay = 0)
    {
        parent::__construct($filepath, $mimetype);

        if (! is_file($filepath)) {
            throw new DoesNotExistException();
        }
        $this->httpRange = $httpRange;
        $this->totalSize = filesize($filepath);
        $this->file = fopen($filepath, "r");
        $this->boundary = md5($filepath);
        $this->delay = $delay;
        if ($fileName)
            $this->name = $fileName;
        else
            $this->name = basename($filepath);
    }

    /**
     * Render a response object.
     */
    function render($output_body = true)
    {
        // $this->process ();
        if ($this->status_code >= 200 && $this->status_code != 204 && $this->status_code != 304) {
            $this->headers['Content-Length'] = strlen($this->content);
        }
        // }
        // public function process() {
        $ranges = NULL;
        $t = 0;
        if ($this->httpRange && $range = stristr(trim($this->httpRange), 'bytes=')) {
            $range = substr($range, 6);
            $ranges = explode(',', $range);
            $t = count($ranges);
        }
        $this->headers['Accept-Ranges'] = 'bytes';
        $this->headers['Content-Type'] = 'application/octet-stream';
        $this->headers['Content-Transfer-Encoding'] = 'binary';
        // $this->headers ['Content-Disposition'] = sprintf ( 'attachment; filename="%s"', $this->name );
        $this->headers['Content-Disposition'] = 'attachment; ' . sprintf('filename="%s"; ', rawurlencode($this->name)) . sprintf("filename*=utf-8''%s", rawurlencode($this->name));
        if ($t > 0) {
            $this->status_code = 206;
            $t === 1 ? $this->pushSingle($range) : $this->pushMulti($ranges);
        } else {
            $this->headers['Content-Length'] = $this->totalSize;
            $this->outputHeaders();
            $this->readFile();
        }
        flush();
    }

    private function pushSingle($range)
    {
        $start = $end = 0;
        $this->getRange($range, $start, $end);
        $this->headers['Content-Length'] = $end - $start + 1;
        $this->headers['Content-Range'] = sprintf(' bytes %d-%d/%d', $start, $end, $this->totalSize);
        $this->outputHeaders();
        fseek($this->file, $start);
        $this->readBuffer($end - $start + 1);
        $this->readFile();
    }

    private function pushMulti($ranges)
    {
        $length = $start = $end = 0;
        $tl = "Content-type: application/octet-stream\r\n";
        $formatRange = "Content-range: bytes %d-%d/%d\r\n\r\n";
        foreach ($ranges as $range) {
            $this->getRange($range, $start, $end);
            $length += strlen("\r\n--$this->boundary\r\n");
            $length += strlen($tl);
            $length += strlen(sprintf($formatRange, $start, $end, $this->totalSize));
            $length += $end - $start + 1;
        }
        $length += strlen("\r\n--$this->boundary--\r\n");
        $this->headers['Content-Length'] = $length;
        $this->headers['Content-Type'] = 'multipart/x-byteranges; boundary=' . $this->boundary;
        $this->outputHeaders();
        foreach ($ranges as $range) {
            $this->getRange($range, $start, $end);
            echo "\r\n--$this->boundary\r\n";
            echo $tl;
            echo sprintf($formatRange, $start, $end, $this->totalSize);
            fseek($this->file, $start);
            $this->readBuffer($end - $start + 1);
        }
        echo "\r\n--$this->boundary--\r\n";
    }

    private function getRange($range, &$start, &$end)
    {
        list ($start, $end) = explode('-', $range);
        $fileSize = $this->totalSize;
        if ($start == '') {
            $tmp = $end;
            $end = $fileSize - 1;
            $start = $fileSize - $tmp;
            if ($start < 0)
                $start = 0;
        } else {
            if ($end == '' || $end > $fileSize - 1)
                $end = $fileSize - 1;
        }
        if ($start > $end) {
            header("Status: 416 Requested range not satisfiable");
            header("Content-Range: */" . $fileSize);
            exit();
        }
        return array(
            $start,
            $end
        );
    }

    private function readFile()
    {
        while (! feof($this->file)) {
            echo fgets($this->file);
            flush();
            usleep($this->delay);
        }
    }

    private function readBuffer($bytes, $size = 1024)
    {
        $bytesLeft = $bytes;
        while ($bytesLeft > 0 && ! feof($this->file)) {
            $bytesRead = $bytesLeft > $size ? $size : $bytesLeft;
            $bytesLeft -= $bytesRead;
            echo fread($this->file, $bytesRead);
            flush();
            usleep($this->delay);
        }
    }

    public function computeSize()
    {
        $t = 0;
        if (! $this->httpRange && $range = stristr(trim($this->httpRange), 'bytes=')) {
            $range = substr($range, 6);
            $ranges = explode(',', $range);
            $t = count($ranges);
        }
        if ($t <= 0)
            return $this->totalSize;
        $myStart = $myEnd = 0;
        if ($t == 1) {
            $this->getRange($range, $myStart, $myEnd);
            return $myEnd - $myStart + 1;
        } else {
            $mySize = 0;
            foreach ($ranges as $rg)
                $mySize += $this->getRange($rg, $myStart, $myEnd);
            return $mySize;
        }
    }
}
