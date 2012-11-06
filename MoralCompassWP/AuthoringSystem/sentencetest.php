<? 
    //getLeadingSentences 
    //Copyright (c) 2000 Jason R. Pitoniak.  All rights reserved. 
    //jason@interbrite.com http://www.interbrite.com 

    //If you find this code useful, find a bug, or have a suggestion, 
    //please email me.  Feel free to use this code for any purpose. 

    function getLeadingSentences($data, $max) 
    { 
        //given string $data, will return the first $max sentences in that string 

        //in: $data = the string to parse, $max = maximum # of sentences to return 
        //returns: string containing the first $max sentences 
        //(If the # of sentences in the string is less than $max, 
        //then entire string will be returned.) 

        //a sentence is any charactors except ., !, and ? 
        //any number of times,  plus one or more .s, ?s, or !s 
        //and any leading or trailing whitespace: 
        $re = "^s*[^.?!]+[.?!]+s*"; 
        $out = ""; 
        for($i = 0; $i < $max; $i++) { 
            if(ereg($re, $data, $match)) { 
                //if a sentence is found, take it out of $data and add it to $out 
                $out .= $match[0]; 
                $data = ereg_replace($re, "", $data); 
            } 
            else { 
                $i = $max; 
            } 
        } 
        return $out; 
    } 

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
        for($i = 0; $i < $max; $i++) { 
            if(ereg($re, $data, $match)) { 
                //if a sentence is found, take it out of $data and add it to $out 
                array_push($out, $match[0]); 
                $data = ereg_replace($re, "", $data); 
            } 
            else { 
                break;
            } 
        } 
        return $out; 
   } 

    //EXAMPLE: 
    $start = "Sentence one...  <b>Sentence two?</b>  Sentence three!  Sentence four.";
    $start = strip_tags($start); 
    //$end = getLeadingSentences($start, 1); 
    //echo("result: $end");
    $sentences = breakUpIntoSentences($start);
    foreach ($sentences as $s)
    	echo ("sentence: " . $s . "<br/>"); 
?>  
