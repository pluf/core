Data View
========

By adding view into a model, you can extend and add some virtual attributes.

A view is consist of:

- group: list of field to group by
- join: models to join
- field: how to map result values into the model
- where: list of where clouse
- field: List of model field to fetch from db

Join to a model
===============

- joinProperty: to use in joing from source model
- inverseJoinModel: name of class that you want to join to current model
- inverseJoinProperty:  to use in joing from target
- type: the join type (left, inner)
- alias: a name to referin 

Suppose there is no relation, to create a join in view:

.. code::php
	$join =[
		'joinProperty' => 'id',
		'inverseJoinModel' => MyModel::class,
		'inverseJoinProperty' => 'id',
		'type' => 'left',
		'alias' => 'myModel'
	]

If the property is a relation itserlf, this is so simle:

.. code::php
	$join =[
		'joinProperty' => 'relation',
		'type' => 'left',
		'alias' => 'myModel'
	]


Group
=====

An array of property to group

The general pattern of a property in the list is:

.. code::php
	$view = [
		'group' => ['{alias}.{property}]
	];

Where alias is optionall. 

Here is an example

.. code::php
	$view = [
		'group' => ['name', 'a.title']
	];


Fields
=======================

Binds query value into the view.

The general form to add a field:

.. code::php
	$view = [
		'field' => [
			'property' => 'field'
		]
	]


using expression:

.. code::php
	$view = [
		'field' => [
			'property' => new Expression('now()')
		]
	]

using model properties:

.. code::php
	$view = [
		'field' => [
			'userName' => 'account.login'
		]
	]

where userName is a property in the current model and account.login is a {alias}.{property} 
from join and where.

Where
======

Where is list of common Data query filters to select a group of items
from a repository. 

Where clouse will be merged with filters used to query an object repository.

A common where clouse to add to the query

.. code::php
	$view = [
		'where' => [
			[{property}, {operation}, {value}],
			[{property}, {value}]
		]
	]

NOTE: The merge of where and filter will be logically AND.

Field
======

It is easy to bind custom data to the data model by field attribute.

Field is list of key-value where key is the data model property name and the
value is data layer expression.

General form of field is:

.. code::php
	$view = [
		'field' =>[
			'{property}' => '{alias}.{property}',
		]
	];


TO bind database layer values directly into the model properties you must use
DB expression.

For example:

.. code::php
	$view = [
		'field' =>[
			'date' => new Expression('now()'),
		]
	];

Sets current data base date to the date attribute of the model.

NOTE: you must define all required attribures if you add field attribute.

Examples
========

Following data model is used in this part:


.. code::php
	class A{
		public int $id;
		public string $title
	}


.. code::php
	class B{
		public int $id;
		public string $title;
		public in $aId;
	}
	
.. code::php
	class C{
		public int $count = 0;
	}


Select Related models
-----------------------

Here is our data models:


A view to select B related to A;


.. code::php
	$view = [
		'join' => [
			'model' => 'A',
			'alias' => 'a',
			'property' => 'id',
			'masterProperty' => 'aId',
			'type' => 'left'
		]
	];
	$this->setView('relatedToA', $view);

The final query is :

.. code::sql
	select * from B left join A as a on a.id = aId

To use view with repository

.. code::php
	$repo = Repository::getInstance('B');
	$list = $repo->getList([
		'filter'=>[
			['a.id', 12]
		],
		'view' => 'relatedToA',
	]);
	var_dump($list);

Selects all B which are related to A with id=12.




Virtual Model
-------------

It is possible to mount several models on a table.

One of them must be a real model and the others must be mapped.

In this example C is a mapped model in which the attributes computed with
expression.


.. code::php
	$repo = Repository::getInstance('C');
	$model = $repo->getOne([
		'view' => 'counter',
	]);
	var_dump($model);

Appendex
========

How mapped Data Join to DB coin
---------------------------------

Data query is defines based on data model (defined in bussines layer) and finally mapped
into the DB query.

Join is part of Data View and must converted to a DB join. Here is mapp of Data to DB join:

.. code::php
	$query->join('{model}.{property} {alias}', '{masterProperty}', '{kind}')

For example, suppose the following view:

.. code::php
	$view = [
		'join' =>[
			'model' => '\Pluf\NotBook\Book',
			'property' => 'id',
			'alias' => 'book',
			'masterProperty' => 'book_id',
			'kind' => 'left'
		]
	];
	
With a simple schema, the following join will be added to a DB query:

.. code::php
	$query->join('book.id book', 'id', 'left');
