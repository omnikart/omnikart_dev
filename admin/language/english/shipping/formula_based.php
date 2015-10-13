<?php
//==============================================================================
// Formula-Based Shipping v201.1
// 
// Author: Clear Thinking, LLC
// E-mail: johnathan@getclearthinking.com
// Website: http://www.getclearthinking.com
// 
// All code within this file is copyright Clear Thinking, LLC.
// You may not copy or reuse code within this file without written permission.
//==============================================================================

$name = 'Formula-Based';
$type = 'Shipping';
$version = 'v201.1';

//------------------------------------------------------------------------------
// Heading
//------------------------------------------------------------------------------
$_['heading_title']						= $name . ' ' . $type;
$_['heading_welcome']					= 'Welcome to ' . $_['heading_title'] . '!';
$_['help_first_time']					= '
Some things to note before you get started:
<br /><br />
<ul>
	<li>For help with any setting, make sure the "Tooltips Enabled" setting is on. You can then hover over any field to get help with what it means and how to use it.</li><br />
	<li>All settings are automatically saved when changed. You can tell they are being saved when they turn yellow, signaling that they cannot be edited while they are recorded to the database.</li><br />
	<li>To create a manual backup of all your current settings, click the "Backup Settings" button. You can then restore from this backup at any time using the "Restore Settings" button. You can also restore from the automatic backup that is created every time the extension is opened, in case you want to go back to the settings as they were when you loaded the page.</li><br />
	<li>Backups are in tab-separated format, so you can easily edit them in a spreadsheet application. For help with creating new rules when using a spreadsheet application, visit http://www.getclearthinking.com/tutorials#editing-backup-files</li>
</ul>
';

// Backup/Restore Settings
$_['button_backup_settings']			= 'Backup Settings';
$_['text_this_will_overwrite_your']		= 'This will overwrite your previous backup file. Continue?';
$_['text_backup_saved_to']				= 'Backup saved to your /system/logs/ folder on';
$_['text_view_backup']					= 'View Backup';
$_['text_download_backup_file']			= 'Download Backup File';

$_['button_restore_settings']			= 'Restore Settings';
$_['text_restore_from_your']			= 'Restore from your:';
$_['text_automatic_backup']				= '<b>Automatic Backup</b>, created when this page was loaded:';
$_['text_manual_backup']				= '<b>Manual Backup</b>, created when "Backup Settings" was clicked:';
$_['text_backup_file']					= '<b>Backup File:</b>';
$_['button_restore']					= 'Restore';
$_['text_this_will_overwrite_settings']	= 'This will overwrite all current settings. Continue?';
$_['text_restoring']					= 'Restoring...';
$_['error_invalid_file_data']			= 'Error: invalid file data';
$_['text_settings_restored']			= 'Settings restored successfully';

// Buttons
$_['button_tooltips_enabled']			= 'Tooltips Enabled';
$_['button_tooltips_disabled']			= 'Tooltips Disabled';
$_['button_expand_all']					= 'Expand All';
$_['button_collapse_all']				= 'Collapse All';
$_['help_expand_all']					= 'Click to expand all rows in this table.';
$_['help_collapse_all']					= 'Click to collapse all rows in this table.';

//------------------------------------------------------------------------------
// Extension Settings
//------------------------------------------------------------------------------
$_['tab_extension_settings']			= 'Extension Settings';
$_['heading_extension_settings']		= 'Extension Settings';

$_['entry_status']						= 'Status:';
$_['entry_sort_order']					= 'Sort Order:';
$_['entry_tax_class_id']				= 'Default Tax Class:';
$_['entry_heading']						= 'Heading:';
$_['entry_display']						= 'Default Admin Display:';
$_['text_expanded']						= 'Expanded';
$_['text_collapsed']					= 'Collapsed';
$_['entry_autocomplete_preloading']		= 'Auto-Complete Pre-loading:';
$_['entry_testing_mode']				= 'Testing Mode:';

