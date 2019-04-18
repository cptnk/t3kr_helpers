<?php
namespace Kreativrudel\Helpers;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;

class AjaxHelper {
    /**
     * @var ObjectManager $objectManager
     */
    protected $objectManager;

    public function __construct() {
        $this->objectManager = GeneralUtility::makeInstance(ObjectManager::class);
    }

    /**
     * Appends variables and its configuration names to the json view
     *
     * @param mixed $jsonView
     * @param array $variables
     * @return string
     */
    public function renderJsonView($jsonView, array $variables = array()): string {
        $variablesToRender = [];
        foreach ($variables as $name => $variable) {
            $jsonView->assign($name, $variable);
            $variablesToRender[] = $name;
        }
        $jsonView->setVariablesToRender($variablesToRender);
        return $jsonView->render();
    }

    /**
     * @param int $maxCount
     * @param int $limit
     * @return int
     */
    public function calculateMaxPageSize($maxCount, $limit) {
        return round($maxCount / $limit, 0, PHP_ROUND_HALF_UP);
    }
}