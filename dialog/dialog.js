var up = document.getElementsByClassName("navigation");
for (i = 0; i < up.length; i++) {
	if (i > 0) {
		var previousId = up[i - 1].parentNode.id;
		up[i].getElementsByClassName("arrow-up")[0].firstElementChild.setAttribute("href", "#" + previousId);
	}
	if (i + 1 < up.length) {
		var nextId = up[i + 1].parentNode.id;
		up[i].getElementsByClassName("arrow-down")[0].firstElementChild.setAttribute("href", "#" + nextId);
	}
}
