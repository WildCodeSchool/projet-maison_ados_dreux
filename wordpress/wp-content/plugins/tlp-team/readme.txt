=== TLP Team ===
Contributors: techlabpro1
Donate link:
Tags: team, wp team, team members profile, our team, team members, team plugin, wp team plugin, responsive team plugin, team showcase, team member display, wp team member
Requires at least: 4
Tested up to: 4.4
Stable tag: 1.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Fully Responsive and Mobile Friendly Team member display plugin for wordpress with Grid and Isotope View.

== Description ==

[Plugin Demo](http://demo.radiustheme.com/wordpress/plugins/tlp-team/) | [Documentation](
https://radiustheme.com/how-to-setup-configure-tlp-team-free-version-for-wordpress/) | [Get Pro Version](https://radiustheme.com/tlp-team-pro-for-wordpress/)


TLP Team is a fully Responsive Grid and Isotope view plugin that display your team members. From admin end you can easily add your team member(s). You can set primary color, image size and detail page (Yes/No) link. It has widget included with settings how many want to display. It has the following fields members name, position, image, short bios and detail bios and social links.

It is HTML5 & CSS3 base and full object oriented (OOP) coding. Display your team member profile with Grid view and Isotope View using our shortCode and widget. It comes with 4 default layouts in shortcode you can call layout like layout="1" / layout="2" / layout="3" / layout="isotope".

= Total 4 Layouts =
* **Grid View 1:**
<code>[tlpteam col="4" member="8" orderby="title" order="ASC" layout="1"]</code>
* **Grid View 2:**
<code>[tlpteam col="2" member="8" orderby="date" order="ASC" layout="2"]</code>
* **Grid View 3 (Rounded Profile image):**
<code>[tlpteam col="2" member="8" orderby="title" order="ASC" layout="3"]</code>
* **Isotope View with Name and Designation Sorting:**
<code>[tlpteam col="4" member="8" orderby="title" order="ASC" layout="isotope"]</code>

= For use template php file:- =
<code><?php echo do_shortcode('[tlpteam col="4" member="8" orderby="title" order="ASC" layout="1"]'); ?></code>
<code><?php echo do_shortcode('[tlpteam col="2" member="8" orderby="date" order="ASC" layout="2"]'); ?></code>
<code><?php echo do_shortcode('[tlpteam col="2" member="8" orderby="menu_order" order="ASC" layout="3"]'); ?></code>
<code><?php echo do_shortcode('[tlpteam col="4" member="8" orderby="menu_order" order="ASC" layout="isotope"]'); ?></code>

= Features =
* Fully Responsive & Mobile Friendly
* 4 Different layouts (3 Grid and 1 Isotope)
* Custom meta filed
* ShortCode
* 3 types of ordering title/ date/ menu_order
* Image Size option
* Widget
* Primary color control
* Detail link (Yes/No)


= Fully translatable =
* POT files included (/languages/)

= Available fields =
* Title/Name (Post Title)
* Description (Post Content)
* Short Bio (Custom field)
* Designations (Custom field)
* Email (Custom field)
* Personal Web URL (Custom field)
* Telephone (Custom field)
* Location (Custom field)
* Social links (FB, Twitter, LinkedIn, Youtube and Google+ )

= Shortcode settings =
* **Short Code:**
<code>[tlpteam col='2' member="8" orderby="title" order="ASC" layout="1"]</code>
* **col =** The number of column you want to display (1, 2, 3, 4)
* **member =** The number of the member, you want to display
* **orderby =** You can order by three ways (title, date, menu_order) here menu_order is custom order
* **order =** ASC, DESC
* **layout =** 1,2, 3, isotope

= Pro Features =
* Total 15 Layouts (Grid, Table, Isotope & Carousel).
* 40+ Layout variation.
* Unlimited ShortCode Generator.
* Visual Composer compatibility.
* Drag & Drop ordering.
* Unlimited color.
* All fields control show/hide.
* All text size, color and text align control.
* Square / Rounded Image Style.
* Grid with Margin or No Margin.
* Social icon, color size and background color control.
* Detail page with Popup and Next Preview button.
* Skill fields with progress bar.
* Pagination (You can set how many show per page).
* Member Display by Department(s) wise.
* Specific Member(s) Display (Select by name in ShortCode)

[Get Pro Version](https://radiustheme.com/tlp-team-pro-for-wordpress/)

For any bug or suggestion please mail us: support@techlabpro.com

== Installation ==

Install and Activate

1. Unzip the downloaded 'tlp-team' zip file
2. Upload the 'tlp-team' folder and its contents into the 'wp-content/plugins/' directory of your WordPress installation
3. Activate 'tlp-team' from Plugins page

== Implement ==

1. Short Code : [tlpteam col='4' member="8" orderby="title" order="ASC" layout="1"]
2. col = The number of column you want to create
3. member = The number of the member, you want to display
4. Go to the wp admin Widget page.
5. Drag and drop "TPL Team" into active sidebar.

= Requirements =
* **WordPress version:** >= 4.3
* **PHP version:** >= 5.2.4


== Frequently Asked Questions ==

Q : How can I add a widget to my sidebar?
A : Go to the Appearance -> Widgets -> Choose a "TPL Team"  and drag it to the sidebar where you wish it to appear.

== Screenshots ==

01. Layout 1
02. Layout 2
03. Layout 3
04. Widget
05. Plugin Settings
06. Widget Settings
07. Layout Isotope


== Changelog ==

= 1.3 =
* Update layout
* Fix some bug
* min height fixed

= 1.2 =
* Add new Sorting layout
* Fix some coding and layout bug
* And Extra setting for controlling color and detail link.


= 1.1 =
* Improve Responsive Layout.
* Added 2 more Layout.
* Widget Layout improve.
* Custom CSS option add.
* 2 more social icons added (youtube and google+)

= 1.0 =
* Initial load of the plugin.
