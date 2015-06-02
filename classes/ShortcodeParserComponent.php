<?php

/*
 * Copyright (C) 2015 Nathan Crause <nathan at crause.name>
 *
 * This file is part of Shortcode
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */

/**
 * This templater component processes the current output buffer and looks for
 * shortcodes.
 * <p>
 * It should be added to the <code>interface_<i>custom</i>/common/htmlfooter.html</code>
 * file so that it will run at the very end of page processing, thus:
 * <pre>
 * &lt;include file="../common/get2post.html"&gt;
 * 
 * &lt;txt name="im_check_include" visible="1"&gt;
 * 	&lt;include file="../communication/im_check.html"&gt;
 * &lt;/txt&gt;
 * &lt;/body&gt;
 * &lt;/html&gt;
 * <b>&lt;component class="ShortcodeParserComponent" /&gt;</b>
 * </pre>
 *
 * @author Nathan Crause
 */
final class ShortcodeParserComponent implements TemplaterComponent {
	
	public function Show($attributes) {
		ClaApplication::Enter('shortcode');
		
		// we do the following instead of purely "ob_get_clean()" because we
		// do not want to turn OFF output buffering - we simply want it's
		// contents and to flush it.
		$contents = ob_get_contents();
		
		// don't parse if we're in the field editor
		if (strpos($_SERVER['REQUEST_URI'], '/intranet/publish/edit_page_fields.php') !== false
				// nor the template editor
				|| strpos($_SERVER['REQUEST_URI'], '/intranet/panels/publish_template_content.php') !== false
				// nor the NEW news editor
				|| strpos($_SERVER['REQUEST_URI'], '/intranet/news/create.php') !== false
				// nor the EXISTING news editor
				|| strpos($_SERVER['REQUEST_URI'], '/intranet/panels/newsadmin_edit_news.php') !== false
				// and don't bother if there are no markers
				|| strpos($contents, '^[') === false) {
//			die(print_r([
//				'edit page fields' => strpos($_SERVER['REQUEST_URI'], '/intranet/publish/edit_page_fields.php'),
//				'publish template content' => strpos($_SERVER['REQUEST_URI'], '/intranet/panels/publish_template_content.php'),
//				'create news' => strpos($_SERVER['REQUEST_URI'], '/intranet/news/create.php'),
//				'markers' => 
//			], true));
			return;
		}
		
		ob_clean();
		
		try {
			return ShortcodeParser::parse($contents);
		}
		catch (Exception $exception) {
			error_log("Fatal error in shortcode parser: {$exception->getMessage()}");
			//error_log($exception->)
			return $exception->getMessage();
		}
	}
	
}