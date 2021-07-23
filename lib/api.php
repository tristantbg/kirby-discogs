<?php

return array(
    'routes' => function ($kirby) {
        return array(
            array(
                'pattern' => 'kirby-discogs/get-data',
                'action' => function() {
                    $response = [];
                    $search = get('search');

                    try {

                        $options = [];
                        $options['apiKey']                  = option('tristantbg.discogs.apiKey');
                        $options['apiSecret']               = option('tristantbg.discogs.apiSecret');
                        $options['min_image_width']         = option('tristantbg.discogs.min_image_width');
                        $options['min_image_height']        = option('tristantbg.discogs.min_image_height');


                        $query = Remote::get('https://api.discogs.com/database/search', [
                          // 'method' => 'GET',
                          'headers' => [
                            'User-Agent' => "KirbyDiscogs/1.0",
                            'Authorization' => "Discogs key=".$options['apiKey'].", secret=".$options['apiSecret'],
                          ],
                          'data' => [
                            'q' => $search,
                            'type' => 'release'
                          ]
                        ]);

                        $results = $query->json();
                        $results = $results['results'];

                        $response['status'] = 'success';
                        $response['data']   = $query->content();
                    }
                    catch (Exception $e) {
                        $response['status'] = 'error';
                        $response['error']  = $e->getMessage();
                    }

                    return $response;
                }
            ),
        );
    }
);
