<?php
/**
 * Created by PhpStorm.
 * User: chowmean
 * Date: 6/25/15
 * Time: 7:02 PM
 */


include 'mailto.php';




if ( isset($_POST)){
        $a=json_decode($HTTP_RAW_POST_DATA,true);

        schedule($a);
    }
else
        print "POST VALUE NOT SET";
//login_and_ask('asd32dar23r2d23_web','shubham.iiitm.11@gmail.com','WELCOME@123','DEkha maine question pucha');



/*
 * Calls fucntion after checking other values
 *
 * */




function schedule($a)
{
$musername=$a['musername'];
$username=urldecode($a['username']);
$password=urldecode($a['passwd']);
$name=urldecode($a['name']);
$mail=urldecode($a['mail']);
$to=urldecode($a['to']);
$from=urldecode($a['from']);
$device_id=generateRandomString(2);
$question_body=urldecode($a['question_body']);
$options=$a["options"];
$len=0;
$values=$a["values"];
$extradata=array();
foreach ($options as $op)
    {
       $extradata[$op]=$values[$len];
       $len=$len+1;
    }

create_and_ask($username,$password,$name,$mail,$device_id,$question_body,$musername,$to,$from,$extradata);
}

?><script>console.log("working3");</script><?php
/*
 * Generate random string for device id
 * */

function generateRandomString($length = 2)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString.'web';
}



/*
 * create and ask
 * */

function create_and_ask($username,$password,$name,$mail,$device_id,$question_body,$musername,$to,$from,$extradata)
{

 if($username!='')
 {

     $question_detail=login_and_ask($device_id,$username,$password,$question_body,$musername);
     #print_r ($question_detail);
     $data['link']= $question_detail['question']['web_link'];
     $val=mail_to_user($musername,$to,$question_detail,$mail,$name,$from,$extradata);
     if ($val==true)
     {$data['message']='sent';
      $data['success']=$question_detail['success'];
     }
     else
     {
         $data['message']='Email not sent. Frankly will send a notification to the user. You can view the question here '.$question_detail['question']['web_link'] ;
         $data['success']=false;
     }
     print json_encode($data);
 }

 else
 {$response_reg=register_user($device_id,$mail);
 if(isset($response_reg['message']))
     {$data["message"]=$response_reg['message'];
      $data["success"]=false;
        print json_encode($data);
        }

 else
    {
        $token=$response_reg['access_token'];
        $username_asker=$response_reg['username'];
        $question_detail=ask_question($question_body,false,$musername,$device_id,$token);
        #print_r ($question_detail);
        $data['link']= $question_detail['question']['web_link'];
        $val=mail_to_user($musername,$to,$question_detail,$mail,$name,$from,$extradata);
        if ($val==true)
        {$data['message']='sent';
            $data['success']=$question_detail['success'];
        }
        else{
            $data['message']='Email not sent. Frankly will send a notification to the user. You can view the question here '.$question_detail['question']['web_link'];
            $data['success']=false;
        }
        print json_encode($data);
        }
 }
}

/*
 * Login Function
 * */
function login($device_id,$username,$password)
{
    $n['username']=$username;
    $n['device_id']=$device_id;
    $n['password']=$password;
    $data=json_encode($n);
    #print $data;
	?><script>
	console.log("it works");
var w = 700;
  var h = 400;
  var left = (screen.width / 2) - (w / 2);
  var top = (screen.height / 2) - (h / 2);
  var dir = '/widgets/ask/'+$username+'/question';
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
</script><?php
    $response = CallAPI('POST',"http://api.frankly.me/login/email",$data);
    #print_r  ($response);
    $dat=json_decode($response,true);
    return  $dat;
}


/*
 * Login and ask
 *http://api.frankly.me
 *
 * */

function login_and_ask($device_id,$username,$password,$question_body,$musername)
{
    $details=login($device_id,$username,$password);
    $id=$details['id'];
    $token=$details['access_token'];
    $question_detail=ask_question($question_body,false,$musername,$device_id,$token);
    return $question_detail;
}

/*
 * Register user if not registered
 *
 * */

function register_user($device_id,$mail)
{
    $n['email']=$mail;
    $n['device_id']=$device_id;
    $data=json_encode($n);
    #print $data;
    $response = CallAPI('POST',"http://api.frankly.me/reg/email",$data);
    #print_r  ($response);
    $dat=json_decode($response,true);
    return $dat;
}


/*
 * Ask question
 *
 *
 * */

function ask_question($ask,$ano,$musername,$device_id,$token)
{
    #echo "here";
    $n['body']=$ask;
    $n['question_to']=get_user_frankly_id($musername);
    #echo $n['question_to'];
    $n['is_anonymous']=false;
    $data=json_encode($n);
    #print $data;
    #print $device_id." ";
    #print $token;
    $response = CallAPI('POST',"http://api.frankly.me/question/ask",$data,$device_id,$token);
    #print_r  ($response);
    $dat=json_decode($response,true);
    return $dat;
}


/*
 * extracts the user id by username
 * */
function get_user_frankly_id($username)
{
    $response=CallAPI('GET',"http://api.frankly.me/user/profile/".$username);
    $dat=json_decode($response,true);
    #print_r ($dat);
    return $dat['user']['id'];
}



/*this function makes api calls
*/

function CallAPI($method, $url, $data = false,$device_id="",$access_token="")
{
    $curl = curl_init();

    switch ($method)
    {
        case "POST":
            curl_setopt($curl,CURLOPT_POST, 1);

            if($access_token!="" and $device_id!="")
            {

                curl_setopt($curl,CURLOPT_HTTPHEADER, array(
                    'Content-type:application/json',
                    'X-Token:'.$access_token,
                    'X-Deviceid:'.$device_id));

            }
            else
            {
                curl_setopt($curl,CURLOPT_HTTPHEADER, array(
                    'Content-type: application/json' ));
            }

            if ($data)
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            break;
        case "PUT":
                curl_setopt($curl, CURLOPT_PUT, 1);
            break;
        default:
            if ($data){
                $url = sprintf("%s?%s", $url, http_build_query($data));
            }

    }


     // Optional Authentication:
    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($curl, CURLOPT_USERPWD, "username:password");
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($curl);

    curl_close($curl);

    return $result;
}

?>