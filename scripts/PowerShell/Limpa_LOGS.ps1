get-childitem C:\xampp\htdocs\NameIt\database\ | foreach ($_) {remove-item $_.fullname}