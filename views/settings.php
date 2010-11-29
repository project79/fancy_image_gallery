<?php

/**
 * Fancy Image Gallery for Wolf CMS- Create gallery and display images with few clicks
 * Gallery is free for non-profit usage. For commercial usage, please contact one of the authors.
 * @package wolf
 * @subpackage plugin.fancy_image_gallery - settings
 *
 * @author Sanja Andjelkovic <sanja@medio.com.hr>
 * @author Dejan Andjelkovic <dejan@medio.com.hr>
 * @version 0.8.0
 * @for Wolf version 0.6.0 and above
 * @license http://www.gnu.org/licenses/gpl.html GPL License
 * @copyright medio.com.hr, 2009
 */
 
?>
<h1><?php echo __('Settings'); ?></h1>
<form action="<?php echo get_url('plugin/fancy_image_gallery/create_thumb'); ?>" method="post">
<fieldset style="padding: 0.5em;">
        <legend style="padding: 0em 0.5em 0em 0.5em; font-weight: bold;"><?php echo __('Create Thumbnails - All fields are mandatory'); ?></legend>
        <table class="fieldset" cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td class="label"><label for="gallery_path"><?php echo __('Gallery Path'); ?>:</label></td>
                <td class="field"><input type="text" id="gallery_path" name="gallery_path" class="textbox" value="" /></td>
                <td class="help"><?php echo __('Specify name of the folder under "public/images/" with a trailing slash. Example: mygallery/'); ?></td>
            </tr>
            <tr>
                <td class="label"><label for="thumb_width"><?php echo __('Thumbnail Width'); ?>:</label></td>
                <td class="field"><input type="text" id="thumb_width" name="thumb_width" class="textbox" value="" /></td>
                <td class="help"><?php echo __('Specify thumbnail width in pixels'); ?></td>
            </tr>
            <tr>
                <td class="label"><label for="thumb_height"><?php echo __('Thumbnail Height'); ?>:</label></td>
                <td class="field"><input type="text" id="thumb_height" name="thumb_height" class="textbox" value="" /></td>
                <td class="help"><?php echo __('Specify thumbnail height in pixels'); ?></td>
            </tr>
		</table>
</fieldset>
	<p class="buttons">
		<input class="button" type="submit" value="<?php echo __('Create Thumbnails'); ?>" />
	</p>
</form>
