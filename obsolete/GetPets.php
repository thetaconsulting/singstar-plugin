<?php 

class GetPets {
  function __construct() {
    global $wpdb;
    $lastQuery = $wpdb->last_query;
    $tablename = $wpdb->prefix . 'pets';
    $backupFile = 'backup/backup.sql';

    $this->args = $this->getArgs();
    $this->placeholders = $this->createPlaceholders();
    $this->orderArgs = $this->getOrder();

    $query = "SELECT * FROM $tablename ";
    $countQuery = "SELECT COUNT(*) FROM $tablename ";

    $countQuery .= $this->createWhereText();

    ?> <br> <?php




    $this->count = $wpdb->get_var($wpdb->prepare($countQuery, $this->placeholders));
	  if ($this->count != 0 ) 
	  { 
			/* wp_safe_redirect(site_url()); */
   				 $query .= $this->createWhereText();
			    $query .= $this->createOrderQuery();
				$this->pets = $wpdb->get_results($wpdb->prepare($query, $this->placeholders));
	}
	  else {
		  $this->pets = $wpdb->get_results($wpdb->prepare($query));
	  }


  }

  function sortBy () {

  }

  function getArgs() {
    $temp = [];
    if (isset($_GET['songname'])) $temp['songname'] = sanitize_text_field($_GET['songname']);
    if (isset($_GET['artist'])) $temp['artist'] = sanitize_text_field($_GET['artist']);
    if (isset($_GET['game'])) $temp['game'] = sanitize_text_field($_GET['game']);

    return $temp;

  }

  function getOrder() {
    $temp = [];
    $temp ['order'] = " ASC ";
    $temp ['orderby'] = " songname ";
    if (isset($_GET['orderby'])) $temp['orderby'] = sanitize_text_field($_GET['orderby']);
    if (isset($_GET['order'])) $temp['order'] = sanitize_text_field($_GET['order']);
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
      $whereQuery .= $this->specificQuery($index);
      if (($currentPosition != count($this->args) - 1) AND ($this->args[$index] != 'orderby')) {
        $whereQuery .= " AND ";
      }
      $currentPosition++;
    }
    return $whereQuery;
  }

  function specificQuery($index) {
          return $index  . " LIKE %s";

}

function createOrderQuery() {
  $orderquery = "";
  if (count($this->orderArgs)) {
    $orderquery = " ORDER BY " . $this->orderArgs['orderby'];
    if ($this->orderArgs['order'] == "DESC") {
      $orderquery .= " DESC ";
    }
    else {
      $orderquery .= " ASC ";
    }
    }
    return $orderquery;
  }

}