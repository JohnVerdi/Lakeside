StreamlineCore Usage
====================

In addition to the eCommerce functionality, the StreamlineCore plugin also
provides a number of ways that your site can include content directly from the
Streamline server. This content can be include in WordPress Widget areas using
the provided widgets. Alternately Shortcodes can be embedded as content within
your pages and posts to include information directly from the Streamline server.

Widgets
-------

Streamline Core plugin includes several widgets that can be dragged to any
available sidebar. These include:

### ResortPro Testimonial - shows property reviews

Via the Widget panel, the following can be specified:

\- Title - Displayed at the top of the results

\- Number of Reviews - Number of Testimonials to show

\- Minimum Points - Lowest rating to show

\- Maximum Points - Maximum rating to show

\- Cache for ... Minutes - Number of minutes wait before refreshing results

 

### StreamlineCore Search Widget - Search Properties with configurable fields

Use button on the Widget panel to drag available fields from the available
fields from the right to the Fields to be Displayed (on the left). There is a
dropdown next to each field on the left. Click the dropdown arrow to reveal
setting for the field, including the label, display width (on a scale of 1 to 12
where 12 is the widest), as well as labels for the field. IMPORTANT - be sure to
include the Search Button in the Displayed fields.

 

### StreamlineCore Map Search - Display properties on a Google Map

We strongly suggest that you NOT use this widget other than on the Search
Results page. At this time, the Map Search widget will not work except on the
Search Results page.

 

### StreamlineCore Filter Widget - Filter the properties on a Search Results page

We strongly suggest that you NOT use this widget other than on the Search
Results page. At this time, the Map Search widget will not work except on the
Search Results page.

 

### StreamlineCore Featured Property - Show selected properties

Via the Widget panel, the following can be specified:

\- Title - Displayed at the top of the results

\- Number - Number of properties to display

\- Ids - Limit results to these properties (rotate results if greater than
Number)

\- Template - Use the template to display results (place in child theme in
streamline\_templates folder)

\- Cache - Number of minutes between updates from the Streamline server

 

Shortcodes
----------

 

In addition to some structural shortcodes used to identify pages on which to
insert results from the Streamline services, there are two shortcodes that can
be used to highlight details from content stored on the Streamline system.

 

### [streamlinecore-featured-properties number="3" ids="9,22,88"]

Accepts two parameters:

number - Number of properties to include in the results

ids - Units to be included in the results

 

### [streamlinecore-testimonials number="4" min\_points="4"]

Accepts two parameters:

number - Number of feedbacks/reviews to return

min\_points = Lowest number review to return (as a whole number)
