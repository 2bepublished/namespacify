Namespacfiy
===========

- Brought to you by [2bePUBLISHED](http://2bepublished.at)
- Developed by [Florian Eckerstorfer](http://florianeckerstorfer.com)

About
-----

Namespacify is a tool automatically add namespaces to PHP classes.

Installation
------------

### With Git

    $ git clone https://github.com/2bepublished/namespacify.git ~/namespacify
    $ ln -s ~/namespacify/namespacify /usr/local/bin/namespacify

### Without Git

    $ wget -q https://github.com/2bepublished/namespacify/tarball/master; tar -xzf master; mv `ls | grep 2bepublished-namespacify` ~/inj
    $ ln -s ~/namespacify/namespacify /usr/local/bin/namespacify

Usage
-----

To add namespaces to all classes in a directory and save them to another directory, you just have to:

    $ namespacify go ~/input-dir ~/output-dir

LICENSE
-------

See `LICENSE` file.