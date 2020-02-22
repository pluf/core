<?php
namespace Pluf\Pluf;

class Module extends \Pluf\Module
{

    const moduleJsonPath = __DIR__ . '/module.json';

    /**
     * All data model relations
     */
    const relations = array(
        '\Pluf\Search\Occ' => array(
            'relate_to' => array(
                '\Pluf\Search\Word'
            )
        )
    );

    const urlsPath = __DIR__ . '/urls.php';
}