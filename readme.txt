=== Form Builder ===
Contributors: zingiri
Donate link: http://www.zingiri.com/donations
Tags: form, forms, online form builder, web form, html form, survey, surveys, payment, registration, email forms, form builder, form creator, form generator, online form, online forms, web forms, create form, create forms
Requires at least: 2.1.7
Tested up to: 3.8.2
Stable tag: 2.3.1

Create amazing web forms with ease. No scripts, no HTML, no coding. Just drag-and-drop the form elements to create your professional looking form.

== Description ==

Create amazing web forms with ease. No scripts, no HTML, no coding. Just drag-and-drop the form elements to create your professional looking form.

The free version allows creation of one form, the Pro version of the plugin offers unlimited forms.

Note: Form Builder uses web services stored on Zingiri's servers, read more in the plugin's FAQ about what that means.

== Installation ==

1. Upload the `form` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Include the code [form ID] in any page to display the form with the selected ID.

Please visit [Zingiri](http://www.zingiri.com/form-builder/#installation "Zingiri") for more information and support.

== Frequently Asked Questions ==

= This plugin uses web services, what exactly does that mean? =
Web services are simple way of delivering software solutions. Basically it means that the software & data is hosted on our secure servers and that you can access it from anywhere in the world. 
No need to worry about backing up your data, managing systems, we do it for you.

= What about data privacy? =
Zingiri Form Builder uses web services stored on Zingiri's servers. 
In doing so, data entered via the forms you create is collected and stored on our servers. 
Your admin email address, together with the API key listed here above is also recored as as a unique identifier for your account on Zingiri's servers. This data remains your property and Zingiri will not use nor make available for use any of this information without your permission. 
The data is stored securely in a database and is only accessible to persons you have authorized to use Zingiri Form Builder. 
We have a very strict [privacy policy](http://www.zingiri.com/privacy-policy/ "privacy policy") as well as [terms & conditions](http://www.zingiri.com/terms/ "terms & conditions") governing data stored on our servers.

Please visit the [Zingiri Support Forums](http://forums.zingiri.com/forumdisplay.php?fid=59 "Zingiri Support Forum") for more information and support.

== Screenshots ==

Screenshots will be coming soon [here](http://www.zingiri.com/form-builder/ "screenshots").

== Changelog ==

= 2.3.1 =
* Verified compatibility with WP 3.8.2

= 2.3.0 =
* Implemented new payment gateways framework
* Added Paypal Express as checkout option
* Verified compatibility with Wordpress 3.8.1

= 2.2.4 =
* Verified compatibility with WP 3.7.1
* Fixed issue with export function only exporting 16 rows instead of all

= 2.2.3 =
* Fixed issues with sending confirmation emails

= 2.2.2 =
* Fixed issue with form settings checkboxes being saved in 'on' status

= 2.2.1 =
* Verified compatibility with Wordpress v3.6

= 2.2.0 =
* Fixed issue with ajax spinner
* Added multiple check box field
* Upgrade mailing library

= 2.1.1 =
* Fixed issue with UTF-8 encoding
* Added option to change length of email field
* Added spinner to display when doing ajax actions in admin back-end

= 2.1.0 =
* Fixed issue with ordering of columns
* Implemented workaround for IE & Chrome object iteration reordering issue
* Added CSV export functionality for pro users

= 2.0.1 =
* Fixed issue when moving plugin to different domain
* Fixed issue with UTF-8

= 2.0.0 =
* Added payment gateways
* Added Paypal support
* Added invoices
* Added pricing
* Added option to set save button text on forms

= 1.3.2 =
* Checked compatibility with Wordpress 3.5.1

= 1.3.1 =
* Fixed issue with utf-8 encoding
* Fixed issue with saved forms not being listed in form overview

= 1.3.0 =
* Added multiple choice type of field
* Improved use of dropdown field type
* Replace field drop down by a field list
* Improved general usability when creating a form
* Added form settings tab
* Added possibility to set a confirmation message
* Added possibility to set a confirmation email
* Added possibility to send form content to user
* Verified compatibility with Wordpress 3.5
* Added possibility to choose position of field labels (left or top)
* Improved navigation in lists

= 1.2.2 =
* Only display field attributes applicable to the selected field
* Added online help (tooltips) in main form builder interface
* Changed name of 'Select' field to 'Dropdown'
* Added support for tabbed forms
* Added support for regular expressions in text input fields

= 1.2.1 =
* Fixed security issue (thanks to Charlie Eriksen via Secunia SVCRP)

= 1.2.0 =
* Fixed issue with header formatting in control panel
* Fixed various minor issues
* Verified compatibility with WP 3.4.2

= 1.1.7 =
* Updated allowed extensions to 'jpg','bmp','png','zip','pdf','gif','doc','xls','wav','jpeg','docx','ppt','pptx','mp3'
* Fixed issue with wrong endpoint in forms
* Checked compatibility with WP 3.4

= 1.1.6 =
* Fixed issue with password showing mandatory although fields are filled
* Fixed rendering of UTF-8 characters in field labels 
* Fixed issue with checkbox always defaulting to 'on'
* Fixed issue with date defaulting to 1970
* Updated css to force removal of bullets in form display
* Removed form edit link in front end
* Default column names to upper case
* Post fix column names with id for uniqueness
* Added verification on duplicate column names
* Fixed issue with US vs Europe date formats for date field
* Added time picker widget to Time element type
* Removed europe_date element type and added option to select date format (US or Europe) to date element type

= 1.1.5 =
* Updated readme.txt and settings page regarding the use of web services and data privacy policy

= 1.1.4 =
* Replaced protoype/scriptaculous scripts with jQuery scripts
* Fixed issue with date elements
* Added uninstall hook
* Remember API key after deactivation
* Removed rules from control panel (for now)

= 1.1.3 =
* Fixed issue with http class not parsing post variables properly
* Removed loading of news feed

= 1.1.2 =
* Only load admin javascript and styles on Bookings pages

= 1.1.1 =
* Fixed issue with url encoding

= 1.1.0 =
* Added new submit button with option to email the form's content
* Fixed issue with textarea with editor
* Checked compatibility with Wordpress 3.3.1

= 1.0.4 =
* Added jQuery UI library
* Added styling for date picker

= 1.0.3 =
* Fixed issue with html area and captcha elements

= 1.0.2 =
* Fixed issues with single and multiple file upload elements
* Fixed issues with single and multiple image upload elements

= 1.0.1 =
* Updated readme file

= 1.0.0 =
* First release