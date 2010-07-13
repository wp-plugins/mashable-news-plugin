<?php
/*
Plugin Name: Mashable News Rss Feed
Plugin URI: http://www.guyro.com/mashable-news-rss-plugin
Description: The Mashable News Plugin simply adds a customizable widget which displays the latest Mashable posts.
Version: 1.0
Author: Guy Roman
Author URI: http://www.guyro.com
License: GPL3
*/

function mashablenews()
{
  $options = get_option("widget_mashablenews");
  if (!is_array($options)){
    $options = array(
      'title' => 'Mashable News',
      'news' => '5',
      'chars' => '30'
    );
  }

  // RSS Feed 
  $rss = simplexml_load_file( 
  'http://feeds.mashable.com/Mashable'); 
  ?> 
  
  <ul> 
  
  <?php 
  // max number of news slots, with 0 (zero) all display
  $max_news = $options['news'];
  // maximum length to which a title may be reduced if necessary,
  $max_length = $options['chars'];
  
  // RSS Elements 
  $cnt = 0;
  foreach($rss->channel->item as $i) { 
    if($max_news > 0 AND $cnt >= $max_news){
        break;
    }
    ?> 
    
    <li>
    <?php
    // Title
    $title = $i->title;
    // Length of title
    $length = strlen($title);
    // if the title is longer than the previously defined maximum length,
    // it'll he shortened and "..." added, or it'll output normaly
    if($length > $max_length){
      $title = substr($title, 0, $max_length)."...";
    }
    ?>
    <a href="<?=$i->link?>"><?=$title?></a> 
    </li> 
    
    <?php 
    $cnt++;
  } 
  ?> 
  
  </ul>
<?php  
}

function widget_mashablenews($args)
{
  extract($args);
  
  $options = get_option("widget_mashablenews");
  if (!is_array($options)){
    $options = array(
      'title' => 'Mashable News',
      'news' => '5',
      'chars' => '30'
    );
  }
  
  echo $before_widget;
  echo $before_title;
  echo $options['title'];
  echo $after_title;
  mashablenews();
  echo $after_widget;
}

function mashablenews_control()
{
  $options = get_option("widget_mashablenews");
  if (!is_array($options)){
    $options = array(
      'title' => 'Mashable News',
      'news' => '5',
      'chars' => '30'
    );
  }
  
  if($_POST['mashablenews-Submit'])
  {
    $options['title'] = htmlspecialchars($_POST['mashablenews-WidgetTitle']);
    $options['news'] = htmlspecialchars($_POST['mashablenews-NewsCount']);
    $options['chars'] = htmlspecialchars($_POST['mashablenews-CharCount']);
    update_option("widget_mashablenews", $options);
  }
?> 
  <p>
    <label for="mashablenews-WidgetTitle">Widget Title: </label>
    <input type="text" id="mashablenews-WidgetTitle" name="mashablenews-WidgetTitle" value="<?php echo $options['title'];?>" />
    <br /><br />
    <label for="mashablenews-NewsCount">Max. News: </label>
    <input type="text" id="mashablenews-NewsCount" name="mashablenews-NewsCount" value="<?php echo $options['news'];?>" />
    <br /><br />
    <label for="mashablenews-CharCount">Max. Characters: </label>
    <input type="text" id="mashablenews-CharCount" name="mashablenews-CharCount" value="<?php echo $options['chars'];?>" />
    <br /><br />
    <input type="hidden" id="mashablenews-Submit"  name="mashablenews-Submit" value="1" />
  </p>
  
<?php
}

function mashablenews_init()
{
  register_sidebar_widget(__('Mashable News'), 'widget_mashablenews');    
  register_widget_control('Mashable News', 'mashablenews_control', 300, 200);
}
add_action("plugins_loaded", "mashablenews_init");
?>