<?php
return array(

	array( // Content urls
        'regex' => '#^/dm/link/(secure_link:.*)$#',
        'model' => 'SaaSDM_Views_Link',
        'method' => 'download',
        'http-method' => 'GET',
        'precond' => array(
            'Pluf_Precondition::loginRequired',
            'SaaS_Precondition::tenantMember'
        )
    )
);