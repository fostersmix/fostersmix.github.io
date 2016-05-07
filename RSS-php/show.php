<?php
  $url = 'https://twitrss.me/twitter_user_to_rss/?user=fostersmix';
  $rss = simplexml_load_file($url);
  //var_dump($rss->channel->item);
  $item = $rss->channel->item;
?>

<?php echo $item->title; ?>
