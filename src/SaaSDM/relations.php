<?php
return array(
    'SaaSDM_Link' => array(
        'relate_to' => array(
            'SaaS_Application',
            'SaaSDM_Asset'
        )
    ),
    'SaaSDM_Asset' => array(
        'relate_to' => array(
            'SaaS_Application'
        )
    )
);
