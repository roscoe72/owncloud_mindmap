<?php
$quickStartFolder="/Mindmap";
$quickStartFilename="Mindmap";
$language="auto"; //Set to "en_GB" to enforce English. Check l10n folder for available languages


//BELOW THIS LINE NO VAR CHANGES!!
//--------------------------------
$appName="mindmap"; //owncloud apps folder name
$appPath="apps/".$appName;

//Check for valid login
OC_Util::checkLoggedIn();
OC_Util::checkAppEnabled($appName);


//Function: Check if file or path is readonly
function readonly($dir,$filename) {
 $filepath=$dir."/".$filename;
 $readonly=true;
 if (!\OC\Files\Filesystem::file_exists($filepath)==0){
  if (!\OC\Files\Filesystem::isUpdatable($filepath)==0){$readonly=false;}
 }
 else
 {
  if (!\OC\Files\Filesystem::isCreatable($dir)==0){$readonly=false;}
 }
 return $readonly;
}




//FILE READING AND SAVING ACTIONS
//-------------------------------
if (isset($_REQUEST['action']) && isset($_REQUEST['filename']) && isset($_REQUEST['dir'])) {
 $dir=$_REQUEST['dir']."/";
 $filename=$_REQUEST['filename'];
 $filepath=$dir."/".$filename;
 //read json file
 if ($_REQUEST['action']=="readjson") {
  if (\OC\Files\Filesystem::file_exists($filepath)==1){
   echo \OC\Files\Filesystem::file_get_contents($filepath);
  }
 }
 //save json file
 if ($_REQUEST['action']=="savejson") {
  if (readonly($dir,$filename)==0) {\OC\Files\Filesystem::file_put_contents($filepath,$_REQUEST['data']);}else{exit("Cannot save file");}
 }
 //save png file
 if ($_REQUEST['action']=="savepng") {
  $imgData = base64_decode($_REQUEST['data']);
  if (readonly($dir,$filename)==0) {\OC\Files\Filesystem::file_put_contents($filepath,$imgData);}else{exit("Cannot save file");}
 }
 exit;
}

//START HTML DOCUMENT FORMATING
echo '<!DOCTYPE html>';
echo '<html>';
echo '<head>';


//DETERMINE USER LANGUAGE
//-----------------------
if ($language=="auto") {$lang=OC_Preferences::getValue(OC_User::getUser(),'core','lang',OC_L10N::findLanguage());}else{$lang=$language;}
if (file_exists($appPath."/l10n/".$lang.".js")) {$langfile=$lang.".js";}else{$langfile="en_GB.js";}
echo '<script src="'.OCP\Util::linkTo($appPath.'/l10n',$langfile).'"></script>';
echo '<script type="text/javascript">';
echo '</script>';

//QUICK START
//-----------
if (empty($_GET)) {
 if (!\OC\Files\Filesystem::file_exists($quickStartFolder)){\OC\Files\Filesystem::mkdir($quickStartFolder);}
 echo '<script type="text/javascript">';
 echo 'var now=new Date(),h=now.getHours(),m=now.getMinutes(),s=now.getSeconds();';
 echo 'if(m<10) m="0"+m;if(s<10) s="0"+s;';
 echo 'var filename="'.$quickStartFilename.' - " + h + lang[\'hoursymbol\'] + m + "." + s + ".json";';
 echo 'window.location.href = "?filename=" + filename + "&dir='.$quickStartFolder.'";';
 echo '</script>';
}




