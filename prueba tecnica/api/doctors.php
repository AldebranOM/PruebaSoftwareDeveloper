<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");


$dataFile = '../data/doctors.json';



// Función para leer los datos de médicos
function getDoctors($dataFile) {
    if (!file_exists($dataFile) || filesize($dataFile) == 0) {
        return []; // Si el archivo no existe o está vacío, devuelve un array vacío
    }
    $json = file_get_contents($dataFile);
    return json_decode($json, true); 
}

// Función para guardar los datos de médicos
function saveDoctors($dataFile, $doctors) {
    file_put_contents($dataFile, json_encode($doctors, JSON_PRETTY_PRINT));
}

// --- Lógica principal basada en el método HTTP ---

$method = $_SERVER['REQUEST_METHOD']; 
if ($method === 'GET') {
    // Si la solicitud es GET, devolvemos todos los médicos
    $doctors = getDoctors($dataFile);
    echo json_encode($doctors);
    exit(); 
}

if ($method === 'POST') {
    // Si la solicitud es POST, intentamos registrar un nuevo médico
    $data = json_decode(file_get_contents('php://input'), true); 

    // 1. Validación básica de los campos
    if (empty($data['nombre']) || empty($data['cedula']) || empty($data['especialidad']) || empty($data['email'])) {
        http_response_code(400); // Código de error Bad Request
        echo json_encode(['message' => 'Todos los campos son requeridos.']);
        exit();
    }

    // Validación de formato de email
    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(['message' => 'Formato de email inválido.']);
        exit();
    }

    // Validación de cédula (solo números)
    if (!ctype_digit($data['cedula'])) {
        http_response_code(400);
        echo json_encode(['message' => 'La cédula debe contener solo números.']);
        exit();
    }

    $doctors = getDoctors($dataFile); // Obtiene la lista actual de médicos

    // 2. Asignar un ID incremental
    $newId = 1;
    if (!empty($doctors)) {
        // Busca el ID más alto y le suma 1
        $lastDoctor = end($doctors); // Obtiene el último elemento del array
        $newId = $lastDoctor['id'] + 1;
    }

    // 3. Crear el nuevo objeto médico
    $newDoctor = [
        'id'          => $newId,
        'nombre'      => $data['nombre'],
        'cedula'      => $data['cedula'],
        'especialidad'=> $data['especialidad'],
        'email'       => $data['email']
    ];

    // 4. Agregar el nuevo médico a la lista y guardar
    $doctors[] = $newDoctor; // Agrega el nuevo médico al array
    saveDoctors($dataFile, $doctors); 

    http_response_code(201); 
    echo json_encode(['message' => 'Médico registrado con éxito.', 'doctor' => $newDoctor]);
    exit();
}

// Si llega aquí, es un método HTTP no soportado
http_response_code(405); 
echo json_encode(['message' => 'Método no permitido.']);
?>