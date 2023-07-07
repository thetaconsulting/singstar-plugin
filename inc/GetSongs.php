<?php 

class GetSongs {
  function __construct() {
    global $wpdb;
    $lastQuery = $wpdb->last_query;
    $tablename = $wpdb->prefix . 'songs';
    $backupFile = 'backup/backup.sql';

    $this->args = $this->getArgs();
    $this->placeholders = $this->createPlaceholders();
   
    $query = "SELECT * FROM $tablename ";
    $countQuery = "SELECT COUNT(*) FROM $tablename ";

   
    if ( $this->placeholders == NULL) {
      $this->count = $wpdb->get_var($countQuery);             // No arguments -> plaeholders-array is empty -> cannot use prepare
    } else { 
      $countQuery .= $this->createWhereText();
      $this->count = $wpdb->get_var($wpdb->prepare($countQuery, $this->placeholders));
    } 

	  if ($this->count != 0 ) 
	  { 
			/* wp_safe_redirect(site_url()); */
      if ( $this->placeholders == NULL) {                     // No arguments -> plaeholders-array is empty -> cannot use prepare
        $this->songs = $wpdb->get_results($query);
      } else {
   			$query .= $this->createWhereText();
				$this->songs = $wpdb->get_results($wpdb->prepare($query, $this->placeholders));
      }
	  } else {
     $this->songs = $wpdb->get_results($query);
	  }

  }

  function getArgs() {
    $temp = [];
    if (isset($_GET['songname'])) $temp['songname'] = sanitize_text_field($_GET['songname']);
    if (isset($_GET['artist'])) $temp['artist'] = sanitize_text_field($_GET['artist']);
    if (isset($_GET['game'])) $temp['game'] = sanitize_text_field($_GET['game']);

    return $temp;

  }

    function createPlaceholders() {
    return array_map(function($x) {
      return "%" . $x . "%";
    }, $this->args);
  }

  function createWhereText() {
    $whereQuery = "";

    if (count($this->args)) {
      $whereQuery = "WHERE ";
    }

    $currentPosition = 0;
    foreach($this->args as $index => $item) {
      $whereQuery .= $index  . " LIKE %s";
      if ($currentPosition != count($this->args) - 1) {
        $whereQuery .= " AND ";
      }
      $currentPosition++;
    }

    return $whereQuery;
  }

}