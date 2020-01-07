<?php
/**
 * @package       extenduser
 * @category      module
 * @author        OXID eSales AG
 * @link          http://www.oxid-esales.com/en/
 * @licenses      GNU GENERAL PUBLIC LICENSE. More info can be found in LICENSE file.
 * @copyright (C) OXID e-Sales, 2003-2017
 */

namespace OxidEsales\ExtendUser;

/**
 * @see \OxidEsales\Eshop\Application\Model\User\UserUpdatableFields
 */
class UserUpdatableFields extends UserUpdatableFields_parent
{
    protected $fieldsToAdd = [
        UserModel::FIELD_ADDITIONAL_INFORMATION,
        UserMOdel::FIELD_CHECKBOX_REGISTRY
    ];


    /**
     * Adds additional field which could be updated.
     *
     * @return array
     */
    public function getUpdatableFields()
    {
        $updatableFields = parent::getUpdatableFields();

        return array_merge($updatableFields, $this->fieldsToAdd);
    }
}
