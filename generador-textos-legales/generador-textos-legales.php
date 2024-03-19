<?php
/*
Plugin Name: Generador de textos Legales
Description: Genera páginas de Aviso Legal, Política de Privacidad y Política de Cookies con información personalizada. Es una tontería, pero te ahorra un ratillo de copiar y pegar.
Version: 1.12
Author: Oriol Piqué Vallverdú
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Registro del menú en el panel de administración
add_action('admin_menu', 'ltg_add_admin_menu');
function ltg_add_admin_menu() {
    $hook_suffix = add_menu_page('Textos Legales', 'Textos Legales', 'manage_options', 'generador-textos-legales', 'ltg_admin_page', 'dashicons-format-aside', 81);
}

// Contenido de la página del formulario

function ltg_admin_page() {
    $message = '';
    $message_type = ''; // 'updated' for success, 'error' for errors
    if (isset($_POST['ltg_generate'])) {
        $result = ltg_generate_legal_pages();
        $message = $result['message'];
        $message_type = $result['type'];
    }

    ?>
    <div class="wrap">
        <h2>Generador de textos legales</h2>
        <?php if ($message): ?>
            <div class="notice <?php echo $message_type === 'error' ? 'notice-error' : 'notice-success'; ?> is-dismissible">
                <p><?php echo $message; ?></p>
            </div>
        <?php endif; ?>
        <form method="post" action="">
            <label for="ltg_company_name" class="ltg-form-label">Nombre de la Empresa:</label><br>
            <input type="text" id="ltg_company_name" name="ltg_company_name" class="ltg-form-input"><br><br>

            <label for="ltg_nif" class="ltg-form-label">NIF:</label><br>
            <input type="text" id="ltg_nif" name="ltg_nif" class="ltg-form-input"><br><br>

            <label for="ltg_address" class="ltg-form-label">Dirección:</label><br>
            <input type="text" id="ltg_address" name="ltg_address" class="ltg-form-input"><br><br>

            <label for="ltg_phone" class="ltg-form-label">Teléfono:</label><br>
            <input type="text" id="ltg_phone" name="ltg_phone" class="ltg-form-input"><br><br>

            <label for="ltg_email" class="ltg-form-label">Correo Electrónico:</label><br>
            <input type="email" id="ltg_email" name="ltg_email" class="ltg-form-input"><br><br>

            <input type="submit" name="ltg_generate" value="Generar Textos Legales" class="ltg-form-submit">
        </form>
    </div>
    <?php
}

function ltg_generate_legal_pages() {
    $company_name = sanitize_text_field($_POST['ltg_company_name']);
    $nif = sanitize_text_field($_POST['ltg_nif']);
    $address = sanitize_text_field($_POST['ltg_address']);
    $phone = sanitize_text_field($_POST['ltg_phone']);
    $email = sanitize_email($_POST['ltg_email']);

    $legal_pages = [
        'Aviso Legal' => 'Texto de muestra para Aviso Legal...',
        'Política de Privacidad' => 'Texto de muestra para Política de Privacidad...',
        'Política de Cookies' => 'Texto de muestra para Política de Cookies...'
    ];

    $created_pages = [];
    foreach ($legal_pages as $title => $content) {
        $page_exists = get_page_by_title($title);

        if (!$page_exists) {
            $page_id = wp_insert_post([
                'post_title'   => $title,
                'post_content' => $content . "<br><br>Nombre de la Empresa: $company_name<br>NIF: $nif<br>Dirección: $address<br>Teléfono: $phone<br>Correo Electrónico: $email",
                'post_status'  => 'publish',
                'post_type'    => 'page'
            ]);

            if ($page_id) {
                $created_pages[] = $title;
            }
        }
    }

    if (count($created_pages) > 0) {
        $plugin_url = admin_url('plugins.php');
        $donation_url = 'https://www.paypal.com/donate?business=orilpik%40gmail.com';
        $message = 'Páginas creadas correctamente: ' . implode(', ', $created_pages).'. Recuerda que la página de Cookies es genérica, deberías rellenarla con tus datos técnicos.';
        $message .= "<br><a href='$donation_url' target='_blank'>Te ha gustado? Se aceptan donaciones por Paypal.</a>";
        $message .= "<br><a href='$plugin_url'>Has terminado? Elimina el plugin.</a>";
        return ['message' => $message, 'type' => 'updated'];
    } else {
        return ['message' => 'No se crearon páginas nuevas. Puede que ya existan.', 'type' => 'error'];
    }
}
