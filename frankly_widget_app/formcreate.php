

<?php function generating_form( $inst )
{

?>


<style>
    .frankly_container
    {
        margin:10%;
        padding: 30px;
        line-height: 20px;
        align: center;
        text-align: center;
        background: -webkit-linear-gradient(left, grey , #bbb); /* For Safari 5.1 to 6.0 */
        background: -o-linear-gradient(right, #bbb, grey); /* For Opera 11.1 to 12.0 */
        background: -moz-linear-gradient(right, #bbb, grey); /* For Firefox 3.6 to 15 */
        background: linear-gradient(to right, #bbb, grey); /* Standard syntax */
        color:darkslategray;
        font-size:0.8em;
        width:80%;
        font-family : verdana, arial, sans-serif;
    }
    .credentails_container
    {
        height:30px;
        padding:30px;
        font-size:10px;
        line-height: 20px;
        font-family : verdana, arial, sans-serif;
        width:80%;
    }



    #credentials
    {
        width:60%;
    }

    .err_class
    {
        background:grey;
        color:#800000;
        font-size:0.8em;
        width:80%;
        font-family : verdana, arial, sans-serif;
        border-radius:10px;

    }

    .in_class
    {
        border-radius:5px;
        color:darkgrey;
        line-height:10px;
        font-size:0.8em;
        font-family : verdana, arial, sans-serif;
        height:30px;
    }

    .in_class:hover
    {
        box-shadow: 1px 1px 1px 1px green;
    }

    .res_class
    {
        background:mediumseagreen;
        color:#800000;
        font-size:0.8em;
        width:100%;
        font-family : verdana, arial, sans-serif;
        border-radius:10px;
    }


    .useful_keys
    {
        background: -webkit-linear-gradient(left, #F15C5C , #E83434); /* For Safari 5.1 to 6.0 */
        background: -o-linear-gradient(right, #E83434, #F15C5C); /* For Opera 11.1 to 12.0 */
        background: -moz-linear-gradient(right, #E83434, #F15C5C); /* For Firefox 3.6 to 15 */
        background: linear-gradient(to right, #E83434, #F15C5C); /* Standard syntax */
        border-radius: 5px;
        padding:5px;
        border: 1px solid indianred;


    }


    </style>






    <script>
var popupWindow=null;
function child_open(){
	document.body.onfocus=parent_disable;
	popupWindow =window.open('wp-content/plugins/frankly_widget_app/new.jsp',"_blank","directories=no, status=no, menubar=no, scrollbars=yes, resizable=no,width=600, height=280,top=200,left=200");
}
//var j=0;
function parent_disable() {
if(popupWindow && !popupWindow.closed){
	//document.getElementById('new').innerHTML=j++;
	//window.blur();
	//document.activeElement.blur();
	popupWindow.focus();
}
}
<!--<body onFocus="parent_disable();" onclick="parent_disable();"><a href="javascript:child_open()">Click me</a></body>-->


        function validateEmail(mail)
        {
            if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(mail))
            {
                return (true)
            }

            return (false)
        }

        function validateForm() {


            document.getElementById('name_error').innerHTML='';
            document.getElementById('email_error').innerHTML='';
            document.getElementById('ques_error').innerHTML='';

            flag =0;
            var x = document.getElementById("name").value;
            if (x == null || x == "") {
                document.getElementById('name_error').innerHTML='Fill your name to ask question';
                flag=1;
            }

            var y = document.getElementById("email").value;
            if (y == null || y == "" ) {
                document.getElementById('email_error').innerHTML='Email field must not be blank';
                flag=1;
            }

            if(validateEmail(y)==false){
                document.getElementById('email_error').innerHTML='Email Must be in correct format';
                flag=1;
            }

            var z = document.getElementById("question").value;
            if (z == null || z == "" ) {
                document.getElementById('ques_error').innerHTML='Question field must not be blank';
                flag=1;
            }
            if(z.length <= 15)
            {
                document.getElementById('ques_error').innerHTML='Question must longer than 15 characters long';
                flag=1;
            }
            if (flag==0)
                return true;
            else
                return false;
        }







        /*function enter_credentials()
        {

            document.getElementById("credentials").style.display='inline';
            document.getElementById("showcred").style.display='none';
        }*/






        function calling() {
			console.log("workking");
            if(validateForm())
            {
                <!-- $inst['key'] is mail id where to recieve mails-->

                var jname = encodeURIComponent(document.getElementById("name").value);
                var jmail = encodeURIComponent(document.getElementById("email").value);
                var jsub = encodeURIComponent(document.getElementById("question").value);
                var options = new Array();
                <?php
                    $j=0;
                    foreach ($inst as $ins)
                         {
                            if($j <= 4)
                              {$j=$j+1;continue;}

                            else
                                {if($ins=='')continue;
                          ?>
                        options.push("<?php echo $ins;?>")

                <?php
                             }
                         }
                ?>
               //console.log(options);
                var values=new Array();
                var arrayLength = options.length;
                for (var i = 0; i < arrayLength; i++) {
                    var t=encodeURIComponent(document.getElementById(options[i]).value);
                    values.push(t)
                }
                //console.log(values);

                var post = JSON.stringify({"name":jname,"mail":jmail,"question_body":jsub,"musername":<?php echo "'".$inst['username']."'";?>,"to":<?php echo "'".$inst['key']."'";?>,"from":<?php echo "'".$inst['from']."'";?>,"options":options,"values":values});
                var http = new XMLHttpRequest();
                http.open("POST", "wp-content/plugins/frankly_widget_app/apicalls.php", true);
                http.setRequestHeader("Content-type", "application/json");
                http.onreadystatechange = function() {
                    if(http.readyState === 4){
						console.log("working2");
                        t=JSON.parse(http.responseText);
                        //console.log(t.link);
                        //console.log(t.success);
                        if(t.success==true)
                        {
                            //console.log(1);
                            document.getElementById('response_frankly').innerHTML="Your question is Posted successfully.<br> Link for your question is <a href='"+t.link+"'>"+ t.link +"</a>";
                        }
                        if(t.success==false)
                        {
                            //console.log(1);
                            document.getElementById('failed_response_frankly').innerHTML= t.message;
                        }
                    }
                }
                http.send(post);}
            else
            {console.log("Error in form validation");}
        }

    </script>

<div class="frankly_container" name="mail_form" >

    <div id='frankly_div'></div>
    
    <img width='100%' height="8%" src="wp-content/plugins/frankly_widget_app/images/xlogo-full.png.pagespeed.ic.Q3_hJvTDeH.png" >
    <p>Your Name (required)
        <span ><input type="text" class="in_class" placeholder="Your Name" name="name" value="Ronit" id="name"/><center><div class='err_class'id="name_error"></div></center></span> </p>
    <p>Your Email (required)
        <span ><input type="email" class="in_class" name="email" id="email" placeholder="Your Email" value="teapyari@maildrop.cc"  /></span> <center><div class='err_class'id="email_error"></div></center></p>
    <p>Your Question (required)
        <span><input type="text"  class="in_class" name="question" id="question" placeholder="Your Question" value="javfjasvfhasfajsfasjb"   /><center><div class='err_class'id="ques_error"></div></center></span> </p>
<div id="new"></div>

    <?php

    $i=0;
    foreach ($inst as $ins)
    {
        if($i<=4) $i=$i+1;
        else {
			if($ins!="")
            ?>
            <p><?php echo $ins;?>
                <span ><input class="in_class" type="text" name="<?php echo $ins;?>" id="<?php echo $ins;?>"  value=""   /></span> </p>
        <?php
        }

    }



    ?>


        <!--<div><button id="showcred" class="useful_keys" onclick="enter_credentials()">Enter credentials</button><br>
    <div id="credentials" style="display: none;" >
        <input class="credentails_container" type="text" name="username" id="username" placeholder="Your frankly Username" value=""  />
        <input class="credentails_container" type="password" name="passwd" id="passwd" placeholder="Your frankly Password"   /><br>
    </div></div>-->


<br>
    <center><div id="failed_response_frankly" class="err_class"></div></center>
    </br><div><button  class="useful_keys" value="ASK" name="submit" onclick="calling()">Ask|<?php echo $inst['username'];?> </button></div><br>
    <center><div id="response_frankly" class="res_class"></div></center>
</div>



        <?php }?>
