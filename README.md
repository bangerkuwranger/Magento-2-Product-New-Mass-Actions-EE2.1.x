# Magento 2 Product New MassActions for EE v2.1.x

This is a relatively simple module that solves a relatively stupid problem: it overrides the 'New' product status in Magento EE2.1.x with a mass action in the product grid 'Set New Status'.

## Why?

Well, starting in 2.1.0, Magento started creating an abstraction layer for datetime related changes... so, in theory, various settings within products (and lots of other places) that required individual settings for start and end date could be set all at once. Also, this would allow for other settings that didn't have start and end date fields to be scheduled.

In reality, not all module/theme developers support all EE features... so it's very possible something your site relies on will break the 'staging' system somewhere. While that shakes out, this allows you to manually set the 'new' status for a product regardless of what the 'staging' system is set to do.

## Caveats

This ONLY works on Enterprise Edition v2.1.0 or later. It is ONLY a temporary hack. If your 'Is New' checkbox is 'stuck' on or off, when the product is saved, Magento WILL overwrite the manual setting to that 'stuck' value... so you'll need to pull it up in the grid and manually set the 'new' status after every save. (working on an observer to fix this too...) It is VERY possible that there are adverse affects to this, including affecting other scheduled changes. It is also possible that your manually set values might be reverted at the end of a scheduled change, and you will have to manually reset the 'new' status again. Use at your own risk.

## How?

This just adds an action to the menu at the top of your product grid to 'Set New Status'. It directly changes the 'Set Product New From Date' and 'Set Product New To Date' fields, which, just like in CE and older versions of EE, still determine whether a product appears as new. If you select 'New', it sets the 'from' date value to the current date and time, and erases the value in the 'to' date field. If you select 'Not New', it erases the values of both the 'from' and 'to' date fields. Since the various staging systems only perform product data changes at the beginning of a scheduled change, end of a scheduled change, and during a manual save of a product, setting these values directly subverts this system. This really only works because these date range fields (new, special price, etc.) are still working behind the scenes... the scheduler just sets the dates.

## Installation

Installation is available via composer. The package name is bangerkuwranger/magento-2-coupon-code-api. Just run these commands at your Magento root:
`composer require bangerkuwranger/magento-2-product-new-mass-actions`
`php bin/magento module:enable Bangerkuwranger_Productnewmassactions`
`php bin/magento setup:upgrade`
`php bin/magento setup:di:compile`

## Usage

Pretty basic. Go to the product grid, select the products that need to have their 'new' status overridden, and then select 'Set New Status', and then either 'New' or 'Not New'. (yes, the nomenclature is confusing... but on a functional, non-muddled Magento 2 install, you shouldn't need this module anyhow... so, compromise?) There's no Submit button or verification dialog... it just does its thing and give you a message at the top of your product grid.
