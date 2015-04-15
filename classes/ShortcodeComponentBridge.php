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
 * This class is intended as a shortcode to provide access to CLA's
 * components.
 * <p>
 * This allows page authors to embed components without having to create
 * publish templates unnecessarily.
 *
 * @author Nathan Crause <nathan at crause.name>
 */
final class ShortcodeComponentBridge {
	
	public function show(ShortcodeContext $context) {
		assert(key_exists('class', $context->attributes));
		
		try {
//			echo 'Looking for ' . $context->attributes['class'];
			if (!class_exists($class = $context->attributes['class'])) {
				return "Class not found: '{$context->attributes['class']}'";
			}
//			echo "Found $class";
			$instance = new $class();
//			return 'Invoking: ' . print_r($instance, true);
			
			return $instance->Show($context->attributes);
		}
		catch (Exception $ex) {
			error_log("Unhandled exception during component bridge: {$ex->getMessage()}");
			return "Unhandled exception during component bridge: {$ex->getMessage()}";
		}
	}
	
}
