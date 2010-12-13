<?php
//security measure
 if (!defined('IN_CMS')) { exit(); }
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

class FancyImageGalleryController extends PluginController {

	public static function _checkLog() {
        AuthUser::load();
        if ( ! AuthUser::isLoggedIn()) {
            redirect(get_url('login'));
        }
    }
	
    public function __construct() {
		self::_checkLog();
		
        $this->setLayout('backend');
        $this->assignToLayout('sidebar', new View('../../plugins/fancy_image_gallery/views/sidebar'));
    }

    function index($page = 0)
    {
        $this->display('fancy_image_gallery/views/settings');
    }

    public function documentation() {
        $this->display('fancy_image_gallery/views/documentation');
    }

    function settings() {

        $this->display('fancy_image_gallery/views/settings', Plugin::getAllSettings('fancy_image_gallery'));
    }

    public function create_thumb(){

            $path = $_POST['gallery_path'];
            
            if(substr($path,-1) != '/')
            {
                Flash::set('error', __('Put the trailing slash at the end of folder name!'));
                redirect(get_url('plugin/fancy_image_gallery/'));
            }

            $fullpath = str_replace ('?', '',BASE_URL) . 'public/images/' . $path;

            $image_dir = CMS_ROOT . '/public/images/' . $path;

            if(! is_dir($image_dir))
            {
                Flash::set('error', __('The folder does not exist!'));
                redirect(get_url('plugin/fancy_image_gallery/'));
            }
            
            $handle = opendir($image_dir);
            $count_images = 0;

            if ($handle)
            {
                while (false !== ($file = readdir($handle)))
		{
			if ($file != '.' && $file != '..')
			{
                           
                if(strstr($file,'.jpg') || strstr($file,'.JPG') || strstr($file,'.png') || strstr($file,'.PNG') || strstr($file,'.gif') || strstr($file,'.GIF'))
				{

                                        if(strtolower(substr($file,-10,-4)) == "-thumb")
                                        {
                                            unlink($image_dir . '/' . $file);
                                        }
                                        else{
                                        $files[] = $file;
                                        }
				
                                        $count_images++;
                                }
                           
			}
		}
		closedir($handle);
            }

            $counter = 0;
            $thumb_width = $_POST['thumb_width'];
            $thumb_height = $_POST['thumb_height'];

            if($thumb_width == NULL)
            {
                Flash::set('error', __('Specify thumbnail width!'));
                redirect(get_url('plugin/fancy_image_gallery/'));
            }

            if($thumb_height == NULL)
            {
                Flash::set('error', __('Specify thumbnail height!'));
                redirect(get_url('plugin/fancy_image_gallery/'));
            }

            if($count_images == 0)
            {
                Flash::set('error', __('There are no images in this folder!'));
                redirect(get_url('plugin/fancy_image_gallery/'));
            }

            else
            {
                if($files)
			{
                  while ($counter<=count($files)-1)
                  {
			foreach($files as $file)
			{
                        
                            $images = $files[$counter]; 
			
                     

                                if(substr($images,-3) == "jpg")
                                {
                                    $starting_image = imagecreatefromjpeg($image_dir . $images);
                                    $width = imagesx($starting_image);
                                    $height = imagesy($starting_image);


                                    $images = str_replace ('.jpg', '', $images);
                                    //$images = str_replace ('.JPG', '', $images);

                                    $thumb_image = imagecreatetruecolor($thumb_width, $thumb_height);
                                    imagecopyresampled($thumb_image, $starting_image, 0, 0, 0, 0, $thumb_width, $thumb_height, $width, $height);
                                    imagejpeg($thumb_image, $image_dir . $images . '-thumb.jpg');
                                
                                }
								elseif(substr($images,-3) == "JPG")
                                {
                                    $starting_image = imagecreatefromjpeg($image_dir . $images);
                                    $width = imagesx($starting_image);
                                    $height = imagesy($starting_image);

                                    $images = str_replace ('.JPG', '', $images);

                                    $thumb_image = imagecreatetruecolor($thumb_width, $thumb_height);
                                    imagecopyresampled($thumb_image, $starting_image, 0, 0, 0, 0, $thumb_width, $thumb_height, $width, $height);
                                    imagejpeg($thumb_image, $image_dir . $images . '-thumb.JPG');
                                
                                }

                                elseif(substr($images,-3) == "gif")
                                {
                                    $starting_image = imagecreatefromgif($image_dir . $images);
                                    $width = imagesx($starting_image);
                                    $height = imagesy($starting_image);

                                    $images = str_replace ('.gif', '', $images);

                                    $thumb_image = imagecreatetruecolor($thumb_width, $thumb_height);
                                    imagecopyresampled($thumb_image, $starting_image, 0, 0, 0, 0, $thumb_width, $thumb_height, $width, $height);
                                    imagegif($thumb_image, $image_dir . $images . '-thumb.gif');
                                }
								elseif(substr($images,-3) == "GIF")
                                {
                                    $starting_image = imagecreatefromgif($image_dir . $images);
                                    $width = imagesx($starting_image);
                                    $height = imagesy($starting_image);

                                    $images = str_replace ('.GIF', '', $images);

                                    $thumb_image = imagecreatetruecolor($thumb_width, $thumb_height);
                                    imagecopyresampled($thumb_image, $starting_image, 0, 0, 0, 0, $thumb_width, $thumb_height, $width, $height);
                                    imagejpeg($thumb_image, $image_dir . $images . '-thumb.GIF');
                                
                                }
				    			elseif(substr($images,-3) == "PNG")
                                {
                                    $starting_image = imagecreatefrompng($image_dir . $images);
                                    $width = imagesx($starting_image);
                                    $height = imagesy($starting_image);

                                    $images = str_replace ('.PNG', '', $images);

                                    $thumb_image = imagecreatetruecolor($thumb_width, $thumb_height);
                                    imagecopyresampled($thumb_image, $starting_image, 0, 0, 0, 0, $thumb_width, $thumb_height, $width, $height);
                                    imagegif($thumb_image, $image_dir . $images . '-thumb.PNG');
                                }

                                else
                                {
                                    $starting_image = imagecreatefrompng($image_dir . $images);
                                    $width = imagesx($starting_image);
                                    $height = imagesy($starting_image);

                                    $images = str_replace ('.png', '', $images);

                                    $thumb_image = imagecreatetruecolor($thumb_width, $thumb_height);
                                    imagecopyresampled($thumb_image, $starting_image, 0, 0, 0, 0, $thumb_width, $thumb_height, $width, $height);
                                    imagepng($thumb_image, $image_dir . $images . '-thumb.png');
                                }
                                $counter++;
                         }

                    }
                    Flash::set('success', __('Thumbnails are successfully created!'));
                    redirect(get_url('plugin/fancy_image_gallery/'));
                }
            }
	}
}
