/**
 * Creates a new command. Base class for all commands
 * 
 * @constructor
 * @borrows EventEmitter
 */
mindmaps.Command = function() {
  this.id = "BASE_COMMAND";
  this.shortcut = null;
  /**
   * The handler function.
   * 
   * @private
   * @function
   */
  this.handler = null;
  this.label = null;
  this.description = null;

  /**
   * @private
   */
  this.enabled = false;
};

/**
 * Events that can be emitted by a command object.
 * @namespace
 */
mindmaps.Command.Event = {
  HANDLER_REGISTERED : "HandlerRegisteredCommandEvent",
  HANDLER_REMOVED : "HandlerRemovedCommandEvent",
  ENABLED_CHANGED : "EnabledChangedCommandEvent"
};

mindmaps.Command.prototype = {
  /**
   * Executes the command. Tries to call the handler function.
   */
  execute : function() {
    if (this.handler) {
      this.handler();
      if (mindmaps.DEBUG) {
        console.log("handler called for", this.id);
      }
    } else {
      if (mindmaps.DEBUG) {
        console.log("no handler found for", this.id);
      }
    }
  },

  /**
   * Registers a new handler.
   * 
   * @param {Function} handler
   */
  setHandler : function(handler) {
    this.removeHandler();
    this.handler = handler;
    this.publish(mindmaps.Command.Event.HANDLER_REGISTERED);
  },

  /**
   * Removes the current handler.
   */
  removeHandler : function() {
    this.handler = null;
    this.publish(mindmaps.Command.Event.HANDLER_REMOVED);
  },

  /**
   * Sets the enabled state of the command.
   * 
   * @param {Boolean} enabled
   */
  setEnabled : function(enabled) {
    this.enabled = enabled;
    this.publish(mindmaps.Command.Event.ENABLED_CHANGED, enabled);
  }
};
/**
 * Mixin EventEmitter into command objects.
 */
EventEmitter.mixin(mindmaps.Command);

/**
 * Node commands
 */

/**
 * Creates a new CreateNodeCommand.
 * 
 * @constructor
 * @augments mindmaps.Command
 */
mindmaps.CreateNodeCommand = function() {
  this.id = "CREATE_NODE_COMMAND";
  this.shortcut = "tab";
  this.label = lang['buttonadd']; //Add
  this.icon = "ui-icon-plusthick";
  this.description = lang['buttonaddD']; //Creates a new node
};
mindmaps.CreateNodeCommand.prototype = new mindmaps.Command();

/**
 * Creates a new CreateSiblingNodeCommand.
 * 
 * @constructor
 * @augments mindmaps.Command
 */
mindmaps.CreateSiblingNodeCommand = function() {
  this.id = "CREATE_SIBLING_NODE_COMMAND";
  this.shortcut = "shift+tab";
  this.label = lang['buttonadd']; //Add
  this.icon = "ui-icon-plusthick";
  this.description = lang['buttonaddD2']; //Creates a new sibling node
};
mindmaps.CreateSiblingNodeCommand.prototype = new mindmaps.Command();

/**
 * Creates a new DeleteNodeCommand.
 * 
 * @constructor
 * @augments mindmaps.Command
 */
mindmaps.DeleteNodeCommand = function() {
  this.id = "DELETE_NODE_COMMAND";
  this.shortcut = ["del", "backspace"];
  this.label = lang['buttondel']; //Delete
  this.icon = "ui-icon-minusthick";
  this.description = lang['buttondelD']; //Deletes a new node
};
mindmaps.DeleteNodeCommand.prototype = new mindmaps.Command();

/**
 * Creates a new EditNodeCaptionCommand.
 * 
 * @constructor
 * @augments mindmaps.Command
 */
mindmaps.EditNodeCaptionCommand = function() {
  this.id = "EDIT_NODE_CAPTION_COMMAND";
  this.shortcut = ["F2", "return"];
  this.label =  lang['editnode']; //Edit node caption 
  this.description = lang['editnodeD']; //Edits the node text
};
mindmaps.EditNodeCaptionCommand.prototype = new mindmaps.Command();

/**
 * Creates a new ToggleNodeFoldedCommand.
 * 
 * @constructor
 * @augments mindmaps.Command
 */
mindmaps.ToggleNodeFoldedCommand = function() {
  this.id = "TOGGLE_NODE_FOLDED_COMMAND";
  this.shortcut = "space";
  this.description = lang['showhideD']; //Show or hide the node's children
};
mindmaps.ToggleNodeFoldedCommand.prototype = new mindmaps.Command();

/**
 * Undo commands
 */

