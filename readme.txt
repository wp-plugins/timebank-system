=== Plugin Name ===
Contributors: iproject.cat
Donate link: http://www.time-bank.info
Tags: timebank, Wordpress
Requires at least: 3.6
Tested up to: 4
Stable tag: 1.53
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

The Wordpress timebank System. Exchange time between wordpress users securely. 

== Description ==

Link info: http://www.time-bank.info

This plugin allows to exchange time between your wordpress users securely. Similar to a banking system!

Sidebar : Any registered user can see his balance on the sidebar (time in minutes), status, total transfers, total votes on transfers, alerts. You can also change your bank-time account info. timebank-sidebar

By entering to the profile of any user you can see the list of exchanges of that user. 
The status of transfers, rating and comments of each exchange. 
All users can rate and review the product or services. (Kind of eBay). 
If you enter to your user profile, you can create new transfers, accept or reject transfers, comment and rate transfers.


== Features ==

Transparency: All users can see historical purchases and sales of any other user

Security: WordPress user system security (user + encrypted password session)

All exchange’s has positive / negative evaluations and comments of the buyer

The administrators can see all the exchange from admin zone and can block users

Limits of positive balance (for leverage) and negative (credit) configurable for every user

Based on the functioning of credit cards but applied to mobile devices and computers

Sidebar with information of user, statistics, etc.

100% BuddyPress compatible

Automatic mailing system when exchanges are created, accepted, rejected



== Installation ==


Wordpress Installation:

    Once installed the plugin you can activate from your widget panel the sidebar called “Timebank -> Options”.
    You have to create a page for the Timebank system adding this line of code in the content: [timebank_exchange]
    COPY the absolute url of your timebank page that you just created and go to “Admin -> timebank -> Configuration”, and PASTE it on the first line “Absolute URL to your Timebank Page Path (Permalinked)”


Wordpress + Buddypress Installation:

    Once installed the plugin you can activate from your widget panel the sidebar called “Timebank -> Options”.

Recommendations:

    To improve SECURITY use of the plugin WP SSL to encrypt the information between client – server
    Install the WP-SMTP plugin to guarantee a correct function of automatic email sending
    We also recommend to install the 5 plugins that you can find at http://time-bank.info/demo/



== Frequently Asked Questions ==

== Screenshots ==

== Changelog ==

= 0.1 =
* Starting! 

= 0.21 =
* Little improvements for all themes compatibility

= 1.0 =
* Fixes / errors

= 1.4 =
* BuddyPress compatible

= 1.5 =
* Ajax Improvements + Ajax security nonce

= 1.51 =
* Fix warning on wordpress 4

= 1.52 =
* Configurable currency (minutes, hours, tokens, €, $...)
* Minor fixes

= 1.53 =
* Sidebar improvements

= 1.54 =
* The text of the automatic email is now configurable from backend

= 1.55 =
* Fancybox removed. Native Wordpress lightbox applied (thickbox)
* Backend minor fixes

== Upgrade Notice ==

= 1.5 =
No upgrade notices