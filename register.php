<?php
session_start();

// Si ya está logueado, redirigir a admin
if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
    header('Location: admin/entradas.php');
    exit;
}
?>
<?php include 'components/header.php'; ?>
<script>
    function togglePasswordVisibility(btn) {
        if (!btn) return;
        const described = btn.getAttribute('aria-describedby');
        const input = described ? document.getElementById(described) : btn.closest('.relative')?.querySelector('input');
        if (!input) return;
        const svg = btn.querySelector('svg');

        if (input.type === 'password') {
            input.type = 'text';
            if (svg) svg.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223a10.477 10.477 0 00-1.942 3.754 1.012 1.012 0 000 .646C3.423 16.49 7.3 19.5 12 19.5c1.874 0 3.63-.5 5.147-1.384M9.88 9.88a3 3 0 104.24 4.24M6.1 6.1l11.8 11.8" />';
        } else {
            input.type = 'password';
            if (svg) svg.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.644C3.423 7.51 7.3 4.5 12 4.5c4.7 0 8.577 3.01 9.964 7.178.07.204.07.44 0 .644C20.577 16.49 16.7 19.5 12 19.5c-4.7 0-8.577-3.01-9.964-7.178z" />\n                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />';
        }
    }
</script>

<div id="smooth-wrapper">
    <div id="smooth-content">
        <main id="primary" class="site-main">
            <div class="space-for-header"></div>
            <!-- start: Register Section -->
            <section class="full-width tj-page__area section-gap">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <div class="tj-page__container">
                                <div class="tj-entry__content">
                                    <div class="flex flex-col lg:flex-row gap-12 items-stretch">

                                        <!-- Left Column: Procedimiento Section -->
                                        <div class="w-full lg:w-1/2">
                                            <div class="pt-0">
                                                <div class="mb-8">
                                                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Proceso de
                                                        Registro</h3>
                                                    <p class="text-gray-600 text-sm">Sigue estos pasos para unirte a
                                                        nuestra comunidad</p>
                                                </div>

                                                <div
                                                    class="!space-y-8 relative before:absolute before:inset-0 before:ml-4 before:-translate-x-px before:h-full before:w-0.5 before:bg-gray-200 before:z-0">

                                                    <!-- Paso 1 -->
                                                    <div class="relative flex gap-6 z-10 mb-6">
                                                        <div
                                                            class="bg-white rounded-xl p-4 md:p-4 border border-gray-100 w-full hover:shadow-md transition-shadow">
                                                            <div class="flex items-center gap-3 mb-2">
                                                                <div
                                                                    class="flex-shrink-0 w-8 h-8 rounded-full bg-[var(--tj-color-theme-primary)] text-white flex items-center justify-center font-bold text-sm shadow-md ring-4 ring-white">
                                                                    1</div>
                                                                <h4 class="font-bold text-gray-900 m-0">Registro en
                                                                    Línea</h4>
                                                            </div>
                                                            <p class="text-sm text-gray-600 leading-relaxed">Completa el
                                                                formulario de registro con tus datos personales y de
                                                                contacto. Asegúrate de que la información ingresada sea
                                                                correcta y veraz.</p>
                                                        </div>
                                                    </div>

                                                    <!-- Paso 2 -->
                                                    <div class="relative flex gap-6 z-10 mb-6">
                                                        <div
                                                            class="bg-white rounded-xl p-4 md:p-4 border border-gray-100 w-full hover:shadow-md transition-shadow">
                                                            <div class="flex items-center gap-3 mb-2">
                                                                <div
                                                                    class="flex-shrink-0 w-8 h-8 rounded-full bg-[var(--tj-color-theme-primary)] text-white flex items-center justify-center font-bold text-sm shadow-md ring-4 ring-white">
                                                                    2</div>
                                                                <h4 class="font-bold text-gray-900 m-0">Revisión de
                                                                    Solicitud</h4>
                                                            </div>
                                                            <p class="text-sm text-gray-600 leading-relaxed">Un
                                                                administrador revisará tu solicitud para verificar que
                                                                cumples con los requisitos iniciales para formar parte
                                                                de la plataforma.</p>
                                                        </div>
                                                    </div>

                                                    <!-- Paso 3 -->
                                                    <div class="relative flex gap-6 z-10 mb-6">
                                                        <div
                                                            class="bg-white rounded-xl p-4 md:p-4 border border-gray-100 w-full hover:shadow-md transition-shadow">
                                                            <div class="flex items-center gap-3 mb-2">
                                                                <div
                                                                    class="flex-shrink-0 w-8 h-8 rounded-full bg-[var(--tj-color-theme-primary)] text-white flex items-center justify-center font-bold text-sm shadow-md ring-4 ring-white">
                                                                    3</div>
                                                                <h4 class="font-bold text-gray-900 m-0">Verificación de
                                                                    Datos</h4>
                                                            </div>
                                                            <p class="text-sm text-gray-600 leading-relaxed">Se validará
                                                                la información proporcionada, como tu cédula y
                                                                antecedentes, para garantizar la seguridad y confianza
                                                                de la comunidad.</p>
                                                        </div>
                                                    </div>

                                                    <!-- Paso 4 -->
                                                    <div class="relative flex gap-6 z-10 mb-6">
                                                        <div
                                                            class="bg-white rounded-xl p-4 md:p-4 border border-gray-100 w-full hover:shadow-md transition-shadow">
                                                            <div class="flex items-center gap-3 mb-2">
                                                                <div
                                                                    class="flex-shrink-0 w-8 h-8 rounded-full bg-[var(--tj-color-theme-primary)] text-white flex items-center justify-center font-bold text-sm shadow-md ring-4 ring-white">
                                                                    4</div>
                                                                <h4 class="font-bold text-gray-900 m-0">Aprobación de
                                                                    Cuenta</h4>
                                                            </div>
                                                            <p class="text-sm text-gray-600 leading-relaxed">Una vez
                                                                verificada la información satisfactoriamente, tu cuenta
                                                                será aprobada oficialmente por el equipo administrativo.
                                                            </p>
                                                        </div>
                                                    </div>

                                                    <!-- Paso 5 -->
                                                    <div class="relative flex gap-6 z-10 mb-6">
                                                        <div
                                                            class="bg-white rounded-xl p-4 md:p-4 border border-gray-100 w-full hover:shadow-md transition-shadow">
                                                            <div class="flex items-center gap-3 mb-2">
                                                                <div
                                                                    class="flex-shrink-0 w-8 h-8 rounded-full bg-[var(--tj-color-theme-primary)] text-white flex items-center justify-center font-bold text-sm shadow-md ring-4 ring-white">
                                                                    5</div>
                                                                <h4 class="font-bold text-gray-900 m-0">Notificación
                                                                </h4>
                                                            </div>
                                                            <p class="text-sm text-gray-600 leading-relaxed">Recibirás
                                                                una notificación por correo electrónico confirmando la
                                                                activación de tu cuenta y los pasos para acceder.</p>
                                                        </div>
                                                    </div>

                                                    <!-- Paso 6 -->
                                                    <div class="relative flex gap-6 z-10 mb-6">
                                                        <div
                                                            class="bg-white rounded-xl p-4 md:p-4 border border-gray-100 w-full hover:shadow-md transition-shadow">
                                                            <div class="flex items-center gap-3 mb-2">
                                                                <div
                                                                    class="flex-shrink-0 w-8 h-8 rounded-full bg-[var(--tj-color-theme-primary)] text-white flex items-center justify-center font-bold text-sm shadow-md ring-4 ring-white">
                                                                    6</div>
                                                                <h4 class="font-bold text-gray-900 m-0">Acceso al
                                                                    Sistema</h4>
                                                            </div>
                                                            <p class="text-sm text-gray-600 leading-relaxed">Podrás
                                                                iniciar sesión en el sistema con tus credenciales y
                                                                acceder a todas las funcionalidades disponibles para
                                                                socios.</p>
                                                        </div>
                                                    </div>

                                                </div>

                                                <div
                                                    class="!mt-8 p-4 bg-blue-50 rounded-xl border border-blue-100 flex items-start gap-3">
                                                    <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                                        </path>
                                                    </svg>
                                                    <p class="text-sm text-blue-800 font-medium">
                                                        Por favor, mantente atento a tu correo electrónico para recibir
                                                        notificaciones sobre el estado de tu solicitud.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Right Column: Form -->
                                        <div class="w-full lg:w-1/2 ">
                                            <div
                                                class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 p-6 lg:p-8">
                                                <h3 class="mb-2">Registro de Usuario</h3>
                                                <p class="mb-6 text-sm text-gray-500">Completa el formulario para crear
                                                    tu cuenta.</p>

                                                <div id="form-alerts"></div>

                                                <form id="registerForm"
                                                    class="woocommerce-form woocommerce-form-register register"
                                                    method="post" novalidate="">

                                                    <!-- Tipo de Perfil -->
                                                    <div class="flex flex-col space-y-2 !mb-3">
                                                        <label for="tipo_perfil"
                                                            class="text-sm font-medium text-gray-700">
                                                            Tipo de Perfil <span class="text-red-500">*</span>
                                                        </label>
                                                        <select name="tipo_perfil" id="tipo_perfil" required
                                                            class="w-full rounded-xl border border-gray-300 px-4 py-2 text-gray-800 bg-white">
                                                            <option value="" disabled selected>Seleccione una opción
                                                            </option>
                                                            <option value="socio">Socio</option>
                                                            <option value="organizacion">Organización</option>
                                                        </select>
                                                    </div>

                                                    <!-- Subtipo Socio (Oculto por defecto) -->
                                                    <div id="container_subtipo_socio"
                                                        class="flex flex-col space-y-2 !mb-3" style="display: none;">
                                                        <label for="subtipo_socio"
                                                            class="text-sm font-medium text-gray-700">
                                                            Tipo de Socio <span class="text-red-500">*</span>
                                                        </label>
                                                        <select name="subtipo_socio" id="subtipo_socio"
                                                            class="w-full rounded-xl border border-gray-300 px-4 py-2 text-gray-800 bg-white">
                                                            <option value="" disabled selected>Seleccione una opción
                                                            </option>
                                                            <option value="natural">Persona Natural</option>
                                                            <option value="juridica">Persona Jurídica</option>
                                                        </select>
                                                    </div>

                                                    <!-- Datos Básicos (Siempre visibles) -->
                                                    <div class="row form-row rg-15">
                                                        <div class="col-md-6">
                                                            <div class="flex flex-col space-y-2 !mb-3">
                                                                <label for="nombre"
                                                                    class="text-sm font-medium text-gray-700">Nombre
                                                                    <span class="text-red-500">*</span></label>
                                                                <input type="text" name="nombre" id="nombre" required
                                                                    class="w-full rounded-xl border border-gray-300 px-4 py-2 text-gray-800"
                                                                    placeholder="Tu nombre">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="flex flex-col space-y-2 !mb-3">
                                                                <label for="apellido"
                                                                    class="text-sm font-medium text-gray-700">Apellido
                                                                    <span class="text-red-500">*</span></label>
                                                                <input type="text" name="apellido" id="apellido"
                                                                    required
                                                                    class="w-full rounded-xl border border-gray-300 px-4 py-2 text-gray-800"
                                                                    placeholder="Tu apellido">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row form-row rg-15">
                                                        <div class="col-md-6">
                                                            <div class="flex flex-col space-y-2 !mb-3">
                                                                <label for="cedula_ruc"
                                                                    class="text-sm font-medium text-gray-700">Cédula /
                                                                    RUC <span class="text-red-500">*</span></label>
                                                                <input type="text" name="cedula_ruc" id="cedula_ruc"
                                                                    required
                                                                    class="w-full rounded-xl border border-gray-300 px-4 py-2 text-gray-800"
                                                                    placeholder="Identificación" maxlength="15">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="flex flex-col space-y-2 !mb-3">
                                                                <label for="telefono_contacto"
                                                                    class="text-sm font-medium text-gray-700">Celular
                                                                    <span class="text-red-500">*</span></label>
                                                                <input type="tel" name="telefono_contacto"
                                                                    id="telefono_contacto" required
                                                                    class="w-full rounded-xl border border-gray-300 px-4 py-2 text-gray-800"
                                                                    placeholder="0987654321">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="flex flex-col space-y-2 !mb-3">
                                                        <label for="email"
                                                            class="text-sm font-medium text-gray-700">Correo electrónico
                                                            <span class="text-red-500">*</span></label>
                                                        <input type="email" name="email" id="email" required
                                                            class="w-full rounded-xl border border-gray-300 px-4 py-2 text-gray-800"
                                                            placeholder="ejemplo@correo.com">
                                                    </div>

                                                    <div class="row form-row rg-15">
                                                        <div class="col-md-6">
                                                            <div class="flex flex-col space-y-2 !mb-3">
                                                                <label for="provincia"
                                                                    class="text-sm font-medium text-gray-700">Provincia
                                                                    <span class="text-red-500">*</span></label>
                                                                <input type="text" name="provincia" id="provincia"
                                                                    required
                                                                    class="w-full rounded-xl border border-gray-300 px-4 py-2 text-gray-800"
                                                                    placeholder="Ej: Pichincha">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="flex flex-col space-y-2 !mb-3">
                                                                <label for="ciudad"
                                                                    class="text-sm font-medium text-gray-700">Ciudad
                                                                    <span class="text-red-500">*</span></label>
                                                                <input type="text" name="ciudad" id="ciudad" required
                                                                    class="w-full rounded-xl border border-gray-300 px-4 py-2 text-gray-800"
                                                                    placeholder="Ej: Quito">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- CAMPOS ESPECÍFICOS -->

                                                    <!-- NATURAL -->
                                                    <div id="fields_natural" style="display: none;">
                                                        <h4
                                                            class="text-lg font-bold text-gray-800 mb-4 mt-6 border-b pb-2">
                                                            Información Personal y Profesional</h4>

                                                        <div class="row form-row rg-15">
                                                            <div class="col-md-6">
                                                                <div class="flex flex-col space-y-2 !mb-3">
                                                                    <label
                                                                        class="text-sm font-medium text-gray-700">Fecha
                                                                        de Nacimiento</label>
                                                                    <input type="date" name="fecha_nacimiento"
                                                                        class="w-full rounded-xl border border-gray-300 px-4 py-2 text-gray-800">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="flex flex-col space-y-2 !mb-3">
                                                                    <label
                                                                        class="text-sm font-medium text-gray-700">Género</label>
                                                                    <select name="genero"
                                                                        class="w-full rounded-xl border border-gray-300 px-4 py-2 text-gray-800 bg-white">
                                                                        <option value="">Seleccione...</option>
                                                                        <option value="Masculino">Masculino</option>
                                                                        <option value="Femenino">Femenino</option>
                                                                        <option value="Otro">Otro</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row form-row rg-15">
                                                            <div class="col-md-6">
                                                                <div class="flex flex-col space-y-2 !mb-3">
                                                                    <label
                                                                        class="text-sm font-medium text-gray-700">Nivel
                                                                        de Educación</label>
                                                                    <select name="nivel_educacion"
                                                                        class="w-full rounded-xl border border-gray-300 px-4 py-2 text-gray-800 bg-white">
                                                                        <option value="">Seleccione...</option>
                                                                        <option value="Tercer Nivel">Tercer Nivel
                                                                        </option>
                                                                        <option value="Cuarto Nivel">Cuarto Nivel
                                                                        </option>
                                                                        <option value="Tecnológico">Tecnológico</option>
                                                                        <option value="Bachiller">Bachiller</option>
                                                                        <option value="Estudiante">Estudiante</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="flex flex-col space-y-2 !mb-3">
                                                                    <label
                                                                        class="text-sm font-medium text-gray-700">Registro
                                                                        Profesional</label>
                                                                    <input type="text" name="registro_profesional"
                                                                        class="w-full rounded-xl border border-gray-300 px-4 py-2 text-gray-800"
                                                                        placeholder="Senescyt / MSP">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="flex flex-col space-y-2 !mb-3">
                                                            <label class="text-sm font-medium text-gray-700">Formación
                                                                Académica</label>
                                                            <textarea name="formacion" rows="2"
                                                                class="w-full rounded-xl border border-gray-300 px-4 py-2 text-gray-800"
                                                                placeholder="Títulos y estudios relevantes"></textarea>
                                                        </div>

                                                        <div class="flex flex-col space-y-2 !mb-3">
                                                            <label
                                                                class="text-sm font-medium text-gray-700">Certificaciones</label>
                                                            <textarea name="certificaciones" rows="2"
                                                                class="w-full rounded-xl border border-gray-300 px-4 py-2 text-gray-800"
                                                                placeholder="Certificaciones adicionales"></textarea>
                                                        </div>

                                                        <div class="flex flex-col space-y-2 !mb-3">
                                                            <label
                                                                class="text-sm font-medium text-gray-700">Habilidades</label>
                                                            <textarea name="habilidades" rows="2"
                                                                class="w-full rounded-xl border border-gray-300 px-4 py-2 text-gray-800"
                                                                placeholder="Tus principales habilidades"></textarea>
                                                        </div>

                                                        <div class="row form-row rg-15">
                                                            <div class="col-md-6">
                                                                <div class="flex flex-col space-y-2 !mb-3">
                                                                    <label
                                                                        class="text-sm font-medium text-gray-700">Actividad
                                                                        Económica</label>
                                                                    <input type="text" name="actividad"
                                                                        class="w-full rounded-xl border border-gray-300 px-4 py-2 text-gray-800"
                                                                        placeholder="A qué te dedicas">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="flex flex-col space-y-2 !mb-3">
                                                                    <label
                                                                        class="text-sm font-medium text-gray-700">Ciudad
                                                                        de Operaciones</label>
                                                                    <input type="text" name="ciudad_operaciones"
                                                                        class="w-full rounded-xl border border-gray-300 px-4 py-2 text-gray-800"
                                                                        placeholder="Ciudad principal">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="flex flex-col space-y-2 !mb-3">
                                                            <label class="text-sm font-medium text-gray-700">Plazas de
                                                                Trabajo Generadas</label>
                                                            <input type="number" name="plazas_trabajo_generadas"
                                                                class="w-full rounded-xl border border-gray-300 px-4 py-2 text-gray-800"
                                                                placeholder="0">
                                                        </div>
                                                    </div>

                                                    <!-- JURIDICA -->
                                                    <div id="fields_juridica" style="display: none;">
                                                        <h4
                                                            class="text-lg font-bold text-gray-800 mb-4 mt-6 border-b pb-2">
                                                            Información de la Empresa</h4>

                                                        <div class="row form-row rg-15">
                                                            <div class="col-md-6">
                                                                <div class="flex flex-col space-y-2 !mb-3">
                                                                    <label
                                                                        class="text-sm font-medium text-gray-700">Nombre
                                                                        Comercial</label>
                                                                    <input type="text" name="empresa"
                                                                        class="w-full rounded-xl border border-gray-300 px-4 py-2 text-gray-800"
                                                                        placeholder="Nombre comercial">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="flex flex-col space-y-2 !mb-3">
                                                                    <label
                                                                        class="text-sm font-medium text-gray-700">Razón
                                                                        Social</label>
                                                                    <input type="text" name="nombre_juridico"
                                                                        class="w-full rounded-xl border border-gray-300 px-4 py-2 text-gray-800"
                                                                        placeholder="Razón social completa">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row form-row rg-15">
                                                            <div class="col-md-6">
                                                                <div class="flex flex-col space-y-2 !mb-3">
                                                                    <label
                                                                        class="text-sm font-medium text-gray-700">Representante
                                                                        Legal</label>
                                                                    <input type="text" name="representante_legal"
                                                                        class="w-full rounded-xl border border-gray-300 px-4 py-2 text-gray-800"
                                                                        placeholder="Nombre del representante">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="flex flex-col space-y-2 !mb-3">
                                                                    <label
                                                                        class="text-sm font-medium text-gray-700">Cargo
                                                                        de quien registra</label>
                                                                    <input type="text" name="cargo"
                                                                        class="w-full rounded-xl border border-gray-300 px-4 py-2 text-gray-800"
                                                                        placeholder="Tu cargo en la empresa">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row form-row rg-15">
                                                            <div class="col-md-6">
                                                                <div class="flex flex-col space-y-2 !mb-3">
                                                                    <label
                                                                        class="text-sm font-medium text-gray-700">Actividad
                                                                        Económica</label>
                                                                    <input type="text" name="actividad"
                                                                        class="w-full rounded-xl border border-gray-300 px-4 py-2 text-gray-800"
                                                                        placeholder="Actividad principal">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="flex flex-col space-y-2 !mb-3">
                                                                    <label
                                                                        class="text-sm font-medium text-gray-700">Fecha
                                                                        Inicio Actividades</label>
                                                                    <input type="date" name="inicio_actividades"
                                                                        class="w-full rounded-xl border border-gray-300 px-4 py-2 text-gray-800">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row form-row rg-15">
                                                            <div class="col-md-6">
                                                                <div class="flex flex-col space-y-2 !mb-3">
                                                                    <label
                                                                        class="text-sm font-medium text-gray-700">Plazas
                                                                        de Trabajo</label>
                                                                    <input type="number" name="plazas_trabajo_generadas"
                                                                        class="w-full rounded-xl border border-gray-300 px-4 py-2 text-gray-800"
                                                                        placeholder="0">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="flex flex-col space-y-2 !mb-3 pt-6">
                                                                    <label class="inline-flex items-center">
                                                                        <input type="checkbox" name="directiva"
                                                                            class="form-checkbox h-5 w-5 text-[var(--tj-color-theme-primary)]">
                                                                        <span class="ml-2 text-gray-700">¿Es parte de la
                                                                            directiva?</span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- ORGANIZACION -->
                                                    <div id="fields_organizacion" style="display: none;">
                                                        <h4
                                                            class="text-lg font-bold text-gray-800 mb-4 mt-6 border-b pb-2">
                                                            Información de la Organización</h4>

                                                        <div class="row form-row rg-15">
                                                            <div class="col-md-6">
                                                                <div class="flex flex-col space-y-2 !mb-3">
                                                                    <label
                                                                        class="text-sm font-medium text-gray-700">Nombre
                                                                        de la Organización</label>
                                                                    <input type="text" name="empresa"
                                                                        class="w-full rounded-xl border border-gray-300 px-4 py-2 text-gray-800"
                                                                        placeholder="Nombre o Siglas">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="flex flex-col space-y-2 !mb-3">
                                                                    <label
                                                                        class="text-sm font-medium text-gray-700">Razón
                                                                        Social</label>
                                                                    <input type="text" name="nombre_juridico"
                                                                        class="w-full rounded-xl border border-gray-300 px-4 py-2 text-gray-800"
                                                                        placeholder="Razón social completa">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row form-row rg-15">
                                                            <div class="col-md-6">
                                                                <div class="flex flex-col space-y-2 !mb-3">
                                                                    <label
                                                                        class="text-sm font-medium text-gray-700">Sector</label>
                                                                    <select name="sector"
                                                                        class="w-full rounded-xl border border-gray-300 px-4 py-2 text-gray-800 bg-white">
                                                                        <option value="">Seleccione...</option>
                                                                        <option value="Academia">Academia</option>
                                                                        <option value="Público">Público</option>
                                                                        <option value="Privado">Privado</option>
                                                                        <option value="ONG">ONG</option>
                                                                        <option value="Mixto">Mixto</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="flex flex-col space-y-2 !mb-3">
                                                                    <label
                                                                        class="text-sm font-medium text-gray-700">Fecha
                                                                        de Constitución</label>
                                                                    <input type="date" name="inicio_actividades"
                                                                        class="w-full rounded-xl border border-gray-300 px-4 py-2 text-gray-800">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row form-row rg-15">
                                                            <div class="col-md-6">
                                                                <div class="flex flex-col space-y-2 !mb-3">
                                                                    <label
                                                                        class="text-sm font-medium text-gray-700">Representante
                                                                        Legal</label>
                                                                    <input type="text" name="representante_legal"
                                                                        class="w-full rounded-xl border border-gray-300 px-4 py-2 text-gray-800"
                                                                        placeholder="Nombre del representante">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="flex flex-col space-y-2 !mb-3">
                                                                    <label
                                                                        class="text-sm font-medium text-gray-700">Director
                                                                        Ejecutivo</label>
                                                                    <input type="text" name="director_ejecutivo"
                                                                        class="w-full rounded-xl border border-gray-300 px-4 py-2 text-gray-800"
                                                                        placeholder="Nombre del director">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row form-row rg-15">
                                                            <div class="col-md-6">
                                                                <div class="flex flex-col space-y-2 !mb-3">
                                                                    <label
                                                                        class="text-sm font-medium text-gray-700">Cargo
                                                                        de quien registra</label>
                                                                    <input type="text" name="cargo"
                                                                        class="w-full rounded-xl border border-gray-300 px-4 py-2 text-gray-800"
                                                                        placeholder="Tu cargo">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="flex flex-col space-y-2 !mb-3">
                                                                    <label
                                                                        class="text-sm font-medium text-gray-700">Número
                                                                        de Miembros</label>
                                                                    <input type="number" name="numero_miembros"
                                                                        class="w-full rounded-xl border border-gray-300 px-4 py-2 text-gray-800"
                                                                        placeholder="0">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Contraseña -->
                                                    <div class="row form-row rg-15">
                                                        <div class="col-md-6">
                                                            <div class="flex flex-col space-y-2 !mb-3">
                                                                <label for="password"
                                                                    class="text-sm font-medium text-gray-700">Contraseña
                                                                    <span class="text-red-500">*</span></label>
                                                                <div class="relative">
                                                                    <input type="password" name="password" id="password"
                                                                        required
                                                                        class="w-full !bg-white rounded-xl border border-gray-300 px-4 py-2 pr-10 text-gray-800 placeholder-gray-400"
                                                                        placeholder="••••••••" />
                                                                    <button type="button"
                                                                        class="absolute inset-y-0 right-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none"
                                                                        aria-label="Mostrar contraseña"
                                                                        aria-describedby="password"
                                                                        onclick="togglePasswordVisibility(this)">
                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                            fill="none" viewBox="0 0 24 24"
                                                                            stroke-width="1.5" stroke="currentColor"
                                                                            class="w-5 h-5">
                                                                            <path stroke-linecap="round"
                                                                                stroke-linejoin="round"
                                                                                d="M2.036 12.322a1.012 1.012 0 010-.644C3.423 7.51 7.3 4.5 12 4.5c4.7 0 8.577 3.01 9.964 7.178.07.204.07.44 0 .644C20.577 16.49 16.7 19.5 12 19.5c-4.7 0-8.577-3.01-9.964-7.178z" />
                                                                            <path stroke-linecap="round"
                                                                                stroke-linejoin="round"
                                                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                                        </svg>
                                                                    </button>
                                                                </div>
                                                                <small class="text-gray-600 text-xs">Mínimo 6
                                                                    caracteres</small>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="flex flex-col space-y-2 !mb-3">
                                                                <label for="password_confirm"
                                                                    class="text-sm font-medium text-gray-700">Repetir
                                                                    contraseña <span
                                                                        class="text-red-500">*</span></label>
                                                                <div class="relative">
                                                                    <input type="password" name="password_confirm"
                                                                        id="password_confirm" required
                                                                        class="w-full !bg-white rounded-xl border border-gray-300 px-4 py-2 pr-10 text-gray-800 placeholder-gray-400"
                                                                        placeholder="••••••••" />
                                                                    <button type="button"
                                                                        class="absolute inset-y-0 right-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none"
                                                                        aria-label="Mostrar contraseña"
                                                                        aria-describedby="password_confirm"
                                                                        onclick="togglePasswordVisibility(this)">
                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                            fill="none" viewBox="0 0 24 24"
                                                                            stroke-width="1.5" stroke="currentColor"
                                                                            class="w-5 h-5">
                                                                            <path stroke-linecap="round"
                                                                                stroke-linejoin="round"
                                                                                d="M2.036 12.322a1.012 1.012 0 010-.644C3.423 7.51 7.3 4.5 12 4.5c4.7 0 8.577 3.01 9.964 7.178.07.204.07.44 0 .644C20.577 16.49 16.7 19.5 12 19.5c-4.7 0-8.577-3.01-9.964-7.178z" />
                                                                            <path stroke-linecap="round"
                                                                                stroke-linejoin="round"
                                                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                                        </svg>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row form-row rg-15">
                                                        <div class="col-sm-12">
                                                            <button type="submit" id="submitBtn"
                                                                class="!bg-[var(--tj-color-theme-primary)] w-full px-4 py-2 !rounded-xl text-white">
                                                                <span class="btn-text"><span>Registrarse</span></span>
                                                            </button>
                                                        </div>
                                                        <div class="col-sm-12 text-center mt-3">
                                                            <p class="text-sm text-gray-600">¿Ya tienes una cuenta? <a
                                                                    href="login.php"
                                                                    class="text-[var(--tj-color-theme-primary)] hover:underline">Inicia
                                                                    sesión aquí</a></p>
                                                        </div>
                                                    </div>

                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- end: Register Section -->
        </main>

        <!-- start: Footer Section -->
        <?php include 'components/footer.php'; ?>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                setTimeout(function () {
                    if (typeof jQuery !== 'undefined') {
                        (function ($) {
                            console.log('Initializing registration form logic');

                            // 1. Destruir NiceSelect para usar controles nativos
                            if ($.fn.niceSelect) {
                                $('#tipo_perfil, #subtipo_socio, #fields_natural select, #fields_organizacion select').niceSelect('destroy');
                            }

                            // 2. Referencias
                            const $tipoPerfil = $('#tipo_perfil');
                            const $containerSubtipo = $('#container_subtipo_socio');
                            const $subtipoSocio = $('#subtipo_socio');

                            const $fieldsNatural = $('#fields_natural');
                            const $fieldsJuridica = $('#fields_juridica');
                            const $fieldsOrganizacion = $('#fields_organizacion');

                            // 3. Función para ocultar todo y deshabilitar inputs (para no enviarlos)
                            function hideAllSpecificFields() {
                                $fieldsNatural.hide().find('input, select, textarea').prop('disabled', true);
                                $fieldsJuridica.hide().find('input, select, textarea').prop('disabled', true);
                                $fieldsOrganizacion.hide().find('input, select, textarea').prop('disabled', true);
                            }

                            // 4. Función de visibilidad principal
                            function updateVisibility() {
                                const perfil = $tipoPerfil.val();
                                const subtipo = $subtipoSocio.val();

                                hideAllSpecificFields();

                                if (perfil === 'organizacion') {
                                    $containerSubtipo.hide();
                                    $subtipoSocio.prop('required', false);

                                    $fieldsOrganizacion.show().find('input, select, textarea').prop('disabled', false);

                                } else if (perfil === 'socio') {
                                    $containerSubtipo.show();
                                    $subtipoSocio.prop('required', true);

                                    if (subtipo === 'natural') {
                                        $fieldsNatural.show().find('input, select, textarea').prop('disabled', false);
                                    } else if (subtipo === 'juridica') {
                                        $fieldsJuridica.show().find('input, select, textarea').prop('disabled', false);
                                    }
                                } else {
                                    $containerSubtipo.hide();
                                    $subtipoSocio.prop('required', false);
                                }
                            }

                            // 5. Bind events
                            $tipoPerfil.on('change', function () {
                                if ($(this).val() !== 'socio') {
                                    $subtipoSocio.val('');
                                }
                                updateVisibility();
                            });

                            $subtipoSocio.on('change', updateVisibility);

                            // 6. Run initial check
                            updateVisibility();

                            // 7. Handle Form Submission
                            $('#registerForm').on('submit', function (e) {
                                e.preventDefault();

                                // Validar contraseñas
                                const pass = $('#password').val();
                                const confirm = $('#password_confirm').val();
                                if (pass !== confirm) {
                                    alert('Las contraseñas no coinciden');
                                    return;
                                }

                                // Determinar tipo_socio final para la API
                                let tipoSocioFinal = '';
                                const perfil = $tipoPerfil.val();
                                if (perfil === 'organizacion') {
                                    tipoSocioFinal = 'organizacion';
                                } else if (perfil === 'socio') {
                                    tipoSocioFinal = $subtipoSocio.val();
                                }

                                if (!tipoSocioFinal) {
                                    alert('Por favor selecciona el tipo de perfil');
                                    return;
                                }

                                // Recopilar datos
                                const formData = new FormData(this);
                                formData.append('tipo_socio', tipoSocioFinal); // Sobrescribir/Añadir tipo_socio correcto

                                // Convertir a objeto para JSON (opcional, pero FormData funciona bien con fetch si no se pone Content-Type)
                                // Pero nuestra API espera JSON o POST normal. Vamos a enviar como JSON para ser más limpios.
                                const data = {};
                                formData.forEach((value, key) => {
                                    data[key] = value;
                                });

                                // Enviar a la API
                                const $btn = $('#submitBtn');
                                const $btnText = $btn.find('.btn-text span');
                                const originalText = $btnText.text();

                                $btn.prop('disabled', true);
                                $btnText.text('Procesando...');

                                fetch('admin/api/auth/register.php', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json'
                                    },
                                    body: JSON.stringify(data)
                                })
                                    .then(response => response.json())
                                    .then(result => {
                                        if (result.success) {
                                            // Mostrar éxito
                                            $('#form-alerts').html(`
                                            <div class="alert alert-success mb-4 p-3 md:p-4 rounded-lg !bg-green-100 border !border-green-200 flex items-start gap-3">
                                                <div class="flex-1">
                                                    <p class="text-sm font-medium mb-0 text-green-800">${result.message}</p>
                                                </div>
                                            </div>
                                        `);
                                            // Limpiar formulario y scroll arriba
                                            $('#registerForm')[0].reset();
                                            hideAllSpecificFields();
                                            window.scrollTo({ top: 0, behavior: 'smooth' });
                                        } else {
                                            // Mostrar error
                                            $('#form-alerts').html(`
                                            <div class="alert alert-error mb-4 p-3 md:p-4 rounded-lg !bg-red-100 border !border-red-200 flex items-start gap-3">
                                                <div class="flex-1">
                                                    <p class="text-sm font-medium mb-0 text-red-800">${result.message}</p>
                                                </div>
                                            </div>
                                        `);
                                            window.scrollTo({ top: 0, behavior: 'smooth' });
                                        }
                                    })
                                    .catch(error => {
                                        console.error('Error:', error);
                                        $('#form-alerts').html(`
                                        <div class="alert alert-error mb-4 p-3 md:p-4 rounded-lg !bg-red-100 border !border-red-200 flex items-start gap-3">
                                            <div class="flex-1">
                                                <p class="text-sm font-medium mb-0 text-red-800">Error de conexión. Intente nuevamente.</p>
                                            </div>
                                        </div>
                                    `);
                                    })
                                    .finally(() => {
                                        $btn.prop('disabled', false);
                                        $btnText.text(originalText);
                                    });
                            });

                        })(jQuery);
                    }
                }, 100);
            });
        </script>