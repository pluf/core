Query
==========

- view
- filter
- order
- start and limit

View
================================

Name of view which is defined in a data model.

Filter
================================

Here is general form of data filter

	$filter =[
		[[{property}, {operation}, {value}], [{property}, {operation}, {value}], ..],
		[{property}, {operation}, {value}],
		...
	]

Order
================================

THe general form of order is:

	$order =[
		'{property}' => '{order}'
	];

Example:

	$order = [
		'id' => 'aes'
	];

Start and limit
================================

You may limit the result set by adding start and limit
