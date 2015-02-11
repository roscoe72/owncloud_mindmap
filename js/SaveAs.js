/**
 * @constructor
 * @param {mindmaps.EventBus} eventBus
 * @param {mindmaps.CommandRegistry} commandRegistry
 * @param {mindmaps.MindMapModel} mindmapModel
 */

//save as JSON
mindmaps.SaveAsJSONController = function(eventBus, commandRegistry, mindmapModel) {
  var saveAsJSONCommand = commandRegistry.get(mindmaps.SaveAsJSONCommand);
  saveAsJSONCommand.setHandler(doSaveAsJSONDocument);

  function doSaveAsJSONDocument() {
    var doc = mindmapModel.getDocument();
    var data = doc.prepareSave().serialize();
    $.ajax({
     global: false,
     type: "POST",
     cache: false,
     dataType: "json",
     data: ({
        action: 'savejson',
        filename: filename,
        dir: dir,
        data: data
     }),
     url: '/index.php/apps/mindmap/',
     success: function (msg) {
      $("#statusbar").notify(lang['saveasjson'],"success");
     },
     error: function (msg) {
      $("#statusbar").notify(lang['saveasjsonError'] + " '" + filename + "'","error");
     }
    });

  }

  eventBus.subscribe(mindmaps.Event.DOCUMENT_CLOSED, function() {
    saveAsJSONCommand.setEnabled(false);
  });

  eventBus.subscribe(mindmaps.Event.DOCUMENT_OPENED, function() {
    if (readonly==0) saveAsJSONCommand.setEnabled(true);
  });
};

//save as PNG
mindmaps.SaveAsPNGController = function(eventBus, commandRegistry, mindmapModel) {
  var saveAsPNGCommand = commandRegistry.get(mindmaps.SaveAsPNGCommand);
  saveAsPNGCommand.setHandler(doSaveAsPNGDocument);

  var renderer = new mindmaps.StaticCanvasRenderer();

  function doSaveAsPNGDocument() {
    var $img = renderer.renderAsPNG(mindmapModel.getDocument());
    var image=$img[0].src; //save as base64 image
    var imagefilename=filename.slice(0,-5)+".png";
    $.ajax({
     global: false,
     type: "POST",
     cache: false,
     dataType: "json",
     data: ({
        action: 'savepng',
        filename: imagefilename,
        dir: dir,
        data: image.replace(/^data:image\/(png|jpg);base64,/, "")
     }),
     url: '/index.php/apps/mindmap/',
     success: function (msg) {
      $("#statusbar").notify(lang['saveaspng'] + " '" + imagefilename + "'","success");
     },
     error: function (msg) {
      $("#statusbar").notify(lang['saveaspngError'] + " '" + imagefilename + "'","error");
     }
    });

  }

  eventBus.subscribe(mindmaps.Event.DOCUMENT_CLOSED, function() {
    saveAsPNGCommand.setEnabled(false);
  });

  eventBus.subscribe(mindmaps.Event.DOCUMENT_OPENED, function() {
     if (readonly==0) saveAsPNGCommand.setEnabled(true);
  });
};
