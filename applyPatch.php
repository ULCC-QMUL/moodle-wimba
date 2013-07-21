<?php
global $CFG;
if (isset($CFG->dirroot)) { // from settings.php
	require_once($CFG->dirroot."/config.php");
} else { //stand alone
	require_once("../../config.php");
	require_once("lib/php/common/WimbaLib.php");
}

//Make sure we are an admin
if (!is_siteadmin()) {
	error("You must be an Site Administrator to run this script");
}

$action=optional_param('action','',PARAM_TEXT);

wimba_add_log(WIMBA_INFO,"voicetools","applyPatch called with action: ".$action);

// map versions to patch files
global $patch_files;
$patch_files = array( "3.3.9.2" => "wimba.patch.3.3.9.2",
					"3.4.2" => "wimba.patch.3.4.2",
					"3.4.6" => "wimba.patch.3.4.6",
					"3.4.9" => "wimba.patch.3.4.9",
					"3.5.1.1" => "wimba.patch.3.5.1.1");

global $patch_success;					
$patch_success = array( "3.3.9.2" => "patching file lib/weblib.php",
					"3.4.2" => "patching file lib/editor/tinymce/lib.php",
					"3.4.6" => "patching file lib/editor/tinymce/lib.php",
					"3.4.9" => "patching file lib/editor/tinymce/lib.php",
					"3.5.1.1" => "patching file lib/editor/tinymce/lib.php");

global $patch_files_list;
$patch_files_list = array("images/wimba.gif",
                          "images/wimba_sound.png",
                          "lib/weblib.php",
                          "lib/editor/tinymce/lib.php",
                          "lib/editor/tinymce/tiny_mce/3.3.9.2/plugins/wimba/editor_plugin.js",
                          "lib/editor/tinymce/tiny_mce/3.3.9.2/plugins/wimba/editor_plugin_src.js",
                          "lib/editor/tinymce/tiny_mce/3.3.9.2/plugins/wimba/wimba.php",
                          "lib/editor/tinymce/tiny_mce/3.3.9.2/plugins/wimba/css/wimba.css",
                          "lib/editor/tinymce/tiny_mce/3.3.9.2/plugins/wimba/img/icon.gif",
                          "lib/editor/tinymce/tiny_mce/3.3.9.2/plugins/wimba/js/wimba.js");

global $ver;
$ver = WIMBA_getVersion();

// check if have a patch for this version
if (!WIMBA_patchAvailable()) {
	wimba_add_log(WIMBA_ERROR,"voicetools","No patch file found for version ".$ver." of the tiny_mce editor");
}

if (!WIMBA_isPatchCommandAvailable()) {
	wimba_add_log(WIMBA_ERROR,"voicetools","No patch utility found.");
}

if ($action == "apply") {
	if (WIMBA_isPatchApplied()) {
		echo get_string("patch_error5", 'voicetools');
		wimba_add_log(WIMBA_ERROR,"voicetools",get_string("patch_error5", 'voicetools'));
		exit();
	}
	WIMBA_apply_patch();
	echo get_string("patch_msg1", 'voicetools');
	wimba_add_log(WIMBA_INFO,"voicetools",get_string("patch_msg1", 'voicetools'));
	exit();
} else if ($action == "reverse") {
	if (!WIMBA_isPatchApplied()) {
		echo get_string("patch_error6", 'voicetools');
		wimba_add_log(WIMBA_ERROR,"voicetools",get_string("patch_error6", 'voicetools'));
		exit();
	}
	WIMBA_reverse_patch();
	echo get_string("patch_msg2", 'voicetools');
	wimba_add_log(WIMBA_INFO,"voicetools",get_string("patch_msg2", 'voicetools'));
	exit();
}

function WIMBA_isPatchCommandAvailable() {
	//See if the patch utility is available
	$output = WIMBA_run_patch_command("patch -v");
	return(preg_match("/^patch.*written by.*/",implode(" ",$output)) );
}

function WIMBA_patchAvailable() {
	global $patch_files,$ver;
	return(array_key_exists($ver,$patch_files));
}

function WIMBA_getVersion() {
	global $CFG;
	$tinymce_root_dir = $CFG->dirroot."/lib/editor/tinymce/tiny_mce";

	wimba_add_log(WIMBA_INFO,"voicetools","looking in directory: ".$tinymce_root_dir." for version.");

	$versions = array();
	// Get version of tiny_mce editor
	if ($handle = opendir($tinymce_root_dir)) {
  		while (false !== ($entry = readdir($handle))) {
			if (preg_match("/^\.|^\.\./",$entry)) {
				continue;
			}
			wimba_add_log(WIMBA_INFO,"voicetools","Found version: ".$entry);
			array_push($versions,$entry);
		}

		closedir($handle);
	}

	if (count($versions) > 1) {
		echo get_string("patch_error1", 'voicetools');
		wimba_add_log(WIMBA_ERROR,"voicetools",get_string("patch_error1", 'voicetools'));
		exit();
	} else if (count($versions) == 0) {
		echo get_string("patch_error2", 'voicetools');
		wimba_add_log(WIMBA_ERROR,"voicetools",get_string("patch_error2", 'voicetools'));
		exit();
	}

	$ver = array_pop($versions);
	wimba_add_log(WIMBA_INFO,"voicetools","Found version ".$ver. " of the tiny_mce editor to patch.");
	return($ver);
}

