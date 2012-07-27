Namespacfiy
===========

- Brought to you by [2bePUBLISHED](http://2bepublished.at)
- Developed by [Florian Eckerstorfer](http://florianeckerstorfer.com)

About
-----

Namespacify is a tool to automatically add namespaces to PHP classes.

Installation
------------

Namespacify can be installed with or without Git. However, in each case [Composer](http://getcomposer.org) is required to install the dependencies.

### With Git

    $ git clone https://github.com/2bepublished/namespacify.git ~/namespacify
    $ cd ~/namespacify; curl -s http://getcomposer.org/installer | php; php composer.phar install
    $ ln -s ~/namespacify/namespacify /usr/local/bin/namespacify

### Without Git

    $ wget -q https://github.com/2bepublished/namespacify/tarball/master; tar -xzf master; mv `ls | grep 2bepublished-namespacify` ~/namespacify
    $ cd ~/namespacify; curl -s http://getcomposer.org/installer | php; php composer.phar install
    $ ln -s ~/namespacify/namespacify /usr/local/bin/namespacify

Usage
-----

To add namespaces to all classes in a directory and save them to another directory, you just have to:

    $ namespacify go ~/input-dir ~/output-dir

There are three options you can use to customize the generated output. The `--prefix` option can be used to prefix the namespace.

    $ namespacify go ~/input-dir ~/output-dir --prefix=MyVendorName

It is also possible to exclude classes based on the file name. The exlude option supports regular expressions:

    $ namespacify go ~/input-dir ~/output-dir --exlucde="SomeFile(s)?"

If the applicatio nrequires custom transformations there is the `--transformer` option. The value should be a PHP file which contains a transformer class:

    $ namespacify go ~/input-dir ~/output-dir --transformer=./my-app-transformer.php

The transformer file could look like this. It is important that the name of the class and the `transform` method are not changed.

    <?php

    class CodeTransformerCallback
    {
        static public function transform($class)
        {
            $code = $class['code'];

            $code = str_replace(
                array(
                    // search
                ),
                array(
                    // replace
                ),
                $code
            );

            $class['code'] = $code;
            return $class;
        }
    }

Features
--------

- Add namespaces to all PHP files in a given directory
- Support for multiple classes per file
- Find uses of these classes and correctly add `use` statements. Currently the following occurences of class names are found:
    - new MyClass();
    - new MyClass;
    - MyClass::method();
    - MyClass::property
    - function a(MyClass $var)
    - function a($var1, MyClass $var2)
    - class ClassOne extends MyClass
- Exclude files based on a regular expression (`--exclude` option)
- Manually transform code (`--transform` option)
- Prefix the namespace (`--prefix` option)


Limitations
-----------

Namespacify is currently not able to find dynamic uses of classes. If the classes that should namespacified contains code like this, it is not possible to automatically add the `use` statements:

    <?php
    $name = 'MyClass';
    $obj = new $name();

However, it is possible to manually transform these lines of code using the `transform` option. The code could, for example, look like this:

    <?php
    $code = str_replace('$name = \'MyClass\';', ''$name = \'\\\\MyVendor\\\\MyClass\';'', $code);


Dependencies
------------

Namespacify has the following dependencies:

- [Symfony Console](https://github.com/symfony/console)
- [Symfony Finder](https://github.com/symfony/finder)
- [Symfony DependencyInjection](https://github.com/symfony/dependencyInjection)
- [Symfony Config](https://github.com/symfony/config)
- [Symfony Yaml](https://github.com/symfony/yaml)
- [Symfony Filesystem](https://github.com/symfony/filesystem)

All dependencies can be installed using [Composer](http://getcomposer.org/).

LICENSE
-------

See `LICENSE` file.
