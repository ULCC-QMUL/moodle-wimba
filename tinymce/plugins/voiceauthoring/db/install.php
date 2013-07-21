<?php

/******************************************************************************
 *                                                                            *
 * Copyright (c) 1999-2013 Blackboard Collaborate, All Rights Reserved.       *
 *                                                                            *
 * COPYRIGHT:                                                                 *
 *      This software is the property of Horizon Wimba.                       *
 *      You can redistribute it and/or modify it under the terms of           *
 *      the GNU General Public License as published by the                    *
 *      Free Software Foundation.                                             *
 *                                                                            *
 * WARRANTIES:                                                                *
 *      This software is distributed in the hope that it will be useful,      *
 *      but WITHOUT ANY WARRANTY; without even the implied warranty of        *
 *      MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the         *
 *      GNU General Public License for more details.                          *
 *                                                                            *
 *      You should have received a copy of the GNU General Public License     *
 *      along with the Horizon Wimba Moodle Integration;                      *
 *      if not, write to the Free Software Foundation, Inc.,                  *
 *      51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA                *
 *                                                                            *
 * Author: Brian Drust                                                        *
 *                                                                            *
 * Date: 13th December 2012                                                   *
 *                                                                            *
 ******************************************************************************/

/* $Id: install.php 64342 2008-06-12 18:12:25Z thomasr $ */

defined('MOODLE_INTERNAL') || die();

function xmldb_tinymce_voiceauthoring_install() {
  global $CFG, $DB;

  $disabled = array();
  $disabledsubplugins = get_config('editor_tinymce', 'disabledsubplugins');
  if ($disabledsubplugins) {
    $disabledsubplugins = explode(',', $disabledsubplugins);
    foreach ($disabledsubplugins as $sp) {
      $sp = trim($sp);
      if ($sp !== '') {
          $disabled[$sp] = $sp;
      }
    }
  }

  $disabled['voiceauthoring'] = 'voiceauthoring';
  set_config('disabledsubplugins', implode(',', $disabled), 'editor_tinymce');
}
