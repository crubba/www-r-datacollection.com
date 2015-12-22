			function resolveSrcMouseover(e) {
				var node = e.srcElement == undefined ? e.target : e.srcElement;
				if (node.nodeName != "UL") {
					node.style.fontWeight= "bold";
					showRollover(e, node.innerHTML);
				}
			}
			function resolveSrcMouseout(e) {
				var node = e.srcElement == undefined ? e.target : e.srcElement;
				node.style.fontWeight = "normal";
				clearRollover(e);
			}
			function takeAction(e) {
				var node = e.srcElement == undefined ? e.target : e.srcElement;
				document.getElementById("DisplayInfo").innerHTML = "Clicked " + node.innerHTML;
				var id = node.getAttribute("id"); 
				if (id != null && id.indexOf("Folder") > -1) {
					if (node.innerHTML == "-") {
						node.innerHTML = "+";
						document.getElementById("ExpandCollapse" + id).style.display = "none";
					} else if (node.innerHTML == "+") {
						node.innerHTML = "-";
						document.getElementById("ExpandCollapse" + id).style.display = "block";
					}
				}
			}