$_['help_status']						= 'Set the status for the extension as a whole.';
$_['help_sort_order']					= 'The sort order for the extension, relative to other ' . strtolower($type) . ' extensions.';
$_['help_tax_class_id']					= 'Set the default tax class applied to charges. Any charge that does not have a "Tax Class" rule will use this tax class.';
$_['help_heading']						= 'The heading under which these shipping options will appear. HTML is supported.<br /><br />Use the shortcodes [distance], [postcode], [quantity], [total], [volume], or [weight] to display the calculated value.<br /><br />This heading is for';
$_['help_display']						= 'Set the way table rows are displayed by default when the page is loaded. Select "Collapsed" to improve page loading speed. Turning off Tooltips can also improve loading speed.';
$_['help_autocomplete_preloading']		= 'Choose whether to pre-load the auto-complete database when the page is loaded, or to pull items dynamically from the database. Pre-loading is faster, but may take too long with large databases.';
$_['help_testing_mode']					= 'Enable testing mode to see why charges are not appearing on the front end. Messages logged during testing can be viewed in System > Error Logs';

//------------------------------------------------------------------------------
// Charges
//------------------------------------------------------------------------------
$_['tab_charges']						= 'Charges';
$_['help_charges']						= 'By default, charges are always available. Use "Rules" to restrict a charge by specific criteria such as geo zone or customer group.';
$_['heading_charges']					= 'Charges';

$_['column_action']						= 'Action';
$_['column_group']						= 'Group';
$_['column_title']						= 'Title';
$_['column_charge']						= 'Charge';
$_['column_rules']						= 'Rules';

$_['text_expand']						= 'Expand (or double-click blank space in a collapsed row)';
$_['text_collapse']						= 'Collapse';
$_['text_copy']							= 'Copy';
$_['text_delete']						= 'Delete';

$_['help_charge_group']					= 'Use a single letter or number (case-insensitive) to set a charge&#39;s group. Charges of the same group can be combined within the "Charge Combinations" tab.<br /><br />If you leave the value blank, the charge will be disabled.';

$_['text_admin_reference']				= 'Admin Reference';
$_['help_admin_reference']				= 'Enter a reference name for this charge, only visible internally to the admin.';
$_['placeholder_charge_title']			= 'Title';
$_['help_charge_title']					= 'The title displayed to the customer. HTML is supported.<br /><br />Use the shortcodes [distance], [postcode], [quantity], [total], [volume], or [weight] to display the calculated value.<br /><br />This title is for';

$_['text_simple_charges']				= 'Simple Charges';
$_['text_flat_charge']					= 'Flat Charge';
$_['text_per_item_charge']				= 'Per Item Charge';
$_['text_bracket_charges']				= 'Bracket Charges';
$_['text_distance']						= 'Distance';
$_['text_quantity']						= 'Quantity';
$_['text_total']						= 'Total';
$_['text_volume']						= 'Volume';
$_['text_weight']						= 'Weight';
$_['text_other_shipping_methods']		= 'Other Shipping Methods';
$_['help_charge_type']					= 'Select the basis for the charge:<br /><br />&bull; A simple charge (flat rate or per item rate).<br /><br />&bull; A charge based on brackets (quantity, total, weight, etc.).<br /><br />&bull; A charge calculated using another shipping method.';
$_['help_charge_charges']				= 'For "Simple Charges", enter the flat charge or per item charge (such as 5.00).<hr />For "Bracket Charges", separate brackets by commas or new lines, and enter them in the format:<br /><br /><b>From - To = Cost / Per</b><br /><br />The "Per" setting is optional. For example, to charge $6.00 for 1 to 3 items, and $2.00 per item for 4 or more items, you would choose "Quantity" and enter:<br /><div class="text-left" style="margin: 10px">1-3 = 6.00<br />4-9999 = 2.00 / 1</div><hr />For "Other Shipping Method" charges, enter one or more titles of the rate to charge (such as Ground). Separate multiple titles by commas or new lines.<br /><br />The charge will be the first eligible rate that contains one of these titles. If left blank, the first rate returned will be chosen.';

$_['button_add_charge']					= 'Add Charge';

//------------------------------------------------------------------------------
// Rules
//------------------------------------------------------------------------------
$_['text_choose_rule_type']				= '--- Choose rule type ---';
$_['help_rules']						= 'Choose a rule type from the list of options. Once you select a rule type, hover over the input field that is created for more information on that specific rule type.';

$_['text_of']							= 'of';
$_['text_is']							= 'is';
$_['text_is_not']						= 'is not';
$_['text_is_on_or_after']				= 'is after';
$_['text_is_on_or_before']				= 'is before';

