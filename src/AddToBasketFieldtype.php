<?php

namespace Basket\AddToBasket;

use Statamic\Fields\Fieldtype;

class AddToBasketFieldtype extends Fieldtype
{
    protected static $handle = 'add_to_basket';

    protected $icon = '<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M10 0.5C2.4 0.5 0.5 2.4 0.5 10C0.5 17.6 2.4 19.5 10 19.5C17.6 19.5 19.5 17.6 19.5 10C19.5 2.4 17.6 0.5 10 0.5Z" stroke="#14171D" style="stroke:#14171D;stroke:color(display-p3 0.0784 0.0902 0.1137);stroke-opacity:1;" stroke-width="0.833333" stroke-linecap="round" stroke-linejoin="round"/><path d="M11.7308 9.65956C11.2308 11.5596 9.83076 12.3596 8.93076 12.1596C7.83076 11.8596 7.73076 10.4596 8.53076 9.55956C9.15681 8.70586 10.6898 8.0465 12.2265 8.00551M12.2265 8.00551C12.1223 6.72904 11.8169 5.64706 10.8308 4.75956C9.23076 3.25956 6.33076 3.35956 4.93076 5.95956C4.33076 6.95956 4.03076 8.35956 4.03076 9.75956C4.03076 11.3596 4.23076 12.9596 4.63076 14.6596C4.83076 15.1596 4.93076 15.2596 5.43076 15.3596C6.93076 15.7596 8.53076 15.9596 10.1308 15.9596C16.6308 16.0596 17.3308 11.3596 15.3308 9.15956C14.5552 8.29783 13.3898 7.97447 12.2265 8.00551Z" stroke="#14171D" style="stroke:#14171D;stroke:color(display-p3 0.0784 0.0902 0.1137);stroke-opacity:1;" stroke-width="0.833333" stroke-linecap="round" stroke-linejoin="round"/></svg>';

    protected $categories = ['media', 'special'];
}
