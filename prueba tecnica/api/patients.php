<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$patientDataFile = '../data/patients.json';
$doctorDataFile = '../data/doctors.json'; 



function getPatients($dataFile) {
    if (!file_exists($dataFile) || filesize($dataFile) == 0) {
        return [];
    }
    $json = file_get_contents($dataFile);
    return json_decode($json, true);
}

function savePatients($dataFile, $patients) {
    file_put_contents($dataFile, json_encode($patients, JSON_PRETTY_PRINT));
}

// Función para obtener médicos (la misma de doctors.php)
function getAllDoctors($dataFile) {
    if (!file_exists($dataFile) || filesize($dataFile) == 0) {
        return [];
    }
    $json = file_get_contents($dataFile);
    return json_decode($json, true);
}



$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $patients = getPatients($patientDataFile);
    echo json_encode($patients);
    exit();
}

if ($method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    // 1. Validación de campos
    if (empty($data['nombre']) || empty($data['fecha_nacimiento']) || empty($data['medico_id'])) {
        http_response_code(400);
        echo json_encode(['message' => 'Todos los campos son requeridos.']);
        exit();
    }

    // Validación de fecha 
    if (!preg_match("/^\d{4}-\d{2}-\d{2}$/", $data['fecha_nacimiento'])) {
        http_response_code(400);
        echo json_encode(['message' => 'Formato de fecha de nacimiento inválido (YYYY-MM-DD).']);
        exit();
    }

    // 2. Validación de que medico_id exista
    $doctors = getAllDoctors($doctorDataFile);
    $medicoExiste = false;
    foreach ($doctors as $doctor) {
        if ($doctor['id'] == $data['medico_id']) {
            $medicoExiste = true;
            break;
        }
    }

    if (!$medicoExiste) {
        http_response_code(400);
        echo json_encode(['message' => 'El médico asignado no existe.']);
        exit();
    }

    $patients = getPatients($patientDataFile);

    // 3. Asignar un ID incremental
    $newId = 1;
    if (!empty($patients)) {
        $lastPatient = end($patients);
        $newId = $lastPatient['id'] + 1;
    }

    // 4. Crear el nuevo objeto paciente
    $newPatient = [
        'id'             => $newId,
        'nombre'         => $data['nombre'],
        'fecha_nacimiento' => $data['fecha_nacimiento'],
        'medico_id'      => (int)$data['medico_id'] // Asegurarse de que sea un entero
    ];

    // 5. Agregar el nuevo paciente a la lista y guardar
    $patients[] = $newPatient;
    savePatients($patientDataFile, $patients);

    http_response_code(201);
    echo json_encode(['message' => 'Paciente registrado con éxito.', 'patient' => $newPatient]);
    exit();
}

http_response_code(405);
echo json_encode(['message' => 'Método no permitido.']);
?>