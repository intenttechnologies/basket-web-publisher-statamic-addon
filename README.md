# Add To Basket

> Statamic addon to simplify adding an Add to Basket button on your website

## How to Install

Run this command from the root of your Statamic project

``` bash
composer require basket/add-to-basket
php artisan vendor:publish --tag=add-to-basket-config --force
```

Add your api_key to the add-to-basket.php config or env file

```
ADD_TO_BASKET_API_KEY=xxx
```

Add env var to use the production environment for prod/live deployments

```
ADD_TO_BASKET_ENVIRONMENT=production
```

## How to Use

1. Add the Add To Basket field to your page blueprint with the handle `add_to_basket`
2. Click the Add To Basket 'Enabled' checkbox in the page entry and Save
3. Add Basket script tag to your template layout `<head>`: `{{ basket:script_tag | raw }}`
4. Add the button to your page template:
``` antlers
{{ if add_to_basket.enabled && add_to_basket.links | length }}
   <button onClick='{{ basket:share_modal_onclick data={{add_to_basket}} | raw }}'>
       Add to Basket
   </button>
{{ /if }}
```

# Dev notes

- run `composer update`