<?php
   
   if (extension_loaded("pHash"))
      echo "pHash loaded :)" . "<br>";
   else 
      echo "something is wrong :(" . "<br>";

   $result = DB::select('SELECT pin_id, image_square_url FROM data_pins_new');

   $start = time();
   for ($i = 0; $i < 100000; $i++) {
      $a = (array)$result[$i];
      $hash = ph_dct_imagehash_hexadecimal($a['image_square_url']);
      
      $queryToBeInserted = "INSERT INTO data_image_hash VALUES ('" . $a['pin_id'] . "', '" . $hash . "')";
      DB::insert($queryToBeInserted); 
   }   
   $end = time();

   echo 'The time it takes to process 100000 rows is: ' . ($end - $start) . ' seconds' . "<br>";

?>
