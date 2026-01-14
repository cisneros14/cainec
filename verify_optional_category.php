<?php
// Script to verify Optional Category in Embajadores API

$domain = 'https://palevioletred-gerbil-452167.hostingersite.com';
$baseUrl = $domain . '/admin/api/embajadores';

function makeRequest($url, $method = 'GET', $data = null) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    
    if ($data) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    }
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    return ['code' => $httpCode, 'body' => json_decode($response, true)];
}

echo "Starting Verification for Optional Category...\n";

// 1. Create without category
echo "1. Testing Create without category...\n";
$newEmbajador = [
    'nombre' => 'NoCategory',
    'apellido' => 'User',
    // 'categoria' => omitted
    'descripcion' => 'Testing optional category',
    'correo' => 'nocat@example.com',
    'orden' => 2
];
$createRes = makeRequest($baseUrl . '/crear.php', 'POST', $newEmbajador);
print_r($createRes);

if ($createRes['code'] == 200 && $createRes['body']['success']) {
    $id = $createRes['body']['id'];
    echo "Created ID: $id\n";
    
    // 2. Verify in List
    echo "2. Verifying in List...\n";
    $listRes = makeRequest($baseUrl . '/listar.php', 'GET');
    $found = false;
    foreach ($listRes['body']['data'] as $emb) {
        if ($emb['id'] == $id) {
            $found = true;
            if ($emb['categoria'] === null || $emb['categoria'] === '') {
                echo "SUCCESS: Category is null/empty as expected.\n";
            } else {
                echo "FAILURE: Category is NOT null/empty: " . $emb['categoria'] . "\n";
            }
            break;
        }
    }
    
    // 3. Delete
    echo "3. Cleaning up...\n";
    makeRequest($baseUrl . '/eliminar.php', 'POST', ['id' => $id]);
    
} else {
    echo "FAILED to create ambassador without category.\n";
}

echo "Verification Complete.\n";
?>
