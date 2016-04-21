<?php
return array(
    // ************************************************************* Content
    array( // Content urls
        'regex' => '#^/content/new$#',
        'model' => 'SaaSCMS_Views_Content',
        'method' => 'create',
        'http-method' => 'POST'
    ),
    array(
        'regex' => '#^/content/(\d+)$#',
        'model' => 'SaaSCMS_Views_Content',
        'method' => 'get',
        'http-method' => 'GET'
    ),
    array(
        'regex' => '#^/content/(\d+)$#',
        'model' => 'SaaSCMS_Views_Content',
        'method' => 'delete',
        'http-method' => 'DELETE'
    ),
    array(
        'regex' => '#^/content/(\d+)$#',
        'model' => 'SaaSCMS_Views_Content',
        'method' => 'update',
        'http-method' => 'POST'
    ),
    array(
        'regex' => '#^/content/find$#',
        'model' => 'SaaSCMS_Views_Content',
        'method' => 'find',
        'http-method' => 'GET'
    ),
    // Download
    array(
        'regex' => '#^/content/(\d+)/download$#',
        'model' => 'SaaSCMS_Views_Content',
        'method' => 'download',
        'http-method' => 'GET'
    ),
    array(
        'regex' => '#^/content/(\d+)/download$#',
        'model' => 'SaaSCMS_Views_Content',
        'method' => 'updateFile',
        'http-method' => 'POST'
    ),
    // ************************************************************* Page
    array( // Page urls
        'regex' => '#^/page/new$#',
        'model' => 'SaaSCMS_Views_Page',
        'method' => 'create',
        'http-method' => 'POST'
    ),
    array(
        'regex' => '#^/page/(\d+)$#',
        'model' => 'SaaSCMS_Views_Page',
        'method' => 'get',
        'http-method' => 'GET'
    ),
    array(
        'regex' => '#^/page/(\d+)$#',
        'model' => 'SaaSCMS_Views_Page',
        'method' => 'delete',
        'http-method' => 'DELETE'
    ),
    array(
        'regex' => '#^/page/(\d+)$#',
        'model' => 'SaaSCMS_Views_Page',
        'method' => 'update',
        'http-method' => 'POST'
    ),
    array(
        'regex' => '#^/page/find$#',
        'model' => 'SaaSCMS_Views_Page',
        'method' => 'find',
        'http-method' => 'GET'
    ),
    array(
        'regex' => '#^/page/(\w+)$#',
        'model' => 'SaaSCMS_Views_Page',
        'method' => 'getByName',
        'http-method' => 'GET'
    ),
    array(
        'regex' => '#^/page/(\w+)$#',
        'model' => 'SaaSCMS_Views_Page',
        'method' => 'updateByName',
        'http-method' => 'POST'
    ),
    array(
        'regex' => '#^/page/(\w+)$#',
        'model' => 'SaaSCMS_Views_Page',
        'method' => 'deleteByName',
        'http-method' => 'DELETE'
    ),
    // related content of page
//     array(
//         'regex' => '#^/page/(\w+)/content$#',
//         'model' => 'SaaSCMS_Views_Page',
//         'method' => 'getContentByName',
//         'http-method' => 'GET'
//     ),
    // ************************************************************* Report
    array( // Report urls
        'regex' => '#^/report/(\w+)$#',
        'model' => 'SaaSCMS_Views_Report',
        'method' => 'get',
        'http-method' => 'GET'
    ),
    array(
        'regex' => '#^/report$#',
        'model' => 'SaaSCMS_Views_Report',
        'method' => 'getTypes',
        'http-method' => 'GET'
    ),
);