<?php
// Script to verify Embajadores API

$baseUrl = 'http://localhost/admin/api/embajadores'; // Adjust if needed, but for CLI we'll include files directly or use curl if web server is running. 
// Since we are in CLI, let's try to simulate requests or just use the logic directly?
// Better to use curl to test the actual endpoints if the server is running.
// Assuming the server is running on the domain.
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

echo "Starting Verification...\n";

// 1. Create
echo "1. Testing Create...\n";
$newEmbajador = [
    'nombre' => 'Test',
    'apellido' => 'User',
    'categoria' => 'Testing',
    'descripcion' => 'This is a test ambassador',
    'correo' => 'test@example.com',
    'orden' => 1
];
$createRes = makeRequest($baseUrl . '/crear.php', 'POST', $newEmbajador);
print_r($createRes);

if ($createRes['code'] == 200 && $createRes['body']['success']) {
    $id = $createRes['body']['id'];
    echo "Created ID: $id\n";
    
    // 2. List
    echo "2. Testing List...\n";
    $listRes = makeRequest($baseUrl . '/listar.php', 'GET');
    // print_r($listRes);
    $found = false;
    foreach ($listRes['body']['data'] as $emb) {
        if ($emb['id'] == $id) {
            $found = true;
            break;
        }
    }
    echo $found ? "Found created ambassador in list.\n" : "FAILED to find ambassador in list.\n";
    
    // 3. Update
    echo "3. Testing Update...\n";
    $updateData = $newEmbajador;
    $updateData['id'] = $id;
    $updateData['nombre'] = 'Updated Test';
    $updateRes = makeRequest($baseUrl . '/editar.php', 'POST', $updateData);
    print_r($updateRes);
    
    // 4. Delete
    echo "4. Testing Delete...\n";
    $deleteRes = makeRequest($baseUrl . '/eliminar.php', 'POST', ['id' => $id]);
    print_r($deleteRes);
    
} else {
    echo "FAILED to create ambassador.\n";
}

echo "Verification Complete.\n";
?>
