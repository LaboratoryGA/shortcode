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
 * 
 * This class holds information pertaining to the context under which a
 * processor is running. It is passed to the processor during runtime, in
 * order to pass through not only attributes (parameters) and the body content,
 * but also the possible models.
 *
 */
final class ShortcodeContext {
    
    /**
     * Associative array of attributes passed to the shortcode
     *
     * @var array
     */
    public $attributes = array();
    
    /**
     * If the shortcode was invoked with a "begin" and "end", then this will
     * contain the body between the beginning and ending.
     *
     * @var string
     */
    public $content;
    
    /**
     * Associative array of localized objects which should be used with the 
     * context.
     *
     * @var array 
     */
    public $models = array();
    
    /**
     * Flags that the shortcode which caused this context to be created was
     * "void", meaning it has no "begin/end". This is useful in situations where
     * you mya want the user to be able to pass either an attribute OR content,
     * but perhaps not both.
     * <p>
     * An example of this might be an HTML &lt;label&gt; tag. You might want
     * to be able to offer: <code>${label(text="My Label")}</code> or
     * <code>${label() begin}This is also my label${label end}</code>
     *
     * @var boolean 
     */
    public $isVoid;
	
}
