$(document).ready(function() {

 if (typeof FileActions !== 'undefined') {
  OCA.Files.fileActions.register('application/json','Mindmap',OC.PERMISSION_READ,function() {return OC.imagePath('core','actions/play');}, function(filename) {startMindmap($('#dir').val(),filename);})
 }



 //New file menu item
 if($('div#new>ul').length > 0) {
  OCA.Files.fileActions.register('application/json', 'Mindmap', OC.PERMISSION_READ, '',
  function(filename) {
   if(FileActions.getCurrentMimeType() == 'application/json') {
   startMindmap($('#dir').val(),filename);
   }
  }
  );
  OCA.Files.fileActions.setDefault('application/json','Mindmap');

  $('<li class="icon-filetype-text svg" data-type="file" data-newname="Mindmap.json"><p>Mindmap file</p></li>')
  .appendTo('div#new>ul')
  $('#newEditDocLi>p').show();
 }

});


function startMindmap(dir,filename){
 //Start Editor
 $("#editor").hide();
 $('#content table').hide();
 $("#controls").hide();
 var editor = OC.linkTo('mindmap', 'index.php')+'?dir='+encodeURIComponent(dir)+'&filename='+encodeURIComponent(filename);
 $("#content").html('<iframe style="width:100%;height:99%;" src="'+editor+'" />');
}


