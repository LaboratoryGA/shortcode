<?php

/*
 * Copyright (C) 2015 Nathan Crause - All rights reserved
 *
 * This file is part of Intranet_Labs
 *
 * Copying, modification, duplication in whole or in part without
 * the express written consent of the copyright holder is
 * expressly prohibited under the Berne Convention and the
 * Buenos Aires Convention.
 */

/**
 * This class is intended as a shortcode to provide access to CLA's
 * components.
 * <p>
 * This allows page authors to embed components without having to create
 * publish templates unnecessarily.
 *
 * @author fiveht
 */
final class ShortcodeComponentBridge {
	
	public function show(ShortcodeContext $context) {
		assert(key_exists('class', $context->attributes));
		
		try {
			echo 'Looking for ' . $context->attributes['class'];
			if (!class_exists($class = $context->attributes['class'])) {
				return "Class not found: '{$context->attributes['class']}'";
			}
			echo "Found $class";
			$instance = new $class();
			return 'Invoking: ' . print_r($instance, true);
			
			return $instance->Show($context->attributes);
		}
		catch (Exception $ex) {
			error_log("Unhandled exception during component bridge: {$ex->getMessage()}");
			return "Unhandled exception during component bridge: {$ex->getMessage()}";
		}
	}
	
}
