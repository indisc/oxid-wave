<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace Fastlane\HfContact\Internal;

use OxidEsales\EshopCommunity\Internal\Common\FormConfiguration\FormFieldsConfigurationDataProviderInterface;
/**
 * Class ContactFormFieldsConfigurationDataProvider
 */
class HfContactFormFieldsConfigurationDataProvider extends \OxidEsales\EshopCommunity\Internal\Form\ContactForm\ContactFormFieldsConfigurationDataProvider
{
    /**
     * @return array
     */
    public function getFormFieldsConfiguration()
    {
        $configuration = parent::getFormFieldsConfiguration();

        $configurationExtends = [
            [
                'name'  => 'street',
                'label' => 'STREET',
            ],
            [
                'name'  => 'streetNumber',
                'label' => 'STREET_NUMBER',
            ],
        ];

        return array_push($configuration, $configurationExtends);
    }
}
