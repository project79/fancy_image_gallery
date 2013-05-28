<?php
if (!defined('IN_CMS')) { exit(); }
/**
 * Fancy Image Gallery for Wolf CMS- Create gallery and display images with few clicks
 * Gallery is free for non-profit usage. For commercial usage, please contact one of the authors.
 * @package wolf
 * @subpackage plugin.fancy_image_gallery - sidebar
 *
 * @author Sanja Andjelkovic <sanja@medio.com.hr>
 * @author Dejan Andjelkovic <dejan@medio.com.hr>
 * @version 0.9.0
 * @for Wolf version 0.7.0 and above
 * @license http://www.gnu.org/licenses/gpl.html GPL License
 * @copyright medio.com.hr & project79.net, 2009-2012
 */
 
?>
<p class="button"><a href="<?php echo get_url('plugin/fancy_image_gallery/documentation'); ?>"><img src="<?php echo URI_PUBLIC; ?>wolf/plugins/fancy_image_gallery/images/documentation.png" align="middle" alt="page icon" /> <?php echo __('Documentation'); ?></a></p>
<p class="button"><a href="<?php echo get_url('plugin/fancy_image_gallery/settings'); ?>"><img src="<?php echo URI_PUBLIC; ?>wolf/plugins/fancy_image_gallery/images/settings.png" align="middle" alt="page icon" /> <?php echo __('Settings'); ?></a></p>
<div class="box">
    <h2>One gallery</h2>
    <p><code>&lt;?php fancy('name of the folder/'); ?&gt;</code></p>
</div>
<div class="box">
    <h2>Gallery with link</h2>
    <p><code>&lt;?php fancy_parent('name of the folder/', 'slug from page/'); ?&gt;</code></p>
</div>
<div class="box">
    <h2>List of all galleries</h2>
    <p><code>&lt;?php fancy_list(); ?&gt;</code></p>
</div>
<div class="box">
    <h2>List galleries with slider</h2>
    <p><code>&lt;?php fancy_slider($path, $page, 400, 250, $title); ?&gt;</code></p>
</div>