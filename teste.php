<?php
	
	require_once("functions.php");
	
	$teste = wmiData("-");
	
	echo $teste;
	

	/*
	//É preciso adicionar 'extension=php_com_dotnet.dll' no php.ini
	$pc = "10.61.200.95"; //IP of the PC to manage
	
	$WbemLocator = new COM ("WbemScripting.SWbemLocator"); //<-NEW
	$obj = $WbemLocator->ConnectServer($pc, 'root\\cimv2' , '.\administrador', '12345678'); //<-NEW
	
	//$obj = new COM ( 'winmgmts://localhost/root/CIMV2' ); //<-OLD
    $fso = new COM ( "Scripting.FileSystemObject" );    
    $wmi_computersystem    =    $obj->ExecQuery("Select * from Win32_ComputerSystem");
    $wmi_bios              =    $obj->ExecQuery("Select * from Win32_BIOS");
    $processor             =    $obj->ExecQuery("Select * from Win32_Processor");
    $PhysicalMemory        =    $obj->ExecQuery("Select * from Win32_PhysicalMemory");
    $BaseBoard             =    $obj->ExecQuery("Select * from Win32_BaseBoard"); 
    $LogicalDisk           =    $obj->ExecQuery("Select * from Win32_LogicalDisk");
	$domainName	           =    $obj->ExecQuery("Select * from Win32_NTDomain");

	$pcDomain = array();
    foreach ( $domainName as $wmi_domain )
    {
        if($wmi_domain == ""){
			array_push($pcDomain, "Local");
		} else {
			array_push($pcDomain, $wmi_domain->Name);
		}
    }
	
	foreach ( $wmi_computersystem as $wmi_call )
    {
        $model = $wmi_call->Model;
    }

    foreach ( $wmi_bios as $wmi_call )
    {
        $serial = $wmi_call->SerialNumber;
        $bios_version = $wmi_call->SMBIOSBIOSVersion;
    }

    foreach ( $processor as $wmi_processor )
    {
        $idprocessor = $wmi_processor->ProcessorId;
        $Architecture = $wmi_processor->Architecture;
        $Name = $wmi_processor->Name;
        $Version = $wmi_processor->Version;
    }
    foreach ( $PhysicalMemory as $wmi_PhysicalMemory )
    {
        $Capacity = $wmi_PhysicalMemory->Capacity;
        $PartNumber = $wmi_PhysicalMemory->PartNumber;
        $Name = $wmi_PhysicalMemory->Name;
    }

    foreach ( $BaseBoard as $wmi_BaseBoard )
    {
        $SerialNumber = $wmi_BaseBoard->SerialNumber;

    }
    foreach ( $LogicalDisk as $wmi_LogicalDisk )
    {
        $SerialNumberDisk = $wmi_LogicalDisk->VolumeSerialNumber;
        $FileSystem = $wmi_LogicalDisk->FileSystem;

    }

    echo "Bios version   : ".$bios_version."<br/>
          Serial number of bios  : ".$serial."<br/>
          Hardware Model : ".$model."<br/>
          ID-Processor : ".$idprocessor."<br/>
          Architecture-Processor : ".$Architecture."<br/>
          Name-Processor : ".$Name."<br/>
          Version-Processor : ".$Version."<br/>
          <hr>
          <hr>
          PhysicalMemory
          <hr>
          <hr>
          Capacity : ".$Capacity."<br/>
          Name : ".$Name."<br/>
          <hr>
          <hr>
          carte mere
          <hr>
          <hr>
          SerialNumber : ".$SerialNumber."<br/>
           <hr>
          <hr>
          disk
          <hr>
          <hr>
          SerialNumber : ".$SerialNumberDisk."<br/>
          FileSystem : ".$FileSystem."<br>
          ";
	echo "<br />";
	echo "<pre>";
	print_r($pcDomain);
	echo "</pre>";
	*/
?>