/**
 * Creates a new UndoCommand.
 * 
 * @constructor
 * @augments mindmaps.Command
 */
mindmaps.UndoCommand = function() {
  this.id = "UNDO_COMMAND";
  this.shortcut = ["ctrl+z", "meta+z"];
  this.label = lang['buttonundo']; //Undo
  this.icon = "ui-icon-arrowreturnthick-1-w";
  this.description = lang['buttonundoD']; //Undo
};
mindmaps.UndoCommand.prototype = new mindmaps.Command();

/**
 * Creates a new RedoCommand.
 * 
 * @constructor
 * @augments mindmaps.Command
 */
mindmaps.RedoCommand = function() {
  this.id = "REDO_COMMAND";
  this.shortcut = ["ctrl+y", "meta+shift+z"];
  this.label = lang['buttonredo']; //Redo
  this.icon = "ui-icon-arrowreturnthick-1-e";
  this.description = lang['buttonredoD']; //Redo
};
mindmaps.RedoCommand.prototype = new mindmaps.Command();

/**
 * Clipboard commands
 */

/**
 * Creates a new CopyNodeCommand.
 * 
 * @constructor
 * @augments mindmaps.Command
 */
mindmaps.CopyNodeCommand = function() {
  this.id = "COPY_COMMAND";
  this.shortcut = ["ctrl+c", "meta+c"];
  this.label = lang['buttoncopy']; //Copy
  this.icon = "ui-icon-copy";
  this.description = lang['buttoncopyD']; //Copy a branch
};
mindmaps.CopyNodeCommand.prototype = new mindmaps.Command();

/**
 * Creates a new CutNodeCommand.
 * 
 * @constructor
 * @augments mindmaps.Command
 */
mindmaps.CutNodeCommand = function() {
  this.id = "CUT_COMMAND";
  this.shortcut = ["ctrl+x", "meta+x"];
  this.label = lang['buttoncut']; //Cut
  this.icon = "ui-icon-scissors";
  this.description = lang['buttoncutD']; //Cut a branch
};
mindmaps.CutNodeCommand.prototype = new mindmaps.Command();

/**
 * Creates a new PasteNodeCommand.
 * 
 * @constructor
 * @augments mindmaps.Command
 */
mindmaps.PasteNodeCommand = function() {
  this.id = "PASTE_COMMAND";
  this.shortcut = ["ctrl+v", "meta+v"];
  this.label = lang['buttonpaste']; //Paste
  this.icon = "ui-icon-clipboard";
  this.description = lang['buttonpasteD']; //Paste a branch
};
mindmaps.PasteNodeCommand.prototype = new mindmaps.Command();

/**
 * Document commands
 */

/**
 * Creates a new SaveAsJSONCommand.
 * 
 * @constructor
 * @augments mindmaps.Command
 */
mindmaps.SaveAsJSONCommand = function() {
  this.id = "SAVE_DOCUMENT_COMMAND";
  this.label = lang['buttonsave']; //Save
  this.icon = "ui-icon-disk";
  this.description = lang['buttonsaveD']; //Save the mind map
};
mindmaps.SaveAsJSONCommand.prototype = new mindmaps.Command();

/**
 * Creates a new SavePNGDocumentCommand.
 * 
 * @constructor
 * @augments mindmaps.Command
 */
mindmaps.SaveAsPNGCommand = function() {
  this.id = "SAVE_DOCUMENT_COMMAND";
  this.label = lang['buttonsaveaspng']; //Save as PNG
  this.icon = "ui-icon-disk";
  this.description = lang['buttonsaveaspngD']; //Save the mind map as PNG Image
};
mindmaps.SaveAsPNGCommand.prototype = new mindmaps.Command();

/**
 * Creates a new CloseDocumentCommand.
 * 
 * @constructor
 * @augments mindmaps.Command
 */
mindmaps.CloseDocumentCommand = function() {
  this.id = "CLOSE_DOCUMENT_COMMAND";
  this.label = lang['buttonclose']; //Close
  this.icon = "ui-icon-close";
  this.description = lang['buttoncloseD']; //Close the mind map
};
mindmaps.CloseDocumentCommand.prototype = new mindmaps.Command();

/**
 * Creates a new PrintCommand.
 * 
 * @constructor
 * @augments mindmaps.Command
 */
mindmaps.PrintCommand = function() {
  this.id = "PRINT_COMMAND";
  this.icon = "ui-icon-print";
  this.label = lang['buttonprint']; //Print
  this.shortcut = ["ctrl+p", "meta+p"];
  this.description = lang['buttonprintD']; //Print the mind map
};
mindmaps.PrintCommand.prototype = new mindmaps.Command();

