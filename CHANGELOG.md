# Changelog

## 3.4.2

- Amended text when an error is thrown in the admin panel while in `live` mode, replaced 'Message' with 'the developer'

## 3.4.1

- Upgraded Medium editor markdown plugin to version 1.2.0, resolves issue where backticks would break the preview

## 3.4.0

- Installed the <a href="https://github.com/daviferreira/medium-editor">Medium</a> WYSIWYG editor with the <a href="https://github.com/IonicaBizau/medium-editor-markdown">the markdown plugin</a>
- Added `WysiwygType` form field which consists the Medium WYSIWYG, and a regular text field which gets populated with the markdown generated by the WYSIWYG
- Added `Richtext` field to override that in Cog to use the `WysiwygType` form field
- Added `Markdown#convert` controller for parsing markdown to HTML to populate the WYSIWYG. Triggered via AJAX when switching from the `Markdown editor` field to the `Preview editor` (WYSIWYG) field
- Added `ControlPanelExtension` form extension to install `WysiwygType` form field
- Added `cp_ext_div_layout` form view file
- Added `wysiwyg.js` to implement Medium WYSIWYG
- Added `wysiwyg_javascript` view to include WYSIWYG javascript

## 3.3.0

- Scrollable dashboard
- Comments icon

## 3.2.0

- Styling for product image deletion and option display

## 3.1.0

- Additional styling for products and refer a friend

## 3.0.0

- Initial open source release