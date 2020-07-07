<?php
namespace Pluf\HTTP;

class ResponseFileBuilder
{

    private ?string $filename;

    private ?string $mimetype;

    private ?string $abspath;

    private ?bool $resumable = false;

    private ?bool $delete = false;

    private ?Request $request;

    public static function getInstance(): ResponseFileBuilder
    {
        return new ResponseFileBuilder();
    }

    public function build(): Response
    {
        $response = new Response\File($this->abspath, $this->mimetype, $this->delete);
        // TODO: maso, 2020: support resumable file too
        if (isset($this->filename)) {
            $response->headers['Content-Disposition'] = sprintf('attachment; filename="%s"', $this->filename);
        }
        return $response;
    }

    public function setFilename(string $filename): ResponseFileBuilder
    {
        $this->filename = $filename;
        return $this;
    }

    public function setMimetype(string $mimetype): ResponseFileBuilder
    {
        $this->mimetype = $mimetype;
        return $this;
    }

    public function setAbsloutPath(string $abspath): ResponseFileBuilder
    {
        $this->abspath = $abspath;
        return $this;
    }

    public function setResumable(bool $resumable): ResponseFileBuilder
    {
        $this->resumable = $resumable;
        return $this;
    }

    public function setRequest(Request $request): ResponseFileBuilder
    {
        $this->request = $request;
        return $this;
    }

    public function deleteOnEnd(bool $delete): ResponseFileBuilder
    {
        $this->delete = $delete;
        return $this;
    }
}

