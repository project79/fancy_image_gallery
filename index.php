<?php

/**
 * Fancy Image Gallery for Wolf CMS- Create gallery and display images with few clicks
 * Gallery is free for non-profit and commercial usage.
 * @package wolf
 * @subpackage plugin.fancy_image_gallery
 *
 * @author Sanja Andjelkovic <sanja@medio.com.hr>
 * @author Dejan Andjelkovic <dejan@medio.com.hr>
 * @version 0.9.3
 * @for Wolf version 0.7.0 and above
 * @license http://www.gnu.org/licenses/gpl.html GPL License
 * @copyright medio.com.hr & project79.net, 2009-2012
 */

 //security measure
 if (!defined('IN_CMS')) { exit(); }

Plugin::setInfos(array(
    'id'          => 'fancy_image_gallery',
    'title'       => 'Fancy Image Gallery',
    'description' => __('Provides easy to use image gallery with fancybox effect.'),
    'version'     => '0.9.4',
    'license'     => 'GPL',
    'author'      => 'Sanja Andjelkovic',
    'website'     => 'http://project79.net/projects/fancy-image-gallery',
    'update_url'  => 'http://www.project79.net/plugin-versions.xml',
    'require_wolf_version' => '0.7.3'
));

Plugin::addController('fancy_image_gallery', 'Fancy Image Gallery', 'admin_view');

// funkcija poziva css i fancybox iz foldera /js i /resources
/*
*   Call to action (bteween <head></head> under jQuery library): <?php fancy_resources(); ?>
*/
function fancy_resources(){
	//putanje
	$jspath = str_replace ('?', '',BASE_URL).'wolf/plugins/fancy_image_gallery/js/';
	$csspath = str_replace ('?', '',BASE_URL).'wolf/plugins/fancy_image_gallery/resources/';
		
	// loadaj fancybox i pripadajuce css fajlove
	echo '<script type="text/javascript" src="',$jspath,'fancybox.js"></script>',"\n";
	echo '<link href="',$csspath,'fancybox.css" rel="stylesheet" type="text/css">',"\n";
}


// funkcija koja izbacuje samo jednu sliku iz direktorija i pravi link na odabranu galeriju - 0.9.0
/*
*   Call to action: <?php fancy_list(); ?>
*/
function fancy_list(){

    $main_dir = CMS_ROOT . '/public/images/';
		
	$open_main = opendir ($main_dir);
		
	if ($open_main) {
            while (false !== ($folder = readdir($open_main)))
            {
                if ($folder != '.' && $folder != '..')
                {
                    $folders[] = $folder;	
                }
            }
            closedir($open_main);
        }
		
	// drugi korak - propusti kroz petlju i ispisi putanje
	$number = count($folders);
        $counter=1;


        if(count($folders)){
            while ($counter<=$number) {
                foreach($folders as $folder) {
                    $counter++;	

                    $image_dir = CMS_ROOT . '/public/images/' . $folder . '/';


                    $handle = opendir($image_dir);
                    $files = array();
                        if ($handle) {
                            while (false !== ($file = readdir($handle)))
                            {
                                if ($file != '.' && $file != '..')
                                {
                                    if(strstr($file,'-thumb'))
                                    {
                                        $files = $file;

                                    }
                                }
                            }

                            closedir($handle);						
                        }

                    $images = $files;
                    /* 
                     * $gallername serves as your main gallery page
                     * In order it to work, you have to replace current 'gallery' with the
                     * slug of your main gallery page e.g. 'my-gallery'
                     */
                    $galleryname = 'gallery';
                    $path = str_replace(dirname($folder), '', $folder);
                    $fullpath = str_replace ('?', '',BASE_URL) . 'public/images/' . $path . '/';

                    if($files) {

                       echo '<a class="link" rel="show-me-all" href="',BASE_URL . $galleryname . '/' . $path . '/','" title="',str_replace('/','',$path),'"><img src="',$fullpath, $images,'" alt="',str_replace('/','',$path),'" /></a>',"\n";

                    }
                    else {
                        echo __('There are no images in this gallery.');
                    }
                }
        }
    }
}

