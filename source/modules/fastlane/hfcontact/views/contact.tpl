



<div class="col-xs-12">
    <div class="col-xs-12 col-md-6">
        <h2>Kontaktformular</h2>
        <p>Ihre persönliche Daten: </p>
    </div>
    <div class="col-xs-12 col-md-6">
    </div>
</div>
<div class="col-xs-12">
    [{*nile: first row-> Fristname and lastname *}]

    <div class="form-group[{if $aErrors && !$oView->getContactSubject()}] oxInValid[{/if}]">
        <label class="[{if $contactFormFields.subject.isRequired}]req[{/if}] control-label col-lg-2"
               for="c_subject">
            [{oxmultilang ident="SUBJECT"}]
        </label>
        <div class="col-lg-10 controls">
            <input type="text"
                   name="c_subject"
                   id="c_subject"
                   size="70"
                   maxlength=80
                   value="[{$oView->getContactSubject()}]"
                   class="form-control"
                   [{if $contactFormFields.subject.isRequired}]required="required"[{/if}]>
        </div>
    </div>


    <div class="col-xs-12 col-md-6">
        <div class="form-group[{if $aErrors.oxuser__oxfname}] oxInValid[{/if}]">
            <label class="[{if $contactFormFields.firstName.isRequired}]req[{/if}] control-label col-lg-4"
                   for="editval[oxuser__oxfname]">
                [{oxmultilang ident="FIRST_NAME"}]
            </label>
            <div class="col-lg-8 controls">
                <input type="text"
                       name="editval[oxuser__oxfname]"
                       id="editval[oxuser__oxfname]"
                       size="70"
                       maxlength="255"
                       value="[{$editval.oxuser__oxfname}]"
                       class="form-control"
                       [{if $contactFormFields.firstName.isRequired}]required="required"[{/if}] >
            </div>
        </div>
    </div>

    <div class="col-xs-12 col-md-6">
        <div class="form-group[{if $aErrors.oxuser__oxlname}] oxInValid[{/if}]">
            <label class="[{if $contactFormFields.lastName.isRequired}]req[{/if}] control-label col-lg-4"
                   for="editval[oxuser__oxlname]">
                [{oxmultilang ident="LAST_NAME"}]
            </label>
            <div class="col-lg-8 controls">
                <input type="text"
                       name="editval[oxuser__oxlname]"
                       id="editval[oxuser__oxlname]"
                       size=70
                       maxlength=255
                       value="[{$editval.oxuser__oxlname}]"
                       class="form-control"
                       [{if $contactFormFields.lastName.isRequired}]required="required"[{/if}]>
            </div>
        </div>
    </div>

</div>

<div class="col-xs-12">
    <div class="col-xs-12 col-md-6">
        <div class="form-group[{if $aErrors.oxuser__oxstreet}] text-danger[{/if}]">
            <label class="control-label col-xs-12 col-lg-4[{if $oView->isFieldRequired(oxuser__oxstreet) || $oView->isFieldRequired(oxuser__oxstreetnr)}] req[{/if}]">[{oxmultilang ident="STREET_AND_STREETNO"}] *</label>
            <div class="col-xs-8 col-lg-6">
                <input class="form-control" type="text" maxlength="255" name="editval[oxuser__oxstreet]" value="[{$editval.oxuser__oxstreet}]"[{if $oView->isFieldRequired(oxuser__oxstreet)}] required=""[{/if}] [{oxmultilang ident="REQUIRED_INPUT_FIELD_WITH_TEXT"}]>
            </div>
            <div class="col-xs-4 col-lg-2">
                <input class="form-control" type="text" maxlength="16" name="editval[oxuser__oxstreetnr]" value="[{$editval.oxuser__oxstreetnr}] "[{if $oView->isFieldRequired(oxuser__oxstreetnr)}] required=""[{/if}] [{oxmultilang ident="REQUIRED_INPUT_FIELD_WITH_TEXT"}]>
            </div>
            <div class="col-lg-offset-3 col-lg-2 col-xs-12">
                [{include file="message/inputvalidation.tpl" aErrors=$aErrors.oxuser__oxstreet}]
                [{include file="message/inputvalidation.tpl" aErrors=$aErrors.oxuser__oxstreetnr}]
                <div class="help-block"></div>
            </div>
        </div>
    </div>

    <div class="col-xs-12 col-md-6">
        <div class="form-group[{if $aErrors.oxuser__oxzip}] text-danger[{/if}]">
            <label class="control-label col-xs-12 col-lg-4[{if $oView->isFieldRequired(oxuser__oxzip) || $oView->isFieldRequired
            (oxuser__oxcity)}] req[{/if}]">[{oxmultilang ident="POSTAL_CODE_AND_CITY"}] *</label>
            <div class="col-xs-5 col-lg-3">
                <input class="form-control" type="text" maxlength="16" name="editval[oxuser__oxzip]" value="[{$editval.oxuser__oxzip}]" required=""
                       [{oxmultilang ident="REQUIRED_INPUT_FIELD_WITH_TEXT"}]>
            </div>
            <div class="col-xs-7 col-lg-5">
                <input class="form-control" type="text" maxlength="255" name="editval[oxuser__oxcity]" value="[{$editval.oxuser__oxcity}]"
                       [{oxmultilang ident="REQUIRED_INPUT_FIELD_WITH_TEXT"}]>
            </div>
            <div class="col-lg-offset-3 col-md-9 col-xs-12">
                [{include file="message/inputvalidation.tpl" aErrors=$aErrors.oxuser__oxzip}]
                [{include file="message/inputvalidation.tpl" aErrors=$aErrors.oxuser__oxcity}]
                <div class="help-block"></div>
            </div>
        </div>
    </div>
