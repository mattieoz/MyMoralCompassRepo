<?php 
	
	function findTodaysFeaturedTopic($featuredTopics)
	{
		$nTopics = count($featuredTopics);
		$now = time(); // or your date as well
		$your_date = strtotime("2012-10-01 00:00:00");
		$datediff = floor(($now - $your_date) / (60*60*24));
		$topicIndex = $datediff % $nTopics;
		//echo "Date diff: " . $datediff . "<br/>";
		//echo "nTopics: " . $nTopics . "<br/>";
		//echo "Topic index: " . $topicIndex . "<br/>";
		//echo "featuredtopic shortname: " . $featuredTopics[$topicIndex]->shortName;
		return $featuredTopics[$topicIndex]->shortName; 
	}

?>