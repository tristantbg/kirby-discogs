<?php

use Kirby\Cms\Content;

return array(
    'toDiscogsObject' => function($field) {
        // allows if($embed = $page->myfield()->toEmbed()) { echo $embed->code() }
        if($field->isEmpty() || !count(Yaml::decode($field->value)) || empty($field->yaml()['media'])) {
            return null;
        }
        $content = new Content($field->yaml()['media'], $field->parent());
        return $content;
    },
    'cachedDiscogsImage' => function($field) {

      if($release = $field->toDiscogsObject()) {

        $filename = $release->id().'.jpg';

        if (!file_exists(kirby()->root('content') . '/discogs-files')) {
          mkdir(kirby()->root('content') . '/discogs-files', 0755, true);
        }

        if (!file_exists(kirby()->root('content') . '/discogs-files/' . $filename)) {

          $url = $release->cover_image();
          $ch = curl_init($url);
          $fp = fopen(kirby()->root('content') . '/discogs-files/' . $filename, 'wb');
          curl_setopt($ch, CURLOPT_FILE, $fp);
          curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
          curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
          curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
          // curl_setopt($ch, CURLOPT_HTTPHEADER, array(
          //                 "Authorization: Bearer " . $this->token
          //             ));
          curl_exec($ch);
          curl_close($ch);
          fclose($fp);

          if ($kirbyFile = site()->find('discogs-files')->file($filename)) {

            return $kirbyFile;

          }

        } else {

          if ($kirbyFile = site()->find('discogs-files')->file($filename)) {

            return $kirbyFile;

          }

        }
      }


    }
);
