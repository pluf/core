<?php
namespace Pluf\NoteBook;

use Pluf;

class Module extends \Pluf\Module
{

    const moduleJsonPath = __DIR__ . '/module.json';

    const relations = array();

    const urlsPath = __DIR__ . '/urls.php';

    public function init(Pluf $bootstrap): void
    {}
}