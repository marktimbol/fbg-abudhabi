<?php

class BWGViewDownload_gallery {
  public function display() {
    global $wpdb;
    global $WD_BWG_UPLOAD_DIR;
    $image_gallery_id = isset($_GET["gallery_id"]) ? $_GET["gallery_id"] : 0;
    $images = $wpdb->get_col('SELECT thumb_url FROM ' . $wpdb->prefix . 'bwg_image ' . ($image_gallery_id ? ' WHERE gallery_id="' . $image_gallery_id . '"' : ''));
    if ( $images ) {
      @setlocale(LC_ALL, 'he_IL.UTF-8');
      $filename = "photo-gallery_" . date('Ymd His');
      $zip = new ZipArchive();
      $zip->open($filename, ZipArchive::CREATE);
      $images = array_unique($images);
      foreach ( $images as $image ) {
        if ( strpos($image, "http") !== FALSE ) {
          continue;
        }
        if ( mb_detect_encoding($image) == "UTF-8" ) {
          mb_convert_encoding($image, 'ASCII', 'auto');
        }
        $image = html_entity_decode($image, ENT_QUOTES);
        $original = str_replace("thumb", ".original", ABSPATH . $WD_BWG_UPLOAD_DIR . $image);
        if ( file_exists($original) ) {
          $download_file_original = file_get_contents($original);
          $zip->addFromString(basename($original), $download_file_original);
        }
      }
      $zip->close();
      header('Content-Type: application/zip');
      header("Content-Disposition: attachment; filename=\"$filename.zip\"");
      readfile($filename);
      die();
    }
    else {
      ?>
      <p><?php _e('There are no images to download.', 'bwg'); ?></p>
      <?php
    }
  }
}