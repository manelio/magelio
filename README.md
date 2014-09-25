# magelio

A set of utilities to work with Magento more easily.

Also a console application to import and export structural data (categories, attributes, attribute values) between Magento versions. Buy by now I prefer not to push the dirty code :).

## Utilities for Magento

### treendent.(rb|php)

It's annoying for me to create files and directories in most of the editor I 
use, like Sublime Text and Vim. Also in the shell.

Because that, I wrote a minimal parser called treendent for intend-based files 
to design a filesystem tree. There are versions of the parser in php (treendent.php) 
and ruby (treendent.rb).

```bash
$ cat src.tree
src/
  Woolr/
    Component/
      Import/
        Parser/
          Db.php
        Tests/
          Import/
            DbTest.php
```

but also:

```bash
$ cat src-alt.tree
src/Woolr/Component/Import/
  Parser/Db.php
  Tests/Import/
    DbTest.php
```    


The treendent script reads the file from the standard input, and this is that 
it writes to the standard output:

```bash
$ cat src-alt.tree | php treendent.php
src/Woolr/Component/Import/Parser/Db.php
src/Woolr/Component/Import/Tests/Import/DbTest.php
```

I always have a file with the tree of the project. It's my method to add new 
source files to the projects. I edit this file and run the next command 
(supose treendent.rb is same directory):

```bash
cat src.tree | ./treendent.rb | xargs -I'{}' sh -c 'test -e {} || install -D /dev/null {}'
```

That means: for each file candidate to be created, create the empty file only 
if not exists.

It's simple. But works for me.

#### Magento module example

There is an example file called mio_promotions.tree in example directory with this content. It's a file prepared to develop a Magento extension using modman (I create my extensions in a directory src, out of the Magento source tree, and install them with modman):

```bash
src/Mio_Promotions/
  
  modman

  app/etc/modules/
    Mio_Promotions.xml

  app/code/community/Mio/Promotions/
    Model/
      Rewrite/
        SalesRule/
          Rule/Condition/Product.php

      Observer/
        Quote.php
        Cart.php
        SalesRule.php

    Block/
      Rewrite/
        Checkout/
          Cart/Sidebar.php
    Helper/
      Data.php
      
  js/mio/
    promotions.js

  skin/frontend/base/default/css/mio/
    promotions.css
```


