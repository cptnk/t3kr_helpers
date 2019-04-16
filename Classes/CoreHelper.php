<?php
namespace Kreativrudel\Helpers;

use TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder;

class CoreHelper {

    /**
     * Generates a Ajay friendly Uri if the $controller Arguments parameter contains a page type i.e
     * 'type' => 1234
     *
     * Keep in mind that this requires a page type entry within your typoscript
     *
     * @param UriBuilder $uriBuilder
     * @param string $actionName
     * @param string $controllerName
     * @param string $pluginName
     * @param array $controllerArguments
     * @return string
     */
    public function generateUri(
        UriBuilder $uriBuilder,
        string $actionName,
        string $controllerName,
        string $pluginName,
        array $controllerArguments = array()
    ): string {
        return $uriBuilder->reset()
            ->uriFor($actionName, $controllerArguments, $controllerName, null, $pluginName);
    }

    /**
     * This will only work if this function is called within a controller context and the typoscript setting is reachable.
     * I tend to create the following settings within the ext_localconf.php
     *
     *  \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScript($_EXTKEY,'constants',' <INCLUDE_TYPOSCRIPT: source="FILE:EXT:'. $_EXTKEY .'/Configuration/TypoScript/constants.txt">');
     *  \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScript($_EXTKEY,'setup',' <INCLUDE_TYPOSCRIPT: source="FILE:EXT:'. $_EXTKEY .'/Configuration/TypoScript/setup.txt">');
     *
     * once this is called the settings are reachable within any location in extbase.
     *
     * @param string $extName
     * @param string $plugin
     * @return array
     */
    public function exposePluginSettings(string $extName, string $plugin): array {
        $configurationManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
            'TYPO3\\CMS\\Extbase\\Configuration\\ConfigurationManager'
        );
        return $configurationManager->getConfiguration(
            \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS,
            $extName,
            $plugin
        );
    }
}