# Manual de Usuario — SHCSO
## Sistema de Historias Clínicas en Salud Ocupacional

**Versión:** 1.0  
**Fecha:** Abril 2026  
**Soporte técnico:** Consultar con el administrador del sistema

---

## Tabla de Contenidos

1. [Acceso al sistema](#1-acceso-al-sistema)
2. [Panel principal (Dashboard)](#2-panel-principal-dashboard)
3. [Módulo Trabajadores](#3-módulo-trabajadores)
   - 3.1 [Buscar trabajadores](#31-buscar-trabajadores)
   - 3.2 [Crear nuevo trabajador](#32-crear-nuevo-trabajador)
   - 3.3 [Historia clínica ampliada](#33-historia-clínica-ampliada)
   - 3.4 [Historial clínico](#34-historial-clínico)
   - 3.5 [Evoluciones clínicas](#35-evoluciones-clínicas)
4. [Módulo Operación Médica](#4-módulo-operación-médica)
   - 4.1 [Consulta médica — Método SOAP](#41-consulta-médica--método-soap)
   - 4.2 [Diagnósticos CIE-10](#42-diagnósticos-cie-10)
   - 4.3 [Receta médica](#43-receta-médica)
   - 4.4 [Certificado y adjunto](#44-certificado-y-adjunto)
5. [Prescripciones — Imprimir receta](#5-prescripciones--imprimir-receta)
6. [Módulo Usuarios](#6-módulo-usuarios)
7. [Mi Perfil profesional](#7-mi-perfil-profesional)
8. [Roles y permisos](#8-roles-y-permisos)
9. [Preguntas frecuentes](#9-preguntas-frecuentes)

---

## 1. Acceso al sistema

### Iniciar sesión

1. Abra el navegador web y acceda a la dirección del sistema (ej: `http://192.168.x.x:8000/sistema`).
2. Ingrese su **correo electrónico** y **contraseña** en el formulario de acceso.
3. Haga clic en **"Entrar"**.

> **Nota:** Si es la primera vez que accede al sistema y no tiene usuario, solicite al administrador que cree su cuenta.

### Recuperación de contraseña

Si olvidó su contraseña:

1. Haga clic en **"Olvidé mi contraseña"**.
2. Ingrese su correo electrónico y haga clic en **"Solicitar token"**.
3. El sistema mostrará un enlace o token de recuperación (en entornos locales aparece en pantalla).
4. Haga clic en **"Ya tengo token"**, ingrese el token recibido, su correo y la nueva contraseña.
5. Haga clic en **"Cambiar contraseña"** y luego inicie sesión normalmente.

---

## 2. Panel principal (Dashboard)

Al ingresar al sistema verá el panel principal con los indicadores clave:

| Indicador | Descripción |
|-----------|-------------|
| **Trabajadores** | Total de trabajadores registrados |
| **Evaluaciones** | Total de consultas médicas realizadas |
| **Certificados** | Total de certificados de aptitud generados |
| **Pendientes** | Certificados pendientes de generación PDF |

El panel también muestra:
- **Gráfico de actividad mensual:** consultas por mes en los últimos 6 meses.
- **Aptitud por empresa:** distribución de resultados de aptitud médica por empresa.

Haga clic en las pestañas superiores (**Dashboard / Trabajadores / Operación / Usuarios**) para navegar entre módulos.

---

## 3. Módulo Trabajadores

Acceda haciendo clic en la pestaña **"Trabajadores"** en la barra superior.

### 3.1 Buscar trabajadores

- En la parte superior encontrará un campo de búsqueda. Escriba el nombre, número de documento, o nombre de empresa del trabajador.
- Haga clic en **"Buscar"** o presione Enter.
- Los resultados aparecerán en la tabla. Haga clic en **"Ver historial"** para abrir el expediente, o en **"Editar"** para modificar los datos.

### 3.2 Crear nuevo trabajador

1. Haga clic en el botón **"+ Nuevo trabajador"** (pestaña 2 del flujo de trabajadores).
2. Complete los campos obligatorios:
   - Tipo de documento (Cédula, Pasaporte, etc.)
   - Número de documento
   - Nombres y apellidos
   - Fecha de nacimiento
   - Sexo
3. Complete los campos opcionales: correo, teléfono, grupo sanguíneo, lateralidad, empresa, puesto de trabajo.
4. Haga clic en **"Guardar trabajador"**.

> **Tip:** Asigne la empresa y el puesto de trabajo para que los certificados e informes incluyan esta información automáticamente.

### 3.3 Historia clínica ampliada

Pestaña **"3. Historia clínica ampliada"** — disponible después de seleccionar un trabajador.

Registre los antecedentes permanentes del trabajador:

| Campo | Contenido |
|-------|-----------|
| **Antecedentes personales** | Enfermedades crónicas, hospitalizaciones previas |
| **Antecedentes familiares** | Enfermedades hereditarias relevantes |
| **Alergias** | Medicamentos, alimentos, materiales a los que es alérgico |
| **Medicación actual** | Fármacos que toma regularmente |
| **Antecedentes patológicos** | Enfermedades diagnosticadas anteriormente |
| **Antecedentes quirúrgicos** | Cirugías realizadas |
| **Historia ocupacional** | Exposiciones laborales previas (ruido, químicos, ergonomía, etc.) |
| **Estilo de vida** | Hábitos (tabaco, alcohol, actividad física) |
| **Notas longitudinales** | Seguimiento clínico general a lo largo del tiempo |

Haga clic en **"Guardar historia clínica"** para registrar los cambios.

### 3.4 Historial clínico

Pestaña **"4. Historial clínico"** — muestra todo el expediente del trabajador seleccionado.

Encontrará:
- **Evaluaciones:** todas las consultas médicas realizadas, con detalle SOAP, diagnósticos, adjuntos y receta.
- **Certificados:** certificados de aptitud emitidos.
- **Línea de tiempo:** cronología de todos los eventos clínicos.

Para **imprimir la receta médica** de una evaluación que tenga prescripciones, haga clic en el botón **"🖨️ Imprimir receta"** que aparece al pie de la evaluación correspondiente. El sistema generará un PDF descargable.

### 3.5 Evoluciones clínicas

Pestaña **"5. Evoluciones"** — registre el seguimiento clínico del trabajador entre consultas formales.

Las evoluciones son notas de seguimiento que complementan la historia clínica sin generar una nueva evaluación formal.

**Tipos de evolución:**
- **Seguimiento:** control de evolución de un problema de salud.
- **Nota clínica:** observación general del estado del paciente.
- **Interconsulta:** resultado de una derivación a otro especialista.

**Para registrar una evolución:**

1. Seleccione el tipo de evolución.
2. (Opcional) Vincule a una evaluación previa.
3. Complete los campos SOAP según corresponda.
4. Registre signos vitales si los tiene disponibles.
5. Haga clic en **"Guardar evolución"**.

Para **editar** una evolución existente, haga clic en **"Editar"** en la tarjeta correspondiente. Para **eliminar**, haga clic en **"Eliminar"** (se pedirá confirmación).

---

## 4. Módulo Operación Médica

Acceda haciendo clic en la pestaña **"Operación"** en la barra superior.

Este módulo tiene 4 pasos del flujo de atención:

1. **Consulta médica** → registrar la atención
2. **Certificado y adjunto** → emitir aptitud y cargar documentos
3. **Evaluaciones recientes** → consultar historial reciente
4. **Certificados recientes** → consultar y descargar PDFs

### 4.1 Consulta médica — Método SOAP

**¿Qué es el Método SOAP?**

El método SOAP es el estándar internacional para documentar consultas médicas de forma ordenada y reproducible. Permite que cualquier profesional que revise el expediente comprenda la atención brindada.

> Haga clic en el botón **"?"** junto al título "MÉTODO SOAP" para ver una guía detallada en pantalla.

#### Campos del formulario

**Datos del paciente:**
- **Trabajador:** seleccione el paciente usando el buscador (escriba nombre o número de documento).
- **Motivo de consulta general:** descripción breve del motivo de la visita.
- **Problema actual:** descripción del problema de salud que genera la consulta.

**Signos vitales:**

| Campo | Unidad | Ejemplo |
|-------|--------|---------|
| Presión arterial | mmHg | 120/80 |
| Temperatura | °C | 36.5 |
| Frecuencia cardíaca | lpm | 72 |
| Frecuencia respiratoria | rpm | 16 |
| Peso | kg | 70 |
| Talla | cm | 170 |

**Método SOAP:**

| Letra | Nombre | Qué registrar |
|-------|--------|---------------|
| **S** | **Subjetivo** | Lo que el paciente refiere: síntomas, historia del problema, tiempo de evolución, intensidad. *Ej: "Dolor lumbar de 3 días, 7/10 de intensidad."* |
| **O** | **Objetivo** | Lo que el médico observa y mide: hallazgos del examen físico, resultados de laboratorio o imágenes. *Ej: "Contractura muscular paravertebral L4-L5."* |
| **A** | **Análisis** | El diagnóstico: interpretación clínica combinando S + O. Incluya el código CIE-10 del diagnóstico principal. *Ej: "M54.5 - Lumbago no especificado."* |
| **P** | **Plan** | Las acciones a tomar: medicamentos, reposo, fisioterapia, interconsultas, seguimiento. *Ej: "Ibuprofeno 400mg c/8h x5 días. Control en 1 semana."* |

**Datos de la consulta:**
- **Tipo:** Ingreso / Periódico / Reintegro / Retiro
- **Aptitud médica:** APTO / APTO CON OBSERVACIÓN / APTO CON LIMITACIONES / NO APTO
- **Fecha de atención:** fecha de la consulta
- **Profesional y Código:** se llenan automáticamente desde su perfil (ver sección 7)

Haga clic en **"Guardar consulta"** para registrar la evaluación.

### 4.2 Diagnósticos CIE-10

El sistema incluye el catálogo completo de la **Clasificación Internacional de Enfermedades (CIE-10)** con más de 12.000 códigos en español.

**Para buscar un diagnóstico:**

1. En el campo "Buscador CIE-10", escriba el código (ej: `J06`) o la descripción en español (ej: `lumbalgia`).
2. Espere los resultados (aparecen automáticamente al escribir 2 o más caracteres).
3. Haga clic en **"Agregar"** junto al diagnóstico correcto.
4. El diagnóstico se agrega a la lista de seleccionados. Puede marcar cada diagnóstico como **DEFINITIVO** o **PRESUNTIVO**.
5. Para eliminar un diagnóstico, haga clic en **"✕"** en su tarjeta.

Puede agregar múltiples diagnósticos a una misma consulta.

### 4.3 Receta médica

La sección "Receta médica" es opcional y permite registrar la prescripción de medicamentos.

**Para agregar un medicamento a la receta:**

1. En el campo **"Medicamento"**, empiece a escribir el nombre genérico o comercial. El sistema muestra sugerencias del cuadro básico automáticamente.
2. Seleccione el medicamento de la lista (o escríbalo manualmente si no está en el catálogo).
3. Complete **Dosis** (obligatorio), Frecuencia, Duración e Indicaciones.
4. Haga clic en **"+ Agregar medicamento"**.
5. El medicamento aparece en la lista de la receta. Puede agregar tantos como necesite.
6. Para eliminar un medicamento de la receta, haga clic en **"✕"** en su fila.

La receta se guarda automáticamente al guardar la consulta.

### 4.4 Certificado y adjunto

Después de registrar la consulta, puede generar el certificado de aptitud médica:

1. En **"Flujo 2/2"**, seleccione la evaluación del paciente en el campo "Evaluación".
2. Ingrese las observaciones y recomendaciones.
3. Seleccione el tipo de aptitud y la fecha de emisión.
4. Haga clic en **"Generar certificado"** para crear el registro.
5. Luego haga clic en **"Generar PDF"** en la tabla de certificados para crear el documento imprimible.
6. Descargue el PDF con el botón **"Descargar"**.

**Cargar documentos adjuntos:**

En la sección "Adjuntos" puede cargar exámenes de laboratorio, imágenes diagnósticas u otros documentos:

1. Seleccione la evaluación correspondiente.
2. Elija el tipo de adjunto (Laboratorio, Imagen, DICOM, Otro).
3. Seleccione el archivo (PDF, JPG, PNG, DICOM).
4. Agregue notas si es necesario.
5. Haga clic en **"Subir adjunto"**.

---

## 5. Prescripciones — Imprimir receta

Para imprimir una receta médica de una consulta anterior:

1. Vaya a **Trabajadores → 4. Historial clínico**.
2. Seleccione el trabajador.
3. Busque la evaluación que contiene la receta (las evaluaciones con prescripciones muestran el botón **"🖨️ Imprimir receta"**).
4. Haga clic en **"🖨️ Imprimir receta"**.
5. El sistema genera y descarga automáticamente un PDF con:
   - Datos del establecimiento de salud
   - Datos del paciente
   - Lista de medicamentos con dosis, frecuencia y duración
   - Indicaciones generales
   - Espacio para firma y sello del profesional

---

## 6. Módulo Usuarios

> **Requiere rol ADMINISTRADOR**

Acceda haciendo clic en la pestaña **"Usuarios"** (visible solo para administradores).

### Crear usuario

1. Complete el formulario con nombre completo, correo electrónico, contraseña y rol.
2. Haga clic en **"Crear usuario"**.

### Editar usuario

1. En la tabla de usuarios, haga clic en **"Editar"** junto al usuario que desea modificar.
2. Actualice los campos necesarios (puede dejar la contraseña en blanco para no cambiarla).
3. Haga clic en **"Actualizar usuario"**.

### Activar/Desactivar usuario

Haga clic en **"Activar"** o **"Desactivar"** en la fila del usuario. Los usuarios desactivados no pueden iniciar sesión.

> **Nota:** No puede desactivar su propia cuenta.

---

## 7. Mi Perfil profesional

Para actualizar sus datos profesionales (nombre y código que aparecen en las consultas y recetas):

1. Haga clic en el botón **"👤 Mi Perfil"** en la barra superior (visible cuando está autenticado).
2. En el modal que aparece, actualice:
   - **Nombre completo:** nombre que aparecerá en los formularios y documentos.
   - **Código profesional:** su código de registro (ej: MED-12345, ENF-98765).
   - **Nueva contraseña:** solo si desea cambiarla (dejar en blanco para mantenerla).
3. Haga clic en **"Guardar cambios"**.

> **⚠️ Importante:** Si el código profesional está vacío, el sistema le mostrará una advertencia en amarillo. Complete este campo para que aparezca correctamente en las recetas médicas y certificados.

Los campos **"Profesional"** y **"Código profesional"** en el formulario de consulta se llenan automáticamente con los datos de su perfil.

---

## 8. Roles y permisos

El sistema maneja los siguientes roles de usuario:

| Rol | Descripción | Permisos |
|-----|-------------|----------|
| **ADMIN** | Administrador del sistema | Acceso total: usuarios, reportes, evaluaciones, certificados PDF, exportar |
| **MEDICO_OCUPACIONAL** | Médico ocupacional | Consultas, historia clínica, evoluciones, certificados PDF, recetas, CIE-10 |
| **ENFERMERIA** | Personal de enfermería | Consultas, historia clínica, evoluciones, recetas (sin PDF de certificados) |
| **RECEPCION** | Recepcionista | Ver trabajadores, evaluaciones y catálogos (solo lectura) |
| **AUDITOR** | Auditor interno | Solo lectura de todo el sistema |

> Los administradores asignan roles al crear o editar usuarios.

---

## 9. Preguntas frecuentes

**¿Por qué no puedo generar PDFs de certificados?**  
Solo los usuarios con rol **ADMIN** o **MEDICO_OCUPACIONAL** pueden generar PDFs. Contacte al administrador para verificar su rol.

**¿Cómo actualizo los códigos CIE-10?**  
El catálogo CIE-10 puede actualizarse desde el servidor con el comando:
```
php artisan cie10:actualizar --fuente=csv
```
Contacte al administrador técnico del sistema.

**¿Puedo usar el sistema desde otro dispositivo en la red?**  
Sí. Abra el navegador e ingrese la dirección IP del servidor seguida del puerto (ej: `http://192.168.x.x:8000/sistema`). Asegúrese de estar en la misma red.

**¿Los datos se guardan automáticamente?**  
No. Debe hacer clic en el botón **"Guardar"** correspondiente en cada formulario. El sistema confirma el guardado con un mensaje de estado en la barra superior.

**¿Qué hago si el sistema muestra "Token inválido o expirado"?**  
Su sesión expiró. Haga clic en **"Cerrar sesión"** e inicie sesión nuevamente.

**¿Puedo eliminar una evaluación o certificado?**  
Por política de integridad clínica, las evaluaciones y certificados no se eliminan. Sí puede eliminar trabajadores (esto elimina todo su historial) y evoluciones clínicas.

**¿Dónde están las prescripciones de un paciente?**  
Las prescripciones se registran durante la consulta médica (Módulo Operación → Receta médica). Para verlas, vaya a **Trabajadores → 4. Historial clínico** → seleccione la evaluación correspondiente. Si tiene receta, aparecerá listada y encontrará el botón **"🖨️ Imprimir receta"**.

**¿Qué significa cada nivel de aptitud médica?**

| Aptitud | Significado |
|---------|-------------|
| **APTO** | El trabajador está en plenas condiciones para desempeñar su puesto |
| **APTO CON OBSERVACIÓN** | Apto, pero requiere seguimiento médico periódico |
| **APTO CON LIMITACIONES** | Puede trabajar con restricciones específicas (ej: no trabajo en altura) |
| **NO APTO** | No está en condiciones de desempeñar el puesto actual |

---

*SHCSO — Sistema de Historias Clínicas en Salud Ocupacional*  
*Para soporte técnico, contacte al administrador del sistema.*
