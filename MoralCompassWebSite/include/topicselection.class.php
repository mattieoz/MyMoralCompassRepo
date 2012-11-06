<?php

class Selection
{
	const INTRO = 1;
	const ARTICLE = 2;
	const VIDEO = 3;
	const GRAPHIC = 4;
	const LINKS = 5;
	const SCORECARD = 6;
	
	public $possibleTypes = array(self::INTRO => "Intro", self::ARTICLE => "Article", self::VIDEO => "Video", self::GRAPHIC => "Graphic",
		self::LINKS => "Links", self::SCORECARD => "Scorecard");
	
	public $id;
	public $type;
	public $sequence;
	public $article;
	public $thumbnail;
	public $image;
	public $breadcrumb;
	public $videoEmbedCode;
	public $videoURL;
	
	public function topicLabel() {
		if ($this->type == self::INTRO)
			return "Intro";
		else if ($this->type == self::ARTICLE)
			return "Article";
		else if ($this->type == self::VIDEO)
			return "Video";
		else if ($this->type == self::GRAPHIC)
			return "Graphic";
		else if ($this->type == self::LINKS)
			return "Links";
		else if ($this->type == self::SCORECARD)
			return "Scorecard";
		else
			return "";
	}
	
	public function tokenizeLink($strLink)
	{
		$strLink = str_replace("<", "&lt;", $strLink);
		$strLink = str_replace(">", "&gt;", $strLink);
		return $strLink;
	}
}

class ScoreItem
{
	public $id;
	public $originalScoreId;
	public $who;
	public $score;
	public $description;
	
	public $possibleGrades = array(
		97 => "A+", 93 => "A", 90 => "A-", 
		87 => "B+", 83 => "B", 80 => "B-",
		77 => "C+", 73 => "C", 70 => "C-",
		67 => "D+", 63 => "D", 60 => "D-",
		55 => "F", -1 => "--");
	
	public function scoreToGrade() {
		if ($this->score >= 97)
			return "A+";
		else if ($this->score >= 93)
			return "A";
		else if ($this->score >= 90)
			return "A-";
		else if ($this->score >= 87)
			return "B+";
		else if ($this->score >= 83)
			return "B";
		else if ($this->score >= 80)
			return "B-";
		else if ($this->score >= 77)
			return "C+";
		else if ($this->score >= 73)
			return "C";
		else if ($this->score >= 70)
			return "C-";
		else if ($this->score >= 67)
			return "D+";
		else if ($this->score >= 63)
			return "D";
		else if ($this->score >= 60)
			return "D-";
		else if ($this->score == -1)
			return "--";
		else
			return "F";
		}
	
}

// don't know if I'll need this, it's the reverse of nl2br
function br2nl($string)
{
    return preg_replace('/\<br(\s*)?\/?\>/i', "\n", $string);
} 

function outputGradeOptions($currentScore)
{
	$scoreItem = new ScoreItem();
	$scoreItem->score = $currentScore;
	$gradeToMatch = $scoreItem->scoreToGrade();
	$optionString = "";
	foreach ($scoreItem->possibleGrades as $n => $grd)
	{
		if ($currentScore == $n)
			$optionString = $optionString . "<option selected value=" . $n . ">" . $grd . "</option>";
		else
			$optionString = $optionString . "<option value=" . $n . ">" . $grd . "</option>";
	}

	return $optionString;
}

?>