<?php
   
   if (extension_loaded("pHash"))
      echo "pHash loaded :)" . "<br>";
   else 
      echo "something is wrong :(" . "<br>";

   if (extension_loaded("libpuzzle")) 
      echo "libpuzzle loaded :)" . "<br>";
   else
      echo "libpuzzle not loaded :(" . "<br>";

   // ----------------------------------pHash stuff ---------------------------------------------------------------
   // image files are located in public folder
   // imgA and imgB are just resources which hold REFERENCES to the real hashes (type ulong64)
   $imgA = ph_dct_imagehash('serious.jpg'); 
   $imgB = ph_dct_imagehash('fallin1.jpg');

   $dist = ph_image_dist($imgA, $imgB);      
    
   $a = ph_dct_imagehash_to_array($imgA);
   $hashString = "";
   // Print out the hash value of image A
   for ($i = 0; $i < count($a); $i++) {
      echo $a[$i] . "<br>";
      $hashString += $a[$i];
   }

  // fallin2: 112 182 222 31 66 205 153 96
  // milla: 2 036 354 194 762 055 256 218 147 155
  // serious: 156 014 019 327 218 147 155
  // fallin1: 17 194 191 162 207 64 254 164
   $test = "112182222316620515396";
   $floatest = (float) $test;
   //$eww = ph_image_dist_with_hash(2036354194762055256, 156014019327218147155); 
   //$eww = ph_image_dist_with_hash(156014019327218147155, 1719419116220764254164); 
   $hmm = ph_image_dist_with_hash("112182222316620515396", "1719419116220764254164");
   //$eww = ph_image_dist_with_hash(2 036 354 194 762 055 256, 218 147 155 );
   $what = ph_image_dist_test("1719419116220764254164", "112182222316620515396");
   echo "Test: " . $what . "<br>";    
   echo "my distance: " . $hmm . "<br>";
   echo "The hamming distance is: " . $dist . "<br><br><br>";

   // --------------------------------------------------------------------------------------------------------

   // ------------LIBPUZZLE STUFF-----------------
   // does not accept .bmp, large images
   # Compute signatures for two images
   $cvec1 = puzzle_fill_cvec_from_file('silly.jpg');
   $cvec2 = puzzle_fill_cvec_from_file('serious.jpg');
   
   echo $cvec1 . "<br><br>";
   echo $cvec2 . "<br><br>";

   # Compute the distance between both signatures
   $d = puzzle_vector_normalized_distance($cvec1, $cvec2);

   # Are pictures similar?
   if ($d < PUZZLE_CVEC_SIMILARITY_LOWER_THRESHOLD) {
      echo "Pictures are looking similar\n";
   } else {
      echo "Pictures are different, distance=$d\n";
   } 

   # Compress the signatures for database storage
   $compress_cvec1 = puzzle_compress_cvec($cvec1);
   $compress_cvec2 = puzzle_compress_cvec($cvec2);

?>
