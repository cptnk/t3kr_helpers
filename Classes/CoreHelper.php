<?php
namespace Kreativrudel\Helpers;

use TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder;
use TYPO3\CMS\Core\Resource\FileRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;
use TYPO3\CMS\Extbase\Service\ImageService;

use Exception;

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
        $configurationManager = GeneralUtility::makeInstance(
            ConfigurationManager::class
        );
        return $configurationManager->getConfiguration(
            \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS,
            $extName,
            $plugin
        );
    }

    /**
     * Temporary creates a Thumbnail for a given file id or filename and sizes
     *
     * @todo: Couldnt get the inner dependency injection to work outside the controller context, so its getting injected trough parameters for now
     *
     *
     * @param ImageService $imageService
     * @param array $sizes
     * @param string $fileName
     *
     * @throws Exception
     *
     * @return string
     */
    public function createThumbnail(ImageService $imageService, array $sizes, string $fileName = '') {
        if (empty($fileName)) {
            throw new Exception('Please provide a valid $fileName');
        }

        $image = $imageService->getImage($fileName, null, false);
        $processingInstructions = $sizes;
        $processedImage = $imageService->applyProcessingInstructions($image, $processingInstructions);
        return $imageService->getImageUri($processedImage);
    }
}