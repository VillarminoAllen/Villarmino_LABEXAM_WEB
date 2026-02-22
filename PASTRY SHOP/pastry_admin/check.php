<?php
$test_file = __DIR__ . '/uploads/test.txt';
if (file_put_contents($test_file, "PHP can write here!")) {
    echo "✅ Success! PHP just created a text file in the uploads folder.";
} else {
    echo "❌ Error: PHP is still blocked. Run the Terminal commands again.";
}
?>