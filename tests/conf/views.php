<?php
$ctl = array();

$ctl[] = array(
    'regex' => '#^/$#',
    'priority' => 4,
    'model' => '\Pluf\Todo\Views',
    'method' => 'main'
);

$ctl[] = array(
    'regex' => '#^/install/$#',
    'priority' => 4,
    'model' => '\Pluf\Todo\Views',
    'method' => 'install'
);

$ctl[] = array(
    'regex' => '#^/uninstall/$#',
    'priority' => 4,
    'model' => '\Pluf\Todo\Views',
    'method' => 'uninstall'
);

$ctl[] = array(
    'regex' => '#^/item/(\d+)/$#',
    'priority' => 4,
    'model' => '\Pluf\Todo\Views',
    'method' => 'viewItem'
);

$ctl[] = array(
    'regex' => '#^/list/(\d+)/item/add/$#',
    'priority' => 4,
    'model' => '\Pluf\Todo\Views',
    'method' => 'addItem'
);

$ctl[] = array(
    'regex' => '#^/item/(\d+)/update/$#',
    'priority' => 4,
    'model' => '\Pluf\Todo\Views',
    'method' => 'updateItem'
);

$ctl[] = array(
    'regex' => '#^/item/(\d+)/delete/$#',
    'priority' => 4,
    'model' => '\Pluf\Todo\Views',
    'method' => 'deleteItem'
);

$ctl[] = array(
    'regex' => '#^/list/$#',
    'priority' => 4,
    'model' => '\Pluf\Todo\Views',
    'method' => 'listLists'
);

$ctl[] = array(
    'regex' => '#^/list/(\d+)/$#',
    'priority' => 4,
    'model' => '\Pluf\Todo\Views',
    'method' => 'viewList'
);

$ctl[] = array(
    'regex' => '#^/list/(\d+)/update/$#',
    'priority' => 4,
    'model' => '\Pluf\Todo\Views',
    'method' => 'updateList'
);

$ctl[] = array(
    'regex' => '#^/list/(\d+)/delete/$#',
    'priority' => 4,
    'model' => '\Pluf\Todo\Views',
    'method' => 'deleteList'
);

$ctl[] = array(
    'regex' => '#^/list/add/$#',
    'priority' => 4,
    'model' => '\Pluf\Todo\Views',
    'method' => 'addList'
);

return $ctl;
