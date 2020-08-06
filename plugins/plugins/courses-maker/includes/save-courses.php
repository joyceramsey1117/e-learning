<?php

require_once( '../../../../wp-config.php' );



global $wpdb;
global $post;

if( current_user_can('publish_posts')) {

  // editor

  $table_name  = $wpdb->prefix."posts";
  $post_name = $_POST['post_title'];



  $new_post_name = str_replace(" ","-",mb_strtolower($post_name));

  if($wpdb->get_row("SELECT post_name FROM wp_posts WHERE post_name = '" . $new_post_name . "'", 'ARRAY_A')) {

      $new_post_name = wp_unique_post_slug( $new_post_name, $wpdb->get_var("SELECT post_name FROM wp_posts WHERE post_name = '" . $new_post_name . "'"), $_POST['add_status'], $_POST['add_post_type'], $_POST['add_parent']);

  } 


  $user_id   = esc_sql($_POST["user_id"]);
  $post_date   = esc_sql($_POST["post_date"]);
  $post_date_gmt   = esc_sql($_POST["post_date_gmt"]);
  $post_content   = esc_sql($_POST["post_content"]);
  $post_title   = esc_sql($_POST["post_title"]);
  $post_status   = esc_sql($_POST["post_status"]);
  $comment_status   = esc_sql($_POST["comment_status"]);
  $ping_status   = esc_sql($_POST["ping_status"]);
  $post_password   = esc_sql($_POST["post_password"]);
  $post_name   = esc_sql($_POST["post_name"]);
  $post_modified   = esc_sql($_POST["modified_date"]);
  $post_modified_gmt   = esc_sql($_POST["modified_date_gmt"]);
  $post_parent   = esc_sql($_POST["post_parent"]);
  $guid   = get_site_url('','courses/'.$new_post_name);
  $menu_order   = esc_sql($_POST["menu_order"]);
  $post_type   = esc_sql($_POST["post_type"]);
  $comment_count   = esc_sql($_POST["comment_count"]);

  $values = array(

    'post_author' => $user_id,

    'post_date' => $post_date,

    'post_date_gmt' => $post_date_gmt,

    'post_content' => stripcslashes($post_content),

    'post_title' => $post_title,

    'post_status' => $post_status,

    'comment_status' => $comment_status,

    'ping_status' => $post_password,

    'post_password' => $post_password,

    'post_name' => $new_post_name,

    'post_modified' => $post_modified,

    'post_modified_gmt' => $post_modified_gmt,

    'post_parent' => $post_parent,

    'guid' => $guid,

    'menu_order' => $menu_order,

    'post_type' => $post_type,

    'comment_count' => $comment_count,

  );





  if (intval($_POST['id'])) {

    $filter = array('id' => $_POST['id']);

    $wpdb->update($table_name, $values, $filter);    
  }

  else {

    if (!$wpdb->insert($table_name, $values)) {

      wp_redirect(get_site_url().'/add-course&err='.urlencode($wpdb->last_error));

      exit;

    }

    $post_id = $wpdb->insert_id;
    $meta_value = array(

    'sfwd-courses_course_materials' => $_POST['sfwd-courses_course_materials'],

    'sfwd-courses_course_price_type' => $_POST['sfwd-courses_course_price_type'],

    'sfwd-courses_custom_button_url' => $_POST['sfwd-courses_custom_button_url'],

    'sfwd-courses_course_price' => $_POST['sfwd-courses_course_price'],

    'sfwd-courses_course_access_list' => $_POST['sfwd-courses_course_access_list'],

    'sfwd-courses_course_lesson_orderby' => $_POST['sfwd-courses_course_lesson_orderby'],

    'sfwd-courses_course_lesson_order' => $_POST['sfwd-courses_course_lesson_order'],

    'sfwd-courses_course_lesson_per_page' => $_POST['sfwd-courses_course_lesson_per_page'],

    'sfwd-courses_course_lesson_per_page_custom' => $_POST['sfwd-courses_course_lesson_per_page_custom'],

    'sfwd-courses_course_prerequisite_enabled' => $_POST['sfwd-courses_course_prerequisite_enabled'],

    'sfwd-courses_course_prerequisite' => explode(",", $_POST['sfwd-courses_course_prerequisite[]']),

    'sfwd-courses_course_prerequisite_compare' => $_POST['sfwd-courses_course_prerequisite_compare'],

    'sfwd-courses_course_points_enabled' => $_POST['sfwd-courses_course_points_enabled'],

    'sfwd-courses_course_points' => $_POST['sfwd-courses_course_points'],

    'sfwd-courses_course_points_access' => $_POST['sfwd-courses_course_points_access'],

    'sfwd-courses_course_disable_lesson_progression' => $_POST['sfwd-courses_course_disable_lesson_progression'],

    'sfwd-courses_expire_access' => $_POST['sfwd-courses_expire_access'],

    'sfwd-courses_expire_access_days' => $_POST['sfwd-courses_expire_access_days'],

    'sfwd-courses_expire_access_delete_progress' => $_POST['sfwd-courses_expire_access_delete_progress'],

    'sfwd-courses_course_disable_content_table' => $_POST['sfwd-courses_course_disable_content_table'],

    'sfwd-courses_certificate' => $_POST['sfwd-courses_certificate'],
  );

    set_post_thumbnail( $post_id, $_POST['attachment_id'] );
    update_post_meta( $post_id, '_sfwd-courses', $meta_value);

// var_dump($post_id, $meta_value);
//    var_dump($values);
//    var_dump($table_name);

  }
  // var_dump($wpdb->insert_id);

  wp_redirect($guid);

  exit;

}

?>