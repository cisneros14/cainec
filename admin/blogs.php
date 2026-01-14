<?php
// Proteger la página - solo administradores (rol = 1)
require_once __DIR__ . '/auth_middleware.php';
verificarRol([1]); // Solo administradores

include __DIR__ . '/components/aside.php';
?>

<!-- Quill CSS -->
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

<main class="min-h-screen p-0 md:p-6">
    <div class="w-full mx-auto">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Entradas</h1>
                <p class="text-gray-500 text-sm mt-1">Administra las entradas</p>
            </div>
            <button onclick="openCreateModal()"
                class="bg-[var(--tj-color-theme-primary)] hover:opacity-90 transition text-white px-5 py-2.5 rounded-lg font-medium flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Nueva entrada
            </button>
        </div>

        <!-- Filtros -->
        <div class="bg-white rounded-xl shadow-sm p-4 mb-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                <div>
                    <input type="text" id="filtro-titulo" placeholder="Buscar por título..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" />
                </div>
                <div>
                    <select id="filtro-categoria"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                        <option value="">Todas las categorías</option>
                    </select>
                </div>
                <div>
                    <select id="filtro-tipo"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                        <option value="">Todos los tipos</option>
                        <option value="blog">Blog</option>
                        <option value="noticia">Noticia</option>
                    </select>
                </div>
                <div>
                    <input type="text" id="filtro-autor" placeholder="Buscar por autor..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" />
                </div>
                <div class="md:col-span-4">
                    <button onclick="limpiarFiltros()"
                        class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium transition flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Limpiar filtros
                    </button>
                </div>
            </div>
        </div>

        <!-- Tabla de blogs -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-16">
                                ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Título</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Categoría</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tipo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Autor</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Publicado</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="blogs-tbody" class="bg-white divide-y divide-gray-200">
                        <!-- Las filas se cargarán dinámicamente -->
                    </tbody>
                </table>
            </div>

            <!-- Estado vacío -->
            <div id="empty-state" class="hidden text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No hay entradas</h3>
                <p class="mt-1 text-sm text-gray-500">Comienza creando una nueva entrada de blog.</p>
            </div>
        </div>
    </div>
</main>

