#!/usr/bin/ruby -i
=begin
* treendent.rb
* Manel R. Doménech (@manelio)

it's annoying for me to create files and directories in most of the editor I 
use, like Sublime Text and Vim. Also in the shell.

Because that, I wrote a minimal parser called treendent for intend-based files 
to design a filesystem tree. There are versions of the parser in php (treendent.php) 
and ruby (treendent.rb).

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

but also:

$ cat src-alt.tree
src/Woolr/Component/Import/
  Parser/Db.php
  Tests/Import/
    DbTest.php


The treendent script reads the file from the standard input, and this is that 
it writes to the standard output:

$ cat src-alt.tree | php treendent.php
src/Woolr/Component/Import/Parser/Db.php
src/Woolr/Component/Import/Tests/Import/DbTest.php

I always have a file with the tree of the project. It's my method to add new 
source files to the projects. I edit this file and run the next command 
(supose treendent.rb is same directory):

cat src.tree | ./treendent.rb | xargs -I'{}' sh -c 'test -e {} || install -D /dev/null {}'

That means: for each file candidate to be created, create the empty file only 
if not exists.

It's simple. But works for me.
=end

indent_levels = [0]
indent_level_roots = ['']
last_indent_level = 0
last_indent_level_root = ''

current_line = 0

STDIN.read.each_line do |line|
  current_line += 1
  line = line.rstrip
  line_content = line.lstrip
  next if line_content.length == 0

  indent_level = line.length - line_content.length + 1

  if indent_level > last_indent_level
    indent_levels << indent_level unless indent_levels.index(indent_level)    
    raise "Indent error [case A] in line #{current_line}" if indent_level_roots[ last_indent_level ] == nil
    last_indent_level_root = indent_level_roots[ last_indent_level ]
  elsif indent_level < last_indent_level
    previous_indent_level_index = indent_levels.index(indent_level)
    raise "Indent error [case B] in line #{current_line}" if previous_indent_level_index == nil    
    previous_indent_level = indent_levels[ previous_indent_level_index - 1]    
    last_indent_level_root = indent_level_roots[ previous_indent_level ]
    raise "Indent error [case C] in line #{current_line}" if last_indent_level_root == nil    
  end

  current_full_line = last_indent_level_root + line_content
  indent_level_roots[ indent_level ] = current_full_line

  last_indent_level = indent_level

  next if line_content[-1, 1] == '/'
  
  puts current_full_line

end