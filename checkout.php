<html>
<head>
    <title>خرید</title>
</head>
<body style="font-family:Tahoma; margin: 0 auto; background-color: #f2f2f2;">
<header>
<blockquote>
	<img src="image/logo.png">
	<input class="hi" style="float: right; margin: 2%;" type="button" name="cancel" value="خانه" onClick="window.location='index.php';" />
</blockquote>
</header>
<?php
session_start();

if(isset($_SESSION['id'])){
	$servername = "localhost";
	$username = "root";
	$password = "";

	$conn = new mysqli($servername, $username, $password);
    $conn->set_charset("utf8");

	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	} 

	$sql = "USE bookstore";
	$conn->query($sql);

	$sql = "SELECT CustomerID from customer WHERE UserID = ".$_SESSION['id']."";
	$result = $conn->query($sql);
	while($row = $result->fetch_assoc()){
		$cID = $row['CustomerID'];
	}

	$sql = "UPDATE cart SET CustomerID = ".$cID." WHERE 1";
	$conn->query($sql);

	$sql = "SELECT * FROM cart";
	$result = $conn->query($sql);
	while($row = $result->fetch_assoc()){
		$sql = "INSERT INTO `order`(CustomerID, BookID, DatePurchase, Quantity, TotalPrice, Status) 
		VALUES(".$row['CustomerID'].", '".$row['BookID']
		."', CURRENT_TIME, ".$row['Quantity'].", ".$row['TotalPrice'].", 'N')";
		$conn->query($sql);
	}
	$sql = "DELETE FROM cart";
	$conn->query($sql);

	$sql = "SELECT customer.CustomerName, customer.CustomerIC, customer.CustomerGender, customer.CustomerAddress, customer.CustomerEmail, customer.CustomerPhone, book.BookTitle, book.Price, book.Image, `order`.`DatePurchase`, `order`.`Quantity`, `order`.`TotalPrice`
		FROM customer, book, `order`
		WHERE `order`.`CustomerID` = customer.CustomerID AND `order`.`BookID` = book.BookID AND `order`.`Status` = 'N' AND `order`.`CustomerID` = ".$cID."";
	$result = $conn->query($sql);
	echo '<div class="container">';
	echo '<blockquote>';
?>
<input class="button" style="float: left;" type="button" name="cancel" value="ادامه خرید" onClick="window.location='index.php';" />
<?php
	echo '<h2 style="color: #000;">خرید با موفقیت انجام شد</h2>';
	echo "<table style='width:100%'>";
	echo "<tr><th>اطلاعات خریدار</th>";
	echo "<th></th></tr>";
	$row = $result->fetch_assoc();
	echo "<tr><td>نام: </td><td>".$row['CustomerName']."</td></tr>";
	echo "<tr><td>ایمیل: </td><td>".$row['CustomerEmail']."</td></tr>";
	echo "<tr><td>موبایل: </td><td>".$row['CustomerPhone']."</td></tr>";
	echo "<tr><td>جنسیت: </td><td>".$row['CustomerGender']."</td></tr>";
	echo "<tr><td>آدرس: </td><td>".$row['CustomerAddress']."</td></tr>";
	echo "<tr><td>تاریخ: </td><td>".$row['DatePurchase']."</td></tr>";
	echo "</blockquote>";

	$sql = "SELECT customer.CustomerName, customer.CustomerIC, customer.CustomerGender, customer.CustomerAddress, customer.CustomerEmail, customer.CustomerPhone, book.BookTitle, book.Price, book.Image, `order`.`DatePurchase`, `order`.`Quantity`, `order`.`TotalPrice`
		FROM customer, book, `order`
		WHERE `order`.`CustomerID` = customer.CustomerID AND `order`.`BookID` = book.BookID AND `order`.`Status` = 'N' AND `order`.`CustomerID` = ".$cID."";
	$result = $conn->query($sql);
	$total = 0;
	while($row = $result->fetch_assoc()){
		echo "<tr><td style='border-top: 2px solid #ccc;'>";
		echo '<img src="'.$row["Image"].'"width="20%"></td><td style="border-top: 2px solid #ccc;">';
    	echo $row['BookTitle']."<br> ".$row['Price']."<br>";
    	echo "تعداد: ".$row['Quantity']."<br>";
    	echo "</td></tr>";
    	$total += $row['TotalPrice'];
	}
	echo "<tr><td style='background-color: #ccc;'></td><td style='text-align: right;background-color: #ccc;''>جمع کل : <b> ".$total."</b></td></tr>";
	echo "</table>";
	echo "</div>";

	$sql = "UPDATE `order` SET Status = 'y' WHERE CustomerID = ".$cID."";
	$conn->query($sql);
}

$nameErr = $emailErr = $genderErr = $addressErr = $icErr = $contactErr = "";
$name = $email = $gender = $address = $ic = $contact = "";
$cID;

