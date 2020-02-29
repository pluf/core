<?php
namespace Pluf\Test;

class Module extends \Pluf\Module
{

    const moduleJsonPath = __DIR__ . '/module.json';

    const relations = array(
        '\Pluf\Test\ModelRecurse' => array(
            'relate_to' => array(
                '\Pluf\Test\ModelRecurse'
            )
        ),
        '\Pluf\Test\RelatedToTestModel' => array(
            'relate_to' => array(
                '\Pluf\Test\Model'
            )
        ),
        '\Pluf\Test\RelatedToTestModel2' => array(
            'relate_to' => array(
                '\Pluf\Test\Model'
            )
        ),
        '\Pluf\Test\ManyToManyOne' => array(
            'relate_to_many' => array(
                '\Pluf\Test\ManyToManyTwo'
            )
        )
    );

    const urlsPath = __DIR__ . '/urls.php';
}