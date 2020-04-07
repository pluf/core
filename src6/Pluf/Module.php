<?php
namespace Pluf\Pluf;

class Module extends \Pluf\Module
{

    const moduleJsonPath = __DIR__ . '/module.json';

    const relations = array(
        'Pluf_Search_Occ' => array(
            'relate_to' => array(
                'Pluf_Search_Word'
            )
        )
    );

    const urlsPath = __DIR__ . '/urls.php';

    public function init(\Pluf $bootstrap): void
    {
        /**
         * For each model having a Engine::FOREIGNKEY or a Engine::MANY_TO_MANY colum, details
         * must be added here.
         * These details are used to generated the methods
         * to retrieve related models from each model.
         */
        \Pluf_Signal::connect('Pluf_Dispatcher::postDispatch', array(
            'Pluf_Log',
            'flushHandler'
        ), 'Pluf_Dispatcher');
    }
}

