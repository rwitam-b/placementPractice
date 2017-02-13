<?php
    require 'tryLogin.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>AOT TT - Guide</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
    <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
    <style>
        .btn{
            white-space:normal !important;
            word-wrap:break-word;
        }
    </style>
</head>

<script>
    $(document).ready(function(){
        $('#changeView').change(function() {
            if ($(this).val()==1) {
                // single accordion mode
                $('.panel-collapse').removeData('bs.collapse');
                $('.collapse1').collapse({parent:'#accordion'});
                $('.collapse2').collapse({parent:'#accordion2'});
                $(this).val(0);
            } else {
                // multi mode
                $('.panel-collapse').removeData('bs.collapse');
                $('.panel-collapse').collapse({parent:false});
                $(this).val(1);
            }
    });
    });
</script>

<body>
    <br>
    <div class="jumbotron">
        <h1 align="center">AOT Talent Transformation
            <br><small>Email Writing Practice</small></h1>
    </div>
    <div class="container-fluid">
        <?php include("header.php");?>
        <div class="row">
            <h2 class="text-center">Points To Remember&nbsp;<small><br>(Click To Elaborate)</small></h2>
        </div>
        <div align="center" class="row">
            <input type="checkbox" id="changeView" data-size="large" data-toggle="toggle" data-onstyle="danger" data-offstyle="success" data-on="Multiple Mode" data-off="Single Mode" value="0">
        </div>
        <br>
        <div class="row">
            <div class="col-md-6">
                <h4 class="text-center">Tips For Provided Data</h4>
                <div name="panel1" class="panel-group" id="accordion">
                    <div class="panel panel-info">
                        <div align="center" class="panel-heading">
                            <h4 class="panel-title"><button class="btn btn-link btn-block" data-toggle="collapse" data-parent="#accordion" data-target="#collapse1-1">Sender &amp; Receiver</button></h4>
                        </div>
                        <div id="collapse1-1" class="panel-collapse collapse collapse1">
                            <div class="panel-body">Please type in the sender and the receiver names <strong>exactly</strong> as you see in the question statement.<br>
                            If asked to write to <i>Mr. Chicken</i>, then <i>Mr. Chicken</i> it is !<br>
                            <div align="center">
                                <img align="center" class="img-responsive" src="images/chicken.png"><br>
                            </div>
                            In the rare case that the sender's name is not provided, use a generic title like <i>"Sir"</i> or <i>"Madam"</i> using common sense !
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-info">
                        <div align="center" class="panel-heading">
                            <h4 class="panel-title"><button class="btn btn-link btn-block" data-toggle="collapse" data-parent="#accordion" data-target="#collapse1-2">Outline Phrases</button></h4>
                        </div>
                        <div id="collapse1-2" class="panel-collapse collapse collapse1">
                            <div class="panel-body">Check and even <strong>double check</strong> whether all the outline phrases have been used(exactly as given) in your email !<br><br>
                            Also, try to maintain their order as best as you can.</div>
                        </div>
                    </div>
                </div>
                <div align="center"><img class="img-thumbnail img-responsive img-circle" src="images/points.png"></div>
            </div>
            <div class="col-md-6">
                <h4 class="text-center">Other Things To Look Out For</h4>
                <div name="panel2" class="panel-group" id="accordion2">
                    <div class="panel panel-info">
                        <div align="center" class="panel-heading">
                            <h4 class="panel-title"><button class="btn  btn-link btn-block" data-toggle="collapse" data-parent="#accordion2" data-target="#collapse2-1">Salutation</button></h4>
                        </div>
                        <div id="collapse2-1" class="panel-collapse collapse collapse2">
                            <div class="panel-body">Address the receiver properly. <strong>Dear &lt;name&gt;,</strong> works fine in a lot of situations.<br>For example -&gt;<br><strong> Dear John,</strong></div>
                        </div>
                    </div>
                    <div class="panel panel-info">
                        <div align="center" class="panel-heading">
                            <h4 class="panel-title"><button class="btn btn-link btn-block" data-toggle="collapse" data-parent="#accordion2" data-target="#collapse2-2">Taking Leave</button></h4>
                        </div>
                        <div id="collapse2-2" class="panel-collapse collapse collapse2">
                            <div class="panel-body">End your mail properly using any one of the standard phrases - "Regards", "Yours faithfully", "Yours sincerely" and so on.....<br>For example -&gt;<strong><br>Regards,<br>&lt;name&gt;</strong></div>
                        </div>
                    </div>
                    <div class="panel panel-info">
                        <div align="center" class="panel-heading">
                            <h4 class="panel-title"><button class="btn  btn-link btn-block" data-toggle="collapse" data-parent="#accordion2" data-target="#collapse2-3">Subject</button></h4>
                        </div>
                        <div id="collapse2-3" class="panel-collapse collapse collapse2">
                            <div class="panel-body">
                                Generic Subject format-><pre>Subject: &lt;subject_text></pre>
                                <ul>
                                    <li>Place this tiny little thing right on top of your email body.
                                    <li>Make it short(3-4 words).
                                    <li>Place a " <strong>:</strong> " immediately after the subject and then a space !
                                    <li>Don't include a full stop(.) at the end for God's sake !
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-info">
                        <div align="center" class="panel-heading">
                            <h4 class="panel-title"><button class="btn btn-link btn-block" data-toggle="collapse" data-parent="#accordion2" data-target="#collapse2-4">Word Count</button></h4>
                        </div>
                        <div id="collapse2-4" class="panel-collapse collapse collapse2">
                            <div class="panel-body">Keep the entire thing short and simple. Nobody got the time for long emails !<br><strong>Word Count(WC) Equation -&gt;<br> 50&nbsp;&lt;=&nbsp;WC&nbsp;&lt;=&nbsp;100</strong></div>
                        </div>
                    </div>
                    <div class="panel panel-info">
                        <div align="center" class="panel-heading">
                            <h4 class="panel-title"><button class="btn  btn-link btn-block" data-toggle="collapse" data-parent="#accordion2" data-target="#collapse2-5">Punctuation</button></h4>
                        </div>
                        <div id="collapse2-5" class="panel-collapse collapse collapse2">
                            <div class="panel-body">A space after every punctuation is mandatory !</div>
                        </div>
                    </div>
                    <div class="panel panel-info">
                        <div align="center" class="panel-heading">
                            <h4 class="panel-title"><button class="btn btn-link btn-block" data-toggle="collapse" data-parent="#accordion2" data-target="#collapse2-6">Spelling &amp; Capitalization</button></h4>
                        </div>
                        <div id="collapse2-6" class="panel-collapse collapse collapse2">
                            <div class="panel-body">Capitalize letters correctly, like <strong>beginning of sentences</strong> and for <strong>proper nouns</strong>. Also ensure you have correct spellings in your email !</div>
                        </div>
                    </div>
                    <div class="panel panel-info">
                        <div align="center" class="panel-heading">
                            <h4 class="panel-title"><button class="btn  btn-link btn-block" data-toggle="collapse" data-parent="#accordion2" data-target="#collapse2-7">Grammar</button></h4>
                        </div>
                        <div id="collapse2-7" class="panel-collapse collapse collapse2">
                            <div class="panel-body">Get correct grammar in your sentences.</div>
                        </div>
                    </div>
                    <div class="panel panel-info">
                        <div align="center" class="panel-heading">
                            <h4 class="panel-title"><button class="btn btn-link btn-block" data-toggle="collapse" data-parent="#accordion2" data-target="#collapse2-8">Standard English</button></h4>
                        </div>
                        <div id="collapse2-8" class="panel-collapse collapse collapse2">
                            <div class="panel-body">No colloquialisms in your email !<br><br><strong>Keep the BTW, TTYL and LOLs for your friends !</strong></div>
                        </div>
                    </div>
                    <div class="panel panel-info">
                        <div align="center" class="panel-heading">
                            <h4 class="panel-title"><button class="btn btn-link btn-block" data-toggle="collapse" data-parent="#accordion2" data-target="#collapse2-9">Time</button></h4>
                        </div>
                        <div id="collapse2-9" class="panel-collapse collapse collapse2">
                            <div class="panel-body">You only have 10 minutes for the entire process.<br><br><strong>READ, TYPE &amp; SUBMIT THE ENTIRE THING WITHIN 10 MINUTES !</strong></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <h2 class="text-center">Sample Email</h2>
        <div class="row">
            <div class="col-md-12">
                <div class="well well-lg">
                    <p><strong>As a member of your residential society, write an email to inspector of local Police station, Mr. Sharma, informing him about miscreants who ride their bikes rashly every evening outside your society.  Sign the email as William.</strong></p><br>
                    <strong>Phrases : </strong><mark class="text-info">residential area - ride - rashly - children - play - elderly - walk - grocery shop - across the road - dangerous - accidents - nuisance - action - immediately</mark><br><br>
                    <div class="well">Subject: Rash driving in the neighborhood<br>Dear Mr.Sharma,<br>I'm the resident of Peerless Nagar, which is deemed a <span class="bg-info">residential area</span> by all. Lately, I have noticed that a few young men <span class="bg-info">ride</span> their bikes very <span class="bg-info">rashly</span> in the neighborhood. Most of the time, the <span class="bg-info">children</span> <span class="bg-info">play</span> on the streets or the <span class="bg-info">elderly</span> people would be taking a <span class="bg-info">walk</span>. Even the people who have to cross the street to get to the <span class="bg-info">grocery shop</span> <span class="bg-info">across the road</span> feel unsafe.<br>The situation is quite <span class="bg-info">dangerous</span>, and can lead to major <span class="bg-info">accidents</span> soon if nothing is done about it. These people are a <span class="bg-info">nuisance</span> to the society. I urge you to take the necessary <span class="bg-info">action</span> <span class="bg-info">immediately</span>.<br>                    Regards,<br>
                    William
                    </div>
                    <h5 class="text-right">Word Count : 105</h5>
                </div>
            </div>
        </div>
        <?php include("footer.php");?>
    </div>
</body>
</html>
