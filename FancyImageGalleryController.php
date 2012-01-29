<?php
//security measure
 if (!defined('IN_CMS')) { exit(); }
/**
 * Fancy Image Gallery for Wolf CMS- Create gallery and display images with few clicks
 * Gallery is free for non-profit and commercial usage.
 * @package wolf
 * @subpackage plugin.fancy_image_gallery
 *
 * @author Sanja Andjelkovic <sanja@medio.com.hr>
 * @author Dejan Andjelkovic <dejan@medio.com.hr>
 * @version 0.8.6
 * @for Wolf version 0.7.0 and above
 * @license http://www.gnu.org/licenses/gpl.html GPL License
 * @copyright medio.com.hr & project79.net, 2009-2011
 */

class FancyImageGalleryController extends PluginController {
	
	
	public static function _checkLog()
	{
        AuthUser::load();
        if ( ! AuthUser::isLoggedIn())
		{
            redirect(get_url('login'));
        }
    }
	
	
    public function __construct()
	{
		self::_checkLog();
        $this->setLayout('backend');
        $this->assignToLayout('sidebar', new View('../../plugins/fancy_image_gallery/views/sidebar'));
    }
	
	
    function index($page = 0)
    {
        $this->display('fancy_image_gallery/views/settings');
    }
	
	
    public function documentation()
	{
        $this->display('fancy_image_gallery/views/documentation');
    }
	
	
    function settings()
	{
        $this->display('fancy_image_gallery/views/settings', Plugin::getAllSettings('fancy_image_gallery'));
    }
	
	
    public function create_thumb()
	{
		$path = $_POST['gallery_path'];
		
		if (substr($path, -1) != '/')
		{
			// Put trailing slash on path instead of warning user about it
			$path = $path . '/';
		}
		
		$image_dir = CMS_ROOT . '/public/images/' . $path;

		if ( ! is_dir($image_dir))
		{
			Flash::set('error', __('The folder does not exist!'));
			redirect(get_url('plugin/fancy_image_gallery/'));
		}
		
		// Check for valid width/height
		$thumb_width = $_POST['thumb_width'];
		$thumb_height = $_POST['thumb_height'];
		
		if ($thumb_width == NULL)
		{
			Flash::set('error', __('Specify thumbnail width!'));
			redirect(get_url('plugin/fancy_image_gallery/'));
		}

		if ($thumb_height == NULL)
		{
			Flash::set('error', __('Specify thumbnail height!'));
			redirect(get_url('plugin/fancy_image_gallery/'));
		}
		
		
		// Open the images folder and find out what's in
		$handle = opendir($image_dir);
		$count_images = 0;

		if ($handle)
		{
			while (false !== ($file = readdir($handle)))
			{
				if ($file != '.' && $file != '..')
				{
					// Grab extension of file and convert to lowercase
					$parts = explode('.', $file);
					$ext = end($parts);
					$ext = strtolower($ext);
					
					if (in_array($ext, array('jpg', 'gif', 'png')))
					{
						if (preg_match('/-thumb\./', strtolower($file)))
						{
							// Delete if thumbnail
							unlink($image_dir . '/' . $file);
						}
						else
						{
							$files[] = $file;
						}
						
						$count_images++;
					}
				}
			}
		}
		
		closedir($handle);
		
		$counter = 0;
		
		if($count_images == 0)
		{
			Flash::set('error', __('There are no images in this folder!'));
			redirect(get_url('plugin/fancy_image_gallery/'));
		}
		
		if($files)
		{
			$success_count = 0;
			$failure_count = 0;
			
			// Loop through all the files
			foreach($files as $file)
			{
				$parts = explode('.', $file);
				$ext = end($parts);
				$ext = strtolower($ext);
				
				// Process the file and get result
				$results[$file] = $this->_process_image($image_dir, $file, $thumb_width, $thumb_height);
				
				// Check status and increment counters
				if ($results[$file] == TRUE)
				{
					$success_count++;
				}
				else
				{
					$failure_count++;
				}
			}
			
			// Set flash messages based on processed status of files
			if ($success_count == count($files))
			{
				Flash::set('success', __('Thumbnails have been successfully created!'));
			}
			
			if ($failure_count > 0 && $success_count > 0)
			{
				Flash::set('error', __('Some thumbnails could not be created.'));
			}
			
			if ($failure_count == count($files))
			{
				Flash::set('error', __('No thumbnail images could be created.'));
			}
		
		}
		else
		{
			Flash::set('error', __('There are no images in this folder!'));
		}
		
		redirect(get_url('plugin/fancy_image_gallery/'));
		
	}
	
	
	private function _process_image($image_dir, $filename = '', $dest_width, $dest_height)
	{
		// Get/set info about the name
		$fileparts = explode('.', $filename);
		$basename = strtolower($fileparts[0]);
		$ext = strtolower(end($fileparts));
		
		// Full path to file
		$file_path = $image_dir . $filename;
		
		// Initial result to return
		$res = false;
		
		// Make correct image handle from source file
		switch ($ext)
		{
			case 'jpg':
				$source = imagecreatefromjpeg($file_path);
			break;
			
			case 'gif':
				$source = imagecreatefromgif($file_path);
			break;
			
			case 'png':
				$source = imagecreatefrompng($file_path);
			break;
		}
		
		// Get dimensions of original file
		$width = imagesx($source);
		$height = imagesy($source);
		
		// Check dimeisions are OK to resize for
		if ($width >= $dest_width AND $height >= $dest_height)
		{
			$proportion_X = $width / $dest_width;
			$proportion_Y = $height / $dest_height;
			
			if ($proportion_X > $proportion_Y )
			{
				$proportion = $proportion_Y;
			}
			else
			{
				$proportion = $proportion_X;
			}
			
			$target['width'] = $dest_width * $proportion;
			$target['height'] = $dest_height * $proportion;
			
			$original['diagonal_center'] = round(sqrt(($width*$width)+($height*$height))/2);
			
			$target['diagonal_center'] = round(sqrt(($target['width'] * $target['width']) + ($target['height'] * $target['height'])) / 2 );
			
			$crop = round($original['diagonal_center'] - $target['diagonal_center']);
			
			if ($proportion_X < $proportion_Y )
			{
				$target['x'] = 0;
				$target['y'] = round( ( ($height / 2) * $crop) / $target['diagonal_center']);
			}
			else
			{
				$target['x'] =  round( ( ($width / 2) * $crop) / $target['diagonal_center']);
				$target['y'] = 0;
			}
			
			// Create handle to new thumbnail image
			$dest_img = imagecreatetruecolor($dest_width, $dest_height);
			
			// Copy the source iamge to the new image with crop/resize parameters
			imagecopyresampled($dest_img, $source, 
				0, 0, 
				$target['x'], $target['y'], 
				$dest_width, $dest_height,
				$target['width'], $target['height']
			);
			
			// Save the destination image
			switch ($ext)
			{
				case 'jpg':
					$res = imagejpeg($dest_img, $image_dir . $basename . '-thumb.jpg');
				break;
				
				case 'gif':
					$res = imagegif($dest_img, $image_dir . $basename . '-thumb.gif');
				break;
				
				case 'png':
					$res = imagepng($dest_img, $image_dir . $basename . '-thumb.png');
				break;
			}
			
			// Close files
			imagedestroy($dest_img);
			imagedestroy($source);
			
		}
		
		return $res;
		
	}
	

}