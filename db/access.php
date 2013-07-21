<?php

defined('MOODLE_INTERNAL') || die();

$capabilities = array(

# We currently do not allow this block to be added to the "my" space 
# Leaving the code here to be uncommented if that ever changes.
#    'block/bvoicerecorder:myaddinstance' => array(
#        'captype' => 'write',
#        'contextlevel' => CONTEXT_SYSTEM,
#        'archetypes' => array(
#            'user' => CAP_ALLOW
#        ),
# 
#        'clonepermissionsfrom' => 'moodle/my:manageblocks'
#    ),

    'block/bvoicerecorder:addinstance' => array(
        'riskbitmask' => RISK_SPAM | RISK_XSS,

        'captype' => 'write',
        'contextlevel' => CONTEXT_BLOCK,
        'archetypes' => array(
            'editingteacher' => CAP_ALLOW,
            'manager' => CAP_ALLOW
        ),

        'clonepermissionsfrom' => 'moodle/site:manageblocks'
    ),
);
