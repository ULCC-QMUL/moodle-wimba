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

/* $Id: editor_plugin.js 64342 2008-06-12 18:12:25Z thomasr $ */

(function() {
  tinymce.create('tinymce.plugins.voiceauthoringPlugin', {
    getInfo : function() {
      return {
        longname : 'Voice Authoring',
        author : 'Blackboard Collaborate',
        authorurl : 'http://www.blackboardcollaborate.com',
        infourl : 'http://docs.moodle.org/en/TinyMCE',
        version : "1.0"
      };
    },

    init : function(ed, url) {
      // Register command
      ed.addCommand('mceVoiceAuthoring', function() {
        ed.windowManager.open({
          file : ed.getParam("moodle_plugin_base") + 'voiceauthoring/wimba.php',
          width : 320 + parseInt(ed.getLang('wimba.delta_width', 0)),
          height : 135 + parseInt(ed.getLang('wimba.delta_height', 0)),
          inline : 1
        }, {
          plugin_url : url
        });
      });

      // Register voiceauthoring button
      ed.addButton('voiceauthoring', {
        title : 'voiceauthoring.desc',
        cmd : 'mceVoiceAuthoring',
        image : url + '/img/icon.gif'
      });
    },

    _parse : function(s) {
       return tinymce.util.JSON.parse('{' + s + '}');
    }
  });

  // Register plugin.
  tinymce.PluginManager.add('voiceauthoring', tinymce.plugins.voiceauthoringPlugin);
})();
