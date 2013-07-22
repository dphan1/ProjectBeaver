<?php
   
   if (extension_loaded("pHash"))
      echo "pHash loaded :)" . "<br>";
   else 
      echo "something is wrong :(" . "<br>";

   // image files are located in public folder
   $imgA = ph_dct_imagehash('serious.jpg');
   $imgB = ph_dct_imagehash('milla.bmp');

   $dist = ph_image_dist($imgA, $imgB);      
    
   $a = ph_dct_imagehash_to_array($imgA);

   // Print out the hash value of image A
   for ($i = 0; $i < count($a); $i++) {
      echo $a[$i] . "<br>";
   }

   echo "The hamming distance is: " . $dist . "<br>"; 
?>