$_['button_add_rule']					= 'Add Rule';
$_['help_add_rule']						= 'All rules must be true for the charge to be enabled. Rules of different types will be combined using AND logic, and rules of the same type using OR logic. For example, if you add these rules:<br /><br />&bull; Customer Group is Wholesale<br />&bull; Geo Zone is United States<br />&bull; Geo Zone is Canada<br /><br />then the charge will be enabled when the customer is part of the Wholesale group <b>AND</b> the location is in the United States <b>OR</b> Canada.';

$_['text_adjustments']					= 'Adjustments';
$_['text_adjust']						= 'Adjust';
$_['text_charge_adjustment']			= 'charge adjustment';
$_['text_final_charge']					= 'final charge';
$_['text_cart']							= 'cart';
$_['text_item']							= 'item';
$_['text_cumulative']					= 'Cumulative Brackets';
$_['text_enabled_successive_brackets']	= 'enabled = successive brackets are added together';
$_['text_max']							= 'Maximum';
$_['text_min']							= 'Minimum';
$_['text_round']						= 'Round';
$_['text_to_the_nearest']				= 'to the nearest';
$_['text_up_to_the_nearest']			= 'up to the nearest';
$_['text_down_to_the_nearest']			= 'down to the nearest';
$_['text_setting_override']				= 'Setting Override';
$_['text_tax_class']					= 'Tax Class';
$_['text_total_value']					= 'Total Value';
$_['text_prediscounted_subtotal']		= 'Pre-Discounted Sub-Total';
$_['text_nondiscounted_subtotal']		= 'Non-Discounted Sub-Total';
$_['text_shipping_cost_subtotal']		= 'Shipping Cost';
$_['text_taxed_subtotal']				= 'Taxed Sub-Total';
$_['text_total_subtotal']				= 'Total';

$_['help_adjust_comparison']			= '&bull; Choose the type of value to adjust. Final charge adjustments occur after the charge has been calculated, and before Maximum or Minimum criteria are checked.<br /><br />&bull; All other adjustments occur before calculations take place. "Cart adjustments" will apply to the entire cart, and "item adjustments" will apply to each individual item.<br /><br />&bull; For example, if the cart contains an item that is 1 kg and an item that is 2 kg, then a "cart weight" adjustment of 1.00 would result in a total weight of:<br /><br />(1 + 2) + 1.00 = 4.00 kg<br /><br />An "item weight" adjustment of 1.00 would result in:<br /><br />(1 + 1.00) + (2 + 1.00) = 5.00 kg';
$_['help_adjust']						= 'Enter a postive or negative value (such as 5.00 or -3.50) or percentage (such as 15% or -10%) by which the value will be adjusted.';
$_['help_cumulative']					= 'Cumulative bracket charges mean that each successive bracket will be added together. For example, if you charge $2.00 for 0-1 kg and $3.00 for 1-2 kg, then an order that is 1.5 kg will charge $2.00 + $3.00 = $5.00';
$_['help_max']							= 'Enter a flat value (such as 49.99) to have the charge always be no more than this maximum value.';
$_['help_min']							= 'Enter a flat value (such as 10.00) to have the charge always be at least this minimum value.';
$_['help_round']						= 'Enter a value to round the charge to (such as 0.25) after calculations have been performed. Also optionally select whether to always round up or down.';
$_['help_setting_override']				= 'Select a store setting to override. Currently, you can only override the config_address setting. Use this if you want to change the location used as the origin for distance calculations.';
$_['help_setting_override_value']		= 'Enter the value with which to override the setting.';
$_['help_tax_class']					= 'Select a tax class to apply to this charge.';
$_['help_total_value']					= 'The cart&#39;s Sub-Total is normally used for calculations involving the total. To change this, use one of the following:<br /><br />&bull; <b>Pre-Discounted Sub-Total:</b> the sub-total of all products&#39; original prices, ignoring Special or Discount prices<br /><br />&bull; <b>Non-Discounted Sub-Total:</b> the sub-total not including products with Special or Discount pricing<br /><br />&bull; <b>Taxed Sub-Total:</b> the sub-total including any tax on products<br /><br />&bull; <b>Total:</b> the total at the relative Sort Order of ' . ($type == 'Shipping' ? 'the "Shipping" Order Total<br /><br />Products that do not require shipping are NOT included in values based on the sub-total.' : 'this extension.');

