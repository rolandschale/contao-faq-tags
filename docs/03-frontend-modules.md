# Frontend modules – FAQ Tags

1. [Installation](01-installation.md)
2. [Configuration](02-backend-ui.md)
3. [**Frontend modules**](03-frontend-modules.md)

## Overview

The bundle supports all genuine Contao FAQ frontend modules out of the box. In addition to that, it comes with a new
`FAQ tag list` frontend module.

Please note that this bundle overrides the genuine Contao FAQ modules and may not work with other FAQ-related extensions!

## FAQ tag list module

This frontend module generates a list of tags used by FAQ items in the selected categories. The list can be sorted in 
several different ways. You can also limit the list of tags to a certain number of displayed records.

You should also specify the FAQ tags target page, which contains the FAQ list or page module that can display filtered 
results when clicking on a tag.

## Filtering listing modules

The genuine Contao FAQ modules work the same way as before. You can optionally enable filtering by tags in the listing 
modules such as FAQ list or FAQ page modules by checking the "Allow filtering FAQ by tags" checkbox. It works great 
with a combination of the FAQ tag list module.

## Displaying tags in modules

To display tags in the frontend, you have to be sure that the following conditions are met:

1. the "Show FAQ tags" checkbox is checked;
2. you have selected the appropriate module template (e.g., mod_faqpage_tags for FAQ page module).

You can also use your custom module templates, just don't forget to insert the `faq_tags` partial template inside.

## Editing the tag list markup 

To edit the displayed tag list markup, simply create a custom template `faq_tags`, and edit it there.
