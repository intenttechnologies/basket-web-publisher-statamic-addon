<?php

namespace Basket\AddToBasket;

use Statamic\Fields\Fieldtype;

class AddToBasketFieldtype extends Fieldtype
{
    protected static $handle = 'add_to_basket';

    protected $icon = '<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M10 0.5C2.4 0.5 0.5 2.4 0.5 10C0.5 17.6 2.4 19.5 10 19.5C17.6 19.5 19.5 17.6 19.5 10C19.5 2.4 17.6 0.5 10 0.5Z" stroke="#14171D" style="stroke:#14171D;stroke:color(display-p3 0.0784 0.0902 0.1137);stroke-opacity:1;" stroke-width="0.833333" stroke-linecap="round" stroke-linejoin="round"/><path d="M12.1228 7.97925C16.464 7.99214 17.9196 13.0811 14.1716 15.0808C12.1215 16.3005 8.95496 16.0272 6.61613 15.6107C3.08983 14.9841 5.15017 11.5713 6.89849 9.98414C8.27807 8.73221 10.1115 7.97151 12.1228 7.97925Z" stroke="#14171D" style="stroke:#14171D;stroke:color(display-p3 0.0784 0.0902 0.1137);stroke-opacity:1;" stroke-width="0.833333" stroke-linecap="round" stroke-linejoin="round"/><path d="M10.1179 13.2049C8.53078 14.9519 5.11924 17.0123 4.49263 13.486C4.07617 11.1484 3.80284 7.98187 5.02254 5.93055C7.02228 2.18378 12.1112 3.63943 12.1228 7.98058C12.1306 9.99063 11.3699 11.8253 10.1179 13.2049Z" stroke="#14171D" style="stroke:#14171D;stroke:color(display-p3 0.0784 0.0902 0.1137);stroke-opacity:1;" stroke-width="0.833333" stroke-linecap="round" stroke-linejoin="round"/></svg>';

    protected $categories = ['media', 'special'];

    public function defaultValue()
    {
        return [
            'enabled' => false
        ];
    }
}
