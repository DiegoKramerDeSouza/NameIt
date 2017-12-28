<?php
	function generateRandomString($length = 1) {
		$characters = '!@#$%&*-+.,:;_=?';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}
	function generetePassword(){
		$senha = array(
						0 => "Index",
						1 => "Home",
						2 => "Wave",
						3 => "Base",
						4 => "Gate",
						5 => "West",
						6 => "Code",
						7 => "Lock",
						8 => "Dado",
						9 => "Icon",
						10 => "Mini",
						11 => "Mute",
						12 => "Boxe",
						13 => "Pote",
						14 => "Nome",
						15 => "Sete",
						16 => "Sobe",
						17 => "Chop",
						18 => "Cast",
						19 => "Plus",
						20 => "Tool",
						21 => "Moto",
						22 => "Maca",
						23 => "Faca",
						24 => "Four",
						25 => "Blue",
						26 => "Data",
						27 => "Dump",
						28 => "Chip",
						29 => "Task",
						30 => "Drop",
						31 => "Next"
					);
		$data = getdate();
		$spChar1 = generateRandomString();
		$spChar2 = generateRandomString();
		$number = array(
					0 => "index",
					1 => rand(0,9),
					2 => rand(0,9),
					3 => rand(0,9),
					4 => rand(0,9),
					5 => rand(0,9),
					6 => rand(0,9)
					);
		$returnSenha = $senha[$data["mday"]] . $spChar1 . $number[1] . $number[2] . $number[3] . $number[4] . $number[5] . $number[6] . $spChar2;
		return $returnSenha;
	}
	

?>