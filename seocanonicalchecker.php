<?php
/*
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

require "vendor/autoload.php";
use Symfony\Component\DomCrawler\Crawler;

$sourceList = getSource();
$outputFile = getOutput();

$linkSum = count($sourceList);
for($i=0; $i < $linkSum; $i++)
{
  $link = trim( $sourceList[$i] );
  $canonical = trim( getCanonial($link) );
  $eq = ( $link == $canonical ) ? 'equal' : 'unequal';

  //$outputList[] = array($eq, $link,$canonical);
  $outputText .= "\n$eq, $link,$canonical";
  print "\nchecked link $i of $linkSum";
  if($i>10) $i=$i+100000;
}

file_put_contents($outputFile, $outputText);



########
function getOutput()
{
  global $argc, $argv;
  if($argc > 2 )
  $filePath = $argv[2];
  else die("Parameter 2 must be the output textfile. FILE WILL BE OVERWRITTEN, ALWAYS!");

  if(touch($filePath) == true)
    return $filePath;
  else die("Parameter 2 must be a writable filepath. FILE WILL BE OVERWRITTEN, ALWAYS!");
}

########
function getSource()
{
  global $argc, $argv;
  if($argc != 1 )
    $filePath = $argv[1];
  else die("Parameter 1 must be the input textfile");

  if( file_exists( $filePath ) )
    $inputList = file($filePath);
  else die("Parameter 1 must be a existing input textfile");

  if( count($inputList) > 0 ) return $inputList;
  else die("Parameter 1 must  be a linewise flat list of links");
}

########
function getCanonial($link)
{
  $crawler = new Crawler( curl_download($link) );
  $filter = $crawler->filter('link')->extract(array('rel', 'href'));

  foreach ($filter as $content) {
    if($content[0] == 'canonical' ) return $content[1];
  }
}


########
function curl_download($Url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $Url);
    curl_setopt($ch, CURLOPT_USERAGENT, "SEOlinkCrawlRob/0.1");
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    // Include header in result? (0 = yes, 1 = no)
    curl_setopt($ch, CURLOPT_HEADER, 0);
    // Should cURL return or print out the data? (true = return, false = print)
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    // Download the given URL, and return output
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
}
