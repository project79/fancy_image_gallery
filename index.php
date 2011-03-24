<?php

/**
 * Fancy Image Gallery for Wolf CMS- Create gallery and display images with few clicks
 * Gallery is free for non-profit usage. For commercial usage, please contact one of the authors.
 * @package wolf
 * @subpackage plugin.fancy_image_gallery
 *
 * @author Sanja Andjelkovic <sanja@medio.com.hr>
 * @author Dejan Andjelkovic <dejan@medio.com.hr>
 * @version 0.8.4
 * @for Wolf version 0.6.0 and above
 * @license http://www.gnu.org/licenses/gpl.html GPL License
 * @copyright medio.com.hr, 2009-2010
 */

 //security measure
 if (!defined('IN_CMS')) { exit(); }

Plugin::setInfos(array(
    'id'          => 'fancy_image_gallery',
    'title'       => 'Fancy Image Gallery',
    'description' => __('Provides easy to use image gallery with fancybox effect.'),
    'version'     => '0.8.5',
    'license'     => 'GPL',
    'author'      => 'Sanja Andjelkovic',
    'website'     => 'http://project79.net/',
    'update_url'  => 'http://www.project79.net/plugin-versions.xml',
    'require_wolf_version' => '0.7.3'
));

Plugin::addController('fancy_image_gallery', 'Fancy Image Gallery', 'admin_view');

// funkcija poziva css i fancybox iz foldera /js i /resources
function fancy_resources(){
	//putanje
	$jspath = str_replace ('?', '',BASE_URL).'wolf/plugins/fancy_image_gallery/js/';
	$csspath = str_replace ('?', '',BASE_URL).'wolf/plugins/fancy_image_gallery/resources/';
		
	// loadaj fancybox i pripadajuce css fajlove
	echo '<script type="text/javascript" src="',$jspath,'jquery.fancybox-1.3.0.pack.js"></script>',"\n";
	echo '<link href="',$csspath,'jquery.fancybox-1.3.0.css" rel="stylesheet" type="text/css">',"\n";
}


// funkcija koja izbacuje samo jednu sliku iz direktorija i pravi link na odabranu galeriju
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

                if($files)
		{

                    echo '<a class="link" rel="show-me-all" href="',BASE_URL . $child,'" title="',str_replace('/','',$path),'"><img src="',$fullpath,$images,'" width="125" height="100" /></a>',"\n";

                    
                }
		else
		{
			echo __('There are no images in this gallery.');
		}
}

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
				echo '<a class="photo" rel="my-gallery" href="',$fullpath,str_replace('-thumb','',$file),'" title="',str_replace('-thumb','',$file),'"><img src="',$fullpath,$file,'"/></a>';
			}
                   
                 }
        }
		else
		{
			echo __('There are no images in this gallery.');
		}
}
?>
