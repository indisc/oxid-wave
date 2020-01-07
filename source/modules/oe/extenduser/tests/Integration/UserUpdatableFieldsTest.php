<?php
/**
 * @package       extenduser
 * @category      module
 * @author        OXID eSales AG
 * @link          http://www.oxid-esales.com/en/
 * @licenses      GNU GENERAL PUBLIC LICENSE. More info can be found in LICENSE file.
 * @copyright (C) OXID e-Sales, 2003-2017
 */

namespace OxidEsales\ExtendUser\tests\Integration;

use OxidEsales\Eshop\Application\Model\User\UserUpdatableFields;
use OxidEsales\ExtendUser\UserModel;
use OxidEsales\TestingLibrary\UnitTestCase;

class UserUpdatableFieldsTest extends UnitTestCase
{
    public function testIsFieldAddedToWhiteList()
    {
        /** @var UserUpdatableFields $userUpdatableFields */
        $userUpdatableFields = oxNew(UserUpdatableFields::class);

        $this->assertTrue(in_array(UserModel::FIELD_ADDITIONAL_INFORMATION, $userUpdatableFields->getUpdatableFields()),
            'In updatable fields array missing field: '.UserModel::FIELD_ADDITIONAL_INFORMATION.'. Returned array is: '
            .implode(" ",$userUpdatableFields->getUpdatableFields())
        );
    }
}