$_['text_cart_criteria']				= 'Cart/Item Criteria';
$_['text_length']						= 'Length';
$_['text_width']						= 'Width';
$_['text_height']						= 'Height';
$_['text_stock']						= 'Stock';
$_['text_eligible_item_comparisons']	= 'eligible item comparisons';
$_['text_of_cart']						= 'of cart';
$_['text_of_any_item']					= 'of any item';
$_['text_of_every_item']				= 'of every item';
$_['text_entire_cart_comparisons']		= 'entire cart comparisons';
$_['text_of_entire_cart']				= 'of entire cart';
$_['text_of_any_item_in_entire_cart']	= 'of any item in entire cart';
$_['text_of_every_item_in_entire_cart']	= 'of every item in entire cart';
$_['text_items']						= 'items';
$_['help_cart_criteria_comparisons']	= '
	<b>of cart</b> = compare the value against the cart as a whole<br /><br />
	<b>of any item</b> = compare the value against each item individually; any that qualify will be included the calculation, and any that do not will be ignored<br /><br />
	<b>of every item</b> = compare the value against every item individually; if any do not qualify, the charge will be disabled
	<hr />
	Values are normally compared only against eligible items (i.e. those that qualify for the charge based on other rules). To compare values against all items in the cart, including ineligible ones, choose a comparison containing "entire cart".';
$_['help_cart_criteria']				= 'Enter a minimum value (such as 5.00) or a range (such as 3.3-10.5) that the cart or individual items must meet.<br /><br />A single value indicates <b>at least</b> that value. For example, if you set a criteria of 5.00, any value of 5.00 or more will be eligible.<br /><br />Ranges are inclusive of the end values. Separate multiple ranges using commas. To specify an exact value, use a range like 5.00-5.00.';

$_['text_datetime_criteria']			= 'Date/Time Criteria';
$_['text_day']							= 'Day of the Week';
$_['text_sunday']						= 'Sunday';
$_['text_monday']						= 'Monday';
$_['text_tuesday']						= 'Tuesday';
$_['text_wednesday']					= 'Wednesday';
$_['text_thursday']						= 'Thursday';
$_['text_friday']						= 'Friday';
$_['text_saturday']						= 'Saturday';
$_['text_date']							= 'Date';
$_['text_time']							= 'Time';
$_['help_day']							= 'Choose the day of the week that this charge is active. Create multiple rules if you want it active on multiple days.';
$_['help_date']							= 'YYYY-MM-DD';
$_['help_time']							= 'HH:MM (12-23 for PM)';
$_['help_datetime_criteria']			= 'Choose when the charge starts or ends. Use the format YYYY-MM-DD for dates and HH:MM for times. Use 12-23 for the PM hours.';

$_['text_location_criteria']			= 'Location Criteria';
$_['text_city']							= 'City';
$_['text_distance']						= 'Distance';
$_['text_geo_zone']						= 'Geo Zone';
$_['text_everywhere_else']				= 'Everywhere Else';
$_['text_location_comparison']			= 'Location Comparison';
$_['text_payment_address']				= 'Payment Address';
$_['text_shipping_address']				= 'Shipping Address';
$_['text_postcode']						= 'Postcode';
$_['help_city']							= 'Enter an exact city name, such as:<br /><br />New York<br /><br />or multiple city names separated by commas, such as<br /><br />New York, New York City, London<br /><br />The city entered by the customer will be compared against these values (case-insensitively).';
$_['help_distance']						= 'Using <b>mi</b> for miles or <b>km</b> for kilometers, enter a maximum value (such as 5.00mi) or a range (such as 3.3km-10.5km) that the customer&#39;s distance must be from the store location. For example, if you enter 5.00mi, any location within 5 miles will be eligible.<br /><br />Ranges are inclusive of the end values. Separate multiple ranges using commas.<br /><br />Location determinations are made using the Google Geocoding API. This API is limited to 2,500 requests every 24 hours. If you need more than this, consider signing up for Google Maps API for Business, which allows 100,000 requests every 24 hours.';
$_['help_geo_zone']						= 'Select a geo zone, or select "Everywhere Else" to restrict the charge to anywhere not in a geo zone.';
$_['help_location_comparison']			= 'By default, ' . $type . ' extensions compare location criteria against the ' . ($type == 'Shipping' ? 'shipping' : 'payment') . ' address. Use this setting to change this behavior.';
$_['help_postcode']						= 'Enter a single postcode or prefix (such as AB1) or a range (such as 91000-94499). Ranges are inclusive of the end values. Separate multiple postcodes using commas.';

