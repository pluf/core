<?php
return array(
    'SaaSCMS_Content' => array(
        'relate_to' => array(
            'SaaS_Application',
            'Pluf_User',
        )
    ),
    'SaaSCMS_Page' => array(
        'relate_to' => array(
            'SaaS_Application',
            'Pluf_User',
            'SaaSCMS_Content'
        )
    )
);
