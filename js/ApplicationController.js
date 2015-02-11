/**
 * Creates a new Application Controller.
 * 
 * @constructor
 */
mindmaps.ApplicationController = function() {
  var eventBus = new mindmaps.EventBus();
  var shortcutController = new mindmaps.ShortcutController();
  var commandRegistry = new mindmaps.CommandRegistry(shortcutController);
  var undoController = new mindmaps.UndoController(eventBus, commandRegistry);
  var mindmapModel = new mindmaps.MindMapModel(eventBus, commandRegistry, undoController);
  var clipboardController = new mindmaps.ClipboardController(eventBus,commandRegistry, mindmapModel);
  var printController = new mindmaps.PrintController(eventBus,commandRegistry, mindmapModel);
  var saveAsPNGController = new mindmaps.SaveAsPNGController(eventBus,commandRegistry, mindmapModel);
  var saveAsJSONController = new mindmaps.SaveAsJSONController(eventBus,commandRegistry, mindmapModel);

  /**
   * Handles the new document command.
   */
  function doNewDocument() {
    // close old document first
    var doc = mindmapModel.getDocument();
    var presenter = new mindmaps.NewDocumentPresenter(eventBus,mindmapModel, new mindmaps.NewDocumentView());
    presenter.go();
  }

  /**
   * Handles the close document command.
   */
  function doCloseDocument() {
   if (parent.location.href==self.location.href) {
    window.location.href='/index.php/apps/files?dir='+dir;
   }
   else
   {
    parent.location.reload();
   }
  }



  /**
   * Initializes the controller, registers for all commands and subscribes to
   * event bus.
   */
  this.init = function() {

    var closeDocumentCommand = commandRegistry.get(mindmaps.CloseDocumentCommand);
    closeDocumentCommand.setHandler(doCloseDocument);

    eventBus.subscribe(mindmaps.Event.DOCUMENT_OPENED, function() {closeDocumentCommand.setEnabled(true);});
  };

  /**
   * Launches the main view controller.
   */
  this.go = function() {
    var viewController = new mindmaps.MainViewController(eventBus,mindmapModel, commandRegistry);
    viewController.go();

    doNewDocument();
  };

  this.init();
};
