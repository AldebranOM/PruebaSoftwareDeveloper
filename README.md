# 📋 Prueba Técnica Software Developer
Tecnologías: JS, PHP, HTML, SCSS


**Tema:** Registro básico de **médicos** y **pacientes**

## Objetivo

Construir una **sola página** con 2 formularios (Médicos y Pacientes) y un listado por cada uno, usando un backend PHP muy sencillo que guarda datos en archivos JSON.

---

## Requisitos mínimos

### 1) Frontend (HTML + SCSS + JS)

* Una página `index.html` con dos secciones:

  * **Médicos:** formulario con campos:

    * `nombre` (requerido)
    * `cedula` (requerido, numérico simple)
    * `especialidad` (requerido; input de texto)
    * `email` (requerido, formato email)
  * **Pacientes:** formulario con campos:

    * `nombre` (requerido)
    * `fecha_nacimiento` (requerido, tipo `date`)
    * `medico_id` (requerido; **select** poblado con los médicos registrados)
* Debajo de cada formulario, mostrar un **listado** sencillo (tabla o lista) con los registros actuales.
* Validación básica en el navegador (HTML5 + un poco de JS para mensajes amigables).
* **SCSS**:

  * Usa un archivo `styles.scss` con **una variable** para color principal y **un mixin** para un botón básico.
  * Estilos mínimos y limpios; no se requiere responsive avanzado.

### 2) Backend (PHP)

* Dos endpoints muy simples:

  * `api/doctors.php`

    * **GET**: devuelve JSON con todos los médicos.
    * **POST**: recibe JSON con `{ nombre, cedula, especialidad, email }`, valida mínimos y **agrega** al archivo `data/doctors.json` (crear si no existe). Asigna un `id` incremental.
  * `api/patients.php`

    * **GET**: devuelve JSON con todos los pacientes.
    * **POST**: recibe JSON con `{ nombre, fecha_nacimiento, medico_id }`, valida mínimos, **verifica** que `medico_id` exista en `doctors.json`, y agrega al archivo `data/patients.json` con `id` incremental.
* Respuestas con `Content-Type: application/json` y códigos HTTP (`201` al crear, `400` si falta algo).

### 3) Ejecución local

* Requisito: **PHP 7.4+**
* Desde la raíz del proyecto:

  ```bash
  php -S localhost:8080
  ```
* Luego abrir `http://localhost:8080` en el navegador.

---

## Estructura sugerida

```
/ (raíz)
├─ index.html
├─ /assets
│  ├─ /scss
│  │  └─ styles.scss      (compila a /assets/css/styles.css)
│  ├─ /css
│  │  └─ styles.css
│  └─ /js
│     └─ app.js          (fetch a la API + render de tablas)
├─ /api
│  ├─ doctors.php
│  └─ patients.php
├─ /data
│  ├─ doctors.json       (inicia como [] o no existe)
│  └─ patients.json      (inicia como [] o no existe)
└─ README.md
```

---

## Criterios de evaluación (100 pts)

* **HTML/SCSS (25 pts):** estructura clara, uso de 1 variable y 1 mixin en SCSS.
* **JS (35 pts):** envío de formularios con `fetch`, carga inicial de listas, actualización de `select` de médicos.
* **PHP (35 pts):** endpoints GET/POST funcionales, validaciones mínimas, escritura/lectura JSON.
* **README (5 pts):** cómo ejecutar y probar.

---

## Datos de ejemplo (opcional)

* Puedes precargar `data/doctors.json` con:

  ```json
  [{ "id": 1, "nombre": "Dra. Ana López", "cedula": "1234567", "especialidad": "General", "email": "ana@example.com" }]
  ```
* Para `patients.json` comienza como `[]`.

---

## Sugerencia de implementación (ultra-resumen)

* `app.js`:

  * `loadDoctors()` → GET `/api/doctors.php` → llena tabla y `<select name="medico_id">`.
  * `loadPatients()` → GET `/api/patients.php` → llena tabla.
  * `submitDoctorForm()` / `submitPatientForm()` → POST JSON y recargar listas.
* `doctors.php`/`patients.php`:

  * Detectar método (`$_SERVER['REQUEST_METHOD']`).
  * En POST: `json_decode(file_get_contents('php://input'), true)`.
  * Validar campos, leer archivo si existe, push + asignar `id`, guardar.
  * En GET: leer archivo o responder `[]`.

---

Posible ejemplo y estructura de proyecto:
<img width="1192" height="798" alt="image" src="https://github.com/user-attachments/assets/63f1d539-b405-414e-b784-9a3c21e72fd4" />



Video Demo
[![Mira el demo 🎬 ](https://github.com/user-attachments/assets/529d308e-a2e9-4814-891f-dfabae9a470b)]([https://www.youtube.com/watch?v=VIDEO_ID](https://drive.google.com/file/d/1_33aB9ssx6-AmGhL9zNtRERWgc9PkxP8/view?usp=sharing))


