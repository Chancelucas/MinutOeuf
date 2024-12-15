<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Services\EggService;

try {
    echo "Initializing database...\n";
    
    $eggService = new EggService();
    $result = $eggService->resetEggData();
    
    if ($result) {
        echo "Database initialized successfully!\n";
    } else {
        echo "Failed to initialize database.\n";
    }
} catch (Exception $e) {
    echo "Error initializing database: " . $e->getMessage() . "\n";
    exit(1);
}
