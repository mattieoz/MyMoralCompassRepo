<?php

function convertUtf8($text)
{
$quotes = array(
    "\xC2\xAB"     => '"', // « (U+00AB) in UTF-8
    "\xC2\xBB"     => '"', // » (U+00BB) in UTF-8
    "\xE2\x80\x98" => "'", // ‘ (U+2018) in UTF-8
    "\xE2\x80\x99" => "'", // ’ (U+2019) in UTF-8
    "\xE2\x80\x9A" => "'", // ‚ (U+201A) in UTF-8
    "\xE2\x80\x9B" => "'", // ? (U+201B) in UTF-8
    "\xE2\x80\x9C" => '"', // “ (U+201C) in UTF-8
    "\xE2\x80\x9D" => '"', // ” (U+201D) in UTF-8
    "\xE2\x80\x9E" => '"', // „ (U+201E) in UTF-8
    "\xE2\x80\x9F" => '"', // ? (U+201F) in UTF-8
    "\xE2\x80\xB9" => "'", // ‹ (U+2039) in UTF-8
    "\xE2\x80\xBA" => "'", // › (U+203A) in UTF-8
	"\xC3\xA2\xE2\x82\xAC\x22" => "-",
	"\xC3\xA2\xE2\x82\xAC\xE2\x84\xA2" => "'",
	"\xC3\xA2\xE2\x82\xAC\xC5\x93" => "\"",
	"\xC3\xA2\xE2\x82\xAC\xC2\x9D" => "\"",
	"\xC3\xA2\xE2\x82\xAC\xC2\xA6" => "-",
	//	"\xC3\xA2\xE2\x82\xAC\xC2\xA8" => "\"",
//	"\xC3\xA2\xE2\x82\xAC\xC2\xA6" => "\"",
);
$text = strtr($text, $quotes);
//echo $str;
//$str = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
//$text = str_replace(chr(130), ',', $text);    // baseline single quote
//$text = str_replace(chr(132), '"', $text);    // baseline double quote
//$text = str_replace(chr(133), '...', $text);  // ellipsis
//$text = str_replace(chr(145), "'", $text);    // left single quote
//$text = str_replace(chr(146), "'", $text);    // right single quote
//$text = str_replace(chr(147), '"', $text);    // left double quote
//$text = str_replace(chr(148), '"', $text);    // right double quote

//$text = mb_convert_encoding($text, 'HTML-ENTITIES', 'UTF-8');

// First, replace UTF-8 characters.
//$text = str_replace(
//array("â€™", "\xe2\x80\x98", "\xe2\x80\x99", "\xe2\x80\x9c", "\xe2\x80\x9d", "\xe2\x80\x93", "\xe2\x80\x94", "\xe2\x80\xa6"),
//array("'", "'", "'", '"', '"', '-', '--', '...'),
//$text);
return $text;
}


function strToHex($string)
{
    $hex='';
    for ($i=0; $i < strlen($string); $i++)
    {
        $hex .= " " . dechex(ord($string[$i]));
    }
    return $hex;
}
?>