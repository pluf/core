<?php
return array(
        array(
                'regex' => '#^/find$#',
                'model' => 'SaaS_Views',
                'method' => 'findObject',
                'http-method' => 'GET',
                'precond' => array(
                        'Pluf_Precondition::adminRequired'
                ),
                'params' => array(
                        'model' => 'SaaS_Configuration',
                        'sql' => new Pluf_SQL('type=0'),
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
                'model' => 'Config_Views',
                'method' => 'get',
                'http-method' => 'GET',
                'precond' => array(
                        'Pluf_Precondition::adminRequired'
                )
        ),
        array(
                'regex' => '#^/(?P<key>[^/]+)$#',
                'model' => 'Config_Views',
                'method' => 'delete',
                'http-method' => 'DELETE',
                'precond' => array(
                        'Pluf_Precondition::adminRequired'
                )
        ),
        array(
                'regex' => '#^/(?P<key>[^/]+)$#',
                'model' => 'Config_Views',
                'method' => 'update',
                'http-method' => 'POST',
                'precond' => array(
                        'Pluf_Precondition::adminRequired'
                )
        )
);
