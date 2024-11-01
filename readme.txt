=== Sell License Key Codes with WordPress PHP-KeyCodes ===
Contributors: paulvgibbs
Donate link:  https://www.withinweb.com/wordpresskeycodes/donation.php
Tags: license keys, software license codes, key codes, pin codes, sell pin codes, mobile phone pins, digital goods with PayPal
Requires at least: 4.7.0
Tested up to: 5.8
Stable tag: 2.1.6
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Sell software license key codes or pin numbers automatically using PayPal.


== Description ==

This Plugin enables you to sell software license key codes, pin numbers, mobile phone key codes or similar codes, automatically when someone makes a PayPal purchase.

The pin numbers are listed in the database one entry per line and when a purchase is made, PayPal sends an IPN notification to the plugin which then extracts the first pin number, sends it to the purchaser and then removes that pin number from the list.

The email sent to the purchaser contains the pin number, and you should receive a copy of the email.

The sales history listing also identifies which pin number has been sold to the purchaser.

A local test system is included which allows you to test without connecting to PayPal.

Setting a value in the Lower Limit entry box causes an email to be sent to the administrator when the minimum number of key codes has been reached.


== Installation ==

The installation into WordPress is the same as for any plugin as is the procedure for upgrades.

If you have the free version of the plugin, Deactivate and Delete it before you install the Premium version.  Having both the Premium and Free version active should not be an issue but may may cause confusion.

In the admin area of your WordPress site, click on "New Plugin" and then click on "Upload Plugin". Browse for "withinweb-wwkc-keycodes.zip" on your computer and click on "Install now".

Activate the plugin once it has been uploaded and un-zipped.

In the "Settings" of the KeyCodes menu, you must enter in your PRIMARY PayPal email address for payments.

Create an item and enter in the key codes in the key codes field one line at a time.

You may test the system using a local test without connecting to PayPal.

In "Settings" make sure you have selected the PayPal enviromment that you want to use, as either PayPal live or PayPal sandbox.

To display the PayPal button on your WordPress page, use the short code [keycodesbutton recid="x"] where x is the record id of the product item. Or you can use the HTML code displayed from the "Item List" Page. You can get the record id of the product by going to "Item List".

PHP-KeyCodes Settings

Before you create your product items, first go to the "Settings" page.

The PayPal environment can be either Live or Sandbox.  If you are going to use the PayPal Sandbox testing environment, you also need to enter the PayPal Sandbox email address which you will have to set up through the PayPal developer environment.

Note that the PayPal email address you enter into PHP-KeyCodes must be your Primary PayPal email address. You can set up multiple email addresses in PayPal but only the Primary PayPal address will work with the IPN system.

Also note that if you receive a purchase which has a currency that is not the same as your PayPal default currency, then you have to accept the currency code before the transaction is completed.  To overcome this, you can set your PayPal account to accept a range of different currencies.

PayPal activation

Make sure that you have enabled IPN in your PayPal account. You may also have to enter in the IPN Call Back URL which you can get from the "Settings" menu of the plugin.

The call back url is acutally sent to PayPal from PHP-KeyCodes as part of the button submission, which means that the url entered in PayPal setup can be different to the url needed for this plugin.

Hence it is possible to have multiple PayPal IPN systems without any conflicts.

The IPN system (Instant Payment Notification) is the way in which PayPal sends messages to and from PHP-KeyCodes.  PayPal will send out a verified message only when the purchase is complete so you can be sure that no one can make a purchase without correct payment.

Creating your items

The create item page should be self-explanatory.  The codes that you are going to sell go in the "keycodes" text box each one entered a line at a time.  The top key code will be removed when sold so that the next key code is avaiable for the next purchase.

Short codes

The PayPal buttons are created using short codes as follows:
   
[keycodesbutton recid="x" ]
						
where x is the record id of the product item.
		
Place the short code onto any of your WordPress pages.    

The short code options are:

recid
a required entry

custom (optional)
default of blank which can be used for the IPN custom field which you can use to return any information back.

quantity (optional)
default 1				

buttontext (optional)
default of "Buy with PayPal"								
							
buttonclass (otpional)
default of "button-primary"							

tax (optional) - this is a percentage
default of 0

So a full example would be:
						
[keycodesbutton recid="3" buttontext="Buy this at quantity 2" quantity="2" custom="Custom string" tax="20"]
						
You can get the record id of the product by listing the items in "Item List" page.  This page also has the short code displayed.  If you want to use more conventional buttons which can be placed on non WordPress web pages, then use the html code which can also be displayed from the "Item List" page.	

 
== Testing ==

The best way to test the application is to use a second live PayPal account as that tests the complete system.  You have to do this if you want to test live because PayPal does not allow you to purchase from your own account.

To open a second PayPal account you need a second bank account which some people may find difficult but you will find it worth doing in the long run.

You can also use the PayPal Sandbox for testing which requires you work in the developer enviromment <strong>http://developer.paypal.com</strong>.  Login into this using your normal PayPal account.

In the developer environment, you can create as many test acoounts as you want, and then set the PHP-KeyCodes to the sandbox environment.  You also need to enter the sandbox email address into the "Settings" dsplay of PHP-KeyCodes.

Local Test

PHP-KeyCodes has a local test facility which will test all the set up and email details, but does not go through PayPal.

Local test has to be enabled before it can be used by going to the "Settings" display and setting the "Do Local Test" check box.  Once you have finished your local tests, you should un-tick this box.

The local test is useful if you don\'t want to sepend time going in and out of PayPal.

Logging

Enable the debug log in the "Setting" section of PHP-KeyCodes.  This will create a file which details the IPN results and other messages to show the path through the application.
		
		
== Trouble Shooting ==

If you have multiple email addresses in your PayPal account, make sure that the one you use in this application is your PRIMARY PayPal email address.

If your emails are not getting to the customer, use SMTP.  There are a number of WordPress Plugins for this.

Conflicts between Plugins can sometimes occur.  To test this, deactive suspect Plugins.

 
== Changelog ==

= 2.1.6 =
Updated tested to

= 2.1.5 =
Minor Updates

= 2.1.4 =
Added in Tax Code

= 2.1.2 =
Improvements to readme.txt file

= 2.1.1 =
Improvements to IPN system

= 1.0.3 =
Minor text changes

= 1.0.2 =
Main Menu name changed
Correction to name of short code button
Other minor changes


== Upgrade Notice ==

Version 2.1.6
Updated tested to
