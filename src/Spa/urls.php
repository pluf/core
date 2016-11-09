<?php
return array(
        array(
                'regex' => '#^/find$#',
                'model' => 'SaaS_Views',
                'method' => 'findObject',
                'http-method' => 'GET',
                'precond' => array(),
                'params' => array(
                        'model' => 'SaaS_SPA',
                        'listFilters' => array(
                                'id',
                                'title',
                                'symbol'
                        ),
                        'listDisplay' => array(
                                'id' => 'spa id',
                                'title' => 'title',
                                'creation_dtime' => 'creation time'
                        ),
                        '$searchFields' => array(
                                'name',
                                'title',
                                'description',
                                'homepage'
                        ),
                        'sortFields' => array(
                                'id',
                                'name',
                                'title',
                                'homepage',
                                'license',
                                'version',
                                'creation_dtime'
                        ),
                        'sortOrder' => array(
                                'creation_dtime',
                                'DESC'
                        )
                )
        ),
        array(
                'regex' => '#^/(?P<modelId>\d+)$#',
                'model' => 'SaaS_Views',
                'method' => 'getObject',
                'http-method' => 'GET',
                'precond' => array(),
                'params' => array(
                        'model' => 'SaaS_SPA'
                )
        ),
        array(
                'regex' => '#^/new$#',
                'model' => 'Spa_Views',
                'method' => 'create',
                'http-method' => 'POST',
                'precond' => array(
                        'Pluf_Precondition::adminRequired'
                )
        ),
        array(
                'regex' => '#^/(?P<spaId>.+)$#',
                'model' => 'Spa_Views',
                'method' => 'update',
                'http-method' => 'POST',
                'precond' => array(
                        'Pluf_Precondition::adminRequired'
                )
        ),
        array(
                'regex' => '#^/(?P<spaId>.+)$#',
                'model' => 'Spa_Views',
                'method' => 'delete',
                'http-method' => 'DELETE',
                'precond' => array(
                        'Pluf_Precondition::adminRequired'
                )
        )
);
