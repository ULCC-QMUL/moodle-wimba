function onOK() {

  var fields = ["f_mid", "f_rid"];
  var param = new Object();
  for (var i in fields) {
    var id = fields[i];
    var el = document.getElementById(id);
    param[id] = el.value;
  }
  __dlg_close(param);
  return false;
};

function insertWimba() {
    var fe, f = document.forms[0], h;
    var ed = tinyMCEPopup.editor;

    tinyMCEPopup.restoreSelection();

    if (!AutoValidator.validate(f)) {
        tinyMCEPopup.alert(ed.getLang('invalid_data'));
        return false;
    }

    fe = ed.selection.getNode();
    var extravals = f.f_rid.value + '__' + f.f_mid.value + '_image_' + f.f_cid.value;
    thtml = '<img title="Click to play" src="'+wwwroot+'/mod/voiceauthoring/lib/web/pictures/items/wimba_sound.png" alt="'+extravals+'" />';

    ed.execCommand('mceInsertRawHTML', false, thtml);

    tinyMCEPopup.close();
}

