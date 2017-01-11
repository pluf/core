<?php
return array(
        array(
                'regex' => '#^/find$#',
                'model' => 'SaaS_Views',
                'method' => 'findObject',
                'http-method' => 'GET',
                'precond' => array(),
                'params' => array(
                        'model' => 'Pluf_Configuration',
                        'sql' => new Pluf_SQL('type=1'),
                        'listFilters' => array(
                                'id',
                                'key',
                                'value',
                                'description'
                        ),
                        'listDisplay' => array(
                                'key' => 'key',
                                'description' => 'description'
                        ),
                        'searchFields' => array(
                                'title',
                                'symbol',
                                'description'
                        ),
                        'sortFields' => array(
                                'title',
                                'symbol',
                                'description',
                                'creation_date',
                                'modif_dtime'
                        )
                )
        ),
        array(
                'regex' => '#^/(?P<key>[^/]+)$#',
                'model' => 'Setting_Views',
                'method' => 'get',
                'http-method' => 'GET'
        ),
        array(
                'regex' => '#^/(?P<key>[^/]+)$#',
                'model' => 'Setting_Views',
                'method' => 'delete',
                'http-method' => 'DELETE',
                'precond' => array(
                        'SaaS_Precondition::tenantOwner'
                )
        ),
        array(
                'regex' => '#^/(?P<key>[^/]+)$#',
                'model' => 'Setting_Views',
                'method' => 'update',
                'http-method' => 'POST',
                'precond' => array(
                        'SaaS_Precondition::tenantOwner'
                )
        )
);
