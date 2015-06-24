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

WordPress Timebank System is a Wordpress plugin that creates an exchange system (minutes, hours, tokens, any currency) for all your Wordpress users.

All registered user can see their balance on the sidebar, status and total transfers. 

By entering to the profile of any user you can see the list of his exchanges, the status of transfers, rating and comments of each exchange. 
All users can rate and review the product or services. 
You can create new transfers, accept or reject incoming transfers, rate and comment transfers that affects you.


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

Once installed the plugin you can activate from your widget panel the sidebar called “Timebank -> Options”.
You have to create a page for the Timebank system adding this line of code in the content: [timebank_exchange]
COPY the url of your timebank page that you just created and go to “Admin -> timebank -> Configuration”, and PASTE it on the first line “Absolute URL to your Timebank Page Path”

Recommendations:
To improve SECURITY use of the plugin WP SSL to encrypt the information between client – server
Install the WP-SMTP plugin to guarantee a correct function of automatic email sending
We also recommend to install the 5 plugins that you can find at http://time-bank.info/demo/


== Frequently Asked Questions ==
 
The purpose of this timebank is to achieve an alternative and reliable exchange without money, but with time ;)
We supose that the admins of the timebank will never win time (or money).
But, if the admins want to win time because of their tasks, they can create a “manager” user for the timebank.
This user have exactly the same characteristics as the other users.

Actually, we do not consider to apply certain percentage to exchanges that could receive the “manager” user. (As normal banks does on money transactions)

Is not possible for an administrator to alter the balance of any user. Therefore, the administrator can manage transactions but never modify the amounts.

It’s important to understand that timebank is an exchange system but has no relation with the way your wordpress website promotes the use of timebank between users.
In the WordPress demo page we have created a system where WordPress users can publish offers and demands in the front end page; but it’s only an example; not the timebank system.
In the WordPress + BuddyPress demo you will find a better approach to a built-in timebank System because of buddyPress plugin system.

== Screenshots ==
1. Front end - Home
2. Front end - Request
3. Front end - Sidebar
4. Backend - Users
5. Backend - Configuration
6. Backend - Exchanges


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

= 1.56 =
* Fixes: Hook creation for new wordpress user or new wordpress install

= 1.57 =
* Posibility to Request or Send on the same window (new exchange)
* Update: Language translation ready
* CSS Improvements

= 1.572 =
* RateIt (stars) working again

== Upgrade Notice ==

= 1.5 =
No upgrade notices