</div>

<div class="col-xs-12">
    <div class="col-xs-12 col-md-6">
        <div class="form-group[{if $aErrors.oxuser__oxusername}] oxInValid[{/if}]">
            <label class="[{if $contactFormFields.email.isRequired}]req[{/if}] control-label col-lg-4"
                   for="contactEmail">
                [{oxmultilang ident="EMAIL"}]
            </label>
            <div class="col-lg-8 controls">
                <input id="contactEmail"
                       type="email"
                       name="editval[oxuser__email]"
                       size=70
                       maxlength=255
                       value="[{$editval.oxuser__email}]"
                       class="form-control"
                       [{if $contactFormFields.email.isRequired}]required="required"[{/if}]>
            </div>
        </div>
    </div>

    <div class="col-xs-12 col-md-6">

    </div>

</div>

<div class="col-xs-12">
    <div class="col-xs-12 col-md-6">
        [{*nile: test contact formfield BRANCH *}]

        <div class="form-group">
            <label class="control-label col-lg-4"
                   for="c_branch">
                Branche
            </label>
            <div class="col-lg-8 controls">
                <input type="text"
                       name="editval[oxuser__c_branch]"
                       id="c_branch"
                       size="70"
                       maxlength=80
                       value="[{$editval.oxuser__c_branch}]"
                       class="form-control">
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-md-6">
        [{*nile: test contact formfield Accessibility *}]

        <div class="form-group">
            <label class="control-label col-lg-4"
                   for="c_accessibility">
                Branche
            </label>
            <div class="col-lg-8 controls">
                <input type="text"
                       name="editval[oxuser__c_accessibility]"
                       id="c_accessibility"
                       size="70"
                       maxlength=80
                       value="[{$editval.oxuser__c_accessibility}]"
                       class="form-control">
            </div>
        </div>
    </div>
</div>

<div class="col-xs-12">
    <hr>
</div>


<div class="col-xs-12">
    <div class="col-xs-12 col-md-6">
        <div class="form-group">
            <label class="[{if $contactFormFields.salutation.isRequired}]req[{/if}] control-label col-lg-4">
                [{oxmultilang ident="TITLE"}]
            </label>
            <div class="col-lg-8 controls">
                [{include
                file="form/fieldset/salutation_hf_contact.tpl"
                name="editval[oxuser__oxsal]"
                value=$editval.oxuser__oxsal
                class="form-control selectpicker show-tick "
                required=$contactFormFields.salutation.isRequired
                }]
            </div>
        </div>
    </div>
</div>

<div class="col-xs-12">
    <div class="col-xs-12 col-md-6">
        <!-- Bestell oder Rechnungsnummer -->
        <div class="form-group">
            <label class="col-lg-4 req" for="order_billing_number">Bestell- oder Rechnungsnummer</label>
            <div class="col-lg-8">
                <input id="order_billing_number" class="form-control " type="text" name="editval[oxuser__order_billing_number]" value="[{$editval.oxuser__order_billing_number}]" required="required" >
                <div class="help-block"></div>
            </div>
        </div>
    </div>
</div>

<h3>Ihr Haustier</h3>
<div class="col-xs-12">
    <div class="col-xs-12 col-md-6">
        <!-- Bestell oder Rechnungsnummer -->
        <div class="form-group">

            <label class="col-lg-5 req" for="userLoginName"></label>
            <div class="col-lg-7">
                <input id="Hund" class="form-control " type="radio" name="Haustier" value="hund"
                       required="required" >Hund
                <div class="help-block"></div>
            </div>

        </div>
    </div>
</div>
<div class="col-xs-12">
    <div class="col-xs-12 col-md-6">
        <!-- Bestell oder Rechnungsnummer -->
        <div class="form-group">

            <label class="col-lg-5 req" for="userLoginName"></label>
            <div class="col-lg-7">
                <input id="katze" class="form-control " type="radio" name="Haustier" value="katze"
                       required="required" >Katze
                <div class="help-block"></div>
            </div>

        </div>
    </div>
</div>

<div class="col-xs-12">
    <div class="col-xs-12 col-md-6">
        <!-- Bestell oder Rechnungsnummer -->
        <div class="form-group">
            <label class="col-lg-4 req" for="userLoginName">Rasse</label>
            <div class="col-lg-8">
                <input id="breed" class="form-control " type="text" name="editval[oxuser__breed]" value="[{$editval.oxuser__breed}]" required="required" >
                <div class="help-block"></div>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-md-6">
        <!-- Bestell oder Rechnungsnummer -->
        <div class="form-group">
            <label class="col-lg-4 req" for="animal_age">Alter des Tieres</label>
            <div class="col-lg-8">
                <input id="animal_age" class="form-control " type="text" name="editval[oxuser__animal_age]" value="[{$editval.oxuser__animal_age}]" required="required" >
                <div class="help-block"></div>
            </div>
        </div>
    </div>

</div>



<div class="col-xs-12">
    <div class="col-xs-12 col-md-6">
        <div class="form-group">
            <label class="[{if $contactFormFields.message.isRequired}]req[{/if}] control-label col-lg-4"
                   for="c_message">
                [{oxmultilang ident="MESSAGE"}]
            </label>
            <div class="col-lg-8 controls">
                <textarea rows="15"
                          cols="70"
                          name="c_message"
                          id="c_message"
                          class="form-control"
                          [{if $contactFormFields.message.isRequired}]required="required"[{/if}]
                >[{$oView->getContactMessage()}]</textarea>
            </div>
        </div>
    </div>
</div>