$_['text_order_criteria']				= 'Order Criteria';
$_['text_currency']						= 'Currency';
$_['text_customer_group']				= 'Customer Group';
$_['text_guests']						= 'Guests';
$_['text_language']						= 'Language';
$_['text_past_orders']					= 'Past Orders';
$_['text_days']							= 'Days';
$_['text_payment_extension']			= 'Payment Method';
$_['text_shipping_extension']			= 'Shipping Method';
$_['text_shipping_rate']				= 'Shipping Rate';
$_['text_store']						= 'Store';
$_['help_currency']						= 'Select a currency. If multiple currency rules are added, the charge will be appropriately converted from the default currency using your currency conversions.<br /><br />If you want to enter a charge value in its foreign currency amount, then add a single currency rule with that currency selected.';
$_['help_customer_group']				= 'Select a customer group, or select "Guests" to restrict the charge to customers not logged in to an account.';
$_['help_language']						= 'Select a language.';
$_['help_past_orders_dropdown']			= 'Choose how to compare customer&#39;s past orders: Days, Quantity, or Total. For example:<br /><br />&bull; To apply the charge to customers who have placed an order within the past 30 days, choose "Days" and enter 0-30<br /><br />&bull; To apply the charge to customers with at least 2 past orders, choose "Quantity" and enter 2<br /><br />&bull; To apply the charge to customers whose past orders total $500 to $1000, choose "Total" and enter 500-1000';
$_['help_past_orders']					= 'Enter a minimum value (such as 5) or a range (such as 50.00-100.00) that the customer&#39;s past orders must meet. A single value indicates <b>at least</b> that value.<br /><br />Ranges are inclusive of the end values. Separate multiple ranges using commas. To specify an exact value, use a range like 50.00-50.00.';
$_['help_payment_extension']			= 'Select a payment method to which this fee/discount applies.';
$_['help_shipping_cost']				= 'Enter a minimum value (such as 5.00) or a range (such as 30.00-70.00) that the shipping cost must meet. A single value indicates <b>at least</b> that value. For example, if you set a criteria of 5.00, the charge will apply when the customer chooses a shipping option whose cost is 5.00 or more.<br /><br />Ranges are inclusive of the end values. Separate multiple ranges using commas. To specify an exact value, use a range like 5.00-5.00';
$_['help_shipping_extension']			= 'Select a shipping method to which this fee/discount applies.';
$_['help_shipping_rate']				= 'Enter a shipping rate title (such as Priority Mail) or multiple titles separated by commas (such as Priority Mail, Express Mail). The shipping rate selected by the customer will be compared against these values (case-insensitively).';
$_['help_store']						= 'Select a store from your multi-store installation.';

$_['text_product_criteria']				= 'Product Criteria';
$_['text_attribute']					= 'Attribute';
$_['text_attribute_group']				= 'Attribute Group';
$_['text_category']						= 'Category';
$_['text_manufacturer']					= 'Manufacturer';
$_['text_option']						= 'Option';
$_['text_product']						= 'Product';

$_['help_attribute']					= 'Enter an attribute name in the auto-complete field. Make sure to leave the attribute_id in the square brackets [ and ] since that is used for comparison purposes. For more complex requirements, use the Product Groups feature.';
$_['help_attribute_group']				= 'Enter an attribute group name in the auto-complete field. Make sure to leave the attribute_group_id in the square brackets [ and ] since that is used for comparison purposes. For more complex requirements, use the Product Groups feature.';
$_['help_category']						= 'Enter a category name in the auto-complete field. Make sure to leave the category_id in the square brackets [ and ] since that is used for comparison purposes. For more complex requirements, use the Product Groups feature.';
$_['help_manufacturer']					= 'Enter a manufacturer name in the auto-complete field. Make sure to leave the manufacturer_id in the square brackets [ and ] since that is used for comparison purposes. For more complex requirements, use the Product Groups feature.';
$_['help_option']						= 'Enter an option in the auto-complete field. Make sure to leave the option_id in the square brackets [ and ] since that is used for comparison purposes.';
$_['help_option_value']					= 'Enter a value (such as Small) or a range (such as 25-50). Products that have the specified option with the value or within the range will be eligible for this charge. Leave this field blank to allow for any option value.<br /><br />If the option value can include a hyphen, use :: in place of - for ranges. Ranges are inclusive of the end values. Separate multiple ranges using commas.';
$_['help_product']						= 'Enter a product name in the auto-complete field. Make sure to leave the product_id in the square brackets [ and ] since that is used for comparison purposes. For more complex requirements, use the Product Groups feature.';

