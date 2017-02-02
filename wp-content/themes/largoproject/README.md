# Setup instructions for homepage signup form

Prereqs:

- Gravity Forms developer API key
- Gravity Forms plugin
- Gravity Forms MailChimp Add-On plugin
- MailChimp API key for INN

For development purposes, download the two plugins from production and activate them. The MailChimp Add-On will need to be activated from the Gravity Forms Add-Ons menu once the Gravity Forms API key has been entered. A personal MailChimp API key is acceptable for testing purposes.

Form Setup

1. In Gravity Forms, create a new form:
	1. From "Advanced Fields", choose "Email"
		- General > Field label: "Email address"
		- Appearance > Placeholder: "Email address"
		- Appearance > Field Size: "Medium"
	2. Press the "Update Form" button
	3. Under "Settings", Make these changes:
		- Form Settings
			- Button Text: "Subscribe"
		- Confirmations: (Optional, but should be configured on production)
			- Page:
				- Choose a "Newsletter thanks" page
		- MailChimp (does not need to be configured for testing purposes)
			- Add a new feed
			- Choose a MailChimp List for the feed to be sent to
			- Map the "Email address" form field to the "Email Addresss" field
			- Leave the "First Name" and "Last Name" fields unmapped
			- Check the "Double Opt-In" box
			- Add a note to be added to the user's subscription, if desired
2. In Appearance > Widgets, add a "Form" widget to the "Homepage Newsletter Slot" widget area, and select the form that you created. Save the widget.
3. Go to the homepage and observe the new form.
