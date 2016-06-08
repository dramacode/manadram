
(function($) {
        $(document).ready(function() {
            var scene = location.search;
   
            if (scene.indexOf("&scene=") > 0) {
           
           
            scene = scene.substring(scene.indexOf("&scene=")+7);
            document.getElementById(scene).style="background-color:yellow;"}

for (i = 0; i < configurationBreaks.length; i++) {
	var play = configurationBreaks[i].id;
	for (j = 0; j < configurationBreaks[i].configurations.length; j++) {
		var td = document.getElementById(play).getElementsByClassName(configurationBreaks[i].configurations[j]);
		for (k = 0; k < td.length; k++) {
			td[k].className += " break";
		}
	}
}
/*
//google
google.load("visualization", "1", {
	packages: ["corechart", 'table']
});
function drawChart(chartType, containerID, dataArray, options) {
	var data = google.visualization.arrayToDataTable(dataArray);
	var containerDiv = document.getElementById(containerID);
	var chart = false;
	if (chartType.toUpperCase() == 'BARCHART') {
		chart = new google.visualization.BarChart(containerDiv);
	} else if (chartType.toUpperCase() == 'COLUMNCHART') {
		chart = new google.visualization.ColumnChart(containerDiv);
	} else if (chartType.toUpperCase() == 'PIECHART') {
		chart = new google.visualization.PieChart(containerDiv);
	} else if (chartType.toUpperCase() == 'TABLECHART') {
		chart = new google.visualization.Table(containerDiv);
	}
	if (chart == false) {
		return false;
	}
	chart.draw(data, options);
}
*/

 
        });
})(jQuery);