// funkcija koja izbacuje samo jednu sliku iz direktorija i pravi link na odabranu galeriju
/*
*   Call to action: <?php fancy_parent('my-first.gallery/', '/gallery/my-first-gallery'); ?>
*/
function fancy_parent($path, $child){

        $fullpath = str_replace ('?', '',BASE_URL) . 'public/images/' . $path;

        $image_dir = CMS_ROOT . '/public/images/' . $path;
                
        $handle = opendir($image_dir);


        if ($handle) {
            while (false !== ($file = readdir($handle)))
            {
                if ($file != '.' && $file != '..')
                {
                    if(strstr($file,'-thumb'))
                    {
                        $files[] = $file;
                    }
                }
            }
            closedir($handle);
        }

        // propusti kroz petlju i ispisi linkove, te ih vezi za galeriju
        // za title ispisi samo krajnji direktorij u kojem se nalaze slike

        $images = $files[0];
        $path = str_replace(dirname($path), '', $path);

        if($files) {

            echo '<a class="link" rel="show-me-all" href="',BASE_URL . $child,'" title="',str_replace('/','',$path),'"><img src="',$fullpath,$images,'" alt="',str_replace('/','',$path),'" /></a>',"\n";

            }
        else {
            echo __('There are no images in this gallery.');
        }
}

/*
*   Call to action: <?php fancy('my-first.gallery/'); ?>
*/


function fancy($path){
         
        $fullpath = str_replace ('?', '',BASE_URL) . 'public/images/' . $path;

        $image_dir = CMS_ROOT . '/public/images/' . $path;
	        
        $handle = opendir($image_dir);


        // prvi korak:  citaj direktorij i napravi polje
        if ($handle) {
            while (false !== ($file = readdir($handle)))
            {
                if ($file != '.' && $file != '..') 
                {
                    if(strstr($file,'-thumb'))
                    {
                            $files[] = $file;
                    }
                }
            }
            closedir($handle);
        }

        // drugi korak - propusti kroz petlju i ispisi slike
        $images = count($files);
        $counter=1;


        if(count($files))
        {
            natsort($files); //sortiranje; dodano u 0.8.1
            while ($counter<=$images)
            {
                foreach($files as $file)
                {
                    $counter++;
                    $str=str_replace('_',' ',$file); // ciscenje naslova; 0.8.6
                    echo '<a class="photo" rel="my-gallery" href="',$fullpath,str_replace('-thumb','',$file),'" title="',preg_replace('/\-thumb..*$/i', '',$str),'"><img src="',$fullpath,$file,'" alt="',preg_replace('/\-thumb..*$/i', '',$str),'" /></a>';
                }

            }
        }
        else
        {
            echo __('There are no images in this gallery.');
        }
}

/*
*   Call to action: <?php fancy_slider($path, $page, 400, 200, $title); ?>
*/

function fancy_slider($path, $child, $width=false, $height=false, $title=false){

        $fullpath = str_replace ('?', '',BASE_URL) . 'public/images/' . $path;

        $image_dir = CMS_ROOT . '/public/images/' . $path;
                
        $handle = opendir($image_dir);

                
        if ($handle) {
            while (false !== ($file = readdir($handle)))
            {
                if ($file != '.' && $file != '..')
                {
                           
                        $files[] = $file;
                                      
                }
            }
            closedir($handle);
        }

        // propusti kroz petlju i ispisi linkove, te ih vezi za galeriju
        // za title ispisi samo krajnji direktorij u kojem se nalaze slike
        
        sort($files);
               
        $images = $files[1];
        $path = str_replace(dirname($path), '', $path);

        if($files)
        {

                    echo '<a href="',BASE_URL . $child,'" title="',str_replace('/','',$path),'"><img src="',$fullpath,$images,'" width="',$width,'" height="',$height,'" title="',$title,'" alt="',$title,'" /></a>',"\n";

                    
                }
        else
        {
            echo __('There are no images in this gallery.');
        }
}
