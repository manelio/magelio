#!/usr/bin/php
<?php
/*
treendent.php (tree indent)
@author: Manel R. Doménech (@manelio)

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
*/

$indentLevels = array(0);
$indentLevelRoots = array('');
$lastIndentLevel = 0;
$lastLevelRoot = '';

$f = fopen( 'php://stdin', 'r' );
$currentLine = 0;

while($line = fgets($f)) {
  $currentLine++;

  $line = rtrim($line, "\n\r\t ");
  $ltrimmedLine = ltrim($line, " ");
  if (!$ltrimmedLineLength = strlen($ltrimmedLine)) continue;

  $indentLevel = strlen($line) - $ltrimmedLineLength + 1;

  if ($indentLevel > $lastIndentLevel) {
    if (!in_array($indentLevel, $indentLevels)) array_push($indentLevels, $indentLevel);
    if (!key_exists($lastIndentLevel, $indentLevelRoots)) 
      throw new Exception("Indent error in line $currentLine", 1);    
    $lastLevelRoot = $indentLevelRoots[$lastIndentLevel];
    
  } else if ($indentLevel < $lastIndentLevel) {
    $previousIndentLevelIndex = array_search($indentLevel, $indentLevels);
    if ($previousIndentLevelIndex === false)
      throw new Exception("Indent error in line $currentLine", 1);
    $previousIndentLevel = $indentLevels[$previousIndentLevelIndex - 1];

    if (!key_exists($previousIndentLevel, $indentLevelRoots)) 
      throw new Exception("Indent error in line $currentLine", 1);
    $lastLevelRoot = $indentLevelRoots[$previousIndentLevel];    
  }

  $currentFullLine = $lastLevelRoot.$ltrimmedLine;
  $indentLevelRoots[$indentLevel] = $currentFullLine;

  $lastIndentLevel = $indentLevel;

  if (substr($ltrimmedLine, -1) === '/') continue;

  printf("%s\n", $currentFullLine);
  
}
