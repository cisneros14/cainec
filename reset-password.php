<?php 
$code = $_GET['code'] ?? '';
$email = $_GET['email'] ?? '';
?>
<?php include 'components/header.php'; ?>

<!-- end: Header Area -->

<div id="smooth-wrapper">
    <div id="smooth-content">
        <main id="primary" class="site-main">
            <div class="space-for-header"></div>
            <!-- start: Reset Password Section -->
            <section class="full-width tj-page__area section-gap">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <div class="tj-page__container">
                                <div class="tj-entry__content">
                                    <div class="woocommerce max-w-lg mx-auto">
                                        <div class="woo-lost-password">
                                            <h3>Restablecer Contraseña</h3>
                                            <p>Ingresa tu nueva contraseña. Debe tener al menos 6 caracteres.</p>

                                            <!-- Alertas -->
                                            <div id="alert-container" class="mb-4"></div>

                                            <form id="form-reset" class="woocommerce-ResetPassword lost_reset_password">
                                                
                                                <div class="flex flex-col space-y-2 !mb-3">
                                                    <label for="email" class="text-sm font-medium text-gray-700 !mb-3">
                                                        Email <span class="text-red-500">*</span>
                                                    </label>

                                                    <input type="email" id="email" name="email" 
                                                        value="<?php echo htmlspecialchars($email); ?>" required
                                                        class="w-full rounded-xl border border-gray-300 px-4 py-2 text-gray-800 placeholder-gray-400"
                                                        placeholder="ejemplo@correo.com" />
                                                </div>
                                                
                                                <div class="flex flex-col space-y-2 !mb-3">
                                                    <label for="code" class="text-sm font-medium text-gray-700 !mb-3">
                                                        Código de Verificación (6 dígitos) <span class="text-red-500">*</span>
                                                    </label>

                                                    <input type="text" id="code" name="code" 
                                                        value="<?php echo htmlspecialchars($code); ?>" 
                                                        required maxlength="6" pattern="[0-9]{6}"
                                                        class="w-full rounded-xl border border-gray-300 px-4 py-2 text-gray-800 placeholder-gray-400 tracking-widest text-center text-2xl font-bold"
                                                        placeholder="000000" />
                                                </div>

                                                <div class="flex flex-col space-y-2 !mb-3">
                                                    <label for="new_password" class="text-sm font-medium text-gray-700 !mb-3">
                                                        Nueva Contraseña <span class="text-red-500">*</span>
                                                    </label>

                                                    <div class="relative">
                                                        <input type="password" name="new_password" id="new_password"
                                                            autocomplete="new-password" required minlength="6"
                                                            class="w-full !bg-white rounded-xl border border-gray-300 px-4 py-2 pr-10 text-gray-800 placeholder-gray-400"
                                                            placeholder="••••••••" />
                                                        <button type="button"
                                                            class="absolute inset-y-0 right-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none"
                                                            aria-label="Mostrar contraseña" 
                                                            onclick="togglePasswordVisibility(this)">
                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                viewBox="0 0 24 24" stroke-width="1.5"
                                                                stroke="currentColor" class="w-5 h-5">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    d="M2.036 12.322a1.012 1.012 0 010-.644C3.423 7.51 7.3 4.5 12 4.5c4.7 0 8.577 3.01 9.964 7.178.07.204.07.44 0 .644C20.577 16.49 16.7 19.5 12 19.5c-4.7 0-8.577-3.01-9.964-7.178z" />
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </div>

                                                <div class="flex flex-col space-y-2 !mb-5">
                                                    <label for="confirm_password" class="text-sm font-medium text-gray-700 !mb-3">
                                                        Confirmar Contraseña <span class="text-red-500">*</span>
                                                    </label>

                                                    <div class="relative">
                                                        <input type="password" name="confirm_password" id="confirm_password"
                                                            autocomplete="new-password" required minlength="6"
                                                            class="w-full !bg-white rounded-xl border border-gray-300 px-4 py-2 pr-10 text-gray-800 placeholder-gray-400"
                                                            placeholder="••••••••" />
                                                        <button type="button"
                                                            class="absolute inset-y-0 right-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none"
                                                            aria-label="Mostrar contraseña" 
                                                            onclick="togglePasswordVisibility(this)">
                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                viewBox="0 0 24 24" stroke-width="1.5"
                                                                stroke="currentColor" class="w-5 h-5">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    d="M2.036 12.322a1.012 1.012 0 010-.644C3.423 7.51 7.3 4.5 12 4.5c4.7 0 8.577 3.01 9.964 7.178.07.204.07.44 0 .644C20.577 16.49 16.7 19.5 12 19.5c-4.7 0-8.577-3.01-9.964-7.178z" />
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </div>

                                                <div class="clear"></div>

                                                <div class="flex flex-col space-y-3">
                                                    <button type="submit" id="btn-submit"
                                                        class="!bg-[var(--tj-color-theme-primary)] w-full px-4 py-3 !rounded-xl text-white font-medium hover:opacity-90 transition-opacity">
                                                        <span class="btn-text"><span>Restablecer Contraseña</span></span>
                                                    </button>

                                                    <p class="text-center">
                                                        <a href="login.php" class="text-sm" style="color: #0e2c5b;">← Volver al inicio de sesión</a>
                                                    </p>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <script>
                // Función para mostrar/ocultar contraseña
                function togglePasswordVisibility(button) {
                    const input = button.closest('.relative').querySelector('input');
                    const svg = button.querySelector('svg');
                    
                    if (input.type === 'password') {
                        input.type = 'text';
                        svg.innerHTML = `
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                        `;
                    } else {
                        input.type = 'password';
                        svg.innerHTML = `
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.644C3.423 7.51 7.3 4.5 12 4.5c4.7 0 8.577 3.01 9.964 7.178.07.204.07.44 0 .644C20.577 16.49 16.7 19.5 12 19.5c-4.7 0-8.577-3.01-9.964-7.178z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        `;
                    }
                }

                document.getElementById('form-reset').addEventListener('submit', async (e) => {
                    e.preventDefault();
                    
                    const alertContainer = document.getElementById('alert-container');
                    const btnSubmit = document.getElementById('btn-submit');
                    const code = document.getElementById('code').value;
                    const email = document.getElementById('email').value;
                    const newPassword = document.getElementById('new_password').value;
                    const confirmPassword = document.getElementById('confirm_password').value;
                    
                    // Validaciones
                    if (!code || !email) {
                        alertContainer.innerHTML = `
                            <div class="alert alert-danger">
                                Código o email inválido. Por favor solicita un nuevo código de recuperación.
                            </div>
                        `;
                        return;
                    }
                    
                    if (newPassword.length < 6) {
                        alertContainer.innerHTML = `
                            <div class="alert alert-danger">
                                La contraseña debe tener al menos 6 caracteres
                            </div>
                        `;
                        return;
                    }
                    
                    if (newPassword !== confirmPassword) {
                        alertContainer.innerHTML = `
                            <div class="alert alert-danger">
                                Las contraseñas no coinciden
                            </div>
                        `;
                        return;
                    }
                    
                    btnSubmit.disabled = true;
                    btnSubmit.innerHTML = '<span class="btn-text"><span>Restableciendo...</span></span>';
                    
                    try {
                        const formData = new FormData();
                        formData.append('code', code);
                        formData.append('email', email);
                        formData.append('new_password', newPassword);
                        formData.append('confirm_password', confirmPassword);
                        
                        const response = await fetch('admin/api/restablecer-password.php', {
                            method: 'POST',
                            body: formData
                        });
                        
                        const data = await response.json();
                        
                        if (data.success) {
                            alertContainer.innerHTML = `
                                <div class="alert alert-success">
                                    ${data.message}
                                </div>
                            `;
                            
                            // Redirigir al login después de 2 segundos
                            setTimeout(() => {
                                window.location.href = 'login.php?success=' + encodeURIComponent('Contraseña actualizada exitosamente');
                            }, 2000);
                        } else {
                            alertContainer.innerHTML = `
                                <div class="alert alert-danger">
                                    ${data.message}
                                </div>
                            `;
                            btnSubmit.disabled = false;
                            btnSubmit.innerHTML = '<span class="btn-text"><span>Restablecer Contraseña</span></span>';
                        }
                    } catch (error) {
                        alertContainer.innerHTML = `
                            <div class="alert alert-danger">
                                Error al procesar la solicitud. Por favor, intenta más tarde.
                            </div>
                        `;
                        btnSubmit.disabled = false;
                        btnSubmit.innerHTML = '<span class="btn-text"><span>Restablecer Contraseña</span></span>';
                    }
                });
            </script>
            <!-- end: Reset Password Section -->
            <?php include 'components/footer.php'; ?>
