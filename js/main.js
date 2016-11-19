/*
 * OpenCombat
*/

var totalServers = 0;

function _ready()
{
	totalServers = 0;
	$(".status").html("Refreshing server list...");
	
	$.getJSON("index.php?do=lists", function(json)
	{
		$.each(json.result, getServerData);
	});
}

function getServerData(i, val)
{
	var startTime = (new Date()).getTime();
	
	$.getJSON("http://" + val.ip + ":" + val.port + "/server", function(json)
	{
		var endTime = (new Date()).getTime();
		var latency = endTime-startTime;
		var html = "";
		
		html += "<tr>";
		html += "<td>" + val.name + "</td>";
		html += "<td>" + val.ip + ":" + val.port + "</td>";
		html += "<td>" + json.players + "</td>";
		html += "<td>" + latency + " ms</td>";
		html += "</tr>";
		
		$(".servers").append(html);
		
		totalServers += 1;
		$(".status").html("Server count: " + totalServers);
	});
}

$(document).ready(_ready);

