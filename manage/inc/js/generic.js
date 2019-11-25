
function disableDate(form){
	if (document.getElementById("d").disabled==false) { document.getElementById("d").disabled = true; } else { document.getElementById("d").disabled = false; }
	if (document.getElementById("m").disabled==false) { document.getElementById("m").disabled = true; } else { document.getElementById("m").disabled = false; }
	if (document.getElementById("y").disabled==false) { document.getElementById("y").disabled = true; } else { document.getElementById("y").disabled = false; }
}

function confirmDelete(msg){
	if (!msg) {
		msg = "Are you sure you want to delete?";
	}
	return confirm(msg);
}

function selectAll(cbList,bSelect) {
	for (var i=0; i<cbList.length; i++) 
	cbList[i].selected = cbList[i].checked = bSelect
}

function disableForm(theform, message) {
	if (document.all || document.getElementById) {
		for (i = 0; i < theform.length; i++) {
			var tempobj = theform.elements[i];
			if (tempobj.type.toLowerCase() == "submit") {
				tempobj.disabled = true;
				tempobj.value = message;
			}
		}
		return true;
	}
	else {
		alert("Your image is being uploaded, please do not click the 'Add Image' button again until the page changes.");
		return false;
	}
}

function showHide(id,hide) {
	if (hide) {
		document.getElementById(id).style.display = 'none';
	} else {
		document.getElementById(id).style.display = 'block';
	}
}

function checkMultiple(ids) {
	var allchecked = true;
	var tobechecked = new Array();
	id = ids.split(' ');
	//see if all items are already checeked
	for (n=0; n<id.length; n++) {
		if (document.getElementById(id[n]).checked == false) {
			tobechecked.push(id[n]);
			allchecked = false;
		}
	}
	//if all are checked, uncheck them
	if (allchecked == true) {
		for (n=0; n<id.length; n++) {
			document.getElementById(id[n]).checked=false;
		}
	//if some are not checked, check those
	} else {
		for (n=0; n<tobechecked.length; n++) {
			document.getElementById(tobechecked[n]).checked=true;
		}
	}
}

function enableMultiple(ids) {
	var allchecked = true;
	var tobechecked = new Array();
	id = ids.split(' ');
	for (n=0; n<id.length; n++) {
		if (document.getElementById(id[n]).disabled==true) {
			document.getElementById(id[n]).disabled=false;
		} else {
			document.getElementById(id[n]).disabled=true;
		}
	}
}

function flvFPW1(){
var v1=arguments,v2=v1[2].split(","),v3=(v1.length>3)?v1[3]:false,v4=(v1.length>4)?parseInt(v1[4]):0,v5=(v1.length>5)?parseInt(v1[5]):0,v6,v7=0,v8,v9,v10,v11,v12,v13,v14,v15,v16,v17,v18;if (v4>1){v10=screen.width;for (v6=0;v6<v2.length;v6++){v18=v2[v6].split("=");if (v18[0]=="width"){v8=parseInt(v18[1]);}if (v18[0]=="left"){v9=parseInt(v18[1]);v11=v6;}}if (v4==2){v7=(v10-v8)/2;v11=v2.length;}else if (v4==3){v7=v10-v8-v9;}v2[v11]="left="+v7;}if (v5>1){v14=screen.height;for (v6=0;v6<v2.length;v6++){v18=v2[v6].split("=");if (v18[0]=="height"){v12=parseInt(v18[1]);}if (v18[0]=="top"){v13=parseInt(v18[1]);v15=v6;}}if (v5==2){v7=(v14-v12)/2;v15=v2.length;}else if (v5==3){v7=v14-v12-v13;}v2[v15]="top="+v7;}v16=v2.join(",");v17=window.open(v1[0],v1[1],v16);if (v3){v17.focus();}document.MM_returnValue=false;}
/*
// Update priority
$(function() {
	$('.priority a').click(function(e){
		e.preventDefault();
		element = $(this);
		element.siblings().removeClass('selected');
		$.post($(this).attr('href'), function(data) {
			element.addClass('selected');
		})
	});
})

// Reorder products
$(function() {
	if ($("ul.groupedProductList ul").length > 0) {
		$("ul.groupedProductList ul").sortable({
			axis: "y",
			revert: 200,
			update: function(event, ui) {
				var items = $(this).sortable('toArray').toString();
				$.post('/includes/webservice.php', {items: items, action: 'updateProductGroupOrder'}, function(data) {
					console.log(data);
				});
			}
		});
		$("ul.groupedProductList ul").disableSelection();
	}
});
*/














