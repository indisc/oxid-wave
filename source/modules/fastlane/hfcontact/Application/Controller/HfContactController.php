<?php



use OxidEsales\Eshop\Core\Email;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\EshopCommunity\Internal\Common\Form\FormInterface;
use OxidEsales\EshopCommunity\Internal\Form\ContactForm\ContactFormBridgeInterface;


class HfContactController extends \OxidEsales\Eshop\Application\Controller\ContactController
{
    /**
     * Entered contact subject.
     *
     * @var string
     */
    protected $_sAccessibility = null;

    /**
     * Entered conatct message.
     *
     * @var string
     */
    protected $_sBranch = null;


    public function getContactAccessibility()
    {
        if ($this->_sContactAccessibility === null) {
            $this->_sContactAccessibility = Registry::getConfig()->getRequestParameter('oxuser__c_accessibility');
        }

        return $this->_sContactAccessibility;
    }

    /**
     * Template variable getter. Returns entered message
     *
     * @return object
     */
    public function getContactBranch()
    {
        if ($this->_sContactBranch === null) {
            $this->_sContactBranch = Registry::getConfig()->getRequestParameter('oxuser__c_branch');
        }

        return $this->_sContactBranch;
    }



    /*
     private function getMappedContactFormRequestExtends()
     {
         $request = Registry::getRequest();
         $personData = $request->getRequestEscapedParameter('editval');

         $ret = $this->getMappedContactFormRequest();


         $ret1 = array(
             'accessibility'       => $request->getRequestEscapedParameter('c_accessibility'),
             'branch'       => $request->getRequestEscapedParameter('c_branch'),
         );

         $ret = array_push($ret, $ret1);



         return $ret;
     }
   */

         private function getMappedContactFormRequest()
         {
             $request = Registry::getRequest();
             $personData = $request->getRequestEscapedParameter('editval');

             return [
                 'email'            => $personData['oxuser__email'],
                 'firstName'        => $personData['oxuser__oxfname'],
                 'lastName'         => $personData['oxuser__oxlname'],
                 'salutation'       => $personData['oxuser__oxsal'],
                 'street'           => $personData['oxuser__oxstreet'],
                 'streetNumber'     => $personData['oxuser__oxstreetnr'],
                 'zip'              => $personData['oxuser__oxzip'],
                 'town'              => $personData['oxuser__oxcity'],
                 'branch'           => $personData['oxuser__c_branch'],
                 'accessibility'    => $personData['oxuser__c_accessibility'],
                 'orderNr'          => $personData['oxuser__order_billing_number'],
                 'breed'             => $personData['oxuser__breed'],
                 'city'             => $personData['oxuser__animal_age'],

                 'subject'          => $request->getRequestEscapedParameter('c_subject'),
                 'message'          => $request->getRequestEscapedParameter('c_message'),
             ];
         }



    /**
     * Composes and sends user written message, returns false if some parameters
     * are missing.
     *
     * @return bool
     */


    public function send()
    {
        $contactFormBridge = $this->getContainer()->get(ContactFormBridgeInterface::class);

        $form = $contactFormBridge->getContactForm();


        $result = $this->getMappedContactFormRequest();
        //var_dump($result);
        //exit("spass stoppt hier");

        //$form->handleRequest($this->getMappedContactFormRequest());

        $form->$this->getMappedContactFormRequest();


        if ($form->isValid()) {
            $this->sendContactMail(
                $form->email->getValue(),
                $form->subject->getValue(),
                $contactFormBridge->getContactFormMessage($form)
            );
        } else {
            foreach ($form->getErrors() as $error) {
                Registry::getUtilsView()->addErrorToDisplay($error);
            }

            return false;
        }
    }

/*
switch($_POST['Anliegen']) {
case "Lieferung":
$zieladresse = "frank@healthfood24.com,formulare@healthfood24.com";
break;
case "Rechnung":
$zieladresse = "burschkat@healthfood24.com,formulare@healthfood24.com";
break;
case "Fragen zur Produktpalette":
$zieladresse = "info@healthfood24.com,formulare@healthfood24.com";
break;
case "Futterproben":
$zieladresse = "petermann@healthfood24.com,formulare@healthfood24.com";
break;
case "Haendler - Interesse am Sortiment":
$zieladresse = "ruehmann@healthfood24.com,formulare@healthfood24.com";
break;
case "Zuechter - Interesse am Sortiment":
$zieladresse = "kuehnert@healthfood24.com,formulare@healthfood24.com";
break;
case "Presse / Werbung / Sponsoring":
$zieladresse = "floch@healthfood24.com,formulare@healthfood24.com";
break;
case "Anregungen":
$zieladresse = "floch@healthfood24.com,formulare@healthfood24.com";
break;
case "Sonstiges":
$zieladresse = "floch@healthfood24.com,formulare@healthfood24.com";
break;
default:
$zieladresse = "floch@healthfood24.com,formulare@healthfood24.com";
break;
}
*/

    /**
     * Send a contact mail.
     *
     * @param string $email
     * @param string $subject
     * @param string $message
     */
    private function sendContactMail($email, $subject, $message)
    {
        $mailer = oxNew(Email::class);

        if ($mailer->sendContactMail($email, $subject, $message)) {
            $this->_blContactSendStatus = 1;
        } else {
            Registry::getUtilsView()->addErrorToDisplay('ERROR_MESSAGE_CHECK_EMAIL');
        }
    }
}