if(isset($_POST['submitButton'])){
	if (empty($_POST["name"])) {
		$nameErr = "لطفا نام را وارد کنید";
	}else{
		if (!preg_match("/^[a-zA-Z ]*$/", $name)){
			$nameErr = "فقط از حروف و فاصله استفاده کنید";
			$name = "";
		}else{
			$name = $_POST['name'];

					if (empty($_POST["email"])){
						$emailErr = "لطفا ایمیل را وارد نمایید";
					}else{
						if (filter_var($email, FILTER_VALIDATE_EMAIL)){
							$emailErr = "ایمیل به درستی وارد نشده است";
							$email = "";
						}else{
							$email = $_POST['email'];
							if (empty($_POST["contact"])){
								$contactErr = "لطفا موبایل را وارد کنید";
							}else{
								if(!preg_match("/^[0-9 -]*$/", $contact)){
									$contactErr = "لطفا تلفن را به صورت صحیح وارد کنید";
									$contact = "";
								}else{
									$contact = $_POST['contact'];
									if (empty($_POST["gender"])){
										$genderErr = "* جنسیت را مشخص نمایید!";
										$gender = "";
									}else{
										$gender = $_POST['gender'];
										if (empty($_POST["address"])){
											$addressErr = "لطفا آدرس را وارد نمایید";
											$address = "";
										}else{
											$address = $_POST['address'];

											$servername = "localhost";
											$username = "root";
											$password = "";

											$conn = new mysqli($servername, $username, $password);
                                            $conn->set_charset("utf8");
											if ($conn->connect_error) {
											    die("Connection failed: " . $conn->connect_error);
											} 

											$sql = "USE bookstore";
											$conn->query($sql);

											$sql = "INSERT INTO customer(CustomerName, CustomerPhone, CustomerIC, CustomerEmail, CustomerAddress, CustomerGender) 
											VALUES('".$name."', '".$contact."', '".$ic."', '".$email."', '".$address."', '".$gender."')";
											$conn->query($sql);
 
											$sql = "SELECT CustomerID from customer WHERE CustomerName = '".$name."' AND CustomerIC = '".$ic."'";
											$result = $conn->query($sql);
											while($row = $result->fetch_assoc()){
												$cID = $row['CustomerID'];
											}

											$sql = "UPDATE cart SET CustomerID = ".$cID." WHERE 1";
											$conn->query($sql);

											$sql = "SELECT * FROM cart";
											$result = $conn->query($sql);
											while($row = $result->fetch_assoc()){
												$sql = "INSERT INTO `order`(CustomerID, BookID, DatePurchase, Quantity, TotalPrice, Status) 
												VALUES(".$row['CustomerID'].", '".$row['BookID']
												."', CURRENT_TIME, ".$row['Quantity'].", ".$row['TotalPrice'].", 'N')";
												$conn->query($sql);
											}
											$sql = "DELETE FROM cart";
											$conn->query($sql);
										}
									}
						}
					}
				}
			}
		}
	}
}
function test_input($data){
	$data = trim($data);
	$data = stripcslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}
?>
<style> 
header {
	background-color: rgb(0,51,102);
	width: 100%;
}
header img {
	margin: 1%;
}
header .hi{
    background-color: #fff;
    border: none;
    border-radius: 20px;
    text-align: center;
    transition-duration: 0.5s; 
    padding: 8px 30px;
    cursor: pointer;
    color: #000;
    margin-top: 15%;
}
header .hi:hover{
    background-color: #ccc;
}
form{
	margin-top: 1%;
	float: right;
	color: #000;
    text-align: right;
}
input[type=text] {
	padding: 5px;
    border-radius: 3px;
    box-sizing: border-box;
    border: 2px solid #ccc;
    transition: 0.5s;
    outline: none;
}
input[type=text]:focus {
    border: 2px solid rgb(0,51,102);
}
textarea {
	outline: none;
	border: 2px solid #ccc;
}
textarea:focus {
	border: 2px solid rgb(0,51,102);
}
.button{
    background-color: rgb(0,51,102);
    border: none;
    border-radius: 20px;
    text-align: center;
    transition-duration: 0.5s; 
    padding: 8px 30px;
    cursor: pointer;
    color: #fff;
}
.button:hover {
    background-color: rgb(102,255,255);
    color: #000;
}
table {
    border-collapse: collapse;
    width: 60%;
    float: left;
}
th, td {
    text-align: right;
    padding: 8px;
}
tr{background-color: #fff;}

th {
    background-color: rgb(0,51,102);
    color: white;
}
.container {
	width: 50%;
    border-radius: 5px;
    background-color: #f2f2f2;
    padding: 20px;
    margin: 0 auto;
}
</style>
<blockquote>
<?php
if(!isset($_SESSION['id'])){
	echo "<form method='post'  action=''>";

	echo 'نام:<br><input type="text" name="name" placeholder="نام کامل">';
	echo '<span class="error" style="color: red; font-size: 0.8em;"><?php echo $nameErr;?></span><br><br>';

	echo 'ایمیل:<br><input type="text" name="email" placeholder="example@email.com">';
	echo '<span class="error" style="color: red; font-size: 0.8em;"><?php echo $emailErr;?></span><br><br>';

	echo 'موبایل:<br><input type="text" name="contact" placeholder="912-3456789">';
	echo '<span class="error" style="color: red; font-size: 0.8em;"><?php echo $contactErr;?></span><br><br>';

	echo '<label>جنسیت:</label><br>';
	echo '<input type="radio" name="gender" if (isset($gender) && $gender == "مرد") echo "checked"; value="مرد">مرد';
	echo '<input type="radio" name="gender" if (isset($gender) && $gender == "زن") echo "checked"; value="زن">زن';
	echo '<span class="error" style="color: red; font-size: 0.8em;"><?php echo $genderErr;?></span><br><br>';

	echo '<label>آدرس:</label><br>';
	   echo '<textarea name="address" cols="30" rows="5" placeholder="آدرس"></textarea>';
	   echo '<span class="error" style="color: red; font-size: 0.8em;"><?php echo $addressErr;?></span><br><br>';
?>
<input class="button" type="button" name="cancel" value="برگشت" onClick="window.location='index.php';" />
<?php
	echo '<input class="button" type="submit" name="submitButton" value="ثبت">';
	echo '</form><br><br>';
}

if(isset($_POST['submitButton'])){
	$servername = "localhost";
	$username = "root";
	$password = "";

	$conn = new mysqli($servername, $username, $password);
    $conn->set_charset("utf8");
	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	} 

	$sql = "USE bookstore";
	$conn->query($sql);

	$sql = "SELECT customer.CustomerName, customer.CustomerIC, customer.CustomerGender, customer.CustomerAddress, customer.CustomerEmail, customer.CustomerPhone, book.BookTitle, book.Price, book.Image, `order`.`DatePurchase`, `order`.`Quantity`, `order`.`TotalPrice`
		FROM customer, book, `order`
		WHERE `order`.`CustomerID` = customer.CustomerID AND `order`.`BookID` = book.BookID AND `order`.`Status` = 'N' AND `order`.`CustomerID` = ".$cID."";
	$result = $conn->query($sql);

	echo '<table style="width: 40%;direction: rtl">';
	echo "<tr><th>اطلاعات خرید</th>";
	echo "<th></th></tr>";
	$row = $result->fetch_assoc();
	echo "<tr><td>نام: </td><td>".$row['CustomerName']."</td></tr>";
	echo "<tr><td>ایمیل: </td><td>".$row['CustomerEmail']."</td></tr>";
	echo "<tr><td>موبایل: </td><td>".$row['CustomerPhone']."</td></tr>";
	echo "<tr><td>جنسیت: </td><td>".$row['CustomerGender']."</td></tr>";
	echo "<tr><td>آدرس: </td><td>".$row['CustomerAddress']."</td></tr>";
	echo "<tr><td>تاریخ: </td><td>".$row['DatePurchase']."</td></tr>";

	$sql = "SELECT customer.CustomerName, customer.CustomerIC, customer.CustomerGender, customer.CustomerAddress, customer.CustomerEmail, customer.CustomerPhone, book.BookTitle, book.Price, book.Image, `order`.`DatePurchase`, `order`.`Quantity`, `order`.`TotalPrice`
		FROM customer, book, `order`
		WHERE `order`.`CustomerID` = customer.CustomerID AND `order`.`BookID` = book.BookID AND `order`.`Status` = 'N' AND `order`.`CustomerID` = ".$cID."";
	$result = $conn->query($sql);
	$total = 0;
	while($row = $result->fetch_assoc()){
		echo "<tr><td style='border-top: 2px solid #ccc;'>";
		echo '<img src="'.$row["Image"].'"width="20%"></td><td style="border-top: 2px solid #ccc;">';
    	echo $row['BookTitle']."<br>RM".$row['Price']."<br>";
    	echo "Quantity: ".$row['Quantity']."<br>";
    	echo "</td></tr>";
    	$total += $row['TotalPrice'];
	}
	echo "<tr><td style='background-color: #ccc;'></td><td style='text-align: right;background-color: #ccc;'>جمع کل: <b> ".$total."</b></td></tr>";
	echo "</table>";

	$sql = "UPDATE `order` SET Status = 'y' WHERE CustomerID = ".$cID."";
	$conn->query($sql);
}
?>
</blockquote>
</body>
</html>