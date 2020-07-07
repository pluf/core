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
namespace Pluf;

use Pluf\ExceptionRenderer\Console;
use Pluf\ExceptionRenderer\HTML;
use Pluf\ExceptionRenderer\HTMLText;
use Pluf\ExceptionRenderer\JSON;
use Pluf\ExceptionRenderer\RendererAbstract;
use Pluf\Translator\ITranslatorAdapter;
use Throwable;

/**
 * Pluf root exception type
 *
 * All pluf application exceptions are subclass of the \Pluf\Exception. If any exeption throw
 * which is not subclass of it, the framework will consider as non expected exception.
 *
 *
 * @author Mostafa Barmshory<mostafa.barmshory@dpq.co.ir>
 * @since Pluf6
 *       
 */
class Exception extends \Exception /* implements \JsonSerializable */
{

    /** @var array */
    public $params = [];

    /** @var string */
    protected $custom_exception_title = 'Critical Error';

    /** @var string The name of the Exception for custom naming */
    protected $custom_exception_name = null;

    /**
     * Most exceptions would be a cause by some other exception, Agile
     * Core will encapsulate them and allow you to access them anyway.
     *
     * @var array
     */
    private $trace2;

    // because PHP's use of final() sucks!

    /** @var string[] */
    private $solutions = [];

    // store solutions

    /** @var ITranslatorAdapter */
    private $adapter;

    /**
     * Constructor.
     *
     * @param string|array $message
     * @param int $code
     * @param Throwable $previous
     */
    public function __construct($message = '', ?int $code = null, Throwable $previous = null)
    {
        if (is_array($message)) {
            // message contain additional parameters
            $this->params = $message;
            $message = array_shift($this->params);
        }

        parent::__construct($message, $code ?? 0, $previous);
        $this->trace2 = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT);
    }

    /**
     * Change message (subject) of a current exception.
     * Primary use is
     * for localization purposes.
     *
     * @param string $message
     *
     * @return $this
     */
    public function setMessage($message): self
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Return trace array.
     *
     * @return array
     */
    public function getMyTrace()
    {
        return $this->trace2;
    }

    /**
     * Return exception message using color sequences.
     *
     * <exception name>: <string>
     * <info>
     *
     * trace
     *
     * --
     * <triggered by>
     *
     * @return string
     */
    public function getColorfulText(): string
    {
        return (string) new Console($this, $this->adapter);
    }

    /**
     * Similar to getColorfulText() but will use raw HTML for outputting colors.
     *
     * @return string
     */
    public function getHTMLText(): string
    {
        return (string) new HTMLText($this, $this->adapter);
    }

    /**
     * Return exception message using HTML block and Semantic UI formatting.
     * It's your job
     * to put it inside boilerplate HTML and output, e.g:.
     *
     * $l = new \atk4\ui\App();
     * $l->initLayout('Centered');
     * $l->layout->template->setHTML('Content', $e->getHTML());
     * $l->run();
     * exit;
     *
     * @return string
     */
    public function getHTML(): string
    {
        return (string) new HTML($this, $this->adapter);
    }

    /**
     * Return exception in JSON Format.
     *
     * @return string
     */
    public function getJSON(): string
    {
        return (string) new JSON($this, $this->adapter);
    }

    /**
     * Safely converts some value to string.
     *
     * @param mixed $val
     *
     * @return string
     */
    public function toString($val): string
    {
        return RendererAbstract::toSafeString($val);
    }

    /**
     * Follow the getter-style of PHP Exception.
     *
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Augment existing exception with more info.
     *
     * @param string $param
     * @param mixed $value
     *
     * @return $this
     */
    public function addMoreInfo($param, $value): self
    {
        $this->params[$param] = $value;

        return $this;
    }

    /**
     * Add a suggested/possible solution to the exception.
     *
     * @todo can be added more features? usually we are out of App
     *      
     * @param string $solution
     *
     * @return Exception
     */
    public function addSolution(string $solution)
    {
        $this->solutions[] = $solution;

        return $this;
    }

    /**
     * Get the solutions array.
     */
    public function getSolutions(): array
    {
        return $this->solutions;
    }

    /**
     * Get the custom Exception name, if defined in $custom_exception_name.
     *
     * @return string
     */
    public function getCustomExceptionName(): string
    {
        return $this->custom_exception_name ?? get_class($this);
    }

    /**
     * Get the custom Exception title, if defined in $custom_exception_title.
     *
     * @return string
     */
    public function getCustomExceptionTitle(): string
    {
        return $this->custom_exception_title;
    }

    /**
     * Set Custom Translator adapter.
     *
     * @param ITranslatorAdapter|null $adapter
     *
     * @return Exception
     */
    public function setTranslatorAdapter(?ITranslatorAdapter $adapter = null): self
    {
        $this->adapter = $adapter;

        return $this;
    }

    // use DiContainerTrait;

    // protected $status;

    // protected $link;

    // protected $developerMessage;

    // protected $data;

    // /**
    // * یک نمونه از این کلاس ایجاد می‌کند.
    // *
    // * @param string $message
    // * @param string $code
    // * @param string $previous
    // */
    // public function __construct($options)
    // {
    // $this->setDefaults($options);
    // }

    // public function getDeveloperMessage()
    // {
    // return $this->developerMessage;
    // }

    // public function getStatus()
    // {
    // return $this->status;
    // }

    // public function setData($data)
    // {
    // $this->data = $data;
    // }

    // public function jsonSerialize()
    // {
    // if (Pluf::f('debug', false)) {
    // return array(
    // 'code' => $this->code,
    // 'status' => $this->status,
    // 'link' => $this->link,
    // 'message' => $this->message,
    // 'data' => $this->data,
    // 'developerMessage' => $this->developerMessage,
    // 'stack' => $this->getTrace()
    // );
    // } else {
    // return array(
    // 'code' => $this->code,
    // 'status' => $this->status,
    // 'link' => $this->link,
    // 'message' => $this->message,
    // 'data' => $this->data
    // );
    // }
    // }
}



