<?php
/*
  Plugin Name: Change Uploads Paths
  Plugin URI: http://investigativenewsnetwork.org
  Description: Use the main uploads folder for all blogs
  Author: Adam Schweigert
  Version: 0.1
 */

add_filter('upload_dir', 'ms_global_upload_dir');

function ms_global_upload_dir($uploads)
{
    $ms_dir = '/sites/' . get_current_blog_id();

    $uploads['path']    = str_replace($ms_dir, "", $uploads['path']);
    $uploads['url']     = str_replace($ms_dir, "", $uploads['url']);
    $uploads['basedir'] = str_replace($ms_dir, "", $uploads['basedir']);
    $uploads['baseurl'] = str_replace($ms_dir, "", $uploads['baseurl']);

    return $uploads;
}