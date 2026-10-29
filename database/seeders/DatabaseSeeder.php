<?php

namespace Database\Seeders;

use App\Models\BusinessHour;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedAdmin();
        $this->seedSettings();
        $this->seedHours();
        $this->call(MenuSeeder::class);
    }

    private function seedAdmin(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@rg2eat.com'],
            [
                'name'     => 'Admin Chilanguinha',
                'password' => Hash::make('rg2admin'),
            ]
        );
    }

    private function seedSettings(): void
    {
        $rows = [
            // SEO / Brand
            ['general', 'brand_name', 'Nombre de la marca', 'text',
                'La Chilanguinha', 'La Chilanguinha', 'La Chilanguinha'],
            ['general', 'brand_logo_url', 'URL del logo', 'url',
                null, null, null],
            ['general', 'page_title', 'Título del navegador', 'text',
                'La Chilanguinha — Comida Mexicana · Porto',
                'La Chilanguinha — Mexican Food · Porto',
                'La Chilanguinha — Comida Mexicana · Porto'],

            // Hero
            ['hero', 'hero_tag', 'Etiqueta superior del hero', 'text',
                '🌮 Comida Mexicana · Porto', '🌮 Mexican Food · Porto', '🌮 Comida Mexicana · Porto'],
            ['hero', 'hero_h1', 'Titular del hero (HTML permitido)', 'html',
                'Sabor<br><em>auténtico</em><br>mexicano.',
                'Authentic<br><em>Mexican</em><br>flavour.',
                'Sabor<br><em>autêntico</em><br>mexicano.'],
            ['hero', 'hero_sub', 'Subtítulo del hero', 'textarea',
                'Bienvenidos a La Chilanguinha, donde los sabores de México cobran vida en el corazón de Porto. Ingredientes frescos, recetas tradicionales y mucho sabor.',
                'Welcome to La Chilanguinha, where the flavours of Mexico come alive in the heart of Porto. Fresh ingredients, traditional recipes and plenty of taste.',
                'Bem-vindos ao La Chilanguinha, onde os sabores do México ganham vida no coração do Porto. Ingredientes frescos, receitas tradicionais e muito sabor.'],
            ['hero', 'hero_cta1', 'Botón CTA 1', 'text', 'Ver menú', 'View menu', 'Ver menu'],
            ['hero', 'hero_cta2', 'Botón CTA 2', 'text', 'Encuéntranos', 'Find us', 'Encontra-nos'],
            ['hero', 'hero_badge_line1', 'Badge línea 1 (cuando está abierto)', 'text', 'Abierto', 'Open', 'Aberto'],
            ['hero', 'hero_badge_line2', 'Badge línea 2', 'text', 'Hoy', 'Today', 'Hoje'],
            ['hero', 'hero_badge_line3', 'Badge línea 3', 'text', 'en Porto', 'in Porto', 'no Porto'],
            ['hero', 'hero_badge_closed_line1', 'Badge cuando está CERRADO', 'text', 'Cerrado', 'Closed', 'Fechado'],

            // Menu section
            ['menu', 'menu_label', 'Etiqueta sección menú', 'text', 'Lo que tenemos para ti', 'What we have for you', 'O que temos para ti'],
            ['menu', 'menu_title', 'Título sección menú', 'text', 'Nuestro menú', 'Our menu', 'O nosso menu'],
            ['menu', 'menu_intro', 'Intro del menú', 'textarea',
                'Escanea el código QR o haz clic para ver el menú completo.',
                'Scan the QR code or click to view the full menu.',
                'Digitaliza o código QR ou clica para ver o menu completo.'],
            ['menu', 'menu_qr_label', 'Texto bajo QR', 'text', 'Escanear para ver menú', 'Scan to view menu', 'Digitalizar para ver menu'],
            ['menu', 'menu_link_label', 'Botón "ver menú online"', 'text', '↗ Ver menú online', '↗ View menu online', '↗ Ver menu online'],

            // About
            ['about', 'about_label', 'Etiqueta sección about', 'text', 'Nuestra historia', 'Our story', 'A nossa história'],
            ['about', 'about_title', 'Título sección about (HTML)', 'html',
                'Tradición<br>en cada bocado',
                'Tradition in<br>every bite',
                'Tradição em<br>cada garfada'],
            ['about', 'about_p1', 'Párrafo 1 about', 'textarea',
                'En La Chilanguinha llevamos la esencia de la cocina mexicana a las calles de Porto. Cada plato es una celebración de los sabores auténticos — desde las especias vibrantes hasta los ingredientes más frescos seleccionados a diario.',
                'At La Chilanguinha we bring the essence of Mexican cuisine to the streets of Porto. Every dish is a celebration of authentic flavours — from vibrant spices to the freshest ingredients selected daily.',
                'No La Chilanguinha trazemos a essência da cozinha mexicana para as ruas do Porto. Cada prato é uma celebração dos sabores autênticos — das especiarias vibrantes aos ingredientes mais frescos selecionados diariamente.'],
            ['about', 'about_p2', 'Párrafo 2 about', 'textarea',
                'Somos un local acogedor donde la comida, la música y la hospitalidad se unen para crear una experiencia única e inolvidable.',
                'We are a welcoming spot where food, music and hospitality come together to create a unique and unforgettable experience.',
                'Somos um espaço acolhedor onde a comida, a música e a hospitalidade se unem para criar uma experiência única e inesquecível.'],
            ['about', 'feat1_title', 'Feature 1 título', 'text', 'Ingredientes frescos', 'Fresh ingredients', 'Ingredientes frescos'],
            ['about', 'feat1_sub',   'Feature 1 subtítulo', 'text', 'Seleccionados cada día', 'Chosen every day', 'Selecionados todos os dias'],
            ['about', 'feat2_title', 'Feature 2 título', 'text', 'Recetas auténticas', 'Authentic recipes', 'Receitas autênticas'],
            ['about', 'feat2_sub',   'Feature 2 subtítulo', 'text', 'Tradición mexicana', 'Mexican tradition', 'Tradição mexicana'],
            ['about', 'feat3_title', 'Feature 3 título', 'text', 'Bebidas especiales', 'Special drinks', 'Bebidas especiais'],
            ['about', 'feat3_sub',   'Feature 3 subtítulo', 'text', 'Cócteles y más', 'Cocktails & more', 'Cocktails e mais'],
            ['about', 'feat4_title', 'Feature 4 título', 'text', 'Hecho con amor', 'Made with love', 'Feito com amor'],
            ['about', 'feat4_sub',   'Feature 4 subtítulo', 'text', 'Desde el corazón', 'From the heart', 'Do fundo do coração'],

            // Social
            ['social', 'social_label', 'Etiqueta sección redes', 'text', 'Síguenos', 'Follow us', 'Segue-nos'],
            ['social', 'social_title', 'Título sección redes', 'text', 'Únete a nuestra comunidad', 'Join our community', 'Junta-te à nossa comunidade'],
            ['social', 'social_sub',   'Subtítulo sección redes', 'textarea',
                'Comparte tus momentos con nosotros y mantente al día de las novedades.',
                'Share your moments with us and stay up to date with the latest news.',
                'Partilha os teus momentos connosco e mantém-te a par das novidades.'],
            ['social', 'instagram_url',    'URL Instagram', 'url', 'https://www.instagram.com/rg2eat/', null, null],
            ['social', 'instagram_handle', 'Handle Instagram', 'text', '@rg2eat', null, null],
            ['social', 'tiktok_url',       'URL TikTok', 'url', 'https://www.tiktok.com/@rg2eat', null, null],
            ['social', 'tiktok_handle',    'Handle TikTok', 'text', '@rg2eat', null, null],

            // Location
            ['location', 'loc_label', 'Etiqueta sección ubicación', 'text', 'Dónde encontrarnos', 'Where to find us', 'Onde nos encontrar'],
            ['location', 'loc_title', 'Título sección ubicación (HTML)', 'html', 'Estamos en<br>Porto 📍', 'We are in<br>Porto 📍', 'Estamos no<br>Porto 📍'],
            ['location', 'loc_address_title', 'Título "Dirección"', 'text', 'Dirección', 'Address', 'Morada'],
            ['location', 'loc_address',       'Dirección', 'textarea', 'Porto, Portugal', 'Porto, Portugal', 'Porto, Portugal'],
            ['location', 'loc_hours_title',   'Título horario', 'text', 'Horario', 'Opening hours', 'Horário'],
            ['location', 'loc_social_title',  'Título redes (en ubicación)', 'text', 'Redes sociales', 'Social media', 'Redes sociais'],
            ['location', 'loc_maps_link',     'Texto enlace Maps', 'text', 'Ver en Google Maps →', 'View on Google Maps →', 'Ver no Google Maps →'],
            ['location', 'loc_maps_cta',      'CTA bajo el mapa', 'text', '📍 Abrir en Google Maps', '📍 Open in Google Maps', '📍 Abrir no Google Maps'],
            ['location', 'maps_share_url',    'URL maps.app.goo.gl', 'url', 'https://maps.app.goo.gl/edWbTTESr36zEu956', null, null],
            ['location', 'maps_embed_src',    'iFrame src del mapa', 'url',
                'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d12072.!2d-8.61099!3d41.14961!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd246533ba3c13d1%3A0x55e32f97e33e3c69!2sPorto%2C%20Portugal!5e0!3m2!1ses!2spt!4v1710000000000', null, null],

            // Footer
            ['footer', 'footer_tagline', 'Footer tagline', 'text', 'Comida Mexicana Auténtica', 'Authentic Mexican Food', 'Comida Mexicana Autêntica'],
            ['footer', 'footer_copy',    'Footer copyright', 'text', '© 2026 La Chilanguinha. Todos los derechos reservados.', '© 2026 La Chilanguinha. All rights reserved.', '© 2026 La Chilanguinha. Todos os direitos reservados.'],
            ['footer', 'footer_map',     'Texto link al mapa', 'text', 'Mapa', 'Map', 'Mapa'],

            // Nav
            ['nav', 'nav_menu',     'Nav: menú', 'text', 'Menú', 'Menu', 'Menu'],
            ['nav', 'nav_about',    'Nav: sobre', 'text', 'Nosotros', 'About', 'Sobre nós'],
            ['nav', 'nav_social',   'Nav: redes', 'text', 'Redes', 'Social', 'Redes'],
            ['nav', 'nav_location', 'Nav: ubicación', 'text', 'Ubicación', 'Location', 'Localização'],
            ['nav', 'nav_reserve',  'Nav: CTA reservar', 'text', 'Reservar', 'Book Now', 'Reservar'],
        ];

        $i = 0;
        foreach ($rows as [$group, $key, $label, $type, $es, $en, $pt]) {
            SiteSetting::updateOrCreate(
                ['key' => $key],
                [
                    'group'      => $group,
                    'label'      => $label,
                    'type'       => $type,
                    'value_es'   => $es,
                    'value_en'   => $en,
                    'value_pt'   => $pt,
                    'sort_order' => $i++,
                ]
            );
        }
    }

    private function seedHours(): void
    {
        // Closed Mondays; Tue–Sun open with lunch and dinner shifts.
        // day_of_week: 0=Sun, 1=Mon, ..., 6=Sat
        $week = [
            0 => [['lunch', '12:00', '15:00'], ['dinner', '18:00', '23:00']],
            1 => [['full',  null,    null]], // closed Mondays
            2 => [['lunch', '12:00', '15:00'], ['dinner', '18:00', '23:00']],
            3 => [['lunch', '12:00', '15:00'], ['dinner', '18:00', '23:00']],
            4 => [['lunch', '12:00', '15:00'], ['dinner', '18:00', '23:00']],
            5 => [['lunch', '12:00', '15:00'], ['dinner', '18:00', '24:00']],
            6 => [['lunch', '12:00', '16:00'], ['dinner', '18:00', '24:00']],
        ];

        BusinessHour::query()->delete();
        foreach ($week as $dow => $shifts) {
            foreach ($shifts as $i => [$shift, $open, $close]) {
                BusinessHour::create([
                    'day_of_week' => $dow,
                    'shift'       => $shift,
                    'open_time'   => $open,
                    'close_time'  => $close,
                    'is_closed'   => $dow === 1,
                    'sort_order'  => $i,
                ]);
            }
        }
    }
}
