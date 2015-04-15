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
 * 
 * This class holds information pertaining to the context under which a
 * processor is running. It is passed to the processor during runtime, in
 * order to pass through not only attributes (parameters) and the body content,
 * but also the possible models.
 * 
 * @author Nathan Crause <nathan at crause.name>
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
