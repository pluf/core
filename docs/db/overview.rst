========
Overview
========

Pluf DB is a dynamic SQL query builder. You can write multi-vendor queries in PHP
profiting from better security, clean syntax and most importantly – sub-query
support. With Pluf DB you stay in control of when queries are executed and what
data is transmitted. Pluf DB is easily composable – build one query and use it as
a part of other query.


Goals of Pluf DB
=============

 - simple and concise syntax
 - consistently scalable (e.g. 5 levels of sub-queries, 10 with joins and 15 parameters? no problem)
 - "One Query" paradigm
 - support for PDO vendors as well as NoSQL databases (with query language similar to SQL)
 - small code footprint (over 50% less than competing frameworks)
 - no dependencies
 - follows design paradigms:
     - "`PHP the Agile way <https://github.com/atk4/dsql/wiki/PHP-the-Agile-way>`_"
     - "`Functional ORM <https://github.com/atk4/dsql/wiki/Functional-ORM>`_"
     - "`Open to extend <https://github.com/atk4/dsql/wiki/Open-to-Extend>`_"
     - "`Vendor Transparency <https://github.com/atk4/dsql/wiki/Vendor-Transparency>`_"

Pluf DB by example
===============
The simplest way to explain Pluf DB is by example::

    $query = new Pluf\Db\Query();
    $query  ->table('employees')
            ->where('birth_date','1961-05-02')
            ->field('count(*)');
    echo "Employees born on May 2, 1961: " . $query->getOne();

The above code will execute the following query:

.. code-block:: sql

    select count(*) from `salary` where `birth_date` = :a
        :a = "1961-05-02"

Pluf DB can also execute queries with multiple sub-queries, joins, expressions
grouping, ordering, unions as well as queries on result-set.

 - See :ref:`quickstart` if you would like to start learning DSQL.
 - See https://github.com/atk4/dsql-primer for various working
   examples of using Pluf DB with a real data-set.


Pluf DB is Part of Pluf Framework
=============================
Pluf DB is a stand-alone and lightweight library with no dependencies and can be
used in any PHP project, big or small.

.. figure:: images/agiletoolkit.png
   :alt: Agile Toolkit Stack

Pluf DB is also a part of `Pluf`_ framework and works best with
`Pluf Models`_. Your project may benefit from a higher-level data abstraction
layer, so be sure to look at the rest of the suite.

.. _Pluf: http://pluf.ir/
.. _Pluf Models: https://pluf.ir/wb/products/module/pluf-models


Requirements
============

#. PHP 7.4 and above

.. _installation:

Installation
============

The recommended way to install Pluf is with
`Composer <http://getcomposer.org>`_. Composer is a dependency management tool
for PHP that allows you to declare the dependencies your project has and it
automatically installs them into your project.


.. code-block:: bash

    # Install Composer
    curl -sS https://getcomposer.org/installer | php
    php composer.phar require pluf/core

You can specify Pluf as a project or module dependency in composer.json:

.. code-block:: js

    {
      "require": {
         "pluf/core": "*"
      }
    }

After installing, you need to require Composer's autoloader in your PHP file::

    require 'vendor/autoload.php';

You can find out more on how to install Composer, configure auto-loading, and
other best-practices for defining dependencies at
`getcomposer.org <http://getcomposer.org>`_.


Getting Started
===============

Continue reading :ref:`quickstart` where you will learn about basics of DSQL
and how to use it to it's full potential.

Contributing
============

Guidelines
----------

1. Pluf utilizes PSR-1, PSR-2, PSR-4, and PSR-7.
2. Pluf is meant to be lean and fast with very few dependencies. This means
   that not every feature request will be accepted.
3. All pull requests must include unit tests to ensure the change works as
   expected and to prevent regressions.
4. All pull requests must include relevant documentation or amend the existing
   documentation if necessary.

Review and Approval
-------------------

1. All code must be submitted through pull requests on GitHub
2. Any of the project managers may Merge your pull request, but it must not be
   the same person who initiated the pull request.


Running the tests
-----------------

In order to contribute, you'll need to checkout the source from GitHub and
install Pluf dependencies using Composer:

.. code-block:: bash

    git clone https://github.com/pluf/core.git
    cd core && curl -s http://getcomposer.org/installer | php && ./composer.phar install --dev

Pluf is unit tested with PHPUnit. Run the tests using the command:

.. code-block:: bash

    vendor/bin/phpunit

There are also vendor-specific test-scripts which will require you to
set database. To run them:

.. code-block:: bash

    # All unit tests including SQLite database engine tests
    vendor/bin/phpunit --config phpunit.xml

    # MySQL database engine tests
    vendor/bin/phpunit --config phpunit-mysql.xml

Look inside these the .xml files for further information and connection details.

Reporting a security vulnerability
==================================

We want to ensure that Pluf is a secure library for everyone. If you've
discovered a security vulnerability in Pluf, we appreciate your help in
disclosing it to us in a `responsible manner <http://en.wikipedia.org/wiki/Responsible_disclosure>`_.

Publicly disclosing a vulnerability can put the entire community at risk. If
you've discovered a security concern, please email us at
info@pluf.ir. We'll work with you to make sure that we understand
the scope of the issue, and that we fully address your concern. We consider
correspondence sent to info@pluf.ir our highest priority, and work
to address any issues that arise as quickly as possible.

After a security vulnerability has been corrected, a security hot-fix release
will be deployed as soon as possible.
