<?php
/**
 * Test bootstrap
 * Load Magento instance and run tests
 *
 * @author Aydus <davidt@aydus.com>
 */
ob_start();

//get the magento path, not the symlink path
$pwd = getenv("PWD");
if (!$pwd){
    $pwd = getcwd();
}

//split current path on app 
//(since current directory is most likely a modman symlink)
preg_match('/^(.+\/app)(.+)$/', $pwd, $matches);

if(is_array($matches) && count($matches)>0){
    
    //change dir to absolute path of magento install
    chdir($matches[1]);
    //now can require Mage.php
    require_once('Mage.php');
    //set mask(?)
    umask(0);
    //run the app
    Mage::app();
    
} else {
    
    die('Could not load Magento instance');
}
