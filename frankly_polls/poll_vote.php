<?php
$url = (!empty($_SERVER['HTTPS'])) ? "https://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] : "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
$url = $_SERVER['REQUEST_URI'];
$my_url = explode('wp-content' , $url);
$path = $_SERVER['DOCUMENT_ROOT'].$my_url[0];
include_once $path . '/wp-config.php';
include_once $path . '/wp-includes/wp-db.php';
include_once $path . '/wp-includes/pluggable.php';

$vote= $_REQUEST['vote'];
$id=$_REQUEST['id'];
$key= $_REQUEST['key'];
set_polls_values($id,$key,$vote);


function set_polls_values($id,$option,$valued)
{
    $newJsonArray=array();
    global $wpdb;
    $pre=$wpdb->prefix;

    $results=$wpdb->get_results( $wpdb->prepare(
        "SELECT * FROM ".$pre."frankly_poll  where id= %d ", $id
    ));


    for($i=0;$i< count($results);$i++)
        {
        $option_saved=$results[$i]->options;
        }
    $option_saved=json_decode($option_saved,true);
    $sum=0;
    foreach($option_saved as $key => $value)
        {
            $sum=$sum+$value;
            if($key==$option)
            {
                $sum=$sum+1;
                $value=$valued+1;
            }
            $newJsonArray[$key]=$value;
        }
    $total_count=$sum;

    $temJsonArray=$newJsonArray;
    $temJsonArray['total']=$total_count;
    $newJsonArray=json_encode($newJsonArray);

    $e=$wpdb->update(
        $pre.'frankly_poll',
        array(
            'options' => $newJsonArray,  // string
             // integer (number)
        ),
        array( 'id' => $id ),
        array(
            '%s'   // value1
                // value2
        ),
        array( '%d' )
    );

    $data=array();


    if($e==1)
    {
        $data['success']=true;
        $data['polls']=(json_encode($temJsonArray));
        $data=json_encode($data);
        print $data;
    }
    else
    {
        $data['success']=false;
        $data['message']='Unable to process the request.';
        $data=json_encode($data);
        print $data;
    }
}
?>