<?php
//Template Name:Custom_Template
?>
<!DOCTYPE html>
<html>
<head>
    <title></title>
</head>
<body>
<h1>Hello World</h1>
<form target="" method="post">
<input type="submit" name="submit" value="pdf" />
</form>
</body>
</html>
<?php

if(isset($_REQUEST['submit']))
{

	require_once 'mpdf/mpdf.php';

	$mpdf = new mPDF();
	$mpdf->WriteHTML('<h1>Hello world!</h1>');
	$mpdf->Output();
}
?>