//LOAD MINDMAP
//------------
if (isset($_GET['filename']) && !empty($_GET['filename'])) {

 //Get vars
 if (isset($_GET['dir']) && !empty($_GET['dir'])) {$dir=$_GET['dir'];}else{$dir='/';}
 $filename=$_GET['filename'];
 $filepath=$dir."/".$filename;

 //Check if file is readonly or can be created
 $readonly=readonly($dir,$filename);

 //Create new file
 if (!\OC\Files\Filesystem::file_exists($filepath)==1){
  if ($readonly==0) {\OC\Files\Filesystem::touch($filepath);}
 }

 //Load stylesheets
 echo '<link rel="stylesheet" href="'.OCP\Util::linkTo($appPath.'/css','common.css').'">';
 echo '<link rel="stylesheet" href="'.OCP\Util::linkTo($appPath.'/css','app.css').'">';
 echo '<link rel="stylesheet" href="'.OCP\Util::linkTo($appPath.'/css/Aristo','jquery-ui-1.8.7.custom.css').'">';
 echo '<link rel="stylesheet" href="'.OCP\Util::linkTo($appPath.'/css/minicolors','jquery.miniColors.css').'">';

 //Navigator and Style window placeholder
 echo '<script id="template-float-panel" type="text/x-jquery-tmpl">';
 echo '<div class="ui-widget ui-dialog ui-corner-all ui-widget-content float-panel no-select">';
 echo '<div class="ui-dialog-titlebar ui-widget-header ui-helper-clearfix">';
 echo '<span class="ui-dialog-title">${title}</span>';
 echo '<a class="ui-dialog-titlebar-close ui-corner-all" href="#" role="button"><span class="ui-icon"></span></a>';
 echo '</div>';
 echo '<div class="ui-dialog-content ui-widget-content"></div>';
 echo '</div>';
 echo '</script>';

 //Navigator section
 echo '<script id="template-navigator" type="text/x-jquery-tmpl">';
 echo '<div id="navigator">';
 echo '<div class="active">';
 echo '<div id="navi-content">';
 echo '<div id="navi-canvas-wrapper">';
 echo '<canvas id="navi-canvas"></canvas>';
 echo '<div id="navi-canvas-overlay"></div>';
 echo '</div>';
 echo '<div id="navi-controls">';
 echo '<span id="navi-zoom-level"></span>';
 echo '<div class="button-zoom" id="button-navi-zoom-out"></div>';
 echo '<div id="navi-slider"></div>';
 echo '<div class="button-zoom" id="button-navi-zoom-in"></div>';
 echo '</div>';
 echo '</div>';
 echo '</div>';
 echo '<div class="inactive"></div>';
 echo '</div>';
 echo '</script>';

 //Style Section
 echo '<script id="template-inspector" type="text/x-jquery-tmpl">';
 echo '<div id="inspector">';
 echo '<div id="inspector-content">';
 echo '<table id="inspector-table">';
 echo '<tr><td><div id="windowstylefontsize"></div></td>'; //font size
 echo '<td><div class="buttonset buttons-very-small buttons-less-padding">';
 echo '<button id="inspector-button-font-size-decrease">A-</button>';
 echo '<button id="inspector-button-font-size-increase">A+</button>';
 echo '</div></td></tr>';
 echo '<tr><td><div id="windowstylefontstyle"></div></td>'; //font style
 echo '<td><div class="font-styles buttonset buttons-very-small buttons-less-padding">';
 echo '<input type="checkbox" id="inspector-checkbox-font-bold"/>';
 echo '<label for="inspector-checkbox-font-bold" id="inspector-label-font-bold">B</label>';
              
 echo '<input type="checkbox" id="inspector-checkbox-font-italic"/>'; 
 echo '<label for="inspector-checkbox-font-italic" id="inspector-label-font-italic">I</label>';
            
 echo '<input type="checkbox" id="inspector-checkbox-font-underline"/>';
 echo '<label for="inspector-checkbox-font-underline" id="inspector-label-font-underline">U</label>';
            
 echo '<input type="checkbox" id="inspector-checkbox-font-linethrough"/>';
 echo '<label for="inspector-checkbox-font-linethrough" id="inspector-label-font-linethrough">S</label>';
 echo '</div></td></tr>';
 echo '<tr><td><div id="windowstylefontcolor"></div></td>'; //font color
 echo '<td><input type="hidden" id="inspector-font-color-picker" class="colorpicker"/></td></tr>';
 echo '<tr><td><div id="windowstylebranchcolor"></div></td>'; //branch color
 echo '<td><input type="hidden" id="inspector-branch-color-picker" class="colorpicker"/>';
 echo '<input id="inspector-button-branch-color-children" type="button">'; //inherit button
 echo '</td></tr>';
 echo '</table>';
 echo '</div>';
 echo '</div>';
 echo '</script>';

 echo '</head>';
 echo '<body>';

 //Printing Section
 echo '<div id="print-area">';
 echo '<p class="print-placeholder"><div id="printoption"></div></p>'; //Please use the print option from the mindmap menu
 echo '</div>';

 echo '<div id="container">';

 //Toolbar Section
 echo '<div id="topbar">';
 echo '<div id="toolbar">';
 echo '<div class="buttons buttons-small">';
 echo '<span class="buttons-left"> </span><span class="buttons-right"></span>';
 echo '</div>';
 echo '</div>'; //toolbar
 echo '</div>'; //topbar

 //Working Area Section
 echo '<div id="canvas-container">';
 echo '<div id="drawing-area" class="no-select"></div>';
 echo '</div>'; //canvas-container


 //Statusbar Section
 echo '<div id="bottombar">';
 echo '<div id="statusbar">';
 echo '<p id="mindmapfile"></p>'; //show filename
 echo '<div class="buttons buttons-right buttons-small buttons-less-padding"></div>';
 echo '</div>'; //statusbar
 echo '</div>'; //bottombar

 echo '</div>'; //container


 //Load js files
 echo '<script src="'.OCP\Util::linkTo($appPath.'/js/libs','jquery-1.6.1.min.js').'"></script>';
 echo '<script src="'.OCP\Util::linkTo($appPath.'/js/libs','jquery-ui-1.8.11.custom.min.js').'"></script>';
 echo '<script src="'.OCP\Util::linkTo($appPath.'/js/libs','dragscrollable.js').'"></script>';
 echo '<script src="'.OCP\Util::linkTo($appPath.'/js/libs','jquery.hotkeys.js').'"></script>';
 echo '<script src="'.OCP\Util::linkTo($appPath.'/js/libs','jquery.mousewheel.js').'"></script>';
 echo '<script src="'.OCP\Util::linkTo($appPath.'/js/libs','jquery.minicolors.js').'"></script>';
 echo '<script src="'.OCP\Util::linkTo($appPath.'/js/libs','jquery.tmpl.js').'"></script>';
 echo '<script src="'.OCP\Util::linkTo($appPath.'/js/libs','events.js').'"></script>';
 echo '<script src="'.OCP\Util::linkTo($appPath.'/js','MindMaps.js').'"></script>';
 echo '<script src="'.OCP\Util::linkTo($appPath.'/js','Command.js').'"></script>';
 echo '<script src="'.OCP\Util::linkTo($appPath.'/js','CommandRegistry.js').'"></script>';
 echo '<script src="'.OCP\Util::linkTo($appPath.'/js','Action.js').'"></script>';
 echo '<script src="'.OCP\Util::linkTo($appPath.'/js','Util.js').'"></script>';
 echo '<script src="'.OCP\Util::linkTo($appPath.'/js','Point.js').'"></script>';
 echo '<script src="'.OCP\Util::linkTo($appPath.'/js','Document.js').'"></script>';
 echo '<script src="'.OCP\Util::linkTo($appPath.'/js','MindMap.js').'"></script>';
 echo '<script src="'.OCP\Util::linkTo($appPath.'/js','Node.js').'"></script>';
 echo '<script src="'.OCP\Util::linkTo($appPath.'/js','NodeMap.js').'"></script>';
 echo '<script src="'.OCP\Util::linkTo($appPath.'/js','UndoManager.js').'"></script>';
 echo '<script src="'.OCP\Util::linkTo($appPath.'/js','UndoController.js').'"></script>';
 echo '<script src="'.OCP\Util::linkTo($appPath.'/js','ClipboardController.js').'"></script>';
 echo '<script src="'.OCP\Util::linkTo($appPath.'/js','ZoomController.js').'"></script>';
 echo '<script src="'.OCP\Util::linkTo($appPath.'/js','ShortcutController.js').'"></script>';
 echo '<script src="'.OCP\Util::linkTo($appPath.'/js','FloatPanel.js').'"></script>';
 echo '<script src="'.OCP\Util::linkTo($appPath.'/js','Navigator.js').'"></script>';
 echo '<script src="'.OCP\Util::linkTo($appPath.'/js','Inspector.js').'"></script>';
 echo '<script src="'.OCP\Util::linkTo($appPath.'/js','ToolBar.js').'"></script>';
 echo '<script src="'.OCP\Util::linkTo($appPath.'/js','StatusBar.js').'"></script>';
 echo '<script src="'.OCP\Util::linkTo($appPath.'/js','CanvasDrawingTools.js').'"></script>';
 echo '<script src="'.OCP\Util::linkTo($appPath.'/js','CanvasView.js').'"></script>';
 echo '<script src="'.OCP\Util::linkTo($appPath.'/js','CanvasPresenter.js').'"></script>';
 echo '<script src="'.OCP\Util::linkTo($appPath.'/js','ApplicationController.js').'"></script>';
 echo '<script src="'.OCP\Util::linkTo($appPath.'/js','MindMapModel.js').'"></script>';
 echo '<script src="'.OCP\Util::linkTo($appPath.'/js','MainViewController.js').'"></script>';
 echo '<script src="'.OCP\Util::linkTo($appPath.'/js','Event.js').'"></script>';
 echo '<script src="'.OCP\Util::linkTo($appPath.'/js','StaticCanvas.js').'"></script>';
 echo '<script src="'.OCP\Util::linkTo($appPath.'/js','PrintController.js').'"></script>';
 echo '<script src="'.OCP\Util::linkTo($appPath.'/js','SaveAs.js').'"></script>';
 echo '<script src="'.OCP\Util::linkTo($appPath.'/js/libs','notify-combined.min.js').'"></script>';

 //Open mindmap file on launching
 echo '<script type="text/javascript">';
 echo 'mindmaps.NewDocumentView = function() {';
 if ($readonly==0) {
  echo '$(\'#mindmapfile\').html(\''.$filename.'\');';
 }
 else
 {
  echo '$(\'#mindmapfile\').html(\''.$filename.'\' + \' <font color="red">(\' + lang[\'readonly\'].toUpperCase() + \')</font>\');';
 }
 echo ' $(\'#windowstylefontsize\').html(lang[\'windowstylefontsize\']);';
 echo ' $(\'#windowstylefontstyle\').html(lang[\'windowstylefontstyle\']);';
 echo ' $(\'#windowstylefontcolor\').html(lang[\'windowstylefontcolor\']);';
 echo ' $(\'#windowstylebranchcolor\').html(lang[\'windowstylebranchcolor\']);';
 echo ' $(\'#windowstyleinherit\').html(lang[\'windowstyleinherit\']);';
 echo ' $(\'#printoption\').html(lang[\'printoption\']);';
 echo ' $(\'#inspector-button-branch-color-children\').attr(\'value\', lang[\'windowstyleinherit\']);';
 echo ' $(\'#inspector-button-branch-color-children\').attr(\'title\', lang[\'windowstyleinheritD\']);';
 echo '};';

 echo 'mindmaps.NewDocumentPresenter = function(eventBus, mindmapModel, view) {';
 echo ' $.ajax(';
 echo ' {';
 echo '    url:\'/index.php/'.$appPath.'/\',';
 echo '    type:\'post\',';
 echo '    data:({action:\'readjson\',filename:\''.$filename.'\',dir:\''.$dir.'\'}),';
 echo '    dataType:\'json\',';
 echo '    success:function(data)';
 echo '    {';
 echo '     dir=\''.$dir.'\';';
 echo '     filename=\''.$filename.'\';';
 echo '     readonly=\''.$readonly.'\';';
 echo '     doc=new mindmaps.Document();';
 echo '     mindmapModel.setDocument(doc);';
 echo '     doc=mindmaps.Document.fromObject(data);';
 echo '     mindmapModel.setDocument(doc);';
 echo '    },';
 echo '    error:function(data)';
 echo '    {';
 echo '     dir=\''.$dir.'\';';
 echo '     filename=\''.$filename.'\';';
 echo '     readonly=\''.$readonly.'\';';
 echo '     doc=new mindmaps.Document();';
 echo '     mindmapModel.setDocument(doc);';
 echo '    }';
 echo '});';
 echo '};';
 echo '</script>';


 echo '</html>';
}
?>
