<?php

// Load CodeIgniter environment
require_once 'spark';

// Get database connection
$db = \Config\Database::connect();

// Delete all existing data
$db->query("DELETE FROM pilihan_jawaban");
echo "✅ Deleted existing pilihan jawaban data\n";

// Reset auto increment
$db->query("ALTER TABLE pilihan_jawaban AUTO_INCREMENT = 1");
echo "✅ Reset auto increment\n";

echo "🎯 Table pilihan_jawaban is now empty and ready for new data\n";