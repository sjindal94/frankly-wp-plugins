<?php
include 'generating_poll.php';
/*
Plugin Name: Frankly Polling Widget
Plugin URI: http://www.frankly.me
Description: Polling widget by Social video selfie sharing and question answering application for android ios and web.
Author: chowmean and sjindal
Version: 1.0
Author URI: www.github.com/chowmean
*/
// Block direct requests
if ( !defined('ABSPATH') )
    die('-1');
add_action( 'widgets_init', function(){
    register_widget( 'My_Poll_Widget' );

});





/**
 * Adds My_Widget widget.
 */
class My_Poll_Widget extends WP_Widget {


    /**
     * Register widget with WordPress.
     */
    function __construct() {
        parent::__construct(
            'My_Poll_Widget', // Base ID
            __('Frankly Poll Widget', 'text_domain'), // Name
            array( 'description' => __( 'Polling widget by Social video selfie sharing and question answering application for android ios and web.', 'text_domain' ), ) // Args
        );
    }



    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget( $args, $instance ) {
        echo $args['before_widget'];
		echo "this widget is for showing pools";
        add_poll();
        echo $args['after_widget'];
    }










    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    public function form( $instance ) {


    }










    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        return $instance;
    }





} // class My_Widget
add_action('admin_menu', 'plugin_admin_add_page');  //adds the plugin and calls plugin_admin_add_page() funtion


function plugin_admin_add_page() {
    add_options_page('Frankly Polls Plugin Page', 'Frankly Polls Plugin Menu', 'manage_options', 'plugin', 'plugin_options_page');//calls plugin_options_page() funtion
}
?><?php // display the admin options page
function plugin_options_page() {
    ?><div id="setting_panel" class=".setting_panel"><center>
        <h2>Frankly polls Settings Panel</h2>
        Options relating to the Frankly Polling Plugin.
			<form action="options.php" method="post">
            <?php settings_fields('plugin_options'); ?>
            <?php do_settings_sections('plugin'); ?>
            <input name="Submit" type="submit" value="<?php esc_attr_e('Save Changes'); ?>" />
        </form>
<center>
    </div><?php
}
?><?php // add the admin settings and such

add_action('admin_init', 'plugin_admin_init');
function plugin_admin_init()
{
    register_setting( 'plugin_options', 'plugin_options', 'plugin_options_validate' );
    add_settings_section('plugin_main', 'Main Settings', 'plugin_section_text', 'plugin');   //calls plugin_section_text()
    //add_settings_field('plugin_text_string', 'Plugin Text Input', 'plugin_setting_string', 'plugin', 'plugin_main');

}



$your_db_name = $wpdb->prefix .'frankly_poll';
function plugin_section_text()
{
    global $wpdb;
	global $your_db_name;
	$sql = "SELECT * FROM ".$your_db_name;
	$results = $wpdb->get_results($sql) or die(mysql_error());
	if ( $results ){
	?><style>
.table{width: 500px;height: 200px;border-collapse:collapse;}
.table-wrap{max-height: 200px;width:100%;overflow-y:auto;overflow-x:hidden;}
.table-dalam{height:100px;width:600px;border-collapse:collapse;}
.td-nya{min-width:30px;border-left:1px solid white;border-right:1px solid grey;border-bottom:1px solid    grey;}
</style>
<script>
function savechange() {
    var i, l, boxes = document.getElementsByName("question");
	var checks=new Array();
	for (i = 0, l = boxes.length; i < l; i++) {
		if(boxes[i].cells[4].firstChild.checked){
			checks.push(boxes[i].cells[0].getAttribute("data-qid"));
		}
	}
	console.log(checks);
	if(checks.length){
		var http = new XMLHttpRequest();
		http.open("POST", "../wp-content/plugins/frankly_polls/savesettings.php", true);
		http.setRequestHeader("Content-type", "application/json");
		http.onreadystatechange = function() {
			if(http.readyState === 4){
				document.getElementById("table").innerHTML="please refresh";
			}
		}
		http.send(checks);
	}
};
</script>
	<table>
<thead>
    <tr>
    <th>ID</th>
	<th>Question</th>
    <th>Options</th>
    <th>Type</th>
    <th>Show</th>
   </tr>
 </thead>
 <tbody>
   <tr>
      <td colspan="5">
      <div class="table-wrap" >
      <table class="table-dalam">
         <tbody>
             <?php foreach( $results as $result ) {?>
                 <tr name="question">
                     <td class="td-nya" data-qid=<?php echo $result->id;?>><?php echo $result->id;?></td>
                     <td class="td-nya"><?php echo $result->question;?></td>
                     <td class="td-nya"><?php echo $result->options;?></td>
                     <td class="td-nya"><?php echo $result->question_type;?></td>
					 <?php if($result->question_type==0){?>
					 <td class="td-nya"><input type="checkbox" name="box"></input></td>
					 <?php }else{?>
					 <td class="td-nya"><input type="checkbox" name="box" checked></input></td>
					 <?php }?>
                 </tr> 
			 <?php }?>
        </tbody>
       </table>
     </div>
   </td>
 </tr>
  </tbody>
 </table>
 <?php
	}
}
// run the install scripts upon plugin activation
$your_db_name = $wpdb->prefix .'frankly_poll';
install_frankly_db();

function  install_frankly_db() {
    global $wpdb;
    global $your_db_name;
    if($wpdb->get_var("show tables like '$your_db_name'") != $your_db_name)
    {
        $sql = "CREATE TABLE " . $your_db_name . " (
		`id` int NOT NULL,
		`question` text NOT NULL,
		`question_type` int NOT NULL,
		`options` text NOT NULL,
		`video_response` text,
		`username` text,
		UNIQUE KEY id (id)
		);";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
    {
//remove this portion
        $id = 1;
        $question="jasvja";
        $question_type=0;//valid
        $options='{"opt1":23,"opt2":66,"opt3":88}';
        $video=NULL;
        $username=NULL;
        $table_name = $your_db_name;

        $wpdb->insert(
            $table_name,
            array(
                'id' => $id,
                'question' => $question,
                'question_type' => $question_type,
                'options' => $options,
                'video_response' => $video,
                'username' => $username,
            )
        );
        $id = 2;
        $question="Did you like this plugin?";
        $question_type=0;//valid
        $options='{"yes":10,"no":2}';
        $video=NULL;
        $username=NULL;
        $wpdb->insert(
            $table_name,
            array(
                'id' => $id,
                'question' => $question,
                'question_type' => $question_type,
                'options' => $options,
                'video_response' => $video,
                'username' => $username,
            )
        );
    }
}
// run the install scripts upon plugin activation



?>