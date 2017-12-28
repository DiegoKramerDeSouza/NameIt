Param(
	[Parameter(Mandatory=$true)][string]$User,
	[Parameter(Mandatory=$true)][string]$Password
 )
#USO(ANTIGO): .\JoinDomain.ps1 -Domain "call" -Computer "10.61.200.60" -User "a24792" -Password "*********" -LocalUser "administrador" -LocalPassword "12345678" -Server "svdf07w000010" -OU "CN=Computers,DC=call,DC=br" -Descricao "COMPUTADOR - LABARATORIO" -Atr "TESTE0001" -NewName "ETLAB0001" -Option "JOIN"
#USO(CORRETO): .\JoinDomain.ps1 -User "a12345" -Password "*********"

Import-Module activedirectory

$datafile = "C:\xampp\htdocs\NameIt\scripts\PowerShell\TEMP\{0}.log" -f $User
$datainfo = get-content($datafile)
for($i = 0; $i -le $datainfo.count; $i++){
	switch($i){
		0{$Domain = $datainfo[0]}
		1{$LocalUser = $datainfo[1]}
		2{$LocalPassword = $datainfo[2]}
		3{$Server = $datainfo[3]}
		4{$NewName = $datainfo[4]}
		5{$OU = $datainfo[5]}
		6{$Option = $datainfo[6]}
		7{$Descricao = $datainfo[7]}
		8{$Atr = $datainfo[8]}
		9{$Computer = $datainfo[9]}
	}
}

$fqdnDomain = "{0}.br" -f $Domain
$domainuser = "{0}\{1}" -f $Domain, $User
$localuser = ".\{0}" -f $LocalUser
$PasswordSec = ConvertTo-SecureString $Password -AsPlainText -Force
$LocalPasswordSec = ConvertTo-SecureString $LocalPassword -AsPlainText -Force
$domainCred = New-Object System.Management.Automation.PSCredential $domainuser, $PasswordSec
$localCred = New-Object System.Management.Automation.PSCredential $localuser, $LocalPasswordSec
$domainserver = "{0}.{1}" -f $Server, $fqdnDomain
$errorLevel = $null

remove-item $datafile -force -confirm:$false

if($Option -eq "JOIN"){
	$errorLevel = "0"
	try{
		Add-Computer -ComputerName $Computer -DomainName $fqdnDomain -NewName $NewName -DomainCredential $domainCred -LocalCredential $localCred -OUPath "$OU" -Server $domainserver -Confirm:$false -Force -Passthru -Verbose -restart
	} catch {
		$ErrorMessage = $_.Exception.Message
		$FailedItem = $_.Exception.ItemName
		$errorLevel = "{0} | {1}" -f $FailedItem, $ErrorMessage
	}
	
	if($errorLevel -eq "0"){
		$aguarda = $true
		while($aguarda){
			try{
				$verify = get-adcomputer $NewName -server $Server
			} catch {
				$verify = $null
			}
			if($verify -ne $null){
				$aguarda = $false
			} else {
				Start-Sleep -s 1
			}
		}

		Set-ADComputer $NewName -Description $Descricao -Server $Server
		Set-ADComputer $NewName -Replace @{extensionAttribute10 = "Nomenclatura.call.br"} -Server $Server
		Set-ADComputer $NewName -Replace @{extensionAttribute11 = $Atr} -Server $Server
	}
}

if($Option -eq "RENAME"){
	$errorLevel = "0"
	try{
		Rename-Computer -ComputerName $Computer -NewName $NewName -DomainCredential $domainCred -Confirm:$false -Force -Passthru -Verbose -restart
	} catch {
		$ErrorMessage = $_.Exception.Message
		$FailedItem = $_.Exception.ItemName
		$errorLevel = "{0} | {1}" -f $FailedItem, $ErrorMessage
	}
	
	if($errorLevel -eq "0"){
		$DCs = (Get-ADDomainController -Filter *).name
		$lock = $false
		foreach($DC in $DCs){
			if($lock -eq $false){
				try{
					$verify = get-adcomputer $NewName -server $DC
				} catch {
					$verify = $null
				}
				if($verify -ne $null){
					$Server = $DC
					$lock = $true
				}
			}
		}
		
		Set-ADComputer $NewName -Description $Descricao -Server $Server
		Set-ADComputer $NewName -Replace @{extensionAttribute10 = "Nomenclatura.call.br"} -Server $Server
		Set-ADComputer $NewName -Replace @{extensionAttribute11 = $Atr} -Server $Server
		Get-ADComputer $NewName -Server $Server | Move-ADObject -TargetPath "$OU"
	}
}

$errorLevel

#Funciona 7 >netdom join 10.61.200.60 /Domain:call.br /OU:"OU=teste,OU=Especialistas,OU=Departamento de Tecnologia,DC=call,DC=br" /UserD:call\a24792 /PasswordD:********** /UserO:LABvm\administrador /PasswordO:12345678
#Funciona 7 >Add-Computer -ComputerName "10.61.200.60" -DomainName "call.br" -NewName "ETLAB0003" -DomainCredential $domainCred -LocalCredential $localCred -Confirm:$false -Force -Passthru -Verbose -restart