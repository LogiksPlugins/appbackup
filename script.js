function reloadList() {
	$("#backupholder tbody").html("<tr><td colspan=10 class='ajaxloading6'></td></tr>");
	$("#backupholder tbody").load(getServiceCMD("appbackup")+"&action=load_backup&format=html",function() {
			$("#loadingmsg").hide();
		});
}
function backupQuery(q,funcOnSuccess,funcOnError) {
	$.ajax({
			url:q,
			timeout:10800000,//7200000
			success:function(txt, textStatus, jqXHR) {
				if(txt.trim().length>0) lgksAlert(txt);
				if(funcOnSuccess!=null) {
					if(typeof funcOnSuccess =='function') {funcOnSuccess(txt);}
					else window[funcOnSuccess](txt);
				}
			},
			error:function(txt, textStatus, jqXHR) {
				if(txt.trim().length>0) lgksAlert(txt);
				if(funcOnError!=null) {
					if(typeof funcOnError == "function") funcOnError();
					else window[funcOnError](txt);
				}
			},
			complete:function(txt, textStatus, jqXHR) {
				//console.log(txt);
				//alert(txt+" "+textStatus);
			},
		});
}
function doAppBackup() {
	$("#loadingmsg").show();
	$("#backupholder tbody").html("<tr><td colspan=10 class='ajaxloading6'></td></tr><tr><td colspan=10 align=center>Creating Backup Image. Do Not Close Tab Or Window</td></tr>");
	url=getServiceCMD("appbackup")+"&action=do_backup&format=html";
	backupQuery(url,reloadList,reloadList);
}
function doDeleteBackup(v) {
	$("#loadingmsg").show();
	url=getServiceCMD("appbackup")+"&action=do_delete&file="+$(v).attr("rel");
	backupQuery(url,reloadList,reloadList);
}
function doDownloadBackup(v) {
	url=getServiceCMD("appbackup")+"&action=do_download&file="+$(v).attr("rel");
	window.open(url);
}
function doRollBack(v) {
	$("#loadingmsg").show();
	$("#backupholder tbody").html("<tr><td colspan=10 class='ajaxloading6'></td></tr><tr><td colspan=10 align=center>System Is Rolling Back Using Selected Image. Do Not Close Tab Or Window</td></tr>");
	url=getServiceCMD("appbackup")+"&action=do_rollback&file="+$(v).attr("rel");
	backupQuery(url,reloadList,reloadList);
}
function showAbout() {
	lgksOverlayDiv("#backuptooloperationtext");
}
