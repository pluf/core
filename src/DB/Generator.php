<?php
namespace Pluf\DB;

abstract class Generator
{

    protected $con = null;

    function __construct($con)
    {
        $this->con = $con;
    }
}

