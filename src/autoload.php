<?php
/**
 * This file represents the autoload algorithm.
 *
 * @author David Pauli <contact@david-pauli.de>
 * @since 0.2.0
 */

do {

	$filename = "config.json";
        
	if (!ep6\InputValidator::isExistingFile($filename)) {
		break;
	}

	$handle = fopen($filename, "r");
	
	if (!$handle) {
		ep6\Logger::warning("ep6\ConfigLoader\nConfiguration file can't be opened.");
		break;
	}
	if (filesize($filename) == 0) {
		ep6\Logger::warning("ep6\ConfigLoader\nConfiguration file is empty.");
		break;
	}
	
	$configuration = fread($handle, filesize($filename));
	
	if (!$configuration) {
		ep6\Logger::warning("ep6\ConfigLoader\nConfiguration file can't be read.");
		break;
	}
	
	fclose($handle);
	
	$configArray = ep6\JSONHandler::parseJSON($configuration);
	
	if (ep6\InputValidator::isEmptyArray($configArray)) {
		ep6\Logger::warning("ep6\ConfigLoader\nConfiguration file has no valid JSON.");
		break;
	}
	
	# handle logging
	if (!ep6\InputValidator::isEmptyArrayKey($configArray, "logging")) {
		
		if (!ep6\InputValidator::isEmptyArrayKey($configArray["logging"], "level")) {
			ep6\Logger::setLogLevel($configArray["logging"]["level"]);
		}
		
		if (!ep6\InputValidator::isEmptyArrayKey($configArray["logging"], "output")) {
			ep6\Logger::setOutput($configArray["logging"]["output"]);
		}
		
		if (!ep6\InputValidator::isEmptyArrayKey($configArray["logging"], "outputfile")) {
			ep6\Logger::setOutputFile($configArray["logging"]["outputfile"]);
		}
	}
	
	# handle formatting
	if (!ep6\InputValidator::isEmptyArrayKey($configArray, "formatting")) {
		
		foreach ($configArray["formatting"] as $formatKey => $formatSetting) {
			
			$formatName = $formatKey . "Formatter";
			$$formatName = new ep6\Formatter();
			
			if (!ep6\InputValidator::isEmptyArrayKey($formatSetting, "id")) {
				$$formatName->setID($formatSetting["id"]);
			}
			
			if (!ep6\InputValidator::isEmptyArrayKey($formatSetting, "classes") &&
				!ep6\InputValidator::isEmptyArray($formatSetting["classes"])) {
				foreach($formatSetting["classes"] as $className) {
					$$formatName->setClass($className);
				}
			}
			
			if (!ep6\InputValidator::isEmptyArrayKey($formatSetting, "attributes") &&
				!ep6\InputValidator::isEmptyArray($formatSetting["attributes"])) {
				foreach($formatSetting["attributes"] as $attributeKey => $attributeName) {
					$$formatName->setAttribute($attributeKey, $attributeName);
				}
			}
			
			if (!ep6\InputValidator::isEmptyArrayKey($formatSetting, "formatters") &&
				!ep6\InputValidator::isEmptyArray($formatSetting["formatters"])) {
				foreach($formatSetting["formatters"] as $formatterName) {
					$$formatName->add($formatterName);
				}
			}
		}
	}

	break;

} while(false);
?>