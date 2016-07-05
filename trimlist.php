<?php
/*
 * trimlist.php
 *
 * delete all lines from a input file there are equal
 *
 * Made by Rob Tranquillo
 * 04. July 2016
 *
 * This programm comes with no guaranties.
 * The source is free to use under the CC-by-SA (https://creativecommons.org/licenses/by-sa/2.0/) licence.
 *
 * Feel free to contact me for everything.
 *
 * https://github.com/RobTranquillo/SEOCanonicalChecker
 */

$sourceList = getSource();
$outputFile = getOutput();

$outputStr = '';
for($i=0; $i < count($sourceList); $i++)
{
  $line = trim($sourceList[$i]);
  if(substr($line, 0, 7) == 'unequal') $outputStr .= "\n".$line;
}
file_put_contents($outputFile, $outputStr);


########
function getOutput()
{
  global $argc, $argv;
  if($argc > 2 )
  $filePath = $argv[2];
  else die("Parameter 2 must be the output textfile. FILE WILL BE OVERWRITTEN, ALWAYS!\n");

  if(touch($filePath) == true)
    return $filePath;
  else die("Parameter 2 must be a writable filepath. FILE WILL BE OVERWRITTEN, ALWAYS!\n");
}

########
function getSource()
{
  global $argc, $argv;
  if($argc != 1 )
    $filePath = $argv[1];
  else die("Parameter 1 must be the input textfile\n");

  if( file_exists( $filePath ) )
    $inputList = file($filePath);
  else die("Parameter 1 must be a existing input textfile\n");

  if( count($inputList) > 0 ) return $inputList;
  else die("Parameter 1 must  be a linewise flat list of links\n");
}
