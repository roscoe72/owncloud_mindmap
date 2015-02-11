MindMap is a Mindmap application for ownCloud 7x. 
The application is based on the orginal mindmaps application created by David Richard (AGPL License).

Included in this package.

Mindmaps - APGL
https://github.com/drichard/mindmaps

Notify.js - Free for usage
https://github.com/alexgibson/notify.js


Install:
- Download and extract the MindMap files in folder '<%owncloud_webroot%>/apps/mindmap/'.
- Enable the ownCloud app in the ownCloud web admin section
- Disable CSP in owncloud config.php (read Known Issue nr1. See below)
- Create Text file with extension .json. 


Known Issue:
1. When opening Mindmap in Firefox, Chrome, eg a blank page with no Mindmap application. This is due to the webbrowser Content-Security-Policy (CSP). CSP is enabled in the lateste webbrowsers.
There are two solutions to this issue:
Option1. In firefox you can disable CSP. Open in address bar 'about:config' and search for 'security.csp.enable' and turn it off.
Option2. Global disable CSP on the owncloud webserver. This can be disabled by adding the custom_csp_policy in the owncloud config.php.

Place these lines in the $CONFIG Array section in the http://[YOURSERVER]/config/config.php file
$CONFIG = array (...
...
/* Custom CSP policy, changing this will overwrite the standard policy */
'custom_csp_policy' => "default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval'; style-src 'self' 'unsafe-inline'; frame-src *; img-src *; font-src 'self' data:; media-src *",
...
);

Check the lastest version on: http://www.toolstogether.nl

Cheers Erwin

