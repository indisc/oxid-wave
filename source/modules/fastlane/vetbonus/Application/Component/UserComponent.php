<?php


namespace Fastlane\VetBonus\Application\Component;


use OxidEsales\Eshop\Application\Model\User\UserShippingAddressUpdatableFields;
use OxidEsales\Eshop\Application\Model\User\UserUpdatableFields;
use OxidEsales\Eshop\Core\Contract\AbstractUpdatableFields;
use OxidEsales\Eshop\Core\Form\FormFields;
use OxidEsales\Eshop\Core\Form\FormFieldsTrimmer;
use OxidEsales\Eshop\Core\Form\UpdatableFieldsConstructor;
use OxidEsales\Eshop\Core\Registry;

class UserComponent extends \OxidEsales\Eshop\Application\Component\UserComponent
{

    /**
     * New user Vetbonus status
     *
     * @var bool
     */
    protected $_blNewVetBonusUser = null;

/**
 * First test if all required fields were filled, then performed
 * additional checking oxcmp_user::CheckValues(). If no errors
 * occured - trying to create new user (\OxidEsales\Eshop\Application\Model\User::CreateUser()),
 * logging him to shop (\OxidEsales\Eshop\Application\Model\User::Login() if user has entered password).
 * If \OxidEsales\Eshop\Application\Model\User::CreateUser() returns false - this means user is
 * already created - we only logging him to shop (oxcmp_user::Login()).
 * If there is any error with missing data - function will return
 * false and set error code (oxcmp_user::iError). If user was
 * created successfully - will return "payment" to redirect to
 * payment interface.
 *
 * Template variables:
 * <b>usr_err</b>
 *
 * Session variables:
 * <b>usr_err</b>, <b>usr</b>
 *
 * @return  mixed    redirection string or true if successful, false otherwise
 */
    public function createUser()
    {
        /*
        $blActiveLogin = $this->getParent()->isEnabledPrivateSales();

        $oConfig = $this->getConfig();

        if ($blActiveLogin && !$oConfig->getRequestParameter('ord_agb') && $oConfig->getConfigParam('blConfirmAGB')) {
            \OxidEsales\Eshop\Core\Registry::getUtilsView()->addErrorToDisplay('READ_AND_CONFIRM_TERMS', false, true);

            return false;
        }

        // collecting values to check
        $sUser = $oConfig->getRequestParameter('lgn_usr');

        // first pass
        $sPassword = $oConfig->getRequestParameter('lgn_pwd', true);

        // second pass
        $sPassword2 = $oConfig->getRequestParameter('lgn_pwd2', true);

        $aInvAdress = $oConfig->getRequestParameter('invadr', true);

        $aInvAdress = $this->cleanAddress($aInvAdress, oxNew(UserUpdatableFields::class));
        $aInvAdress = $this->trimAddress($aInvAdress);

        $aDelAdress = $this->_getDelAddressData();
        $aDelAdress = $this->cleanAddress($aDelAdress, oxNew(UserShippingAddressUpdatableFields::class));
        $aDelAdress = $this->trimAddress($aDelAdress);
        */
        parent::createUser();


        try {
            /**
             * @var \OxidEsales\Eshop\Application\Model\User $oUser
             */
            $oUser = oxNew(\OxidEsales\Eshop\Application\Model\User::class);
            $oUser->checkValues($sUser, $sPassword, $sPassword2, $aInvAdress, $aDelAdress);

            $iActState = $blActiveLogin ? 0 : 1;

            // setting values
            $oUser->oxuser__oxusername = new \OxidEsales\Eshop\Core\Field($sUser, \OxidEsales\Eshop\Core\Field::T_RAW);
            $oUser->setPassword($sPassword);
            $oUser->oxuser__oxactive = new \OxidEsales\Eshop\Core\Field($iActState, \OxidEsales\Eshop\Core\Field::T_RAW);

            // used for checking if user email currently subscribed
            $iSubscriptionStatus = $oUser->getNewsSubscription()->getOptInStatus();

            $database = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();
            $database->startTransaction();
            try {
                $oUser->createUser();
                $oUser = $this->configureUserBeforeCreation($oUser);
                $oUser->load($oUser->getId());
                $oUser->changeUserData($oUser->oxuser__oxusername->value, $sPassword, $sPassword, $aInvAdress, $aDelAdress);

                if ($blActiveLogin) {
                    // accepting terms..
                    $oUser->acceptTerms();
                }

                $database->commitTransaction();
            } catch (Exception $exception) {
                $database->rollbackTransaction();

                throw $exception;
            }

            $sUserId = \OxidEsales\Eshop\Core\Registry::getSession()->getVariable("su");
            $sRecEmail = \OxidEsales\Eshop\Core\Registry::getSession()->getVariable("re");
            if ($this->getConfig()->getConfigParam('blInvitationsEnabled') && $sUserId && $sRecEmail) {
                // setting registration credit points..
                $oUser->setCreditPointsForRegistrant($sUserId, $sRecEmail);
            }

            // assigning to newsletter
            $blOptin = \OxidEsales\Eshop\Core\Registry::getConfig()->getRequestParameter('blnewssubscribed');
            if ($blOptin && $iSubscriptionStatus == 1) {
                // if user was assigned to newsletter
                // and is creating account with newsletter checked,
                // don't require confirm
                $oUser->getNewsSubscription()->setOptInStatus(1);
                $oUser->addToGroup('oxidnewsletter');
                $this->_blNewsSubscriptionStatus = 1;
            } else {
                $blOrderOptInEmailParam = $this->getConfig()->getConfigParam('blOrderOptInEmail');
                $this->_blNewsSubscriptionStatus = $oUser->setNewsSubscription($blOptin, $blOrderOptInEmailParam);
            }

            // create new account and assigned to tieraerzte group
            $blActivVetBonus = \OxidEsales\Eshop\Core\Registry::getConfig()->getRequestParameter('chbx_vetbonus');
            if ( $blActivVetBonus == 1){
                $oUser->addToGroup('2ded4fb8cab4d844316d576054f18a13');
                $this->_blNewVetBonusUser = 1;
            }

            $oUser->addToGroup('oxidnotyetordered');
            $oUser->logout();
        } catch (\OxidEsales\Eshop\Core\Exception\UserException $exception) {
            Registry::getUtilsView()->addErrorToDisplay($exception, false, true);

            return false;
        } catch (\OxidEsales\Eshop\Core\Exception\InputException $exception) {
            Registry::getUtilsView()->addErrorToDisplay($exception, false, true);

            return false;
        } catch (\OxidEsales\Eshop\Core\Exception\DatabaseConnectionException $exception) {
            Registry::getUtilsView()->addErrorToDisplay($exception, false, true);

            return false;
        } catch (\OxidEsales\Eshop\Core\Exception\ConnectionException $exception) {
            Registry::getUtilsView()->addErrorToDisplay($exception, false, true);

            return false;
        }

        if (!$blActiveLogin) {
            \OxidEsales\Eshop\Core\Registry::getSession()->setVariable('usr', $oUser->getId());
            $this->_afterLogin($oUser);

            // order remark
            //V #427: order remark for new users
            $sOrderRemark = \OxidEsales\Eshop\Core\Registry::getConfig()->getRequestParameter('order_remark', true);
            if ($sOrderRemark) {
                \OxidEsales\Eshop\Core\Registry::getSession()->setVariable('ordrem', $sOrderRemark);
            }
        }

        // send register eMail
        //TODO: move into user
        if ((int) \OxidEsales\Eshop\Core\Registry::getConfig()->getRequestParameter('option') == 3) {
            $oxEMail = oxNew(\OxidEsales\Eshop\Core\Email::class);
            if ($blActiveLogin) {
                $oxEMail->sendRegisterConfirmEmail($oUser);
            } else {
                $oxEMail->sendRegisterEmail($oUser);
            }
        }

        // new registered
        $this->_blIsNewUser = true;

        $sAction = 'payment?new_user=1&success=1';
        if ($this->_blNewVetBonusUser !== null && !$this->_blNewVetBonusUser) {
            $sAction = 'payment?new_user=1&success=1&newslettererror=4';
        }

        return $sAction;
    }

