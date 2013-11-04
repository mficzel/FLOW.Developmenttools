<?php

namespace KayStrobach\DevelopmentTools\Command;

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Exception;

class ControllerCommandController extends \TYPO3\Flow\Cli\CommandController {

	/**
	 * @FLOW\Inject
	 * @var \TYPO3\Flow\Object\ObjectManager
	 */
	protected $objectManager;

	/**
	 * @FLOW\Inject
	 * @var \TYPO3\Flow\Reflection\ReflectionService
	 */
	protected $reflectionService;

	/**
	 * @var array
	 */
	protected $ignoredPackages = array(
		'TYPO3.Flow',
		'TYPO3.Fluid',
		'TYPO3.Welcome',
		'KayStrobach.Menu',
		'KayStrobach.DevelopmentTools',
	);

	/**
	 * Show a list of controllers and actions
	 */
	public function listCommand() {
		$controllersFromReflectionService = $this->reflectionService->getAllSubClassNamesForClass('\TYPO3\Flow\Mvc\Controller\ActionController');

		foreach($controllersFromReflectionService as $controller) {
			if(!in_array($this->objectManager->getPackageKeyByObjectName($controller), $this->ignoredPackages)) {
				$this->outputLine($controller);
			}
		}
	}

}
?>