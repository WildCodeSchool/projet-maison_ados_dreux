=== Plugin Name ===
Contributors: Rustaurius, EtoileWebDesign
Tags: frequently asked questions, FAQ, FAQs, easy FAQ, knowledge base, simple FAQ, FAQ categories, FAQs, knowledgebase, answer, answers, faq page, FAQ Plugin, help, help desk, helpdesk, questions, wordpress faq, FAQ list, FAQ accordion, custom post type with accordion, faq list, faq with accordion, jquery ui accordion, jquery-ui, shortcodes, wordpress, WordPress Plugin, Categories, social media, facebook, widget, widgets, statistics, AJAX, analytics, responsive, submit questions, WooCommerce, WooCommerce FAQ, WooCommerce Tab, WooCommerce product page
Requires at least: 3.7.0
Tested up to: 4.4.2
License: GPLv3
License URI:http://www.gnu.org/licenses/gpl-3.0.html

A simple FAQ plugin that lets you create, order and publicize FAQs, insert 3 styles of FAQs on a page or WooCommerce product, and use AJAX FAQ search using shortcodes

== Description == 

<a href='http://www.etoilewebdesign.com/ultimate-faq-demo/'>FAQ Demo</a>

[youtube https://www.youtube.com/watch?v=GOuiqcF9DTQ]

Create, organize and publicize your Frequently Asked Questions in no time through your Wordpress Admin Panel with the incredibly easy-to-use, responsive Ultimate FAQ plugin. You can use either the accordion FAQ style, to display one answer on click, or the list FAQ style, to have answers displayed by default. Features include statistics on how many times FAQs have been viewed, styling options, categories and tags, display and ordering options among many others. 

Want to decide exactly what order your FAQs are displayed in? Use our simple drag-and-drop reordering feature! Create SEO-friendly links to individual FAQ posts to simply direct customers to exactly the right answer, right away. You can even let your customers add to your FAQ list with the [submit-question] shortcode, which lets visitors submit a question and even propose and answer for it! Easily add links to popular social media, such as Facebook, Twitter and Pinterest so that your customers can help you spread the word!

Using WooCommerce to sell your products? Easily add an "FAQ" tab to each product page, so your customers can see answers to common questions about the products they're browsing. 

Great for combining with our <a href='https://wordpress.org/plugins/front-end-only-users/'>user management plugin </a> to create a member's only FAQ area.


= Key Features =

* Create FAQ categories
* Create FAQ posts and assign to categories to them
* An AJAX search form
* Export all FAQs to a PDF to create a user manual
* Insert custom CSS to style your FAQ posts
* Select animation options for displaying FAQ posts
* Toggle accordion (close open FAQ when a new one is opened) behaviour on/off
* Share on social media 

= Premium features include =
* WooCommerce FAQ tab on product page
* Different display styles
* User-submitted FAQs
* AJAX FAQ search with autocomplete for titles
* Import/Export of FAQs from spreadsheet
* Export FAQs to PDF
* SEO-Friendly FAQ, category and tag permalinks
* Advanced styling options
* Re-ordering of FAQs

= Shortcodes =

* [ultimate-faqs]: display all FAQs, or only specific categories using include_category and exclude_category attributes (both take a comma-separated list of category slugs)
* [popular-faqs]: displays a number of the most viewed FAQs (5 unless specified).
* [recent-faqs]: displays a number of the most recently added FAQs (5 unless specified).
* [select-faq]: display specific FAQ posts, using the attributes faq_name, faq_slug and faq_id which take comma-separated lists of post names, slugs and ids respectively.
* [ultimate-faq-search]: display a search form that allows users to find FAQs with a specific string in the title or body of the post (premium).
* [submit-question]: display a form that allows users to submit FAQs of their own and, if enabled, enter an enter to their submitted question as well (premium).

Check out our Frequently Asked Questions here:
<https://wordpress.org/plugins/ultimate-faqs/faq/>

Please head to the "Support" forum to report issues or make suggestions:
<https://wordpress.org/support/plugin/ultimate-faqs>



--------------------------------------------------------------




== Frequently Asked Questions ==

= How do I get my FAQs to show up on my page? =

Try adding the shortcode [ultimate-faqs] to whatever page you'd like to display it on. 

= What are the current FAQ shortcodes? =

* [ultimate-faqs]: display all FAQs, or only specific categories using "include_category" and "exclude_category" attributes (both take a comma-separated list of category slugs) 
* [popular-faqs]: displays a number of the most viewed FAQs (5 unless specified).
* [recent-faqs]: displays a number of the most recently added FAQs (5 unless specified).
* [select-faq]: display specific FAQ posts, using the attributes “faq_name”, “faq_slug” and “faq_id” which take comma-separated lists of post names, slugs and ids respectively.
* [ultimate-faq-search]: display a search form that allows users to find FAQs with a specific string in the title or body of the post. (Premium)
* [submit-question]: display a form that allows users to submit FAQs of their own and, if enabled, enter an enter to their submitted question as well (premium).

= What attributes does the [ultimate-faqs] shortcode accept? =

The shortcode accepts two attributes, “include_category? and “exclude_category”, both take a comma-separated list of category slugs. For example, to include only FAQs about the Category "Cars" (which has a slug "cars"), you would use"

[ultimate-faqs include_category='cars']

= Can I hide my FAQ categories? =

Yes, you can choose to display or hide categories on the FAQ settings page.

= Is it possible to re-order my FAQs? =

Currently you can choose between ascending or descending ordering by either Title, Date Created, or Date Modified. 

With the premium version, you can use the drag and drop ordering table to set exactly the order you want.

= How can I make my FAQs sharable over social media? =

On the FAQ settings page you can choose to link to twitter, facebook and more!

= How do I make my FAQs searchable? =

You can use the shortcode, [ultimate-faq-search], which displays an AJAX search form. You can use the "Auto-Complete Titles" option to have a list of all matching questions pop up when a user has typed 3 or more characters.

= How do I customize my FAQs, for example, to change the font? =

You can customize the plugin by adding code to the Custom CSS box on the settings page, go to the "Custom CSS" box. For example to change the font you might want to add something like:

.ufaq-faq-title h4, .ufaq-faq-category-title h4  {font-family:"Palatino Linotype", "Book Antiqua", Palatino, serif;}

You can also use the "Styling" area of the "Options" tab if you're using the premium version, which has a bulit-in color picker for color fields and more!

For more questions and support you can post in the support forum:
<https://wordpress.org/support/plugin/ultimate-faqs>

= Videos =

Video 1 - Shortcodes and Attributes
[youtube https://www.youtube.com/watch?v=zf-tYLqHpRs]

Video 2 - Categories and Tags
[youtube https://www.youtube.com/watch?v=ZV4PM0M1l7M]

Video 3 - FAQs Ordering
[youtube https://www.youtube.com/watch?v=3gVBuCo7bHU]


== Screenshots ==

1. Car FAQ demo page - Default display style
2. Example of the "Color Block" FAQ display style
3. Example of the "Block" FAQ display style
4. The AJAX FAQ search shortcode in use
5. Simple user submitted FAQs form
6. Admin area showing all FAQs with their number of views as well as their categories
7. Admin simple drag-and-drop FAQ ordering area
8. Responsive, simple styling options with precise color controls
9. All answers displayed in the 'list' FAQ mode

== Changelog ==
= 1.2.3 = 
- Added in WPML support for the main shortcodes
- Fixed an error with a missing ID tag

= 1.2.2 =
- Added the ability to have multiple widgets or shortcodes on a page
- Fixed a small error with the popular FAQs shortcode

= 1.2.1 =
- Fixed a missing file error

= 1.2.0 =
- Added in a new premium feature: WooCommerce FAQs tab, which lets you add a different list of FAQs on the product page for each WooCommerce product
- Added the ability to filter FAQs by category

= 1.1.19 =
- Added another set of labeling options

= 1.1.18 =
- Minor CSS update

= 1.1.17 =
- Added anumber of extra labeling options

= 1.1.16 =
- Minor CSS update

= 1.1.15 =
- CSV files can now be used for FAQ imports

= 1.1.14 =
- Fixed a parent-child issue, where if a parent category was added in the include_category attribute, it was possible to end up with unexpected categories when an FAQ was in multiple categories

= 1.1.13 =
- Fixed a number of PHP notices

= 1.1.12 =
- Minor CSS update

= 1.1.11 =
- Fixed a missing div error that could come up with certain options selected

= 1.1.10 =
- Minor CSS update

= 1.1.9 =
- Added a "Category Toggle" options for users who group their FAQs by category
- Cleaned up some of the code dealing with options

= 1.1.8 =
- Fixed a scrolling error with non-FAQ links

= 1.1.7 =
- Should fix small problems with the "select-faq" shortcode and the "FAQ ID List" widget

= 1.1.6 =
- Removed blank categories from FAQ search results
- Added in a "Permalink Type" option, which lets you decide between linking to the post in the main page and the individual post page

= 1.1.5 =
- Fixed a problem with FAQ permalinks not opening in the list

= 1.1.4 = 
- Fixed a conflict with WooCommerce, where a UFAQ script was keeping product information tabs open

= 1.1.3 =
- Fixed a problem where links inside of a toggable FAQ weren't clickable

= 1.1.2 =
- Added a color picker to the color fields on the styling options area

= 1.1.1 =
- Added 'display_all_answers' as a shortcode attribute, so some pages can have all answers displayed and others can have the started list style
- Fixed an option mistake

= 1.1.0 =
- Added new premium display styles
- Added an autocomplete titles option for the AJAX search shortcode
- Added more styling options to customize new display styles
- Added an option to add a 'Back to Top' link to each FAQ post
- Fixed a reveal error with non-accordion display and no effect selected

= 1.0.9 =
- Added some CSS classes in preparation for an upcoming large CSS/styling update

= 1.0.8 =
- Added a unique identifier to each FAQ so that if it is repeated on the same page, the correct post should open

= 1.0.7 =
- Minor CSS update

= 1.0.6 =
- Fixed an issue where new users weren't able to update ordering even after upgrading to premium

= 1.0.5 =
- Minor CSS update

= 1.0.4 =
- Fixed a status error message

= 1.0.3 =
- Added in widgets to display a number of popular or recently created faqs

= 1.0.2 =
- CSS update for the ordering table in the admin area

= 1.0.1 =
- Fix for the FAQ ordering bug

= 1.0.0 =
- Premium version release, check out our website for all of the details <http://www.etoilewebdesign.com/ultimate-faq/>