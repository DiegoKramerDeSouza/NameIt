import-module activedirectory

remove-item -force -path "C:\xampp\htdocs\NameIt\scripts\PowerShell\Results\TEMP\computers.txt"
$computers = get-adcomputer -filter * -properties *
foreach($computer in $computers){
	try{
		$lastlogon = $computer.lastlogontimestamp
	}catch{
		$lastlogon = "0"
	}
	if($lastlogon -ne "0"){
		$lastlogon = [datetime]::FromFileTime($lastlogon)
	}
	try{
		$os = $computer.operatingsystem
	}catch{
		$os = "0"
	}
	if($os -eq "unknown" -or ($os -eq "SLES" -or ($os -eq "Hyper-V Server 2012" -or ($os -eq "Hyper-V Server 2012 R2" -or $os -eq $null)))){
		$os = "Outros"
	}
	"#{0}|{1}|{2}|{3}" -f $computer.name, $os, $computer.created, $lastlogon | out-file C:\xampp\htdocs\NameIt\scripts\PowerShell\Results\TEMP\computers.txt -encoding ASCII -append
}
move-item C:\xampp\htdocs\NameIt\scripts\PowerShell\Results\TEMP\computers.txt C:\xampp\htdocs\NameIt\scripts\PowerShell\Results -force

$timelimit = (Get-Date).AddDays(-30)
$timemaxlimit = (Get-Date).AddDays(-44)
remove-item -force -path "C:\xampp\htdocs\NameIt\scripts\PowerShell\Results\TEMP\30DaysOff.txt"
$lastlogon = (get-adcomputer -filter {LastLogonTimeStamp -lt $timelimit -and LastLogonTimeStamp -gt $timemaxlimit} -properties *)
foreach($user in $lastlogon){
	try{
		$lastlogon = $user.lastlogontimestamp
	}catch{
		$lastlogon = "0"
	}
	if($lastlogon -ne "0"){
		$lastlogon = [datetime]::FromFileTime($lastlogon)
	}
	try{
		$os = $user.operatingsystem
	}catch{
		$os = "0"
	}
	"#{0}|{1}|{2}" -f $user.name, $os, $lastlogon | out-file C:\xampp\htdocs\NameIt\scripts\PowerShell\Results\TEMP\30DaysOff.txt -encoding ASCII -append
}
move-item C:\xampp\htdocs\NameIt\scripts\PowerShell\Results\TEMP\30DaysOff.txt C:\xampp\htdocs\NameIt\scripts\PowerShell\Results -force

$timelimit = (Get-Date).AddDays(-45)
$timemaxlimit = (Get-Date).AddDays(-59)
remove-item -force -path "C:\xampp\htdocs\NameIt\scripts\PowerShell\Results\TEMP\45DaysOff.txt"
$lastlogon = (get-adcomputer -filter {LastLogonTimeStamp -lt $timelimit -and LastLogonTimeStamp -gt $timemaxlimit} -properties *)
foreach($user in $lastlogon){	
	try{
		$lastlogon = $user.lastlogontimestamp
	}catch{
		$lastlogon = "0"
	}
	if($lastlogon -ne "0"){
		$lastlogon = [datetime]::FromFileTime($lastlogon)
	}
	try{
		$os = $user.operatingsystem
	}catch{
		$os = "0"
	}
	"#{0}|{1}|{2}" -f $user.name, $os, $lastlogon | out-file C:\xampp\htdocs\NameIt\scripts\PowerShell\Results\TEMP\45DaysOff.txt -encoding ASCII -append
}
move-item C:\xampp\htdocs\NameIt\scripts\PowerShell\Results\TEMP\45DaysOff.txt C:\xampp\htdocs\NameIt\scripts\PowerShell\Results -force

$timelimit = (Get-Date).AddDays(-60)
$timemaxlimit = (Get-Date).AddDays(-74)
remove-item -force -path "C:\xampp\htdocs\NameIt\scripts\PowerShell\Results\TEMP\60DaysOff.txt"
$lastlogon = (get-adcomputer -filter {LastLogonTimeStamp -lt $timelimit -and LastLogonTimeStamp -gt $timemaxlimit} -properties *)
foreach($user in $lastlogon){	
	try{
		$lastlogon = $user.lastlogontimestamp
	}catch{
		$lastlogon = "0"
	}
	if($lastlogon -ne "0"){
		$lastlogon = [datetime]::FromFileTime($lastlogon)
	}
	try{
		$os = $user.operatingsystem
	}catch{
		$os = "0"
	}
	"#{0}|{1}|{2}" -f $user.name, $os, $lastlogon | out-file C:\xampp\htdocs\NameIt\scripts\PowerShell\Results\TEMP\60DaysOff.txt -encoding ASCII -append
}
move-item C:\xampp\htdocs\NameIt\scripts\PowerShell\Results\TEMP\60DaysOff.txt C:\xampp\htdocs\NameIt\scripts\PowerShell\Results -force

$timelimit = (Get-Date).AddDays(-75)
$timemaxlimit = (Get-Date).AddDays(-89)
remove-item -force -path "C:\xampp\htdocs\NameIt\scripts\PowerShell\Results\TEMP\75DaysOff.txt"
$lastlogon = (get-adcomputer -filter {LastLogonTimeStamp -lt $timelimit -and LastLogonTimeStamp -gt $timemaxlimit} -properties *)
foreach($user in $lastlogon){	
	try{
		$lastlogon = $user.lastlogontimestamp
	}catch{
		$lastlogon = "0"
	}
	if($lastlogon -ne "0"){
		$lastlogon = [datetime]::FromFileTime($lastlogon)
	}
	try{
		$os = $user.operatingsystem
	}catch{
		$os = "0"
	}
	"#{0}|{1}|{2}" -f $user.name, $os, $lastlogon | out-file C:\xampp\htdocs\NameIt\scripts\PowerShell\Results\TEMP\75DaysOff.txt -encoding ASCII -append
}
move-item C:\xampp\htdocs\NameIt\scripts\PowerShell\Results\TEMP\75DaysOff.txt C:\xampp\htdocs\NameIt\scripts\PowerShell\Results -force

$timelimit = (Get-Date).AddDays(-90)
remove-item -force -path "C:\xampp\htdocs\NameIt\scripts\PowerShell\Results\TEMP\90DaysOff.txt"
$lastlogon = (get-adcomputer -filter {LastLogonTimeStamp -lt $timelimit} -properties *)
foreach($user in $lastlogon){	
	try{
		$lastlogon = $user.lastlogontimestamp
	}catch{
		$lastlogon = "0"
	}
	if($lastlogon -ne "0"){
		$lastlogon = [datetime]::FromFileTime($lastlogon)
	}
	try{
		$os = $user.operatingsystem
	}catch{
		$os = "0"
	}
	"#{0}|{1}|{2}" -f $user.name, $os, $lastlogon | out-file C:\xampp\htdocs\NameIt\scripts\PowerShell\Results\TEMP\90DaysOff.txt -encoding ASCII -append
}
move-item C:\xampp\htdocs\NameIt\scripts\PowerShell\Results\TEMP\90DaysOff.txt C:\xampp\htdocs\NameIt\scripts\PowerShell\Results -force