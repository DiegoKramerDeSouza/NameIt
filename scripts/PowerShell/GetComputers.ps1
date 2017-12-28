import-module activedirectory
$DCs = (Get-ADDomainController -Filter *).name
foreach($DC in $DCs){
	$computers = get-adcomputer -filter * -properties * -server $DC
	foreach($computer in $computers){
		$file = "C:\xampp\htdocs\NameIt\database\{0}.log" -f $computer.name
		"{0}" -f $computer.operatingSystem > $file 
	}
}
exit