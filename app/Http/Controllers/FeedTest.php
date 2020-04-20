<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FeedTest extends Controller
{
    public function FeedTest() {
      $feed = \FeedReader::read('https://weather.gc.ca/rss/battleboard/on30_e.xml');
      $location_title = str_replace("- Weather Alert - Environment Canada", "", $feed->get_title());
      print_r($location_title);
      echo "<pre>";
      $max = $feed->get_item_quantity();
      	for ($x = 0; $x < $max; $x++):
      		$item = $feed->get_item($x);
          echo "<br />";
          //print_r($item);
          $issue_date = trim(str_replace("Issued:", "", $item->get_content()));
          print_r($issue_date);
          echo "<br />";
          $alert_array = explode(",", $item->get_title());
          foreach($alert_array as $aa) {
              $result = trim($aa);
              print_r($result);
          }
          echo "<br />";
        endfor;
    }
}
