var GLOBAL_msg = null;
function showRollover(e, msg) {
	if (!e) { 
		e = window.event;
	}
	if (GLOBAL_msg == null) {
		GLOBAL_msg = msg;
	}
	var clientX = e.clientX + 20;
	var clientY = e.clientY + 10;
	var rollover = document.getElementById('Rollover');
	rollover.innerHTML = GLOBAL_msg;
	rollover.style.marginTop = clientY + "px";
	rollover.style.marginLeft = clientX + "px";
	rollover.style.display = "inline";
	document.onmousemove = showRollover;
}
function clearRollover(e) {
	if (!e) { 
		e = window.event;
	}
	var rollover = document.getElementById('Rollover');
	rollover.style.display = "none";
	GLOBAL_msg = null;
	document.onmousemove = null;
}
