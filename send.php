<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
<style>
body {font-family: Arial, Helvetica, sans-serif;}

/* The Modal (background) */
.modal {
  display: none; /* Hidden by default */
  position: absolute; /* ????*/
  /*position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  padding-top: 100px; /* Location of the box */
  left: 0;
  top: 0;
  width: 70%; 
  height: 70%;
  /*width: 100%; /* Full width */
  /*height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

/* Modal Content */
.modal-content {
  background-color: #fefefe;
  margin: auto;
  padding: 20px;
  border: 1px solid #888;
  width: 80%;
}

/* The Close Button */
.close {
  color: #aaaaaa;
  float: right;
  font-size: 28px;
  font-weight: bold;
}

.close:hover,
.close:focus {
  color: #000;
  text-decoration: none;
  cursor: pointer;
}
</style>


</head>
<body>


<?php

    date_default_timezone_set("Europe/Amsterdam");

    // Save these form data into session variable for later use
    $_SESSION['submit'] = $_POST['submit'];
    if(!isset($_POST['status'])){
        $_SESSION['status'] = 'Absent';
    } else 
    {
        $_SESSION['status'] = $_POST['status'];
    }
 

    // Open database connection
    openDBConnection();

    // Create members table if not exist
    createMembers();

    // Validate member and proceed ...
    $validated = validateMember($_POST['member2']);
// print_r($validated);
// die;

    // Create Attendance table if not exists ...
    createAttendance($_POST);
  
    // Save form data to database
    saveData($validated,$_POST);
    // saveData($_POST);

    //////////////////////////
    //-Functions below -----//
    //////////////////////////
    function openDBConnection(){
        include_once("dbconnect2.php");   // include settings for database connection
        $conn = new mysqli($servername, $username, $password);

        // Check connection
        if ($conn->connect_error)
        {
        die("Connection failed: " . $conn->connect_error);
        }

        // Now check if database exists already
        if (!$conn->query("CREATE DATABASE IF NOT EXISTS $dbname"))
        {
        die("Error creating database: " . $conn->error());
        }

    }
    //- End function: openDBConnection - for database connection
    

    function validateMember($member){
        include("dbconnect2.php");   // include settings for database connection
        $conn = new mysqli($servername, $username, $password,$dbname);
    
        // Check connection
        if ($conn->connect_error)
        {
        die("Connection failed: " . $conn->connect_error);
        }

        // Now check if member exists already
        $name = filter_var($member, FILTER_SANITIZE_STRING);

        // Abort if input data empty
        if($name == ""){
            $msg = "<h3 class='alert alert-danger'>Your input is blank!</h3>";
            ?>
            <script>
                parent.document.getElementById("box").innerHTML= "<?php echo $msg; ?>";
            </script>
            <?php
            die;
        }
        
       
        // Run query
        // split input into separate words for use in query
        $parts = explode(' ',$name);
        // print_r($parts);
        // die;
        $sql ="select * from members  where fname ='".$parts[0]."' or lname ='".$name."' or email = '".$name."'";
        //$sql ="select * from members  where fname like '%".$parts[0]."%' or lname ='".$name."' or email = '".$name."'";
        // $sql ="select * from members  where fname = '".$name."' or lname ='".$name."' or email = '".$name."'";

        // die($sql);

        $succeeded = $conn->query($sql);

        if ($succeeded->num_rows==0)
        {
            $msg = "<h3 class='alert alert-danger'>Your data not found! </h3>";            
            ?>
            <script>
                parent.document.getElementById("box").innerHTML= "<?php echo $msg; ?>";
            </script>
            <?php

            makeForm(); // Display input form
             die;
        }

        return $succeeded->fetch_assoc();
    }
    //- End function: validateMember - for member validation

    //////////////////////////////
    function makeForm()
    /////////////////////////////
    {
  // Provide button below to enter new member data via a modal box
    ?>
        <h2>Register Your Data?</h2>
        
        <!-- Trigger/Open The Modal -->
        <button id="myBtn">Form for Click to Add New Data Form</button>

        <form action="newMemberProcess.php" method="POST" target="_self">
            <!-- The Modal -->
            <div id="myModal" class="modal">

            <!-- Modal content -->

                <div class="modal-content">
                    <span class="close">&times;</span>
                    <p>Enter your key data..</p>
                    First Name: <br><input type="text" name="fname"> <br>
                    Last Name: <br><input type="text" name="lname"><br>
                    Email: <br><input type="email" name="email"><br>
                    <!-- <input class="btn btn-primary" type="submit" value="submit"> -->
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>

            </div>
        </form>
        <!--- End function ---->

        <script>
            // Get the modal
            var modal = document.getElementById("myModal");

            // Get the button that opens the modal
            var btn = document.getElementById("myBtn");

            // Get the <span> element that closes the modal
            var span = document.getElementsByClassName("close")[0];

            // When the user clicks the button, open the modal 
            btn.onclick = function() {
            modal.style.display = "block";
            }

            // When the user clicks on <span> (x), close the modal
            span.onclick = function() {
            modal.style.display = "none";
            }

            // When the user clicks anywhere outside of the modal, close it
            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }
        </script>
    <?php

    }

    function createAttendance(){
        // Create Attendance table if not exists ...
        include("dbconnect2.php");   // include settings for database connection
        $conn = new mysqli($servername, $username, $password);
        $sql = "CREATE TABLE IF NOT EXISTS `attendance` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `name` varchar(255) NOT NULL,
            `t_stamp` int DEFAULT NULL,
            `date` varchar(255) DEFAULT NULL,
            `time` varchar(255) DEFAULT NULL,
            `ip_addr` varchar(255) DEFAULT NULL,
            `in_out` varchar(10) DEFAULT NULL,
            PRIMARY KEY (`id`)
            ) ENGINE=MyISAM AUTO_INCREMENT=84 DEFAULT CHARSET=latin1";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if (!$conn->query($sql))
        {
            die("Error creating table: " . $conn->error);
        }
    }
    //-end of function: createAttendance() -------/
    

    function createMembers(){
        // Create Members table if not exists ...
        include("dbconnect2.php");   // include settings for database connection
        $conn = new mysqli($servername, $username, $password);
        $sql = "CREATE TABLE IF NOT EXISTS `members` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `fname` varchar(255) NOT NULL,
            `lname` varchar(255) DEFAULT NULL,
            `email` varchar(255) NOT NULL,
            `signupDate` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`)
            ) ENGINE=MyISAM AUTO_INCREMENT=84 DEFAULT CHARSET=latin1";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if (!$conn->query($sql))
        {
            die("Error creating table: " . $conn->error);
        }
    }
    //-end of function: createMembers() -------/

    function saveData($member,$attendData)
    {
        // print_r($member);
        // echo "Attend Data below:";
        // print_r($attendData);
    //   die("Paused here!");

        include("dbconnect2.php");   // include settings for database connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Save form data to database
        $ip="'".$_SERVER['REMOTE_ADDR']."'";


        if (isset($attendData['submit'])){   
          
            if(isset($attendData['status']))
            {
                $status = "Present";
            } else {
                $status = "'Absent'";
            }

            //---------------------------------//
            // Check if entry already present
            //---------------------------------//
            $time = "'".date("H:i:s a")."'";

            $unixTime= date('U');  // store current Unix timestamp
            //echo "<h2>".date("l d-M-Y h:i A",$unixTime)."</h2>";
            // die(date("U"));

            $d=strtotime("now");
            $date = "'".date("l d-M-Y h:i A", $d)."'";
            $date2 = "'".date("l d-M-Y", $d)."'";

            $sqlsearch = "select * from attendance where name like '%". $attendData['member2'] . "%' and date like ".$date2." ";
            // $sqlsearch = "select * from attendance where name = '". $attendData['member'] . "' and date like ".$date2." ";
            // die($sqlsearch);
        
            $result = $conn->query($sqlsearch);

            if ($result->num_rows>0){  //  Check whether member entry for this date exists
                $row = $result->fetch_assoc();

                $old_in_out = $row['in_out'];  // retrieve existing member status
                $date = $row['date'];

                session_validate($row); // validate session

                updateMeeting($member,$attendData,$conn,$date);  // Update meeting data

            } else  // This is a NEW attendance entry
            {   
                if(!isset($attendData['status']))
                {
                    $status= "'Absent'";
                }
                    else 
                {
                    $status= "'Present'";
                }
                //echo $member['fname']." ".$member['lname'];

                $sql ="INSERT into attendance (name,t_stamp,date,time,ip_addr,in_out) values ('". $member['fname']." ".$member['lname']."',$unixTime, $date2, $time,$ip,$status)";
                // $sql ="INSERT into attendance (name,timestamp,date,time,ip_addr,in_out) values ('". $attendData['member']."',$unixTime, $date2, $time,$ip,$status)";

                // die($sql);

                if (!$conn->query($sql)){
                    // die($sql);
                    die("Mysql error - ".$conn->errno);
                };
                
                $msg = "<h2 class='alert alert-success'> Success! ".$member['fname']." ".$member['lname']." registered as: ".$status. " </h2>";
                // $msg = "<h2 class='alert alert-success'> Success! ".$attendData['member']." registered as: ".$status. " </h2>";
                
                ?>
                <script>
                    parent.document.getElementById("box").innerHTML= "<?php echo $msg; ?>";
                </script>
                <?php
            }



        } else {
            unset($attendData);
        }        
    }
    /*-end of function: saveData() ------*/


    function updateMeeting($memberData,$var,$conn2,$date2){

        /* -- Query to compare 'in_out' status values...*/

    
        $sql = "SELECT * from  attendance where name like '%".$var['member2']. "%' and date= '".$date2."'";
       
        $result = $conn2->query($sql);
        if (!$result){
            die("Mysql error - ".$conn2->errno);
        };   

        $row = mysqli_fetch_row($result);

        if(!isset($var['status'])){
            $var['status'] = 'off';
            // $status = "Absent";
        }
       

        if($var['status'] == 'off') {  
            $status= "Absent";
         }
             else 
         {
             $status= "Present";
            
         }


        if($var['status'] == 'off' and  $row[6]=='Absent'){  // if not set then nothing changed!
                $msg = "<h2 class='alert alert-info'><strong> Nothing changed:</strong> Your status = ".$status. " </h2>";
                ?>
                <script>
                    parent.document.getElementById("box").innerHTML= "<?php echo $msg; ?>";
                </script>
                <?php
                die;

        }


        if($var['status'] == 'on' and  $row[6]=='Present'){  // if not set then nothing changed!
            $msg = "<h2 class='alert alert-info'> <strong>Nothing changed:</strong> Your status = ".$status. "</h2>";
        
            ?>
            <script>
                parent.document.getElementById("box").innerHTML= "<?php echo $msg; ?>";
            </script>
            <?php
            die;

         }
      
        $sql ="UPDATE attendance set in_out = '".$status."' where name like '%".$var['member2']. "%' and date= '".$date2."'";
        // die($sql);

        // $sql ="UPDATE attendance set in_out = '".$status."' where name ='".$var['member']. "' and date= '".$date2."'";

        if (!$conn2->query($sql))
        {
            die("Mysql error - ".$conn2->errno);
        };    
        $msg = "<h2 class='alert alert-warning'>Changed status for <strong>".$memberData['fname']." ".$memberData['lname']."</strong> now : ".$status. " </h2>";
    
        ?>
        <script>
            parent.document.getElementById("box").innerHTML= "<?php echo $msg; ?>";
        </script>
        <?php
    }
    /*-end of function: updateMeeting() ------*/

    function transformStatus($statusVal){
    // test - add extra symbols to status changes before flash msg display
        if($statusVal == 'Present'){
            $response = $statusVal.'<i class="far fa-check-square"></i>';
        }
        if($statusVal == 'Absent'){
            $response = $statusVal. '<i class="fas fa-times-circle"></i>';
        } 
        return $response;       
    }

    function session_validate($row2){

        // echo "Date - ".$row2['date']."<br>";
        // echo "Time - ".$row2['time']."<br>";

        // echo "XXX ".strtotime("now")."<br>";
        // echo "YYY ".strtotime($row2['date'])."<br>";
        //$timeStr='Wed, 10 Apr 19 16:28:20 +0200';

        //echo '<h2>'.strtotime('Wed, 10 Apr 19 10:34:29 -0400').'- AA</h2>';
        //echo date(DATE_RFC822,$row2['date'].' '.$row2['time']) . "<br>";
        //echo strtotime('Wednesday 10th May 2019 13:07:04 pm')." BB";
        //die($timeStr);
        //die("ZZZ ".strtotime($timeStr));
 
        // echo "Registered in_time: XXX ".strtotime($row2['date'].", ".$row2['time']);
        // echo ", ".date("l d-M-Y h:i:s A",strtotime($row2['date']));
        // echo "<br> Time now: ".time();

        // echo "<br> DIfference = ".(time()-strtotime($row2['date']));
    
        // var_dump($row2);
        // die;

        $timeDifference = time()-$row2['t_stamp'];
        // die($row2['t_stamp']);
        // $timeDifference = time()-strtotime($row2['date'].", ".$row2['time']);
        //echo "<br> DIfference = ". $timeDifference/60 . " minutes"; ////(60*60)." hrs";
        
        // die("<br>Inside session validate!!!");

      if ($timeDifference > 900) {  // after 900 sec = 15 mins 
    
        session_unset();     // unset $_SESSION variable for the run-time 
        session_destroy();   // destroy session data in storage
         $msg = "<h3 class='alert alert-danger'>Sorry your session expired, please start all over!<br>";
         $msg .= "You've been idle for ". number_format(($timeDifference/60),2) ." minutes</h3>";

         //echo number_format(($timeDifference/60),2);

            ?>
            <script>
                parent.document.getElementById("box").innerHTML= "<?php echo $msg; ?>";
            </script>
            <?php
            die;        
   
        }
        $_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp

    }
    /*-end of function: session_validate() ------*/

?>
