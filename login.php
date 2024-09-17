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
<!--Access files at http://mercury.swin.edu.au/cos30045/s105408256/ict10022/login.php-->
    <header>
        <h1>Login</h1>
    </header>
    <main>
        <form method="post" action="bikeshare.php">
            <p>
                <label class="flabel" for="uid">User ID: </label>
                <input class="input" type="text" name="uid" id="uid" required>
            </p> 
            <p>
                <label class="flabel" for="pwd">Password: </label>
                <input class="input" type="password" name="pwd" id="pwd" required>
            </p> 
            <input type="submit" value="Login">
        </form>
    </main>
    <footer>
        <p>Bookings are non-refundable. Tokens are earned passively over time based on your relationship to the university and the type of account you hold.</p>
    </footer>
</body>
</html>