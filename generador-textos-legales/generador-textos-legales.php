<?php
/*
Plugin Name: Generador de textos Legales
Description: Genera páginas de Aviso Legal, Política de Privacidad y Política de Cookies con información personalizada. Es una tontería, pero te ahorra un ratillo de copiar y pegar.
Version: 1.1
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
            <p><?php echo wp_kses_post($message); ?></p>

            </div>
        <?php endif; ?>
        <form method="post" action="">
        <?php wp_nonce_field('ltg_generate_nonce', 'ltg_nonce_field'); ?>
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
    if (isset($_POST['ltg_generate']) && check_admin_referer('ltg_generate_nonce', 'ltg_nonce_field')) {
        $company_name = sanitize_text_field($_POST['ltg_company_name']);
        $nif = sanitize_text_field($_POST['ltg_nif']);
        $address = sanitize_text_field($_POST['ltg_address']);
        $phone = sanitize_text_field($_POST['ltg_phone']);
        $email = sanitize_email($_POST['ltg_email']);

        // Textos de las páginas legales con marcadores de posición
        $legal_pages = [
            'Política de Privacidad' => "
            <h2>DATOS DEL RESPONSABLE:</h2>

            [INFO]

            En adelante, el Responsable.

            A través de este sitio web no se recaban datos de carácter personal de las personas usuarias sin su conocimiento, ni se ceden a terceros.

            Con la finalidad de ofrecerle el mejor servicio y con el objeto de facilitar el uso, se analizan el número de páginas visitadas, el número de visitas, así como la actividad de las persona visitantes y su frecuencia de utilización. A estos efectos, la Agencia Española de Protección de Datoel responsable) utiliza la información estadística elaborada por el Proveedor de Servicios de Internet.
            
            el responsable no utiliza cookies para recoger información de las personas usuarias, ni registra las direcciones IP de acceso. Únicamente se utilizan cookies propias, de sesión, con finalidad técnica (aquellas que permiten la navegación a través del sitio web y la utilización de las diferentes opciones y servicios que en ella existen).
            
            El portal del que es titular el responsable contiene enlaces a sitios web de terceros, cuyas políticas de privacidad son ajenas a la de el responsable. Al acceder a tales sitios web usted puede decidir si acepta sus políticas de privacidad y de cookies. Con carácter general, si navega por internet usted puede aceptar o rechazar las cookies de terceros desde las opciones de configuración de su navegador.
            
            <h2>Información básica sobre protección de datos</h2>
            A continuación le informamos sobre la política de protección de datos del responsable.
            
            <h3>Responsable del tratamiento</h3>
            Los datos de carácter personal que se pudieran recabar directamente de la persona interesada serán tratados de forma confidencial y quedarán incorporados a la correspondiente actividad de tratamiento titularidad de la Agencia Española de Protección de Datoel responsable).
            
            La relación actualizada de las actividades de tratamiento que el responsable lleva a cabo se encuentra disponible en el siguiente registro de actividades de el responsable.
            
            <h3>Finalidad</h3>
            La finalidad del tratamiento de los datos corresponde a cada una de las actividades de tratamiento que realiza el responsable y que están accesibles en el registro de actividadesde tratamiento.
            
            <h3>Legitimación</h3>
            El tratamiento de sus datos se realiza para el cumplimiento de obligaciones legales por parte de el responsable, para el cumplimiento de misiones realizada en interés público o en el ejercicio de poderes públicos conferidos al responsable, así como cuando la finalidad del tratamiento requiera su consentimiento, que habrá de ser prestado mediante una clara acción afirmativa.
            
            Puede consultar la base legal para cada una de las actividades de tratamiento que lleva a cabo el responsable en el siguiente Acceder al registro de actividades de el responsable.
            
            <h3>Conservación de datos</h3>
            Los datos personales proporcionados se conservarán durante el tiempo necesario para cumplir con la finalidad para la que se recaban y para determinar las posibles responsabilidades que se pudieran derivar de la finalidad, además de los períodos establecidos en la normativa de archivos y documentación.
            
            <h3>Comunicación de datos</h3>
            Con carácter general no se comunicarán los datos personales a terceros, salvo obligación legal, entre las que pueden estar las comunicaciones al Defensor del Pueblo, Jueces y Tribunales, personas interesadas en los procedimientos relacionados con la reclamaciones presentadas.
            
            Puede consultar los destinatarios para cada una de las actividades de tratamiento que lleva a cabo el responsable en el siguiente Acceder al registro de actividades de el responsable.
            
            Derechos de las personas interesadas
            Para solicitar el acceso, la rectificación, supresión o limitación del tratamiento de los datos personales o a oponerse al tratamiento, en el caso de se den los requisitos establecidos en el Reglamento General de Protección de Datos, así como en la Ley Orgánica 3/2018, de 5 de diciembre, de Protección de Datos Personal y garantía de los derechos digitales, puede dirigir un escrito al responsable del tratamiento, en este caso, el responsable, dirigiendo el mismo a cualquiera de los datos de contacto arriba descritos.",
            'Aviso Legal' => "
            Este portal, pertenece a: 
            [INFO]
            en adelante, el responsable.            
            
            <h2>Propiedad intelectual e industrial</h2>
            El diseño del portal y sus códigos fuente, así como los logos, marcas y demás signos distintivos que aparecen en el mismo pertenecen al responsable y están protegidos por los correspondientes derechos de propiedad intelectual e industrial.
            
            <h2>Responsabilidad de los contenidos</h2>
            el responsable no se hace responsable de la legalidad de otros sitios web de terceros desde los que pueda accederse al portal. el responsable tampoco responde por la legalidad de otros sitios web de terceros, que pudieran estar vinculados o enlazados desde este portal.
            
            el responsable se reserva el derecho a realizar cambios en el sitio web sin previo aviso, al objeto de mantener actualizada su información, añadiendo, modificando, corrigiendo o eliminando los contenidos publicados o el diseño del portal.
            
            el responsable no será responsable del uso que terceros hagan de la información publicada en el portal, ni tampoco de los daños sufridos o pérdidas económicas que, de forma directa o indirecta, produzcan o puedan producir perjuicios económicos, materiales o sobre datos, provocados por el uso de dicha información.
            
            <h2>Reproducción de contenidos</h2>
            Se prohíbe la reproducción total o parcial de los contenidos publicados en el portal. No obstante, los contenidos que sean considerados como datos abiertos en la Sede Electrónica, publicados según lo previsto en el Real Decreto 1495/2011, de 24 de octubre, de desarrollo de la Ley 37/2007, sobre reutilización de la información del sector público, para el ámbito del sector público estatal, podrán ser objeto de reproducción en los términos contenidos en el siguiente Aviso.
            
            <h2>Sede Electrónica</h2>
            De acuerdo con lo establecido por el artículo 12 del Real Decreto 203/2021, de 30 de marzo, por el que se aprueba el Reglamento de actuación y funcionamiento del sector público por medios electrónicos, el responsable se responsabiliza de la integridad, veracidad y actualización de la información y los servicios a los que pueda accederse a través de su Sede Electrónica (Sede electronica de el responsable).
            
            <h2>Portal de transparencia</h2>
            A través de la información publicada en el portal de transparencia, el responsable atiende de forma periódica y actualizada el principio de publicidad activa establecido por la Ley 19/2013, de 9 de diciembre, de transparencia, acceso a la información pública y buen gobierno, con los mecanismos adecuados para facilitar la accesibilidad, la interoperabilidad, la calidad y la reutilización de la información, así como su identificación y localización.
            
            <h2>Ley aplicable</h2>
            La ley aplicable en caso de disputa o conflicto de interpretación de los términos que conforman este aviso legal, así como cualquier cuestión relacionada con los servicios del presente portal, será la ley española.",
            'Política de Cookies' => "El responsable de esta Política es [INFO]
            
            El responsable no utiliza cookies para recoger información de las personas usuarias. Únicamente se utilizan cookies propias, de sesión, con finalidad técnica (aquellas que permiten a la persona usuaria la navegación a través del sitio web y la utilización de las diferentes opciones y servicios que en ella existen).

            El portal del que es titular El responsable contiene enlaces a sitios web de terceros, cuyas políticas de privacidad son ajenas a la de El responsable. Al acceder a tales sitios web usted puede decidir si acepta sus políticas de privacidad y de cookies. Con carácter general, si navega por internet usted puede aceptar o rechazar las cookies de terceros desde las opciones de configuración de su navegador."
        ];

        $created_pages = [];
        foreach ($legal_pages as $title => $template) {
            $info = "<br><br>Nombre de la Empresa: " . esc_html($company_name) . "<br>NIF: " . esc_html($nif) . "<br>Dirección: " . esc_html($address) . "<br>Teléfono: " . esc_html($phone) . "<br>Correo Electrónico: " . esc_html($email);
            $content = str_replace('[INFO]', $info, $template);

            $args = [
                'post_type'      => 'page',
                'post_status'    => 'publish',
                'title'          => $title,
                'posts_per_page' => 1
            ];

            $query = new WP_Query($args);

            if (!$query->have_posts()) {
                $page_id = wp_insert_post([
                    'post_title'   => $title,
                    'post_content' => $content,
                    'post_status'  => 'publish',
                    'post_type'    => 'page'
                ]);

                if ($page_id) {
                    $created_pages[] = $title;
                }
            }
            wp_reset_postdata();
        }

        if (count($created_pages) > 0) {
            $plugin_url = admin_url('plugins.php');
            $donation_url = 'https://www.paypal.com/donate?business=orilpik%40gmail.com';
            $message = 'Páginas creadas correctamente: ' . implode(', ', $created_pages) . '. Recuerda que la página de Cookies es genérica, deberías rellenarla con tus datos técnicos. Revisa también el resto, por si acaso.';
            $message .= '<br><a href="' . $donation_url . '" target="_blank">Te ha gustado? Se aceptan donaciones por Paypal.</a>';
            $message .= '<br><a href="' . $plugin_url . '">Has terminado? Elimina el plugin.</a>';
            return ['message' => $message, 'type' => 'updated'];
        } else {
            return ['message' => 'No se crearon páginas nuevas. Puede que ya existan.', 'type' => 'error'];
        }
        
    }
}

