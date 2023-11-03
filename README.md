# Add To Basket

> Statamic addon to simplify adding an Add to Basket button on your website

## How to Install

Run this command from the root of your Statamic project

``` bash
composer require basket/add-to-basket
php artisan vendor:publish --tag=add-to-basket-config --force
php artisan vendor:publish --tag=add-to-basket-template --force
```

Add your api_key to env file

```
ADD_TO_BASKET_API_KEY=xxx
```

Set the environment to either `staging` when testing or `production`

```
ADD_TO_BASKET_ENVIRONMENT=staging
```

## How to Use

1. Add the Add To Basket field to your page blueprint with the handle `add_to_basket`
2. Click the Add To Basket 'Enabled' checkbox in the page entry and Save
3. Add script tag to your template `{{ basket:script_tag | raw }}`
4. Add style tag to your template `{{ basket:style_tag }}`
5. Add the button to your template:
``` antlers
{{ if add_to_basket.enabled && add_to_basket.links | length }}
   {{ basket:share_modal_button data={{add_to_basket}} | raw }}
{{ /if }}
```
