Future _CI_
===========

A Continuous Integration tool by Future500 B.V.

[![Build Status](https://travis-ci.org/f500/future-ci.png)](https://travis-ci.org/f500/future-ci)

Development
-----------

Clone the repository:

    $ git clone git@github.com:f500/future-ci.git future-ci
    $ git checkout develop

Install dependencies:

    $ composer.phar install
    $ npm install
    $ bower install

Install and watch assets:

    $ grunt
    $ grunt watch

Configure the application by copying the skeleton file and editing it:

    $ cp app/config/parameters.yml.dist app/config/parameters.yml
    $ vi app/config/parameters.yml

License
-------

[The MIT License (MIT)](https://github.com/f500/future-ci/blob/master/LICENSE)

Copyright (c) 2014 Future500 B.V.
