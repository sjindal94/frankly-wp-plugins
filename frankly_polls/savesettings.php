<?php
$data="";
require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/wp-load.php');
global $wpdb;
$your_db_name = $wpdb->prefix .'frankly_poll';
if ( isset($_POST)){
        $qids=$HTTP_RAW_POST_DATA;
		$sql=$wpdb->prepare("UPDATE `".$your_db_name."` SET `question_type`=1 WHERE `id` in %s",$qids);
		$sql=str_replace( "'$qids'", "(".$qids.")", $sql );
		echo $sql;
		$result = $wpdb->query($sql);
		print_r($result);
		if ($result) $data="treue";
		else $data="falese";
    }
else $data="error";
print '{"success":'.$data.'}';
//wasted 1 whole day and lots of google searches on this piece...*APPLAUSE* *STANDING OVATIONS*
//-sjindal
?>