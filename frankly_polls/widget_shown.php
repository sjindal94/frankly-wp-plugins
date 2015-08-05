<?php
function showing_polls()
{


    global $wpdb;
    $pre=$wpdb->prefix;
    $results = $GLOBALS['wpdb']->get_results( 'SELECT * FROM `'.$pre.'frankly_poll` WHERE 1' );
    return $results;
    /*for($i=0;$i<= count($results);$i++) // loop to give you the data in an associative array so you can use it however.
    {
        print $results[$i]->id;
        print $results[$i]->question;
        print $results[$i]->question_type;
        print $results[$i]->options;
    }*/


}