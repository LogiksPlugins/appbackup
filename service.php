<?php
if(!defined('ROOT')) exit('No direct script access allowed');
checkServiceSession();
set_time_limit(0);

if(isset($_REQUEST["action"]) && isset($_REQUEST["forsite"])) {
	checkUserSiteAccess($_REQUEST['forsite'],true);
	user_admin_check(true);

	loadModule("lgksbackup");
	switch($_REQUEST["action"]) {
		case "load_backup":
			$lb=new LogiksBackup($_REQUEST["forsite"]);
			$arr=$lb->listBackup();
			if($_REQUEST['format']=="html") {
				foreach($arr as $backup){
					$size=getFileSizeInString($backup['size']);
					if($backup['kind']=="folder") {
						$s="<tr>
							<td width=153px align=left>{$backup['date']}</td>
							<td width=153px align=center>{$backup['time']}</td>
							<td width=93px align=center>{$size}</td>
							<td width=83px align=center>
								
							</td>
							<td width=83px align=center><button rel='".$backup['name']."' class='toolbutton' onclick='doDeleteBackup(this);return false;'
							id='deletebtn'>Delete</button></td>
							<td width=83px align=center><button rel='".$backup['name']."' class='toolbutton' onclick='doRollBack(this);return false;'
							id='rollbackbtn'>Rollback</button></td></tr>
							<tr><td colspan=10 align=right>Please use FTP method for deleting or restoring.</td></tr>";
					} else {
						$s="<tr>
							<td width=153px align=left>{$backup['date']}</td>
							<td width=153px align=center>{$backup['time']}</td>
							<td width=93px align=center>{$size}</td>
							<td width=83px align=center><button rel='".$backup['name']."' class='toolbutton' onclick='doDownloadBackup(this);return false;'
							id='downloadbtn'>Download</button></td>
							<td width=83px align=center><button rel='".$backup['name']."' class='toolbutton' onclick='doDeleteBackup(this);return false;'
							id='deletebtn'>Delete</button></td>
							<td width=83px align=center><button rel='".$backup['name']."' class='toolbutton' onclick='doRollBack(this);return false;'
							id='rollbackbtn'>Rollback</button></td></tr>";
					}
					echo $s;
				}
			} else printServiceMsg($arr);
			break;
		case "do_backup":
			$lb=new LogiksBackup($_REQUEST["forsite"]);
			$s=$lb->createBackup();
			if(is_array($s)) echo "Backup For <b>'{$s['Site']}'</b> Completed On {$s['Dated']}";
			else echo $s;
			break;
		case "do_delete":
			$lb=new LogiksBackup($_REQUEST["forsite"]);
			$s=$lb->deleteBackup($_REQUEST["file"]);
			if(is_array($s)) echo "";//"Backup For <b>'{$s['Site']}'</b> Deleted On {$s['Dated']}";
			else echo $s;
			break;
		case "do_download":
			$lb=new LogiksBackup($_REQUEST["forsite"]);
			$f=$lb->getBackupFileFor($_REQUEST["file"]);
			if(!file_exists($f)) {
				printErr("FileNotFound","Download Backup Index Not Found");
			} else {
				download_large_file($f);
			}
			break;
		case "do_rollback":
			$lb=new LogiksBackup($_REQUEST["forsite"]);
			$s=$lb->rollbackBackup($_REQUEST["file"]);
			if(is_array($s)) echo "Rollback For <b>'{$s['Site']}'</b> Completed On {$s['Dated']}";
			else echo $s;
			break;
	}
}
?>
