<?
$filename = "demo_csv/demo_gradvantage_company.csv";
	$ctype="application/x-msdownload";

	if (!file_exists($filename)) 
	{
   		die("NO FILE HERE");
	}

	header("Content-type: $ctype");
	header("Content-disposition: attachment; filename=" . basename($filename));
	set_time_limit(0);
	@readfile("$filename") or die("File not found.");
?>