$_['text_product_group']				= 'Product Group';
$_['text_cart_has_items_from']			= 'Cart has items from';
$_['text_any']							= 'any';
$_['text_all']							= 'all';
$_['text_not']							= 'not';
$_['text_only_any']						= 'only any';
$_['text_only_all']						= 'only all';
$_['text_none_of_the']					= 'none of the';
$_['text_members_of']					= 'members of';
$_['help_product_group']				= 'Select a product group from the list. New groups can be created in the "Product Groups" tab.<br /><br />Note: product groups must be created before adding a "Product Group" rule.';
$_['help_product_group_comparison']		= '
	<b>any</b> = cart has at least one item from group members, and can have items not from members<br /><br />
	<b>all</b> = cart has items from all group members, and can have items not from members<br /><br />
	<b>not</b> = cart has at least one item <b>not</b> from group members, and can have items from members<br /><br />
	<b>only any</b> = cart has <b>only</b> items from group members, and <b>cannot</b> have items not from members<br /><br />
	<b>only all</b> = cart has <b>only</b> items from all group members, and <b>cannot</b> have items not from members<br /><br />
	<b>none</b> = cart has <b>no</b> items from any group member
';

$_['text_other_product_data']			= 'Other Product Data';
$_['help_other_product_data_column']	= 'Choose which database column to use for the comparison.';
$_['help_other_product_data']			= 'This rule has two functions:<br /><br />1. Enter a value (such as ABC001X) or a range (such as 500-1000). Products that match this value or have a value in this range will be eligible for this charge.<br /><br />If product data includes hyphens, use :: in place of - for ranges. Ranges are inclusive of the end values. Separate multiple ranges using commas.<br /><br />For example, if you choose "model" for the database column, and then enter "Model XYZ" (without quotes) in the field, any product with a matching model will be used for this charge&#39;s calculation.<br /><br />2. If you leave the value blank, then that field will instead be used to calculate the charge for each product. For example, if you are using a Quantity charge type and choose "sku", then the SKU data for each product will be calculated as quantity brackets.<br /><br />In this example, if a product had a value of 5.00 / 1 in that field, its charge would be $5.00 per item. If another product had 7.50 in that field, then its charge would be $7.50. These charges would be added together as the final charge displayed to the customer.';

$_['text_rule_sets']					= 'Rule Sets';
$_['text_rule_set']						= 'Rule Set';
$_['help_rule_set']						= 'Select a rule set from the list. New rule sets can be created in the "Rule Sets" tab.<br /><br />All rules in the rule set will be applied just like other rules, so remember that rules of different types will be combined using AND logic, and rules of the same type using OR logic.<br /><br />Note: rule sets must be created before adding a "Rule Set" rule.';

//------------------------------------------------------------------------------
// Charge Combinations
//------------------------------------------------------------------------------
$_['tab_charge_combinations']			= 'Charge Combinations';
$_['help_charge_combinations']			= 'Charge combinations allow you to combine charges created in the "Charges" area. If used, only charge combinations will be displayed as shipping choices.';
$_['heading_charge_combinations']		= 'Charge Combinations';
$_['button_add_combination']			= 'Add Combination';

$_['column_sort_order']					= 'Sort Order';
$_['column_title_combination']			= 'Title Combination';
$_['column_combination_formula']		= 'Combination Formula';

$_['text_single_title']					= 'Single Title';
$_['text_combined_title_no_prices']		= 'Combined Title, No Prices';
$_['text_combined_title_with_prices']	= 'Combined Title, With Prices';

$_['help_combination_sort_order']		= 'The sort order in which the charge combinations will appear to the customer as shipping options.';
$_['help_combination_title']			= '&bull; <b>Single Title</b> means the first applicable title will be shown as the shipping choice title. If choosing this then you should use the same title for all charges in the formula.<br /><br />&bull; <b>Combined Title</b> means that all charge titles will be combined in a list, so the shipping choice would appear as something like "Category A Charge + Category B Charge"<br /><br />&bull; <b>With Prices</b> means the title will include the price of each charge, so the shipping choice would appear as something like "Category A Charge ($5.00) + Category B Charge ($7.00)"';
$_['help_combination_formula']			= 'Enter a formula for how the charges are combined together.<br /><br /><b>SUM</b> = The sum of all charges<br /><b>AVG</b> = The average of all charges<br /><b>MAX</b> = The highest of all charges<br /><b>MIN</b> = The lowest of all charges<br /><br />Use the charge&#39;s Group value to designate which charges are part of the combination. For example, to add together all charges for Group A, you would enter:<br /><br /><span style="font-family: monospace; font-size: 14px">SUM(A)</span><br /><br />If you wanted to then take the highest of that value and the average of Group B charges, then you would enter:<br /><br /><span style="font-family: monospace; font-size: 14px">MAX(SUM(A), AVG(B))</span>';
$_['placeholder_formula']				= 'SUM(), AVG(), MAX(), MIN()';

