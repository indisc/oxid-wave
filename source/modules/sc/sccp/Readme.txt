==Title==
SinkaCom CreditPlus Financing

==Author==
SinkaCom AG

==Prefix==
sccp

==Version==
0.6.13

==Link==
http://www.creditplus.de/
http://www.sinkacom.de/

==Mail==
info@sinkacom.de

==Description==
The CreditPlus Module offers an easy integration into your OXID Shop System 4.7 and later.
There is a payment method called "Financing" which is added if you follow the instructions below.
You can adjust some minor features such as displayed financing months.
This plugin further adds the smallest allowed rate to the details page
and inserts the basket calculation on the payment page right below the description for the payment type.

For one of the backends features (filtering articles by category) this module requires a correctly indexed category table.
It does work without it, just one of the assignment windows is a bit off then.

==Extend==
account_order
-- Adds CSS and "Pay Now" URLs to the Order History in "My Account"

module_config
-- Adds logical check to minimum rate

oxarticle
-- Adds rate table logic to article itself, provides calculations for entire baskets as well

oxbasket
-- Provides rate table logic to basket based on articles in it

oxcmp_basket
-- Adds logic for payment method exclusion

oxpaymentgateway
-- Handles payment provider communication or redirects to finishing page after setting the transaction id
-- Also handles payment provider URL creation

oxpaymentlist
-- Adds exclusion handling to remove payment method if not allowed (by articles or by rejection)

oxorder
-- Provides cancel features to promote these to the payment service
-- Also handles information retrieval from payment service

oxorderarticle
-- Handles singular product returns

order
-- Provides minor functionality if payment is placed to appear before finishing the order

details
-- Provides CSS implementation and financing table functions for older OXID versions

oxwarticledetails
-- Provides financing table functions for newer OXID version

order_main
-- If order is sent (delivery started), this updates the corresponding order on the payment service

payment
-- Provides CSS implementation for the rate table taken from oxbasket

thankyou
-- Provides CSS implementation and additional parameters for the block inserted into the final page

basket
-- Provides CSS implementation for the rate table taken from oxbasket

oxemail
-- Provides a Retry URL for the template (to be able to finish it later, if the user closes the browser)

==Installation==

This module is for OXID Versions 4.7.x and later ONLY. Do not attempt to install it on 4.5.x Systems.
This is considered the Quick Start Guide, as you will have it up and running in about 45 minutes.

1. Extract the module package.

2. Copy the content of the folder "copy_this" into your shop root-folder (where config.inc.php lies).

3. Transfer the content of the folder "changed_full" belonging to your shop version into the shop.
	If there have been changes in the shop referring to the files listed in "changed_full you have to merge these files manually.
	You will find comments with "SCCP start" at the beginning, and "SCCP end" at the end of each modified block.

4. If you are using Oxid 4.7.0 or higher go to Extensions -> Modules,
	select the "CreditPlus Finanzierung" extension
	and press the "Activate" Button in the "Overview" tab.
	
5. Once this is done, go to the settings tab, open the "Features" part and update the listed settings:
	1. "Dealer ID" is given to you by CreditPlus.
		Does not have a default value.
	2. "Display type" is preferrably Popup, you can try running it in an iframe,
		though the space required for this display type is too wide and will not look good.
		Defaults to "Popup"
	3. "Minimal Rate" is the smalles accepted rate. This needs to be at least 25.00 and
		will increase itself to this if input was smaller
		Defaults to 25.00
	4. "Payment time" is when the payment process will jump in. It can be either before or after the order is completed.
		Defaults to "After finishing the order"
	5. "Shared Secret" is the value which is used to sign the data on the payment services side.
		This needs to be set in equally on both sides (shop and payment service).
	6. "Basket financing" - The rule used for basket calculations. The options are "Highest interest", "Weighted price", "Highest amount", "Lowest interest"
		"Weighted price" uses the product which makes the highest singular part of the baskets total sum. Sorted by (product price * amount) descending first and interest ascending after that.
		"Highest amount" uses the product which is the most used product. Sorted by amount descending first and interest ascending after that.
		Defaults to "Weighted price"

6. Execute the installation-/update-script - For doing that, open your browser at: http://www.myoxidshop.com/index.php?cl=sinkacom_creditplusmodule_installcontroller.
	Swap out http://www.myoxidshop.com/ with your URL.
	If your URL is https://shop.homeland.com/v4/ then the URL should look like this:
	https://shop.homeland.com/v4/index.php?cl=sinkacom_creditplusmodule_installcontroller
	The core tables remain untouched, so you don't have to update the views.

7. Go to Backend -> Shop Settings -> CP Product groups
	Create new product groups as you see fit. They need to be created according to the
	groups creating your portfolios on CreditPlus' side.
	Follow the instructions on the screen for further guidance.

8. Go to Backend -> Shop Settings -> CP Interest table
	This is the main table which needs to be filled. Each row represents one collection of months and interest.
	The product code is used for sorting the table, afterwards it is sorted by months.
	You can activate and deactivate interest rows as you see fit.
	Also you can assign the product groups from before to these.
	Follow the instructions on the screen for further guidance.

9. Go to Shop -> Payment methods and configure the payment "CreditPlus Financing"
	1. Set the correct minimal loan amount
	2. Assign the correct user groups
	3. Assign the correct countries for this payment

10. Go to Shop -> Shipping options and add "CreditPlus Financing" to the shipping options you want to use with this payment.

