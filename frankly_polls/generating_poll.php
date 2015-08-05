<?php
include 'widget_shown.php';

/**
 * Created by PhpStorm.
 * User: chowmean
 * Date: 7/1/15
 * Time: 8:08 PM
 */
function add_poll(){
?><style>
    .form_data_poll{

        background:#bbb;
        padding:20px;
        border:1px solid #454545;
        border-radius: 3px;
        width:100%;
        

    }



    .form_poll_container
    {
        line-height: 20px;
        align: center;
        text-align: center;
        background: -webkit-linear-gradient(left, grey , #bbb); /* For Safari 5.1 to 6.0 */
        background: -o-linear-gradient(right, #bbb, grey); /* For Opera 11.1 to 12.0 */
        background: -moz-linear-gradient(right, #bbb, grey); /* For Firefox 3.6 to 15 */
        background: linear-gradient(to right, #bbb, grey); /* Standard syntax */
        color:darkslategray;
        font-size:0.8em;
        padding :30px;
        font-family : verdana, arial, sans-serif;
    }

    table
    {

        border:0px;
    }

    </style><html>
<head>
<script>
function getVote(value,id,key,question) {
    if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();
    } else {  // code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange=function() {
        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
            tr=JSON.parse(xmlhttp.responseText)
            if(tr.success==true)
            {
                data=JSON.parse(tr.polls);
                //console.log(question);
                var t=data.total;;
                var htm='<h2>'+question+'</h2>';
                for (var k in data){
                    if (k=='total') {
                    continue;
                    }
                    else
                    {
                     htm=htm+'<table><tr><td>'+k+'</td><td><img src="wp-content/plugins/frankly_polls/images/poll.gif" width="'+((data[k]/t)*100).toFixed(2)+'" style="height:20px;"> '+((data[k]/t)*100).toFixed(2)+'% </td></tr></table>';
                    }
                }

                document.getElementById(id).innerHTML=htm;
             }
        }
    }
  xmlhttp.open("GET","wp-content/plugins/frankly_polls/poll_vote.php?vote="+value+"&id="+id+"&key="+key,true);
  xmlhttp.send();
}
function recordresponse(question_id){
	console.log("it works");
	console.log(question_id);
var w = 700;
  var h = 400;
  var left = (screen.width / 2) - (w / 2);
  var top = (screen.height / 2) - (h / 2);
  var dir = '/recorder/recorder?type=question&resourceId='+question_id;
  document.addEventListener('click', function (ev) {
    window.openUniquePopUp(dir, 'popUp', 'width=' + w + ',height=' + h + ',top=' + top + ',left=' + left);
  }, true);
  (function initializeOpenUniquePopUp() {
    //set this to domain name
    var openedDomain = 'http://node.staging.frankly.me'; //app.utils.domain();
    var trackedWindows = {};
    window.openUniquePopUp = function (path, windowName, specs) {
      trackedWindows[windowName] = false;
      var popUp = window.open(null, windowName, specs);
      popUp.postMessage('ping', openedDomain);
      setTimeout(checkIfOpen, 1000);
      setInterval(checkIfPinged, 1000);

      function checkIfOpen() {
        if (!trackedWindows[windowName]) {
          console.log(openedDomain, path, windowName, specs);
          window.open(openedDomain + path, windowName, specs);
          popUp.postMessage('ping', openedDomain);
        }
      }

      function checkIfPinged() {
        popUp.postMessage('ping', openedDomain);
      }
    };

    if (window.addEventListener) {
      window.addEventListener('message', onPingBackMessage, false);

    } else if (window.attachEvent) {
      window.attachEvent('message', onPingBackMessage, false);
    }

    function onPingBackMessage(event) {
      if (1) {
        var winst = event.source;
        winst.close();
        console.log(event.data);
        trackedWindows[event.data] = true;
      }
    };
  })();
}
</script>
</head>
<body>
<div id="poll" class="form_poll_container">
<img width='100%' height="8%" src="wp-content/plugins/frankly_polls/images/xlogo-full.png.pagespeed.ic.Q3_hJvTDeH.png" ><br><br>


    <?php  $results=showing_polls();


for($i=0;$i<count($results);$i++) // loop to give you the data in an associative array so you can use it however.
{
	if($results[$i]->question_type==1){
?>


    <div class='form_data_poll' id='<?php echo $results[$i]->id;?>'>
        <h1><?php echo $results[$i]->question   ?></h1>
        </br><?php

        $option=json_decode($results[$i]->options,true);
        foreach($option as $key => $value)
        {echo $key;?>
            <input type="radio" name="<?php echo $results[$i]->id; ?>" value="<?php echo $value; ?>" onclick="getVote(this.value,this.name,'<?php print $key;?>','<?php print $results[$i]->question;?>')"><br>
        <?php
        }
        ?>
    </div>
	<button name="record" onclick="recordresponse('<?php print $results[$i]->id;?>')">Velfie Response</button>

<?php
echo '<br><br>';}}
?></div>
</body>
</html><?php



}


