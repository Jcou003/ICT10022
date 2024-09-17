<?php
    require_once("settings.php"); //db login details
    $tokens = 0;
    $cost = 0;
    $overdraw = false;
    $pattern = "/^[0-9]+$/"; //regex
    if (!empty($_POST['uid'])&&!empty($_POST['pwd'])&&preg_match($pattern, $_POST['uid'])){ //user login check
        $uid = $_POST['uid'];
        $pwd = $_POST['pwd'];
        $dbConnect = @new mysqli($host, $user, $pswd, $dbnm) //db connection
            or die("<p>User database connection failed. Error No. " . $dbConnect->connect_errno . ": " . $dbConnect->connect_error . ".</p>");
        $query = "SELECT tokens FROM bikeusers WHERE id={$uid} AND pwd='{$pwd}';"; //fetching user tokens
        $result = $dbConnect->query($query);
        if ($result->num_rows<1)
            header("location:login.php"); //redirect if uid and pwd have no matches
        $row = $result->fetch_row();
        $tokens = $row[0];

        if (isset($_POST['booking'])&&(isset($_POST['elec'])||isset($_POST['pedal']))&&!empty($_POST['duration'])){
            if (preg_match($pattern, $_POST['duration'])){
                if ($_POST['booking']=='immediate'){
                    $cost += 2;
                } else {
                    $cost += 1;
                }
                if (isset($_POST['elec'])&&isset($_POST['pedal'])){
                    $cost += 2;
                } elseif (isset($_POST['elec'])&&!isset($_POST['pedal'])){
                    $cost += 3;
                } else {
                    $cost += 1;
                }
                $durmult = $_POST['duration'] * 0.5;
                $cost = round($cost*$durmult); //booking cost calculation

                if ($tokens>=$cost){ //if user has enough tokens proceed
                    $tokens -= $cost;
                    $query = "UPDATE {$tbnm} SET tokens={$tokens} WHERE id={$uid};";
                    $dbConnect->query($query); //updating user tokens
                    $book = true;
                } else {
                    $book=false; 
                    $overdraw=true; //if cost is greater than tokens mark an overdraw
                }   
            } else {
                $book = false;
            }
        } else {
            $book = false;
        }

        $dbConnect->close();
    } else {
        header('location:login.php'); //rederict if no login info or incorrect information
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>Swinburne Bike Share</title>
	<meta charset="utf-8">
	<meta name="description" content="Web development">
	<meta name="keywords" content="PHP, MySQL">
	<meta name="author" content="JacobC">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<!--Access files at http://mercury.swin.edu.au/cos30045/s105408256/ict10022/bikeshare.php-->
    <header>
        <h1>Reserve A Bike!</h1>
    </header>
    <nav>
        <button id="tokens"><?php echo $tokens . " Tokens";?></button>
    </nav>
    <main>
        <h3>Reserve a Bike with your tokens</h3>
        <form method="post" action="bikeshare.php">
            <p>
                <p class="flabel">Booking Type: </p>
                <g class="input">
                    <label for="immediate">Immediate: </label>
                    <input type="radio" id="immediate" name="booking" value="immediate" required>
                    <label for="reserve">Reservation</label>
                    <input type="radio" id="reserve" name="booking" value="reserve" required>
                </g>
            </p>
            <p>
                <p class="flabel">Bike Type: </p>
                <g class="input">
                    <label for="elec">Electric: </label>
                    <input type="checkbox" id="elec" name="elec" value="elec">
                    <label for="pedal">Pedal: </label>
                    <input type="checkbox" id="pedal" name="pedal" value="pedal">
                </g>
            </p>
            <p>
                <label class="flabel" for="duration">Duration (days): </label>
                <input class="input" type="text" name="duration" id="duration"required>
            </p>
            <input type="hidden" name="uid" value='<?php echo $uid;?>'>
            <input type="hidden" name="pwd" value='<?php echo $pwd;//storing login info in hidden form field?>'>
            <input type="submit" value="Book Now">
        </form>
        <?php
            if ($book){
                echo "<p style='color:green'>Succesfully Booked, {$cost} tokens withdrawn.</p><p>Details sent to e-mail address listed on account.</p>";
            }
            if ($overdraw){
                echo "<p style='color:red'>Booking failed. Cost of {$cost} is greater than number of tokens on account.</p>";
            }
        ?>
    </main>
    <footer>
        <p>Bookings are non-refundable. Tokens are earned passively over time based on your relationship to the university and the type of account you hold.</p>
    </footer>
</body>
</html>