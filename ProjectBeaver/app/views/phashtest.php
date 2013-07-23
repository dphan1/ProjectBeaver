<?php
   
   if (extension_loaded("pHash"))
      echo "pHash loaded :)" . "<br>";
   else 
      echo "something is wrong :(" . "<br>";

   // image files are located in .../app/public folder 
   $imgFileA = 'serious.jpg';
   $imgFileB = 'fallin1.jpg';  

   // imgA and imgB are just resources which hold REFERENCES to the real hashes 
   $imgA = ph_dct_imagehash($imgFileA); 
   $imgB = ph_dct_imagehash($imgFileB);

   $distOriginal = ph_image_dist($imgA, $imgB); // original distance function

   /*
    * Unlike the original ph_dct_imagehash, which returns a reference to the hash, 
    * ph_dct_imagehash_hexadecimal returns an actual hash string (in hexadecimal).
    * 
    * These hashes can then be stored in the database.
    *
    * Reference: https://github.com/lucidix/phash/commit/5be2d454c932152e9b2395e21f97a008c6bd8766
    */
   $hashA = ph_dct_imagehash_hexadecimal($imgFileA);
   $hashB = ph_dct_imagehash_hexadecimal($imgFileB);

   /*
    * To compare a pair of pHash hashes, I created this function: (in pHash.xml)
    * 
    *         ph_image_dist_with_hashes(string, string)
    *
    */
   $distModified = ph_image_dist_with_hashes($hashA, $hashB);
    
   // $distOriginal and $distModified should be the same
   echo "Hamming distance using original function: " . $distOriginal . "<br><br>";
   echo "Hamming distance using modified function: " . $distModified . "<br><br>";

?>
