<?php
namespace Pluf\Smallest;

class Module extends \Pluf\Module
{

    const moduleJsonPath = __DIR__ . '/module.json';

    const relations = array(
        'Test_ModelRecurse' => array(
            'relate_to' => array(
                'Test_ModelRecurse'
            )
        ),
        'Test_RelatedToTestModel' => array(
            'relate_to' => array(
                'Test_Model'
            )
        ),
        'Test_RelatedToTestModel2' => array(
            'relate_to' => array(
                'Test_Model'
            )
        ),
        'Test_ManyToManyOne' => array(
            'relate_to_many' => array(
                'Test_ManyToManyTwo'
            )
        )
    );

    const urlsPath = __DIR__ . '/urls.php';
}