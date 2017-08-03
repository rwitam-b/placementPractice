<?php
    require 'sessionize.php';
    require_once 'DB.php';

    function startsWith($haystack, $needle) {
        // search backwards starting from haystack length characters from the end
        return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== false;
    }

    function endsWith($haystack, $needle) {
        // search forward starting from end minus needle length characters
        return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== false);
    }

    function encodeStrong($s){
        return "<strong>".$s."</strong>";
    }
    
    function encodeOutput($head, $body){
        $id=str_replace(" ","_",strtolower($head));
        $show = "<div id=\"$id\" class=\"well text-center myWell\"><h4>".encodeStrong(strtoupper($head));        
        if(strpos($body, "class=\"text-danger\">")!== false){
            $show = $show." -> <span class=\"text-danger glyphicon glyphicon-remove\"></span></h4><br>".$body."</div>";
        }
        else{
            $show = $show." -> <span class=\"text-success glyphicon glyphicon-ok\"></span></h4><br>".$body."</div>";
        }
        return $show;
    }

    function encodeSuccess($s){
        return "<div class=\"text-success\">".encodeStrong($s)."</div>";
    }

    function encodeFailure($s){
        return "<div class=\"text-danger\">".encodeStrong($s)."</div>";
    }

    function encodeInfo($s){
        return "<div class=\"text-info\">".encodeStrong($s)."</div>";
    }

    function checkSubject($sub,$data){        
        $msg = "";
        try{
            if(!startsWith(strtolower($sub),"subject")){
                throw new Exception(encodeFailure("Subject Not Found !<br>The First Line Should Contain The Subject !"));
            }
            $subjectWords = explode(" ",$sub);
            if($subjectWords[0] == "Subject:"){
                $msg = encodeSuccess("Subject Started Properly !");
            }            
            elseif(strtolower($subjectWords[0]) == "subject:"){
                $tempMsg = "Your Subject Does Not Seem To Have Started Properly !<br>";
                $tempMsg .= "Email Writing Is Case Sensitive !<br>";
                $tempMsg .= "\"".encodeStrong(substr($subjectWords[0],0,8))."\""." Should Have Been "."\"";
                $tempMsg .= encodeStrong("Subject:")."\"";
                throw new Exception(encodeFailure($tempMsg));
            }
            else{
                $tempMsg = "Your Subject Does Not Seem To Have Started Properly !<br>";
                $tempMsg .= "Subject Line Should Have Started With \"<strong>Subject: </strong>\"";
                $tempMsg .= "Instead Of \"<strong>".substr($sub,0,strlen($sub)>9?9:strlen($sub))."....</strong>\"";
                throw new Exception(encodeFailure($tempMsg));
            }
            $msg .= "<br>";
            if(strlen(trim($sub)) == 8){
                throw new Exception(encodeFailure("Subject Started Properly, But No Subject Body Found !"));
            }            
            if(!ctype_alpha($sub[strlen($sub)-1])){
                $msg .= encodeFailure("Subject Should Not Have \"" . encodeStrong($sub[strlen($sub)-1]) . "\" At The End !");
                $msg .= "<br>";
            }
        }catch(Exception $e){
            $msg .= $e->getMessage()."<br>";
            $msg .= encodeFailure("Correct Format -> <pre>Subject:&lt;space>&lt;subject text></pre>");
        }
        return $msg;
    }

    function checkRecipient($receiver,$data){
        $msg = "";
        try{
            if(strlen($receiver) < 5){
                throw new Exception(encodeFailure("Receiver Not Found !<br>The Second Line Should Address The Recipient !"));
            }
            if(startswith($receiver,"Dear ")){
                $msg = encodeSuccess("Receiver Salutation Looks Correct !");
                $receiver = substr($receiver,5);
            }
            elseif(startswith(strtolower($receiver),"dear ")){
                $msg = "Receiver Salutation Is Case Sensitive!<br>";
                $msg .= "\"" . encodeStrong(substr($receiver,0,4)) . "\" Should Have Been \"" . encodeStrong("Dear") . "\" !";
                $msg = encodeFailure($msg);
                $receiver = substr($receiver,5);
            }
            else{
                $tempMsg = "Receiver Salutation Wrong !<br>";
                $tempMsg .= "Receiver Line Should Have Started With \"" . encodeStrong("Dear ") . "\" ";
                $tempMsg .= "Instead Of \"" . encodeStrong(substr($receiver,0,4)) . "....\" !";
                throw new Exception(encodeFailure($tempMsg));
            }
            $msg .= "<br>";
            if($receiver[strlen($receiver)-1] != ","){
                $msg .= encodeFailure("There Should Be A \"" . encodeStrong(",") . "\" After The Recipient Name !") . "<br>";
            }
            else{
                $receiver = substr($receiver,0,-1);
            }
            if($data["receiver_type"] == "S"){
                $tempMsg = "The Receiver Type Here Is " . encodeStrong("Specific");
                $tempMsg .= ", Meaning That Exact Name Is Provided !";
                $msg .= encodeInfo($tempMsg);
                if($data["receiver"] == $receiver){
                    $msg .= encodeSuccess("Receiver Name Correct !");
                }
                else{
                    $tempMsg = "Receiver Name Incorrect !<br>";
                    $tempMsg .= "\"" . encodeStrong($receiver) . "\" Should Have Been \"";
                    $tempMsg .= encodeStrong($data["receiver"]) . "\" !";
                    $msg .= encodeFailure($tempMsg);
                }
            }
            elseif($data["receiver_type"] == "NS"){
                $tempMsg = "The Receiver Here Is " . encodeStrong("Not Mentioned,");
                $tempMsg .= " But It Is Established That The Recipient Is " . encodeStrong("Singular In Nature!");
                $msg .= encodeInfo($tempMsg);
                $msg .= "Receiver Name Looks Right !<br>";
            }
            elseif($data["receiver_type"] == "NP"){
                $tempMsg = "The Receiver Here Is " . encodeStrong("Not Mentioned,");
                $tempMsg .= " But It Is Established That The Recipient Is " . encodeStrong("Generic In Nature!");
                $msg .= encodeInfo($tempMsg);                
                $names=explode(",",$data["receiver"]);
                if(in_array($receiver,$names)){
                    $msg .= encodeSuccess("Receiver Name Looks Correct !");
                }
                else{
                    $msg .= encodeFailure("Receiver Name \"" . encodeStrong($receiver) . "\" Does Not Look Right !");
                    $tempMsg = "Name Suggestions -> ";
                    $tempMsg .= implode(" - ",$names);
                    $msg .= encodeInfo($tempMsg);
                    $names_lower=explode(",",strtolower($data["receiver"]));
                    $receiver_lower=strtolower($receiver);
                    if(in_array($receiver,$names)){
                        $tempMsg = "You Got The Name Correct, But It Is Case Sensitive !<br>";
                        $tempMsg .= "\"" . encodeStrong($receiver) . "\" Should Have Been \"";
                        $index = array_search($receiver_lower,$names_lower);
                        $tempMsg .= encodeStrong($names[index]);
                        $tempMsg .= "\" !";
                        $msg .= encodeFailure($tempMsg);
                    }
                }
            }
        }
        catch(Exception $e){
            $msg .= $e->getMessage()."<br>";
            $msg .= encodeFailure("Correct Format -> <pre>Dear&lt;space>&lt;receiver name>,</pre>");
        }
        return $msg;
    }

    function checkSender($sender,$data){
        $msg = "";
        try{
            if(count($sender) == 0){
                throw new Exception(encodeFailure("You Did Not Take Leave Properly !"));
            }
            elseif(count($sender) > 2){
                $tempMsg = "Extra Information Found In The Leave Taking Section !<br><pre>";
                foreach($sender as $line){                
                    $tempMsg .= $line . "\n";
                }
                $tempMsg .= "</pre>";
                throw new Exception(encodeFailure($tempMsg));
            }
            elseif(count($sender) == 1){
                throw new Exception(encodeFailure("Inadequate Data In The Leave Taking Section !<br>Sender Details Missing !"));
            }
            else{
                $msg = encodeSuccess("Leave Taking Is Appropriate !");
                $msg .= "<br>";
                $senderName = $sender[1];
                if($data["sender_type"] == "S"){
                    $tempMsg = "The Sender Type Here Is " . encodeStrong("Specific");
                    $tempMsg .= ", Meaning That Exact Name Is Provided !";
                    $msg .= encodeInfo($tempMsg);
                    if($data["sender"] == $senderName){
                        $msg .= encodeSuccess("Sender Name Correct !");
                    }
                    else{
                        $tempMsg = "Sender Name Incorrect !<br>";
                        $tempMsg .= "\"" . encodeStrong($senderName) . "\" Should Have Been \"";
                        $tempMsg .= encodeStrong($data["sender"]) . "\" !";
                        $msg .= encodeFailure($tempMsg);
                    }
                }
                elseif($data["sender_type"] == "N"){
                    $tempMsg = "The Sender Name Is " . encodeStrong("Not Provided");
                    $tempMsg .= ", Meaning That Any Name Will Do !";
                    $msg .= encodeInfo($tempMsg);                    
                    $msg .= encodeSuccess("Sender Name Looks Correct !");                   
                }
            }
        }
        catch(Exception $e){
            $msg .= $e->getMessage() . "<br>";
            $msg .= encodeFailure("Correct Format -> <pre>Regards,\n&lt;sender name></pre>");
        }
        return $msg;
    }

    function checkPhrases($email,$data){
        $msg = "";      
        $found=array();  
        try{            
            $phrases=explode(",",$data["phrases"]);            
            foreach($email as $line){
                $temp = explode(" ",$line);
                for($a=0;$a<count($temp);$a++){
                    $word = $temp[$a];
                    if(ctype_alpha($word) and in_array($word,$phrases) and !in_array($word,$found)){
                        array_push($found,$word);                        
                    }
                    elseif(!ctype_alpha($word[strlen($word)-1]) and in_array(substr($word,0,-1),$phrases) and !in_array(substr($word,0,-1),$found)){                    
                        array_push($found,substr($word,0,-1));
                    }
                    else{
                        $b = -1;
                        for($x=0;$x<count($phrases);$x++){
                            if(startsWith($phrases[$x],$word)){
                                $b = $x;
                                break;
                            }
                        }
                        if($b != -1){
                            $check = explode(" ",$phrases[$b]);
                            $flag = 1;
                            for($x=0;$x<count($check);$x++){
                                if(($a + $x) > (count($temp) - 1)){
                                    $flag = 0;
                                    break;
                                }
                                if(ctype_alpha($temp[$a + $x]) and $temp[$a + $x] != $check[$x]){
                                    $flag = 0;
                                    break;
                                }
                                if(!ctype_alpha($temp[$a + $x][strlen($temp[$a + $x])-1]) and substr($temp[$a + $x],0,-1) != $check[$x]){
                                    $flag = 0;                                
                                    break;
                                }
                            }
                            if($flag==1 and !in_array($phrases[$b],$found)){                            
                                array_push($found,$phrases[$b]);
                            }                    
                        }
                    }
                }
            }
            $temp = explode(",",$data["phrases"]);
            if(count($temp) == count($found)){
                $msg .= encodeSuccess("All The Phrases Of The Given Outline Were Used !");
            }
            else{
                $tempMsg = "You Missed Out These Phrases -><br><pre>";
                $missing = array_diff($temp,$found);
                foreach($missing as $phraseMiss){
                    $tempMsg .= " -" . $phraseMiss . "- ";
                }
                $tempMsg .= "</pre>";
                $msg .= encodeFailure($tempMsg);
            }
            $GLOBALS["found"]=$found;
        }
        catch(Exception $e){
            $msg = encodeFailure("Error In Phrase Checking");
        }
        return $msg;
    }

    function checkPhraseSequence($data) {
        $msg = "";
        try{
            $found=$GLOBALS["found"];
            if(count($found) == 0){
                throw new Exception();
            }
            $actual = explode(",",$data["phrases"]);            
            $misplaced = array();                  
            foreach($found as $item){                
                if(array_search($item,$actual) != array_search($item,$found)){
                    array_push($misplaced,$item);                    
                }                
            }
            if(count($misplaced) == 0){
                if(count($actual) != count($found)){
                    $msg .= encodeSuccess("The Phrases Were Being Used In Proper Sequence !");
                    $msg .= encodeFailure("But All The Phrases Were Not Used !");
                }
                else{
                    $msg .= encodeSuccess("The Phrases Are In Proper Sequence !");
                }
            }
            else{
                $tempMsg = "The Sequence Of The Following Phrases Were Not Maintained !<br><pre>";
                foreach($misplaced as $phraseMiss){
                    $tempMsg .= " -" . $phraseMiss . "- ";
                }
                $tempMsg .= "\n</pre>";
                $tempMsg .= "<br>Better Try And Maintain The Order !";
                $msg .= encodeFailure($tempMsg);
            }
        }
        catch(Exception $e){
            $msg .= encodeFailure("No Phrases Were Used At All !");
        }
        return $msg;
    }

    if (isLoggedIn() and $_SERVER["REQUEST_METHOD"]=="POST" and isset($_POST["q_id"]) and isset($_POST["text"])){
        try{            
            $con = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
            if(mysqli_connect_errno())
                throw new Exception(encodeOutput("Error", encodeFailure("Database Connection Failed!")));
            $id=mysqli_real_escape_string($con,htmlspecialchars(trim($_POST["q_id"])));
            $sql="SELECT * FROM email_questions WHERE id='$id'";
            $result=mysqli_query($con,$sql);
            $data = mysqli_fetch_assoc($result);              
            $text=trim($_POST["text"]);            
            $text = preg_replace("/(<--nl-->)+/", "<--nl-->", $text);            
            $text = explode("<--nl-->",$text);
            $results=array();
            array_push($results,encodeOutput("Subject", checkSubject($text[0],$data)));
            $index=0;
            if(startswith(strtolower($text[0]),"subject:")){
                $index = 1;
            }
            else{
                $index = 0;                
            }            
            array_push($results,encodeOutput("Receiver", checkRecipient($text[$index],$data)));
            if(startswith(strtolower($text[$index]),"dear")){
                $index += 1;
            }
            $body=array();
            foreach($text as $line){
                if("Regards," == $line or "Yours faithfully," == $line or "Yours sincerely," == $line){
                    break;
                }
                else{
                    array_push($body,$line);
                }
            }
            array_push($results,encodeOutput("Sender", checkSender(array_slice($text,count($body)),$data)));
            if(count($body)==0){
                throw new Exception(encodeOutput("Error", encodeFailure("Email Looks Incomplete !")));
            }
            array_push($results,encodeOutput("Usage Of Outline Phrases",checkPhrases($text,$data)));
            array_push($results,encodeOutput("Order Of Outline Phrases", checkPhraseSequence($data)));
            foreach($results as $line){
                echo $line;
            }
        }
        catch(Exception $e){
            echo $e->getMessage();            
        }
    }
    else{
        echo "Not Available";
    }
?>