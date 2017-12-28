<?php
	require_once('../fpdf/fpdf.php');

	class PDF extends FPDF
	{
	// Load data
	function LoadData($file)
	{
		// Read file lines
		//$lines = file($file);
		$document = file_get_contents($file);
		$lines = explode("#", $document);
		$data = array();
		foreach($lines as $line){
			$data[] = explode('|',trim($line));
		}
		array_splice($data, 0, 1);
			
		return $data;
		
	}

	// Simple table
	function BasicTable($header, $data)
	{
		// Header
		foreach($header as $col)
			$this->Cell(60,7,$col,1);
		$this->Ln();
		// Data
		foreach($data as $row)
		{
			foreach($row as $col)
				$this->Cell(60,6,$col,1);
			$this->Ln();
		}
	}

	// Better table
	function ImprovedTable($header, $data)
	{
		// Column widths
		$w = array(50, 60, 50);
		// Header
		for($i=0;$i<count($header);$i++)
			$this->Cell($w[$i],7,$header[$i],1,0,'C');
		$this->Ln();
		// Data
		foreach($data as $row)
		{
			$this->Cell($w[0],6,$row[0],'LR');
			$this->Cell($w[1],6,$row[1],'LR');
			$this->Cell($w[2],6,$row[2],'LR',0,'R');
			//$this->Cell($w[3],6,number_format($row[3]),'LR',0,'R');
			$this->Ln();
		}
		// Closing line
		$this->Cell(array_sum($w),0,'','T');
	}

	// Colored table
	function FancyTable($header, $data)
	{
		// Colors, line width and bold font
		$this->SetFillColor(30,80,120);
		$this->SetTextColor(255,255,255);
		$this->SetDrawColor(0,0,0);
		$this->SetLineWidth(0);
		$this->SetFont('','B');
		// Header
		$w = array(60, 80, 50);
		for($i=0;$i<count($header);$i++)
			$this->Cell($w[$i],7,$header[$i],1,0,'C',true);
		$this->Ln();
		// Color and font restoration
		$this->SetFillColor(224,235,255);
		$this->SetTextColor(0);
		$this->SetFont('');
		// Data
		$fill = false;
		foreach($data as $row)
		{
			$this->Cell($w[0],6,$row[0],'LR',0,'L',$fill);
			$this->Cell($w[1],6,$row[1],'LR',0,'L',$fill);
			$this->Cell($w[2],6,$row[2],'LR',0,'R',$fill);
			//$this->Cell($w[3],6,number_format($row[3]),'LR',0,'R',$fill);
			$this->Ln();
			$fill = !$fill;
		}
		// Closing line
		$this->Cell(array_sum($w),0,'','T');
	}
	}

	$pdf = new PDF();
	// Column headings
	$header = array(utf8_decode('Máquina'), utf8_decode('Sistema Operacional'), utf8_decode('Último Acesso'));
	// Data loading
	if(isset($_GET["content"])){
		$txt = "C:\\xampp\\htdocs\\NameIt\\scripts\\PowerShell\\Results\\" . $_GET["content"] . "DaysOff.txt";
		$data = $pdf->LoadData($txt);
		
		$pdf->SetFont('Arial','',12);
		//$pdf->AddPage();
		//$pdf->BasicTable($header,$data);
		//$pdf->AddPage();
		//$pdf->ImprovedTable($header,$data);
		$pdf->AddPage();
		$pdf->FancyTable($header,$data);
		$pdf->Output();
	}
	
?>