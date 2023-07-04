<?php

class GetOrder {
    function __construct() {
        session_start(); 
        $this->orderColumn = $this->getOrderColumn();
        $this->direction = $this->getOrderDirection();

    }

    function getOrderColumn() {
        if (isset($_POST['column'])) {
            $columnforOrder = sanitize_text_field($_POST['column']);

        } else {
            $columnforOrder="songname";
        }
        return $columnforOrder;
    }

function getOrderDirection() {

if ($_SESSION[$this->orderColumn] == NULL) {
            $dir = SORT_ASC;
            $_SESSION[$this->orderColumn] = SORT_ASC;
        } else {
            $dir = $_SESSION[$this->orderColumn];
        }
        if ($_SESSION['selected'] == NULL) $_SESSION['selected'] = "songname";

        // error_log($this->orderColumn . ", old direction: " . $dir . "\n" , 3, plugin_dir_path(FILE) . '../tmp/errors.log');
        if (isset($_POST['asc'])) {
            if ($this->orderColumn == $_SESSION['selected']) {
                // error_log("Selected same column twice: " . $this->orderColumn . "\n" , 3, plugin_dir_path(FILE) . '../tmp/errors.log');
                // error_log("vanha järjestys " . $_SESSION[$this->orderColumn] . "\n" , 3, plugin_dir_path(FILE) . '../tmp/errors.log');
                    $_SESSION['selected'] = $this->orderColumn;
                    if ($_SESSION[$this->orderColumn] == SORT_ASC) {
                        $dir = SORT_DESC;
                        $_SESSION[$this->orderColumn] = SORT_DESC;
                    } else {
                        $dir = SORT_ASC;
                        $_SESSION[$this->orderColumn] = SORT_ASC;
                    }
                    // error_log("uusi järjestys " . $_SESSION[$this->orderColumn] . "\n" , 3, plugin_dir_path(FILE) . '../tmp/errors.log');
                 } else {
                    // error_log("Selected different column: " . $this->orderColumn . "\n" , 3, plugin_dir_path(FILE) . '../tmp/errors.log');
                    $_SESSION['selected'] = $this->orderColumn;
                    $dir = SORT_ASC;
                    $_SESSION[$this->orderColumn] = SORT_ASC;
                    // error_log("uusi järjestys " . $_SESSION[$this->orderColumn] . "\n" , 3, plugin_dir_path(FILE) . '../tmp/errors.log');
                 }

        }  else {
         //   error_log("nothing happens here" . "\n" , 3, plugin_dir_path(FILE) . '../tmp/errors.log');
        }
        // error_log($this->orderColumn . ", new direction: " . $dir . "\n" , 3, plugin_dir_path(FILE) . '../tmp/errors.log');
        return $dir;

    }
}