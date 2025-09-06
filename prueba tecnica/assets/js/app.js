document.addEventListener('DOMContentLoaded', () => {
    // 1. Obtener referencias a elementos del DOM
    const doctorForm = document.getElementById('doctorForm');
    const patientForm = document.getElementById('patientForm');
    const doctorsListBody = document.querySelector('#doctorsList tbody');
    const patientsListBody = document.querySelector('#patientsList tbody');
    const patientMedicoSelect = document.getElementById('patientMedicoId');

   

    // Función genérica para hacer solicitudes GET
    async function fetchData(url) {
        try {
            const response = await fetch(url);
            if (!response.ok) { // Si la respuesta no es 2xx (ej. 400, 500)
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return await response.json();
        } catch (error) {
            console.error('Error fetching data:', error);
            alert('Error al cargar datos. Consulta la consola para más detalles.');
            return []; 
        }
    }

    // Función genérica para hacer solicitudes POST
    async function postData(url, data) {
        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data) 
            });
            const responseData = await response.json();

            if (!response.ok) {
                
                throw new Error(responseData.message || `HTTP error! status: ${response.status}`);
            }
            return responseData;
        } catch (error) {
            console.error('Error posting data:', error);
            alert(`Error al guardar: ${error.message}`);
            return null;
        }
    }

    // --- Funciones para cargar y renderizar listas ---

    async function loadDoctors() {
        const doctors = await fetchData('api/doctors.php');
        doctorsListBody.innerHTML = ''; 
        patientMedicoSelect.innerHTML = '<option value="">Selecciona un médico</option>'; 

        if (doctors.length === 0) {
            doctorsListBody.innerHTML = '<tr><td colspan="5">No hay médicos registrados.</td></tr>';
            return;
        }

        doctors.forEach(doctor => {
            const row = doctorsListBody.insertRow();
            row.insertCell().textContent = doctor.id;
            row.insertCell().textContent = doctor.nombre;
            row.insertCell().textContent = doctor.cedula;
            row.insertCell().textContent = doctor.especialidad;
            row.insertCell().textContent = doctor.email;

            // Rellenar el <select> de pacientes
            const option = document.createElement('option');
            option.value = doctor.id;
            option.textContent = `${doctor.nombre} (${doctor.especialidad})`;
            patientMedicoSelect.appendChild(option);
        });
    }

    async function loadPatients() {
        const patients = await fetchData('api/patients.php');
        patientsListBody.innerHTML = ''; 

        if (patients.length === 0) {
            patientsListBody.innerHTML = '<tr><td colspan="4">No hay pacientes registrados.</td></tr>';
            return;
        }

        patients.forEach(patient => {
            const row = patientsListBody.insertRow();
            row.insertCell().textContent = patient.id;
            row.insertCell().textContent = patient.nombre;
            row.insertCell().textContent = patient.fecha_nacimiento;
            row.insertCell().textContent = patient.medico_id;
        });
    }

    // --- Manejo de envío de formularios ---

    doctorForm.addEventListener('submit', async (event) => {
        event.preventDefault(); 

        const formData = new FormData(doctorForm); 
        const doctorData = Object.fromEntries(formData.entries()); 

        const result = await postData('api/doctors.php', doctorData);
        if (result) {
            alert(result.message);
            doctorForm.reset(); 
            await loadDoctors(); 
            await loadPatients(); 
        }
    });

    patientForm.addEventListener('submit', async (event) => {
        event.preventDefault();

        const formData = new FormData(patientForm);
        const patientData = Object.fromEntries(formData.entries());

        const result = await postData('api/patients.php', patientData);
        if (result) {
            alert(result.message);
            patientForm.reset(); 
            await loadPatients(); 
        }
    });

    // --- Cargar datos iniciales al cargar la página ---
    loadDoctors();
    loadPatients();
});