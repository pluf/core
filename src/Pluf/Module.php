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
            ),
            'relate_to_many' => array(),
        ),
        '\Pluf\DB\SchemaInfo' => array(
            'relate_to' => array(),
            'relate_to_many' => array(),
        )
    );

    const urlsPath = __DIR__ . '/urls.php';
}