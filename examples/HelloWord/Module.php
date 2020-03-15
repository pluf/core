<?php
namespace Pluf\HelloWord;

use Pluf;

class Module extends \Pluf\Module
{

    const moduleJsonPath = __DIR__ . '/module.json';

    const relations = array();

    public function init(Pluf $bootstrap): void
    {
        // Nothing to do
    }
}