<?php
/*Template Name:  PDF GENERATION*/
if(isset($_POST['submit']))
{
	$html = '
	<h1><a name="top"></a>mPDF</h1>
	<h2>Basic HTML Example</h2>
	This file demonstrates most of the HTML elements.
	<h3>Heading 3</h3>
	<h4>Heading 4</h4>
	<h5>Heading 5</h5>
	<h6>Heading 6</h6>
	';
	 
	if(include("MPDF57/mpdf.php"))
	{
		echo "true";
	}
	$mpdf=new mPDF();
	$mpdf->WriteHTML($html);
	$mpdf->Output();
}
?>

<form name="" method="post" action="">
	<input type="submit" name="submit" />
</form>