//------------------------------------------------------------------------------
// Product Groups
//------------------------------------------------------------------------------
$_['tab_product_groups']				= 'Product Groups';
$_['help_product_groups']				= 'Product groups are used to restrict charges based on a group of categories, manufacturers, products, attributes, and/or options.';
$_['heading_product_groups']			= 'Product Groups';
$_['button_add_product_group']			= 'Add Product Group';

$_['column_group_members']				= 'Group Members';
$_['column_']							= '';

$_['text_autocomplete_from']			= 'Auto-Complete From:';
$_['text_all_database_tables']			= 'All Database Tables';

$_['help_product_group_sort_order']		= 'The sort order in which the product group will appear when selecting it as a Rule.';
$_['help_product_group_name']			= 'The name displayed for the product group when selecting it as a Rule.';
$_['help_autocomplete_from']			= 'Choose whether the auto-complete field pulls items from all database tables, or from specific ones (categories, products, etc.).';
$_['placeholder_group_members']			= 'Start typing a name';
$_['help_group_members']				= 'Start typing a name in the auto-complete field. If more than 15 entries are found, the list will be scrollable, up to 100 entries.<br /><br />Hit "enter" to add the first entry and clear the input field, or click an entry to add it to the list and keep the dropdown open, allowing you to choose multiple entries from the list quickly.<br /><br />Make sure to leave the data within the square brackets [ and ] alone, since that is used for comparison purposes.';

//------------------------------------------------------------------------------
// Rule Sets
//------------------------------------------------------------------------------
$_['tab_rule_sets']						= 'Rule Sets';
$_['help_rule_sets']					= 'Rule sets are used to apply multiple rules to a single charge at once. You can reuse the same rule set for different charges to quickly create the charges you need.';
$_['heading_rule_sets']					= 'Rule Sets';
$_['button_add_rule_set']				= 'Add Rule Set';

$_['column_name']						= 'Name';

$_['help_rule_set_sort_order']			= 'The sort order in which the rule set will appear when selecting it as a Rule.';
$_['help_rule_set_name']				= 'The name displayed for the rule set when selecting it as a Rule.';

//------------------------------------------------------------------------------
// Standard Text
//------------------------------------------------------------------------------
$_['copyright']							= '<hr /><div class="text-center" style="margin: 15px">' . $_['heading_title'] . ' (' . $version . ') &copy; <a target="_blank" href="http://www.getclearthinking.com">Clear Thinking, LLC</a></div>';
$_['standard_select']					= '--- Select ---';
$_['standard_confirm']					= 'This operation cannot be undone. Continue?';
$_['standard_error']					= '<strong>Warning:</strong> You do not have permission to modify ' . $_['heading_title'] . '!';

//------------------------------------------------------------------------------
// Extension-Specific Text
//------------------------------------------------------------------------------
$_['help_charge_type']					= 'Select the basis for the charge:<br /><br />&bull; A simple charge (flat rate or per item rate).<br /><br />&bull; A charge based on brackets (quantity, total, weight, etc.).';
$_['help_charge_charges']				= 'For "Simple Charges", enter the flat charge or per item charge (such as 5.00).<hr />For "Bracket Charges", separate brackets by commas or new lines, and enter them in the format:<br /><br /><b>From - To = Cost / Per</b><br /><br />The "Per" setting is optional. For example, to charge $6.00 for 1 to 3 items, and $2.00 per item for 4 or more items, you would choose "Quantity" and enter:<br /><div class="text-left" style="margin: 10px">1-3 = 6.00<br />4-9999 = 2.00 / 1</div>';

$_['help_setting_override']				= 'Select a store setting to override. Currently, you can only override the config_address setting. Use this if you want to change the location used as the origin for distance calculations.';
?>