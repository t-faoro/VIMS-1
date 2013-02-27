VIMS
====
I have gotten the initial load up on GitHub, fought with it for a little while but it's actually not that bad.
There are a few things that i've done here so I'll do my best to explain them and I'll answer any questions you have in person.
I will do my best to give you guys a brief explanation of what I have going here.

//::=========================================================================================================================

[Folder] Images
	  --> Contains any images that the application may require.

[Folder] Include
	  --> Any externally declared classes, functions, and scripts go into this folder

[Folder] Snippets
	  --> Any small pieces of code to be included go in here

[Folder] Style
	  --> All stylesheets will be placed in here, functions are created to add them to the site

[Folder] VIMS <== DISREGARD!!

	== Config.php
		- All variables used by the application are declared.
		- All included external files are declared.
		- Any Constants such as file paths are declared.
	
	== Index.php
		- Content Block code is handled.
		- At this time, several functions are declared to attempt to make things easier.
		- Commenting is vast, if any changes are made please document appropriately. (Javadoc format)

//::=========================================================================================================================

I invite you to read through the comments made through the application to assist in understanding. Comment a plenty, even if 
the function seems trivial we must document sufficiently in english terminology.