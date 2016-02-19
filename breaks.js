for (i = 0; i < configurationBreaks.length; i++) {
	var play = configurationBreaks[i].id;
	for (j = 0; j < configurationBreaks[i].configurations.length; j++) {
		var td = document.getElementById(play).getElementsByClassName(configurationBreaks[i].configurations[j]);
		for (k = 0; k < td.length; k++) {
			td[k].className += " break";
		}
	}
}
//moyenne des présents
var table = document.getElementsByClassName("result");
for (i = 0; i < table.length; i++) {
	var tableid = table[i].id;
	var conf = configurationLength[i];
	var act = table[i].getElementsByClassName("averagePresents")[0].getElementsByClassName("act");
	for (j = 0; j < act.length; j++) {
		var actId = act[j].id.replace("averagePresents", "");
		var td = table[i].getElementsByClassName("numberPresents")[0].querySelectorAll("." + actId + ".configuration");
		var configurations = td.length;
		var speakings = 0;
		for (k = 0; k < td.length; k++) {
			if (td[k].innerHTML != '') {
				speakings += parseInt(td[k].innerHTML);
			}
		}
		var average = Math.round((speakings / configurations) * 10) / 10;
		act[j].innerHTML = average;
	}
	var act = table[i].getElementsByClassName("averageSpeakings")[0].getElementsByClassName("act");
	for (j = 0; j < act.length; j++) {
		var actId = act[j].id.replace("averageSpeakings", "");
		var td = table[i].getElementsByClassName("numberSpeakings")[0].querySelectorAll("." + actId + ".configuration");
		var configurations = td.length;
		var speakings = 0;
		for (k = 0; k < td.length; k++) {
			if (td[k].innerHTML != '') {
				speakings += parseInt(td[k].innerHTML);
			}
		}
		var average = Math.round((speakings / configurations) * 10) / 10;
		act[j].innerHTML = average;
	}
	var td = table[i].getElementsByClassName("length")[0].querySelectorAll(".configuration");
	for (j = 0; j < td.length; j++) {
		td[j].innerHTML = conf[j];
	}
	var act = table[i].getElementsByClassName("averagePresentsTime")[0].getElementsByClassName("act");
	for (j = 0; j < act.length; j++) {
		var actId = act[j].id.replace("averagePresentsTime", "");
		var lengthTd = table[i].getElementsByClassName("length")[0].querySelectorAll("." + actId + ".configuration");
		var numberTd = table[i].getElementsByClassName("numberPresents")[0].querySelectorAll("." + actId + ".configuration");
		var configurations = lengthTd.length;
		var actLength = 0;
		for (k = 0; k < lengthTd.length; k++) {
			if (lengthTd[k].innerHTML != '') {
				actLength += parseInt(lengthTd[k].innerHTML)
			}
		}
		var count = 0;
		for (k = 0; k < lengthTd.length; k++) {
			if ((numberTd[k].innerHTML != '') && (lengthTd[k].innerHTML != '')) {
				length = parseInt(lengthTd[k].innerHTML);
				number = parseInt(numberTd[k].innerHTML);
				count += (length * number);
			}
		}
		var average = Math.round((count / actLength) * 10) / 10;
		act[j].innerHTML = average;
	}
	var act = table[i].getElementsByClassName("averageSpeakingsTime")[0].getElementsByClassName("act");
	var averages = [];
	for (j = 0; j < act.length; j++) {
		var actId = act[j].id.replace("averageSpeakingsTime", "");
		var lengthTd = table[i].getElementsByClassName("length")[0].querySelectorAll("." + actId + ".configuration");
		var numberTd = table[i].getElementsByClassName("numberSpeakings")[0].querySelectorAll("." + actId + ".configuration");
		var offstageTd = table[i].getElementsByClassName("numberOffstage")[0].querySelectorAll("." + actId + ".configuration");
		var configurations = lengthTd.length;
		var actLength = 0;
		for (k = 0; k < lengthTd.length; k++) {
			if (lengthTd[k].innerHTML != '') {
				actLength += parseInt(lengthTd[k].innerHTML)
			}
		}
		var count = 0;
		for (k = 0; k < lengthTd.length; k++) {
			if ((numberTd[k].innerHTML != '') && (lengthTd[k].innerHTML != '')) {
				var length = parseInt(lengthTd[k].innerHTML);
				var number = parseInt(numberTd[k].innerHTML);
				var offstage = 0;
				if (offstageTd[k].innerHTML != '') {
					var offstage = parseInt(offstageTd[k].innerHTML);
				}
				count += (length * (number - offstage));
			}
		}
		var average = Math.round((count / actLength) * 10) / 10;
		act[j].innerHTML = average;
		averages.push(average);
	}
	var acts = table[i].getElementsByClassName("acts")[0].getElementsByClassName("act");
	var labels = [];
	for (l = 0; l < acts.length; l++) {
		labels.push(acts[l].innerHTML);
	}
	var data = averages;
	var bar = new RGraph.Bar({
		id: 'graph' + tableid,
		data: data,
		options: {
			labels: labels,
			labelsAbove: true,
			labelsAboveDecimals: 1,
			gutterLeft: 45,
			backgroundBarcolor1: 'white',
			backgroundBarcolor2: 'white',
			backgroundGrid: true,
			colors: ['grey']
		}
	}).draw();
}