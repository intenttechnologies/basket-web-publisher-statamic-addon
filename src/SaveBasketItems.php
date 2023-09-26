<?php

namespace Basket\AddToBasket;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Statamic\Facades\Entry;
use Statamic\Events\EntrySaved;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Statamic\Facades\CP\Toast;

class SaveBasketItems
{
    public function handle(EntrySaved $event)
    {
        // $data = Entry::query()
        //     ->where('collection', 'pages')
        //     ->get()
        //     ->transform(function ($post) {
        //         return [
        //             'title' => $post->title,
        //             'categories' => $post->categories,
        //             'slug' => (string)$post->slug()
        //         ];
        //     })
        //     ->toArray();

        Log::debug('SaveBasketItems');
        Toast::info('Saving to Basket...');

        $url = $this->getFunctionsUrl(config('add-to-basket.environment'), 'publisher-basket-save');
        $body = [
            'data' =>
            [
                'basketName' => 'test-p',
                'apiKey' => config('add-to-basket.api_key'),
                'urls' => ["https://www.amazon.co.uk/Swim-Ways-6045217-ToyPedo-Assorted/dp/B07KFXL2JT"]
            ]
        ];
        $response = Http::post($url, $body);
        $result = $response->json()['result'];
        $basketId = $result['basketId'];
        $userId = $result['userId'];
        Log::debug($response->json());




        $url = $this->getFunctionsUrl(config('add-to-basket.environment'), 'basket-retrieve');
        $body = [
            'data' =>
            [
                'userId' => $userId,
                'basketId' => $basketId
            ]
        ];
        $response = Http::post($url, $body);
        $result = $response->json()['result'];
        Log::debug($response->json());


        $e = Entry::find($event->entry->id);
        $e->set(
            'add_to_basket',
            [
                'enabled' => $event->entry->add_to_basket['enabled'],
                'basketId' => $basketId,
                'userId' => $userId,
                'links' => array()
            ]
        );
        $e->saveQuietly();
    }

    private function getFunctionsUrl($environment, $path)
    {
        return "https://europe-west2-basket-" . $environment . ".cloudfunctions.net/" . $path;
    }
}
