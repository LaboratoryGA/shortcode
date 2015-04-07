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
 * @author fiveht
 */
final class ShortcodeParser {
	
    /**
     * Parses the supplied content for shortcodes.
     * 
     * @param string $body the content to parse
     * @param array $models associative array of objects to pass through to
     * the processor
     * @return string the resulting content with all shortcodes processed
     */
    public static function parse($body, array $models = array()) {
        $instance = new self($body);
        
		try {
			return $instance->run($models);
		}
		catch (Exception $ex) {
			return $ex->getMessage();
		}
    }
    
	/**
	 * The content body to parse for shortcodes
	 *
	 * @var string 
	 */
    private $body;
    
    private function __construct($body) {
        $this->body = $body;
    }
	
	/**
	 * 
	 * @param array $models associative array of models to expose in the run
	 */
	private function run(array $models) {
        $offset = 0;
        $return = '';
		
		while (($startPos = strpos($this->body, '^[', $offset)) !== false
				&& ($endPos = strpos($this->body, ']^', $startPos)) !== false) {
			$instruction = substr($this->body, $startPos + 2, $endPos - $startPos - 2);
			
			if (!preg_match('/^([\w\\\]+)\s*\((.*?)\)\s*(begin)*$/', $instruction, $matches, PREG_OFFSET_CAPTURE)) {
//				throw new Exception("Malformed shortcode '$instruction'");
				// simply push the content back into the return, and move along
				$return .= "<!-- Malformed shortcode '$instruction' -->" . substr($this->body, $offset, $endPos - $offset + 2);
				$offset = $endPos + 2;
				continue;
			}
			
			$context = new ShortcodeContext();
			
			$context->models = $models;
			
            // make some friendly names
			$processorGroup = (object) array(
				'text' => $matches[1][0],
				'offset' => $startPos + 2 + $matches[1][1]
			);
			$attributesGroup = (object) array(
				'text' => $matches[2][0],
				'offset' => $startPos + 2 + $matches[2][1]
			);
			
            // append the content leading up to this discovery into the return
            $return .= substr($this->body, $offset, $startPos - $offset);
            // reposition the offset
            $offset = $endPos + 2;
            
            $context->isVoid = true;    // default to a void shortcode
            // if we have a 'begin' marker, capture the end, and move the offset
            // to that location instead (since everything between the begin and
            // end is passed through a new processor anyway
			// **NOTE** unlike the original implementation
			// we no longer support embeddeding the same shortcode inside
			// of itself.
            if (count($matches) > 3) {
                $context->isVoid = false;
				
				$closeMarker = '[[' . $processorGroup->text . ' end]]';
				if (($closePos = strpos($this->body, $closeMarker, $offset)) === false) {
//                    throw new Exception("Unclosed shortcode '{$processorGroup->text}' near $offset");
					$return .= "<!-- Unclosed shortcode '{$processorGroup->text}' near $offset" . substr($this->body, $offset, $endPos - $offset + 2);
					$offset = $endPos + 2;
					continue;
				}
				
                // grab the in-between
				$context->content = substr($this->body, $offset, $closePos - $offset);
                // reposition the offset
                $offset = $closePos + strlen($closeMarker);
			}
            
            // process the attributes
            if ($attributesGroup->text) {
				// we have to run html_entity_decode below, because (for some
				// reason) Claromentis' CKEditor convert vanilla quotation
				// marks into HTML entites
                if (!preg_match_all('/\G\s*(\w+)=([\'"])(.*?)\2/ms', html_entity_decode($attributesGroup->text), $attrMatches)) {
//                    throw new Exception("Unparsable attribute list: '{$attributesGroup->text}'");
					$return .= "<!-- Unparsable attribute list: '{$attributesGroup->text}'" . substr($this->body, $offset, $endPos - $offset + 2);
					$offset = $endPos + 2;
					continue;
                }
                
                foreach ($attrMatches[1] as $index => $name) {
                    $context->attributes[$name] = $attrMatches[3][$index];
                }
            }
			
			//echo $processorGroup->text . ' ( ' . print_r($context->attributes, true) . ")<br>";
            
            // invoke the processor, appending it's contents to the return
            if (!class_exists($processorGroup->text)) {
//                throw new Exception("Unknown class '{$processorGroup->text}'");
				// simply push the content back into the return, and move along
				$return .= "<!-- Unknown class '{$processorGroup->text}'" . substr($this->body, $offset, $endPos - $offset + 2);
				$offset = $endPos + 2;
				continue;
            }
			
			$class = $processorGroup->text;
			$instance = new $class();
			//print_r($instance);
            
            // capture all it's output, too
            ob_start();
			
			$functionReturn = call_user_func(array($instance, 'show'), $context);
			$bufferReturn = ob_get_clean();
            
            $return .= $functionReturn . $bufferReturn;
		}
        
        // append the remaining content
        $return .= substr($this->body, $offset);
        
        return $return;
	}
	
}
