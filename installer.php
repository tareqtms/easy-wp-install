<?php
/**
 * Easy Wordpress Installer.
 *
 * Just place this file to the directory where you want to install wordpress
 * and then browse the file (./installer.php) using any browser.
 * The files will be downloaded and you will be moved to the setup page
 *
 * @author Tareq Mahmood <tareqtms@yahoo.com>
 * Created at: 10/10/16 11:51 AM UTC+06:00
 */

/**
 * @var string WP Download URL
 */
$wpDownloadLink = "https://wordpress.org/latest.zip";

/**
 * @var string Installation directory (current directory)
 */
$installDir = dirname(__FILE__);

/**
 * @var string Temp zip file name
 */
$tmpZipFile = $installDir . "/tmpWP.zip";

if (is_callable('exec') && false === stripos(ini_get('disable_functions'), 'exec')) {
    //Download Zip File
    $command = "curl $wpDownloadLink -o $tmpZipFile";
    exec($command, $result, $return);

    if ($return !== 0) {
        echo "Sorry, the Download failed! Pls check if you have curl enabled!";
        exit;
    }

    //Extract Zip File
    $command = 'unzip '.$tmpZipFile.' -d '.$installDir;
    exec($command, $result, $return);

    if ($return !== 0) {
        echo "Sorry, couldn't unzip the downloaded file!";
        exit;
    }

    //Remove temporary zip file
    @unlink($tmpZipFile);

    //Move wordpress files to root directory
    $command = 'mv -f '.$installDir.'/wordpress/* '.$installDir;
    exec($command, $result, $return);

    if ($return !== 0) {
        echo "Sorry, couldn't move the wordpress files to the root directory!";
        exit;
    }
    //Remove the directory named "wordpress"
    @rmdir($installDir.'/wordpress');

    //Remove this file
    @unlink(__FILE__);
    ?>
    <script type="text/javascript">
        //Redirect to wp setup page
        document.location.href = './wp-admin/setup-config.php';
    </script>
    <?php
} else {
    echo "Sorry! This file depends on exec() function. Pls check if that function is disabled!";
}