function WIMBA_apply_patch() {
	global $CFG, $ver, $patch_files, $patch_success;

	// Let's run the patch and copy commands
	$src_dir = $CFG->dirroot."/wysiwyg/lib/editor/tinymce/tiny_mce/3.3.9.2/plugins/wimba";
	$dest_dir = $CFG->dirroot."/lib/editor/tinymce/tiny_mce/".$ver."/plugins/wimba";
	WIMBA_recursive_copy($src_dir,$dest_dir);
	wimba_add_log(WIMBA_INFO,"voicetools","Copied ".$src_dir." -> ".$dest_dir);

	$patch_cmd = "patch -p1 < wysiwyg/".$patch_files["$ver"];
	wimba_add_log(WIMBA_INFO,"voicetools","Running patch command: ".$patch_cmd);
	$output = WIMBA_run_patch_command($patch_cmd);
	wimba_add_log(WIMBA_INFO,"voicetools","Output patch command: ".implode("\n",$output));
	if (end($output) != $patch_success["$ver"]) {
		echo get_string("patch_error4", 'voicetools');
		wimba_add_log(WIMBA_ERROR,"voicetools","Expected this output from patch command: ".$patch_success.", but got this instead: ".end($output));
		wimba_add_log(WIMBA_INFO,"voicetools","Removing directory: /lib/editor/tinymce/tiny_mce/".$ver."/plugins/wimba");
		WIMBA_deleteDir($dest_dir);
		exit();
	}
}

function WIMBA_reverse_patch() {
	global $CFG, $ver, $patch_files, $patch_success;

	// Let's run the patch and copy commands
	$src_dir = $CFG->dirroot."/wysiwyg/lib/editor/tinymce/tiny_mce/3.3.9.2/plugins/wimba";
	$dest_dir = $CFG->dirroot."/lib/editor/tinymce/tiny_mce/".$ver."/plugins/wimba";
	exec("rm -rf ".$dest_dir,$output);
	wimba_add_log(WIMBA_INFO,"voicetools","Removed ".$dest_dir);
	wimba_add_log(WIMBA_INFO,"voicetools","Output from rm -rf ".$dest_dir." command: ".implode("\n",$output));

	$patch_cmd = "patch -p1 -R < wysiwyg/".$patch_files["$ver"];
	wimba_add_log(WIMBA_INFO,"voicetools","Running patch command: ".$patch_cmd);
	$output = WIMBA_run_patch_command($patch_cmd);
	wimba_add_log(WIMBA_INFO,"voicetools","Output patch command: ".implode("\n",$output));
	if (end($output) != $patch_success["$ver"]) {
		echo get_string("patch_error4", 'voicetools');
		wimba_add_log(WIMBA_ERROR,"voicetools","Expected this output from patch command: ".$patch_success.", but got this instead: ".end($output));
		exit();
	}
}

function WIMBA_run_patch_command($cmd) {
	global $CFG;
	if (!chdir($CFG->dirroot)) {
		echo get_string("patch_error4", 'voicetools');
		wimba_add_log(WIMBA_ERROR,"voicetools","Unable to chdir() into ".$CFG->dirroot);
		exit();
	}
	
	exec($cmd,$output);
	return($output);
}

function WIMBA_recursive_copy($src,$dst) { 
	$dir = opendir($src); 
	@mkdir($dst); 
	while(false !== ( $file = readdir($dir)) ) { 
		if (( $file != '.' ) && ( $file != '..' )) { 
			if ( is_dir($src . '/' . $file) ) { 
				WIMBA_recursive_copy($src . '/' . $file,$dst . '/' . $file); 
			} else {
				copy($src . '/' . $file,$dst . '/' . $file); 
			} 
		} 
	} 
	closedir($dir); 
}

function WIMBA_deleteDir($dirPath) {
	if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
		$dirPath .= '/';
	}
	$files = glob($dirPath . '*', GLOB_MARK);
	foreach ($files as $file) {
		if (is_dir($file)) {
			self::WIMBA_deleteDir($file);
		} else {
			unlink($file);
		}
	}
	rmdir($dirPath);
}


function WIMBA_isPatchApplied() {
	global $CFG, $ver;
	
	return(file_exists($CFG->dirroot."/lib/editor/tinymce/tiny_mce/".$ver."/plugins/wimba"));
}

function WIMBA_patchFilesExist() {
	global $patch_files_list,$CFG;

	$src_dir = $CFG->dirroot."/wysiwyg/";
	$error = 1;
 
        foreach ($patch_files_list as $file) {
		if (!is_file($src_dir.$file)) {
			wimba_add_log(WIMBA_ERROR,"voicetools","Missing patch file: ".$src_dir.$file);
			$error = 0;
		}
	}

	return($error);
}

?>
