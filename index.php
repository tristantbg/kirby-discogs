<?php

@include_once __DIR__ . '/vendor/autoload.php';
@include_once __DIR__ . '/lib/functions.php';

Kirby::plugin('tristantbg/discogs', [
    'options' => array(
        'apiKey' => null,
        'apiSecret' => null,
        'min_image_width'  => 60,
        'min_image_height' => 60,
        'max_images'       => 10,
        'external_images'  => false,
    ),
	  'fields'       => require_once __DIR__ . '/lib/fields.php',
    'fieldMethods' => require_once __DIR__ . '/lib/fieldMethods.php',
    'api'          => require_once __DIR__ . '/lib/api.php',
    'translations' => array(
        'en' => require_once __DIR__ . '/lib/languages/en.php',
        'fr' => require_once __DIR__ . '/lib/languages/fr.php',
    ),
]);
