function makeVisible(tag, vis) {
	var element = document.getElementById(tag);
	if (element)
	{
		if (vis)
			element.className = "visible";
		else
			element.className = "hidden";
	}
}

function setState(param) {
	for (i=1;i<=6;i++)
	{	
		var rsrcName = "divResource" + i;
		makeVisible(rsrcName, false);
	}
	if (param == 1)
	{
		makeVisible("divIntro", true);
		makeVisible("divFullArticle", false);
		makeVisible("divScoreCard", true);
		makeVisible("divMyScoreCard", false);
		makeVisible("divResourceThumbnails", true);
		makeVisible("divLinks", true);
		location.href = "#";
	}
	else if (param == 2)
	{
		makeVisible("divIntro", false);
		makeVisible("divFullArticle", true);
		makeVisible("divScoreCard", true);
		makeVisible("divMyScoreCard", false);
		makeVisible("divResourceThumbnails", true);
		makeVisible("divLinks", true);
		location.href = "#";
	}
	else if (param == 3)
	{
		location.href = "#scorecard";
	}
	else if (param == 4)
	{
		makeVisible("divIntro", true);
		makeVisible("divFullArticle", false);
		makeVisible("divScoreCard", true);
		makeVisible("divMyScoreCard", true);
		makeVisible("divResourceThumbnails", true);
		makeVisible("divLinks", true);
		location.href = "#myscorecard";
	}
	else if (param == 5)
	{
		location.href = "#resources";
	}
	else if (param == 6)
	{
		location.href = "#links";
	}
}

var divCurrentlyPlayingVideo = "";

function setResource(rsrc) {
	var divRsrc = rsrc + 1;
	
	makeVisible("divIntro", true);
	makeVisible("divFullArticle", false);
	makeVisible("divScoreCard", true);
	makeVisible("divMyScoreCard", false);
	makeVisible("divResourceThumbnails", true);
	makeVisible("divLinks", true);
	if (divCurrentlyPlayingVideo != "")
	{
		toggleVideo(divCurrentlyPlayingVideo, 'hide');
		divCurrentlyPlayingVideo = "";
	}
	for (i=1;i<=6;i++)
	{	
		var rsrcName = "divResource" + i;
		if (i == divRsrc)
		{
			makeVisible(rsrcName, true);
			divCurrentlyPlayingVideo = rsrcName;
		}
		else
			makeVisible(rsrcName, false);
	}
} 


function toggleVideo(videoDivId, state)
{
    var div = document.getElementById(videoDivId);
	if (div.getElementsByTagName("iframe")[0] == undefined)
		return;
    var iframe = div.getElementsByTagName("iframe")[0].contentWindow;
	//div.style.display = state == 'hide' ? 'none' : '';
	func = (state == 'hide' ? 'pauseVideo' : 'playVideo');
	iframe.postMessage('{"event":"command","func":"' + func + '","args":""}','*');
}
