function makeVisible(tag, vis) {
	var element = document.getElementById(tag);
	if (element)
	{
		if (vis)
		{
			if (tag.substring(3, 11) == "Resource")
				element.className = "media-area";
			else
				element.className = "visible";
			//element.style.display = "block";	
		}
		else
			element.className = "hidden";
			//element.style.display = "none";		
	}
}

function setAuthorScoreCardVisible(visible) {
	if (visible)
	{
		makeVisible("divAuthorScoreCard", true);
		makeVisible("divAuthorScoreCardHeader", false);
	}
	else
	{
		makeVisible("divAuthorScoreCard", false);
		makeVisible("divAuthorScoreCardHeader", true);
	}
}

function setState(param) {
	for (i=1;i<=8;i++)
	{	
		var rsrcName = "divResource" + i;
		makeVisible(rsrcName, false);
	}
	if (param == 1)
	{
		makeVisible("divIntro", true);
		makeVisible("divFullArticle", false);
		makeVisible("divMyScoreCard", false);
		//makeVisible("divResourceThumbnails", true);
		//makeVisible("divLinks", true);
		location.href = "#";
	}
	else if (param == 2)
	{
		makeVisible("divIntro", false);
		makeVisible("divFullArticle", true);
		makeVisible("divMyScoreCard", false);
		//makeVisible("divResourceThumbnails", true);
		//makeVisible("divLinks", true);
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
		makeVisible("divMyScoreCard", true);
		//makeVisible("divResourceThumbnails", true);
		//makeVisible("divLinks", true);
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
var thumbnailOffset = 1;

function setResource(rsrc) {
	var divRsrc = (rsrc + 1);
	//alert("divRsrc = " + divRsrc);
	setState(5);
	//makeVisible("divIntro", true);
	//makeVisible("divFullArticle", false);
	//makeVisible("divAuthorScoreCard", true);
	//makeVisible("divMyScoreCard", false);
	//makeVisible("divResourceThumbnails", true);
	//makeVisible("divLinks", true);
	
	if (divCurrentlyPlayingVideo != "")
	{
		toggleVideo(divCurrentlyPlayingVideo, 'hide');
		divCurrentlyPlayingVideo = "";
	}
	for (i=1;i<=8;i++)
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

// attempt to delay video to fix IE8 problem, didn't work
function setResourceXXXXX(rsrc) {
	setTimeout("setResourceActual(" + (rsrc + 1) + ")", 1500);
} 

function setVisibleThumbnails(increment, maxvalue)
{
	if (thumbnailOffset + increment < 1)
		return;
	if (thumbnailOffset + increment + 2 > maxvalue)
		return;
	thumbnailOffset = thumbnailOffset + increment;
	for (i=1;i<=8;i++)
	{
		var thumbnailName = "divThumbnail" + i;
		if (i < thumbnailOffset || i > thumbnailOffset + 2)
			makeVisible(thumbnailName, false);
		else
			makeVisible(thumbnailName, true);
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
