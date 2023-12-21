<?php

namespace Basket\AddToBasket;

use Statamic\Fields\Fieldtype;

class AddToBasketFieldtype extends Fieldtype
{
    protected static $handle = 'add_to_basket';

    protected $icon = '<svg width="20px" height="20px" viewBox="-1 -1 20 20" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" stroke-linecap="round" stroke-linejoin="round"><g stroke="#666" stroke-width="1.2499995"><path d="M11.8653613,6.00008793 C18.210224,6.0194617 20.3376501,13.6682071 14.8597764,16.6737713 C11.8634613,18.5069896 7.23541803,18.0962177 3.81711083,17.470215 C-1.33673819,16.5284305 1.67454312,11.3989663 4.2297929,9.01345277 C6.24611228,7.13179251 8.92575423,5.98845466 11.8653613,6.00008793 Z" id="Path"></path><path d="M8.98604024,13.7716983 C6.60019851,16.3252226 1.4717933,19.3368319 0.5298404,14.1825717 C-0.0962040359,10.7657895 -0.507087976,6.13739152 1.3264288,3.1390541 C4.33254248,-2.33745891 11.982473,-0.209789968 11.9999107,6.13550597 C12.0116361,9.07352058 10.8681121,11.7551888 8.98604024,13.7716983 Z"></path></g></g></svg>';

    protected $categories = ['media', 'special'];

    public function defaultValue()
    {
        return [
            'enabled' => false
        ];
    }
}