<!-- MODAL CREAR/EDITAR -->
<dialog id="modalForm" class="backdrop:bg-black/60 backdrop:backdrop-blur-sm p-0 rounded-2xl w-full max-w-4xl
           fixed inset-0 m-auto shadow-xl border-0 max-h-[90vh]">
    <form id="formBlog" class="p-6 space-y-4 overflow-y-auto max-h-[90vh]">
        <input type="hidden" id="blog_id" name="id">
        <input type="hidden" id="portada_actual" name="portada_actual">

        <h2 id="modal-title" class="text-xl font-semibold text-gray-900 sticky top-0 bg-white pb-3 border-b">Nueva
            entrada de blog</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Título -->
            <div class="md:col-span-2">
                <label for="titulo" class="block text-sm font-medium text-gray-700 mb-2">
                    Título <span class="text-red-500">*</span>
                </label>
                <input type="text" id="titulo" name="titulo" required
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                    placeholder="Título de la entrada" />
            </div>

            <!-- Slug -->
            <div>
                <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">
                    Slug (URL amigable) <span class="text-red-500">*</span>
                </label>
                <input type="text" id="slug" name="slug" required
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                    placeholder="titulo-de-la-entrada" />
            </div>

            <!-- Categoría -->
            <div>
                <label for="id_categoria" class="block text-sm font-medium text-gray-700 mb-2">
                    Categoría <span class="text-red-500">*</span>
                </label>
                <select id="id_categoria" name="id_categoria" required
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                    <option value="">Seleccionar categoría</option>
                </select>
            </div>

            <!-- Tipo -->
            <div>
                <label for="tipo" class="block text-sm font-medium text-gray-700 mb-2">
                    Tipo <span class="text-red-500">*</span>
                </label>
                <select id="tipo" name="tipo" required
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                    <option value="blog">Blog</option>
                    <option value="noticia">Noticia</option>
                </select>
            </div>

            <!-- Autor -->
            <div>
                <label for="autor" class="block text-sm font-medium text-gray-700 mb-2">
                    Autor
                </label>
                <input type="text" id="autor" name="autor"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                    placeholder="Nombre del autor" />
            </div>

            <!-- Fecha de publicación -->
            <div>
                <label for="published_at" class="block text-sm font-medium text-gray-700 mb-2">
                    Fecha de publicación
                </label>
                <input type="datetime-local" id="published_at" name="published_at"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" />
            </div>

            <!-- Imagen de portada -->
            <div class="md:col-span-2">
                <label for="portada" class="block text-sm font-medium text-gray-700 mb-2">
                    Imagen de portada
                </label>
                <input type="file" id="portada" name="portada" accept="image/jpeg,image/png,image/jpg,image/webp"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" />
                <p class="text-xs text-gray-500 mt-1">Formatos: JPG, PNG, WEBP. Tamaño máximo: 5MB</p>
                <div id="preview-portada" class="mt-3 hidden">
                    <img id="preview-img" src="" alt="Vista previa" class="max-h-40 rounded-lg border">
                </div>
            </div>

            <!-- Meta descripción -->
            <div class="md:col-span-2">
                <label for="meta_descripcion" class="block text-sm font-medium text-gray-700 mb-2">
                    Meta descripción (SEO)
                </label>
                <textarea id="meta_descripcion" name="meta_descripcion" rows="2" maxlength="300"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                    placeholder="Descripción breve para SEO (máx. 300 caracteres)"></textarea>
            </div>

            <!-- Meta keywords -->
            <div>
                <label for="meta_keywords" class="block text-sm font-medium text-gray-700 mb-2">
                    Meta keywords (SEO)
                </label>
                <input type="text" id="meta_keywords" name="meta_keywords"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                    placeholder="palabra1, palabra2, palabra3" />
            </div>

            <!-- Etiquetas -->
            <div>
                <label for="etiquetas" class="block text-sm font-medium text-gray-700 mb-2">
                    Etiquetas
                </label>
                <input type="text" id="etiquetas" name="etiquetas"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                    placeholder="tecnología, innovación, negocios" />
            </div>

            <!-- Contenido con Quill -->
            <div class="md:col-span-2">
                <div class="flex justify-between items-center mb-2">

                    <label for="contenido" class="block text-sm font-medium text-gray-700 mb-2">
                        Contenido <span class="text-red-500">*</span>
                    </label>
                    <button class="bg-[var(--tj-color-theme-primary)] text-white px-4 py-2 rounded-lg">Generar con IA</button>
                </div>
                <div id="editor" class="bg-white border border-gray-300 rounded-lg" style="min-height: 300px;"></div>
                <input type="hidden" id="contenido" name="contenido">
            </div>
        </div>

        <div class="flex justify-end gap-2 pt-4 border-t border-gray-100 sticky bottom-0 bg-white">
            <button type="button" onclick="document.getElementById('modalForm').close()"
                class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium transition">
                Cancelar
            </button>

            <button type="submit"
                class="px-4 py-2 bg-[var(--tj-color-theme-primary)] hover:opacity-90 text-white rounded-lg font-medium transition">
                Guardar
            </button>
        </div>
    </form>
</dialog>

<!-- MODAL ELIMINAR -->
<dialog id="modalDelete" class="backdrop:bg-black/60 backdrop:backdrop-blur-sm p-0 rounded-2xl w-full max-w-md
           fixed inset-0 m-auto shadow-xl border-0">
    <div class="p-6 space-y-4">
        <div class="flex items-center gap-3">
            <div class="flex-shrink-0 w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <div>
                <h2 class="text-xl font-semibold text-gray-900">Eliminar entrada</h2>
            </div>
        </div>

        <p class="text-gray-600 leading-relaxed">
            ¿Estás seguro de que deseas eliminar la entrada <strong id="delete-blog-titulo"></strong>?
            Esta acción no se puede deshacer.
        </p>

        <div class="flex justify-end gap-2 pt-4 border-t border-gray-100">
            <button type="button" onclick="document.getElementById('modalDelete').close()"
                class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium transition">
                Cancelar
            </button>

            <button type="button" onclick="confirmDelete()"
                class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition">
                Eliminar
            </button>
        </div>
    </div>
</dialog>

<!-- Toast notifications -->
<div id="toast"
    class="hidden fixed top-4 right-4 z-50 bg-white rounded-lg shadow-lg p-4 max-w-sm border-l-4 transition-all">
    <div class="flex items-center gap-3">
        <div id="toast-icon"></div>
        <div class="flex-1">
            <p id="toast-message" class="text-sm font-medium text-gray-900"></p>
        </div>
        <button onclick="hideToast()" class="text-gray-400 hover:text-gray-600">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
