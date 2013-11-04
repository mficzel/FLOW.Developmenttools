<?php

namespace KayStrobach\DevelopmentTools\Command;

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Exception;

class ModelCommandController extends \TYPO3\Flow\Cli\CommandController {

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
	);

	/**
	 * Show a list of controllers and actions
	 */
	public function listCommand() {
		$entitiesFromReflectionService = $this->reflectionService->getClassNamesByAnnotation('TYPO3\\Flow\\Annotations\\Entity');

		foreach($entitiesFromReflectionService as $model) {
			if(!in_array($this->objectManager->getPackageKeyByObjectName($model), $this->ignoredPackages)) {
				$this->outputLine($model);
			}
		}
	}

}

?>