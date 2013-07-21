<?php

defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {
    require_once($CFG->dirroot.'/mod/voicetools/lib.php');
    require_once($CFG->dirroot.'/mod/voicetools/settingslib.php');
    require_once($CFG->dirroot.'/mod/voicetools/applyPatch.php');

    global $PAGE;
    $PAGE->requires->js('/mod/voicetools/js/voicetools.js');
    $jsstrs = array('wrongconfigurationURLunavailable','emptyAdminUsername','emptyAdminPassword','trailingSlash','trailingHttp','spacesAdminUsername','spacesAdminPassword');
    $PAGE->requires->strings_for_js($jsstrs, 'voicetools');

    $settings->add(new admin_setting_heading('voicetools_header', get_string('serverconfiguration', 'voicetools'), ''));
    $settings->add(new admin_setting_configtext('voicetools_servername', get_string('servername', 'voicetools'), get_string('configservername', 'voicetools'), ''));
    $settings->add(new admin_setting_configtext('voicetools_adminusername', get_string('adminusername', 'voicetools'), '', '', PARAM_TEXT));
    $settings->add(new admin_setting_configpasswordunmask('voicetools_adminpassword', get_string('adminpassword', 'voicetools'), '', ''));

    $settings->add(new admin_setting_voicetools_voiceversion('voicetools_voiceversion', get_string('vtversion', 'voicetools'), ''));
    $settings->add(new admin_setting_voicetools_integrationversion('voicetools_integrationversion', get_string('integrationversion', 'voicetools'), ''));

    $logchoices = array('1' => 'DEBUG', '2' => 'INFO', '3' => 'WARN', '4' => 'ERROR');
    $settings->add(new admin_setting_voicetools_loglevel('voicetools_loglevel', get_string('loglevel', 'voicetools'), '', 2, $logchoices));

    $settings->add(new admin_setting_voicetools_configtest('voicetools_configtest'));
    // Automated patch only supported on Linux and MAC
    if (strpos(strtoupper(php_uname()), "WIN") !== false && strpos(strtoupper(php_uname()), "DARWIN") === false) {
        // Must be windows
    } else {
        if (version_compare(WIMBA_getVersion(),"3.5.1.1") > 0) {
            // Do nothing.  Do not add patch button if we are moodle 2.4+, it's now a tinymce plugin.
        } else if (!WIMBA_patchFilesExist()) {
            $settings->add(new admin_setting_voicetools_patch_files_unavailable('voicetools_patch_files_unavailable'));
        } else if (!WIMBA_patchAvailable()) { // do we have a patch for this version of the tinymce?
            $settings->add(new admin_setting_voicetools_patch_unavailable('voicetools_patch_unavailable'));
        } else if (!WIMBA_isPatchCommandAvailable()) { // is the patch command available?
    	    $settings->add(new admin_setting_voicetools_patch_command_unavailable('voicetools_patch_command_unavailable'));
        } else if (WIMBA_isPatchApplied()) {
            $settings->add(new admin_setting_voicetools_patch_reverse('voicetools_patch_reverse'));
        } else {
    	    $settings->add(new admin_setting_voicetools_patch_apply('voicetools_patch_apply'));
        }
    }
}

