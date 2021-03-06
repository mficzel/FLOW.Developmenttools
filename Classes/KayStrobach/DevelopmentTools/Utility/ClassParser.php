<?php

namespace KayStrobach\DevelopmentTools\Utility;

use TYPO3\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;

/**
 * Objekt zum erzeugen von Studenten!
 *
 * @Flow\Scope("singleton")
 */

class ClassParser {
	/**
	 * @param string $objectName
	 * @return array()
	 */
	public function parseClass($objectName) {
		$reflectionClass   = new \ReflectionClass($objectName);
		$properties        = $reflectionClass->getProperties();
		$defaultProperties = $reflectionClass->getDefaultProperties();
		$result = array(
			'name'       => '\\' . $reflectionClass->getName(),
			'parent'     => NULL,
			'properties' => array(),
		);
		if($reflectionClass->getParentClass()) {
			if($reflectionClass->getParentClass()->getParentClass()) {
				$result['parent'] = '\\' . $reflectionClass->getParentClass()->getParentClass()->getName();
			} else {
				$result['parent'] = '\\' . $reflectionClass->getParentClass()->getName();
			}
			if(substr($result['parent'], -9, 9) === '_Original') {
				$result['parent'] = NULL;
			}
		}

		foreach($properties as $property) {
			$propertyName = $property->getName();

			$parsedComment = $this->parseComment($property->getDocComment());
			$result['properties'][$propertyName] = array(
				'comment'      => $property->getDocComment(),
				'name'         => $propertyName,
				'defaultValue' => $defaultProperties[$propertyName],
				'tags'         => $parsedComment['tags'],
				'description'  => $parsedComment['description'],
				'model'        => $parsedComment['tags']['var'][0],
			);
			if(substr($parsedComment['tags']['var'][0], 0, 29) === '\\Doctrine\\Common\\Collections\\') {
				$pos = strpos($parsedComment['tags']['var'][0], '<');
				$result['properties'][$propertyName]['model'] = substr(
					$parsedComment['tags']['var'][0],
					($pos + 1),
					(strlen($parsedComment['tags']['var'][0]) - $pos - 2)
				);
			}

		}

		return $result;
	}

	private function parseComment($comment) {
		$docCommentParser = new \TYPO3\Flow\Reflection\DocCommentParser();
		$docCommentParser->parseDocComment($comment);
		$return =  array(
			'description' => $docCommentParser->getDescription(),
			'tags'        => $docCommentParser->getTagsValues(),
		);
		if(!array_key_exists('var', $return['tags'])) {
			$return['tags']['var'] = array(
				''
			);
		}
		return $return;
	}
}