<?php
OCP\Util::addScript('mindmap', 'mindmap');

OCP\App::addNavigationEntry( array(
'id' => 'mindmap',
'order' => 50,
'href' => '/index.php/apps/files/?dir=%2FMindmap',
'icon' => OCP\Util::imagePath( 'mindmap', 'mindmap.svg' ),
'name' => 'Mindmap Files'
));

OCP\App::addNavigationEntry( array(
'id' => 'mindmap',
'order' => 50,
'href' => '/index.php/apps/mindmap',
'icon' => OCP\Util::imagePath( 'mindmap', 'mindmap.svg' ),
'name' => 'New Mindmap'
));
