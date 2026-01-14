<?php include 'components/header.php'; ?>

<!-- end: Header Area -->

<div id="smooth-wrapper">
    <div id="smooth-content">
        <main id="primary" class="site-main">
            <div class="space-for-header"></div>
            <!-- start: Password Section -->
            <section class="full-width tj-page__area section-gap">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <div class="tj-page__container">
                                <div class="tj-entry__content">
                                    <div class="woocommerce max-w-lg mx-auto">
                                        <div class="woo-lost-password">
                                            <h3>¿Olvidaste tu contraseña?</h3>
                                            <p>Ingresa tu usuario o correo electrónico. Recibirás un código de verificación para crear una nueva contraseña.</p>

                                            <!-- Alertas -->
                                            <div id="alert-container" class="mb-4"></div>

                                            <form id="form-recuperar" class="woocommerce-ResetPassword lost_reset_password">
                                                <div class="flex flex-col space-y-2 !mb-5">
                                                    <label for="user_login" class="text-sm font-medium text-gray-700 !mb-3">
                                                        Usuario o Email <span class="text-red-500">*</span>
                                                    </label>

                                                    <input type="text" name="user_login" id="user_login"
                                                        autocomplete="username" required aria-required="true"
                                                        class="w-full rounded-xl border border-gray-300 px-4 py-2 text-gray-800 placeholder-gray-400"
                                                        placeholder="ejemplo@correo.com o usuario" />
                                                </div>

                                                <div class="clear"></div>

                                                <div class="flex flex-col space-y-3">
                                                    <button type="submit" id="btn-submit"
                                                        class="!bg-[var(--tj-color-theme-primary)] w-full px-4 py-3 !rounded-xl text-white font-medium hover:opacity-90 transition-opacity">
                                                        <span class="btn-text"><span>Recuperar Contraseña</span></span>
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
                document.getElementById('form-recuperar').addEventListener('submit', async (e) => {
                    e.preventDefault();
                    
                    const alertContainer = document.getElementById('alert-container');
                    const btnSubmit = document.getElementById('btn-submit');
                    const userLogin = document.getElementById('user_login').value.trim();
                    
                    if (!userLogin) {
                        alertContainer.innerHTML = `
                            <div class="alert alert-danger">
                                Por favor ingresa tu usuario o email
                            </div>
                        `;
                        return;
                    }
                    
                    btnSubmit.disabled = true;
                    btnSubmit.innerHTML = '<span class="btn-text"><span>Enviando...</span></span>';
                    
                    try {
                        const formData = new FormData();
                        formData.append('user_login', userLogin);
                        
                        const response = await fetch('admin/api/recuperar-password.php', {
                            method: 'POST',
                            body: formData
                        });
                        
                        const data = await response.json();
                        
                        // Siempre mostrar el mismo mensaje por seguridad
                        alertContainer.innerHTML = `
                            <div class="alert alert-info">
                                Si el usuario existe, recibirás un correo con instrucciones para recuperar tu contraseña. Redirigiendo...
                            </div>
                        `;
                        document.getElementById('user_login').value = '';
                        
                        // Redirigir a reset-password después de 2 segundos
                        setTimeout(() => {
                            window.location.href = 'reset-password.php';
                        }, 2000);
                        
                    } catch (error) {
                        alertContainer.innerHTML = `
                            <div class="alert alert-info">
                                Si el usuario existe, recibirás un correo con instrucciones para recuperar tu contraseña. Redirigiendo...
                            </div>
                        `;
                        document.getElementById('user_login').value = '';
                        
                        // Redirigir incluso si hay error
                        setTimeout(() => {
                            window.location.href = 'reset-password.php';
                        }, 2000);
                    }
                });
            </script>
            <!-- end: Password Section -->
            <?php include 'components/footer.php'; ?>