</div>

<!-- Quill JS -->
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

<script>
    // Estado global
    let deleteId = null;
    let quill = null;
    let todosBlogs = []; // Guardar todos los blogs para filtrado
    let autoSlugEnabled = true; // Controlar si el auto-slug está activo

    // Inicializar Quill
    function initQuill() {
        quill = new Quill('#editor', {
            theme: 'snow',
            modules: {
                toolbar: [
                    [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                    [{ 'color': [] }, { 'background': [] }],
                    [{ 'align': [] }],
                    ['link', 'image', 'video'],
                    ['clean']
                ]
            },
            placeholder: 'Escribe el contenido de la entrada...'
        });
    }

    // Cargar al iniciar
    document.addEventListener('DOMContentLoaded', function () {
        initQuill();
        loadCategorias();
        loadBlogs();

        // Filtros en tiempo real
        document.getElementById('filtro-titulo').addEventListener('input', aplicarFiltros);
        document.getElementById('filtro-categoria').addEventListener('change', aplicarFiltros);
        document.getElementById('filtro-tipo').addEventListener('change', aplicarFiltros);
        document.getElementById('filtro-autor').addEventListener('input', aplicarFiltros);

        // Auto-generar slug desde título (solo si está habilitado)
        const tituloInput = document.getElementById('titulo');
        const slugInput = document.getElementById('slug');

        tituloInput.addEventListener('input', function (e) {
            if (autoSlugEnabled) {
                const slug = e.target.value
                    .toLowerCase()
                    .normalize('NFD').replace(/[\u0300-\u036f]/g, '')
                    .replace(/[^a-z0-9]+/g, '-')
                    .replace(/^-+|-+$/g, '');
                slugInput.value = slug;
            }
        });

        // Si el usuario escribe manualmente en el slug, deshabilitar auto-generación
        slugInput.addEventListener('keydown', function () {
            autoSlugEnabled = false;
        });

        // Preview de imagen
        document.getElementById('portada').addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    document.getElementById('preview-img').src = e.target.result;
                    document.getElementById('preview-portada').classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }
        });
    });

    // Cargar categorías para los selects (modal y filtro)
    async function loadCategorias() {
        try {
            const response = await fetch('api/categorias/listar.php');
            const data = await response.json();

            if (data.success && data.data.length > 0) {
                // Select del modal
                const select = document.getElementById('id_categoria');
                select.innerHTML = '<option value="">Selecciona una categoría</option>';

                // Select del filtro
                const filtroSelect = document.getElementById('filtro-categoria');
                filtroSelect.innerHTML = '<option value="">Todas las categorías</option>';

                data.data.forEach(cat => {
                    // Para el modal
                    const option = document.createElement('option');
                    option.value = cat.id;
                    option.textContent = cat.nombre;
                    select.appendChild(option);

                    // Para el filtro
                    const optionFiltro = document.createElement('option');
                    optionFiltro.value = cat.id;
                    optionFiltro.textContent = cat.nombre;
                    filtroSelect.appendChild(optionFiltro);
                });
            }
        } catch (error) {
            console.error('Error al cargar categorías:', error);
        }
    }

    // Cargar blogs
    async function loadBlogs() {
        try {
            const response = await fetch('api/blogs/listar.php');
            const data = await response.json();

            if (data.success && data.data.length > 0) {
                todosBlogs = data.data;
                aplicarFiltros();
            } else {
                todosBlogs = [];
                mostrarEstadoVacio();
            }
        } catch (error) {
            showToast('Error al cargar las entradas', 'error');
            console.error('Error:', error);
        }
    }

    // Aplicar filtros
    function aplicarFiltros() {
        const filtroTitulo = document.getElementById('filtro-titulo').value.toLowerCase();
        const filtroCategoria = document.getElementById('filtro-categoria').value;
        const filtroTipo = document.getElementById('filtro-tipo').value;
        const filtroAutor = document.getElementById('filtro-autor').value.toLowerCase();

        const blogsFiltrados = todosBlogs.filter(blog => {
            const coincideTitulo = blog.titulo.toLowerCase().includes(filtroTitulo);
            const coincideCategoria = !filtroCategoria || blog.id_categoria == filtroCategoria;
            const coincideTipo = !filtroTipo || blog.tipo === filtroTipo;
            const coincideAutor = !filtroAutor || (blog.autor && blog.autor.toLowerCase().includes(filtroAutor));

            return coincideTitulo && coincideCategoria && coincideTipo && coincideAutor;
        });

        renderBlogs(blogsFiltrados);
    }

    // Renderizar blogs en la tabla
    function renderBlogs(blogs) {
        const tbody = document.getElementById('blogs-tbody');
        const emptyState = document.getElementById('empty-state');

        if (blogs.length > 0) {
            tbody.innerHTML = blogs.map(blog => `
            <tr class="hover:bg-gray-50 transition">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${blog.id}</td>
                <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                        ${blog.portada_url ? `<img src="../${blog.portada_url}" alt="" class="w-12 h-12 rounded object-cover">` : ''}
                        <div>
                            <div class="text-sm font-medium text-gray-900">${escapeHtml(blog.titulo)}</div>
                            <div class="text-xs text-gray-500">${blog.slug}</div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="text-sm text-gray-600">${escapeHtml(blog.categoria_nombre || '-')}</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${blog.tipo === 'noticia' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800'}">
                        ${blog.tipo === 'noticia' ? 'Noticia' : 'Blog'}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    ${blog.autor ? escapeHtml(blog.autor) : '-'}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    ${blog.published_at ? formatDate(blog.published_at) : 'Borrador'}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <button
                        onclick='openEditModal(${JSON.stringify(blog).replace(/'/g, "&#39;")})'
                        class="text-blue-600 hover:text-blue-900 mr-3"
                        title="Editar"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </button>
                    <button
                        onclick="openDeleteModal(${blog.id}, '${escapeHtml(blog.titulo)}')"
                        class="text-red-600 hover:text-red-900"
                        title="Eliminar"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </td>
            </tr>
        `).join('');
            emptyState.classList.add('hidden');
        } else {
            mostrarEstadoVacio();
        }
    }

    // Mostrar estado vacío
    function mostrarEstadoVacio() {
        const tbody = document.getElementById('blogs-tbody');
        const emptyState = document.getElementById('empty-state');
        tbody.innerHTML = '';
        emptyState.classList.remove('hidden');
    }

    // Limpiar filtros
    function limpiarFiltros() {
        document.getElementById('filtro-titulo').value = '';
        document.getElementById('filtro-categoria').value = '';
        document.getElementById('filtro-tipo').value = '';
        document.getElementById('filtro-autor').value = '';
        aplicarFiltros();
    }

    // Abrir modal para crear
    async function openCreateModal() {
        document.getElementById('blog_id').value = '';
        document.getElementById('portada_actual').value = '';
        document.getElementById('formBlog').reset();
        quill.setContents([]);
        document.getElementById('preview-portada').classList.add('hidden');
        document.getElementById('modal-title').textContent = 'Nueva entrada de blog';

        await loadCategorias();

        // Habilitar auto-slug para crear (después de reset)
        autoSlugEnabled = true;

        document.getElementById('modalForm').showModal();

        // Dar foco al campo título después de abrir el modal
        setTimeout(() => {
            document.getElementById('titulo').focus();
        }, 100);
    }

    // Abrir modal para editar
    async function openEditModal(blog) {
        await loadCategorias(); // Cargar categorías primero

        autoSlugEnabled = false; // Deshabilitar auto-slug al editar

        document.getElementById('blog_id').value = blog.id;
        document.getElementById('titulo').value = blog.titulo;
        document.getElementById('slug').value = blog.slug;
        document.getElementById('id_categoria').value = blog.id_categoria;
        document.getElementById('tipo').value = blog.tipo || 'blog';
        document.getElementById('autor').value = blog.autor || '';
        document.getElementById('meta_descripcion').value = blog.meta_descripcion || '';
        document.getElementById('meta_keywords').value = blog.meta_keywords || '';
        document.getElementById('etiquetas').value = blog.etiquetas || '';
        document.getElementById('portada_actual').value = blog.portada_url || '';

        if (blog.published_at) {
            const date = new Date(blog.published_at);
            const formatted = date.toISOString().slice(0, 16);
            document.getElementById('published_at').value = formatted;
        } else {
            document.getElementById('published_at').value = '';
        }

        if (blog.portada_url) {
            document.getElementById('preview-img').src = '../' + blog.portada_url;
            document.getElementById('preview-portada').classList.remove('hidden');
        } else {
            document.getElementById('preview-portada').classList.add('hidden');
        }

        quill.root.innerHTML = blog.contenido || '';

        document.getElementById('modal-title').textContent = 'Editar entrada de blog';
        document.getElementById('modalForm').showModal();
    }

    // Abrir modal para eliminar
    function openDeleteModal(id, titulo) {
        deleteId = id;
        document.getElementById('delete-blog-titulo').textContent = titulo;
        document.getElementById('modalDelete').showModal();
    }

    // Manejar submit del formulario
    document.getElementById('formBlog').addEventListener('submit', async (e) => {
        e.preventDefault();

        const id = document.getElementById('blog_id').value;
        const formData = new FormData();

        // Agregar contenido de Quill
        const contenido = quill.root.innerHTML;
        document.getElementById('contenido').value = contenido;

        // Construir FormData - IMPORTANTE: incluir todos los campos, incluso los vacíos para campos obligatorios
        const campos = ['titulo', 'slug', 'id_categoria', 'tipo', 'autor', 'meta_descripcion', 'meta_keywords', 'etiquetas', 'contenido', 'published_at', 'portada_actual'];

        campos.forEach(campo => {
            const elemento = document.getElementById(campo);
            const valor = elemento ? elemento.value : '';

            // Siempre agregar campos obligatorios, incluso si están vacíos
            if (campo === 'tipo' || campo === 'titulo' || campo === 'slug' || campo === 'id_categoria' || campo === 'contenido') {
                formData.append(campo, valor);
            } else if (valor) {
                // Para campos opcionales, solo agregar si tienen valor
                formData.append(campo, valor);
            }
        });

        if (id) formData.append('id', id);

        // Debug: Mostrar lo que se va a enviar
        console.log('Datos a enviar:');
        for (let pair of formData.entries()) {
            console.log(pair[0] + ': ' + pair[1]);
        }

        // Agregar archivo de portada si existe
        const portadaFile = document.getElementById('portada').files[0];
        if (portadaFile) {
            formData.append('portada', portadaFile);
        }

        const url = id ? 'api/blogs/editar.php' : 'api/blogs/crear.php';

        try {
            const response = await fetch(url, {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            if (result.success) {
                showToast(result.message || 'Operación exitosa', 'success');
                document.getElementById('modalForm').close();
                loadBlogs();
            } else {
                showToast(result.message || 'Error en la operación', 'error');
                console.error('Error del servidor:', result);
            }
        } catch (error) {
            showToast('Error de conexión', 'error');
            console.error('Error:', error);
        }
    });

    // Confirmar eliminación
    async function confirmDelete() {
        if (!deleteId) return;

        try {
            const response = await fetch('api/blogs/eliminar.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id: deleteId })
            });

            const result = await response.json();

            if (result.success) {
                showToast(result.message || 'Entrada eliminada', 'success');
                document.getElementById('modalDelete').close();
                loadBlogs();
            } else {
                showToast(result.message || 'Error al eliminar', 'error');
            }
        } catch (error) {
            showToast('Error de conexión', 'error');
            console.error('Error:', error);
        }

        deleteId = null;
    }

    // Utilidades
    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('es-ES', {
            year: 'numeric',
            month: 'short',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    }

    function showToast(message, type = 'success') {
        const toast = document.getElementById('toast');
        const toastMessage = document.getElementById('toast-message');
        const toastIcon = document.getElementById('toast-icon');

        toastMessage.textContent = message;

        if (type === 'success') {
            toast.className = 'fixed top-4 right-4 z-50 bg-white rounded-lg shadow-lg p-4 max-w-sm border-l-4 border-green-500 transition-all';
            toastIcon.innerHTML = `
            <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        `;
        } else {
            toast.className = 'fixed top-4 right-4 z-50 bg-white rounded-lg shadow-lg p-4 max-w-sm border-l-4 border-red-500 transition-all';
            toastIcon.innerHTML = `
            <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        `;
        }

        toast.classList.remove('hidden');

        setTimeout(() => {
            hideToast();
        }, 4000);
    }

    function hideToast() {
        document.getElementById('toast').classList.add('hidden');
    }
</script>

<?php include __DIR__ . '/components/footer.php'; ?>