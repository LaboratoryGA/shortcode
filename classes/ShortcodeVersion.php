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
 * Description of ShortcodeVersion
 *
 * @author fiveht
 */
class ShortcodeVersion {
	
	public function show(ShortcodeContext $context) {
		return 'Shortcode Parser Version: ' . SHORTCODE_VERSION;
	}
	
}
