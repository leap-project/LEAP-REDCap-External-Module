<?php
require_once APP_PATH_DOCROOT . 'ControlCenter/header.php';
$settings = $module->getSystemSetting('leap_auth');
?>

<h4><img src="<?php echo APP_PATH_IMAGES; ?>application_go.png"> LEAP External Module</h4>

<?php if ($settings): ?>
    <p>Your LEAP authentication key is:</p>
    <h3><?php echo $settings ?></h3>
    <a href="https://github.com/leap-project/leap/wiki/REDCap-External-Module-APIs" target="_blank">Click here to latest API documentation.</a>
<?php else: ?>
    <p>Generate a key by going to the External Module Config in the control center. For more information, visit:</p>
    <a href="https://github.com/leap-project/leap/wiki/Setup-a-REDCap-Connection" target="_blank">Setup a REDCap Connection to LEAP</a>
<?php endif; ?>

<?php require_once APP_PATH_DOCROOT . 'ControlCenter/footer.php'; ?>