    /**
     * Creates new oxid user
     *
     * @return string partial parameter string or null
     */


    public function registerUser()
    {
        // registered new user ?
        if ($this->createUser() != false && $this->_blIsNewUser) {
            if ($this->_blNewVetBonusUser === null || $this->_blNewVetBonusUser) {
                return 'register?success=1';
            }
        } else {
            // problems with registration ...
            $this->logout();
        }
    }

    /**
     * @param array                   $address
     * @param AbstractUpdatableFields $updatableFields
     *
     * @return array
     */
    private function cleanAddress($address, $updatableFields)
    {
        if (is_array($address)) {
            /** @var UpdatableFieldsConstructor $updatableFieldsConstructor */
            $updatableFieldsConstructor = oxNew(UpdatableFieldsConstructor::class);
            $cleaner = $updatableFieldsConstructor->getAllowedFieldsCleaner($updatableFields);
            return $cleaner->filterByUpdatableFields($address);
        }

        return $address;
    }


    /**
     * Returns trimmed address.
     *
     * @param array $address
     *
     * @return array
     */
    private function trimAddress($address)
    {
        if (is_array($address)) {
            $fields  = oxNew(FormFields::class, $address);
            $trimmer = oxNew(FormFieldsTrimmer::class);

            $address = (array)$trimmer->trim($fields);
        }

        return $address;
    }


}