<?php session_start();
  date_default_timezone_set("Europe/Amsterdam");
 ?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<style>
.switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
}

.switch input { 
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #2196F3;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}
</style>

</head>
<body>


<div class="jumbotron text-center">
  <h2>PCC Meeting App</h2>
  <p>Register Yourself via options below:</p>
  <!-- <h4><?php echo "Today is ". date("l").', ' . date("d-M-Y") ; ?></h4> -->
</div>

<div class="container text-center">
    <!-- <h2>PCC Meeting Register</h2> -->
    <h4><?php echo "Today is ". date("l").', ' . date("d-M-Y") ; ?></h4>

    <!-- Testing here -->
    <!-- <h2>Modal Example</h2> -->

<!-- Trigger/Input new member -->
<!-- <button id="myBtn">Input Your data</button> -->

<!-- The Modal -->
<!-- <div id="myModal" class="modal"> -->

  <!-- Modal content -->
  <!-- <div class="modal-content">
    <span class="close">&times;</span>
    <p>Some text in the Modal..</p>
    Text1: <input type="text"><br>
    Text2: <input type="text"><br>
    Text3: <input type="text"><br>
          <input type="submit" value="Submit"><br>
  </div> -->

<!-- </div> -->

    <!--End test -->

    <form action="send.php" method="post" target="box">
    <!-- <h3> Select your name:</h3>
    <select name="member" id="member">
      <option value="..">- Members -</option>
      <option value="Larry Dorkenoo">Larry Dorkenoo</option>
      <option value="Volvo">Volvo</option>
      <option value="Saab">Saab</option>
      <option value="Opel">Opel</option>
      <option value="Audi">Audi</option>
    </select> <br><br> -->

  <input type="text" name="member2" id="member2" placeholder="Enter your name or email">
  <div style="font-size:20px;">
    Absent
        <label class="switch">
        <input type="checkbox" name="status" id="status">
        <span class="slider round"></span>
        </label>
    Present
  </div>
        <div>
            <input type="submit" name="submit" onclick="Myfunction()" value="Send">
        </div>
    </form>

    <hr>
    <p id="box"></p>
   

    <iframe name="box"  src="" frameborder="0" width="500" height="200"></iframe>
</div>


<!-- Javascript for modal box -->
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
<!--end Javascript -->

</body>
</html> 
