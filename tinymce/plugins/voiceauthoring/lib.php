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

/* $Id: lib.php 64342 2008-06-12 18:12:25Z thomasr $ */

defined('MOODLE_INTERNAL') || die();

class tinymce_voiceauthoring extends editor_tinymce_plugin {
  protected $buttons = array('voiceauthoring');

  protected function update_init_params(array &$params, context $context, array $options = null) {
    
    // The "|," puts the voiceauthoring button in an unattached  new section   
    $this->add_button_after($params, 3, '|,voiceauthoring');

    // Add JS file, which uses default name.
    $this->add_js_plugin($params);
  }
}
