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


//check if have wordpress dir 
if(is_dir( $installDir.'/wordpress/'))
{
    //remove wordpress dir
    @rmdir($installDir.'/wordpress');
}


if(!@copy($wpDownloadLink,$tmpZipFile))
{
    $errors= error_get_last();
    echo "COPY ERROR: ".$errors['type'];
    echo "<br />\n".$errors['message'];
} else {
    $zip = new ZipArchive;
    if ($zip->open('tmpWP.zip') === TRUE) {
        $zip->extractTo($installDir);
        $zip->close();

        //Remove temporary zip file
        @unlink($tmpZipFile);



        // Identify directories
        $source = $installDir.'/wordpress/';
        // Get array of all source files
        $files = scandir($source);
        
        $destination = $installDir.'/';
        // Cycle through all source files
        foreach ($files as $file) {
          if (in_array($file, array(".",".."))) continue;
          
          if (!rename($source.$file, $destination.$file)) {
            die("error in rename..!");
          }
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
        echo "ZipArchive ERROR..";
    }
}  