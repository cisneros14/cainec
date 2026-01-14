1️⃣ CAMPOS COMUNES (APLICAN A TODOS LOS TIPOS)

Estos campos SIEMPRE deben existir, independientemente de tipo_socio.

🔐 Identidad & acceso

id

usuario

email

password_hash

rol

estado

password_reset_code

password_reset_expires

📍 Ubicación y contacto

provincia

ciudad

direccion

telefono_contacto (celular principal)

telefono_contacto2 (opcional / alterno)

🌐 Presencia digital

pagina_web

facebook

instagram

linkedin

🖼 Imagen / identidad visual

img_url (foto de perfil o representante)

logo_url (si aplica)

🕒 Control del sistema

fecha_creacion

fecha_actualizacion

🧾 Identificación general

cedula_ruc (cédula o RUC según tipo)

2️⃣ PERSONA NATURAL (tipo_socio = 'natural')
🧍 Datos personales

nombre

apellido

cedula_ruc

fecha_nacimiento

genero

🎓 Formación y perfil profesional

nivel_educacion

formacion

registro_profesional

certificaciones

habilidades

💼 Actividad económica

actividad

ciudad_operaciones

plazas_trabajo_generadas

📝 Información descriptiva

descripcion

3️⃣ PERSONA JURÍDICA / EMPRESA (tipo_socio = 'juridica')
🏢 Identidad legal y comercial

empresa (nombre comercial)

nombre_juridico

cedula_ruc (RUC)

representante_legal

📈 Actividad empresarial

actividad

inicio_actividades

plazas_trabajo_generadas

👔 Gestión / dirección

cargo (si el usuario representa a la empresa)

directiva

📝 Información institucional

descripcion

4️⃣ ORGANIZACIÓN / GREMIO / ASOCIACIÓN (tipo_socio = 'organizacion')
🏛 Identidad institucional

empresa (nombre comercial o siglas)

nombre_juridico

sector

cedula_ruc

👥 Representación

representante_legal

director_ejecutivo

cargo

👨‍👩‍👧‍👦 Base social

numero_miembros

📅 Actividad institucional

inicio_actividades

📝 Información corporativa

descripcion

5️⃣ CAMPOS TRANSVERSALES RECOMENDADOS (ya cubiertos en tu modelo)

Estos campos son correctos y bien pensados para cualquier tipo:

estado → flujo de aprobación

directiva → rol institucional

licencia → permisos, membresía o acreditación

rol → control de acceso

img_url / logo_url → identidad visual