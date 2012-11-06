<?php
class SearchResult
{
	public $shortName;
	public $title;
	public $text;
	
	function breakUpIntoSentences($data)
	{
		//given string $data, will return the first $max sentences in that string
	
		//in: $data = the string to parse, $max = maximum # of sentences to return
		//returns: string containing the first $max sentences
		//(If the # of sentences in the string is less than $max,
		//then entire string will be returned.)
	
		//a sentence is any charactors except ., !, and ?
		//any number of times,  plus one or more .s, ?s, or !s
		//and any leading or trailing whitespace:
		$max = 400;
		$re = "^s*[^.?!]+[.?!]+s*";
		$out = array();
        for($i = 0; $i < $max; $i++) 
        {
			if(ereg($re, $data, $match)) 
			{
			//if a sentence is found, take it out of $data and add it to $out
				array_push($out, $match[0]);
				$data = ereg_replace($re, "", $data);
			}
			else
				break;
		}
		return $out;
	}
	
	
	public function getTextSnippet($fullText, $searchString)
	{
		$sentences = $this->breakUpIntoSentences($fullText);
		$startSentence = -1;
		$textPosition = -1;
		for ($i=0;$i<count($sentences);$i++)
		{
			//echo "Sentence " . $i . " is " . $sentences[$i] . "<br/>";
			$textPosition = stripos($sentences[$i], $searchString);
			if ($textPosition != false)
			{
				$startSentence = $i;
				$replacementSnippet = substr($sentences[$i], $textPosition, strlen($searchString));
				$replacementString = "<b>" . $replacementSnippet . "</b>";
				$sentences[$i] = str_ireplace($searchString, $replacementString, $sentences[$i]);
				break;
			}
		}
		$keySentence = $startSentence;
		if ($startSentence > 0)
			$startSentence -= 1;
		//echo "Count of sentences: " . count($sentences) . "<br/>";
		//echo "key sentence: " .  $keySentence . "<br/>";
		//echo "start sentence: " . $startSentence . "<br/>";
		$newText = $sentences[$startSentence];
		$startSentence += 1;
		if ($startSentence < count($sentences))
		{
			do
			{
				$newText .= $sentences[$startSentence];
				$startSentence += 1;
				//echo "new text: " . $newText . "<br/>";
				//echo "length of new text: " . strlen($newText) . "<br/>";
			}
			while (strlen($newText) < 150 && $startSentence < count($sentences));
		}
		return $newText;
		/*
		$textPosition = stripos($fullText, $searchString);
		if ($textPosition < 50)
		{
			$startText = 0;
		}
		else
		{
			$startText = $textPosition - 30;
		}
		$replacementSnippet = substr($fullText, $textPosition, strlen($searchString));
		$newText = substr($fullText, $startText, 100);
		$replacementString = "<b>" . $replacementSnippet . "</b>";
		$newText = str_ireplace($searchString, $replacementString, $newText);
		return $newText;
		*/		
	}
}

?>