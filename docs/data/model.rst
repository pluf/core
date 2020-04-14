Model
=====



Relations
=========

Here is list of possible relations:

- Many To Many
- Many To One
- One To Many

There are two type of property to define a relation:

- DB Layer 
- Data Layer

DB Layer properties are used to bind Data Base field into the Data Object and the 
Data Layer properties are used to define relation of data.

In each section these properties are defiened.

Many To One
-----------

It is a forign key from a model to other.

Add a forigne key by useing ManyToOne:

.. code:: php
	/**
	 * @Model(
	 *	table='a',
	 * )
	 */
	 class Category {
	 	
	 	/**
	 	 * @Property(
	 	 *	type='Sequence',
	 	 *	editable=false,
	 	 *	readable=true,
	 	 *	columne='id'
	 	 * )
	 	 */
	 	public $id = null;
	 	
	 	/**
	 	 * @Property(
	 	 *	type='ManyToOne',
	 	 *	columne='parent_id',
	 	 *	joinModel='A',
	 	 *	joinProperty='id',
	 	 *  model='A'
	 	 * )
	 	 */
	 	public $parent = null;
	 }

To bind a forigne key to data property colomne is used.

Following fields are used to define the relation in data layer:

- joinModel: a data model to join
- joinProperty: a property to join from the model

One To Many
-----------

Is used to conect forigne key to data objects.

The One To Many relation does not exist in DB layer.

.. code:: php
	/**
	 * @Model(
	 *	table='a',
	 * )
	 */
	 class A {
	 	
	 	/**
	 	 * @Property(
	 	 *	type='Sequence',
	 	 *	editable=false,
	 	 *	readable=true,
	 	 *	columne='id'
	 	 * )
	 	 */
	 	public $id = null;
	 	
	 	/**
	 	 * @Property(
	 	 *	type='ManyToOne',
	 	 *	columne='parent_id',
	 	 *	joinModel='A',
	 	 *	joinProperty='id',
	 	 *  model='A'
	 	 * )
	 	 */
	 	public $parent = null;
	 	
	 	
	 	/**
	 	 * @Property(
	 	 *	type='OneToMany',
	 	 *	joinModel='A',
	 	 *	joinProperty='parent'
	 	 * )
	 	 */
	 	public $clidren = null;
	 }

To bind related type and join proeprty, following properties is used:

- joinModel: a related model
- joinProperty: a property of the related model to join on (forigne key to this model)

Many To Many
------------

Many to many relations are designed by a relation table. So to bind the relation to data
layer following properties is used:

- joinTable: 
- joinColumne: 
- inverseJoinColumns:

To bind data layer:

- joinProperty: a property of the current model to use in join
- inverseJoinModel: A model to join
- inverseJoinProperty: The property of the model to use in join


