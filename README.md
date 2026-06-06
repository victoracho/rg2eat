# RG2 Eat — CMS (Laravel)

Landing + mini-CMS para el restaurante mexicano **RG2 Eat** (Porto). Todo el contenido de la landing se administra desde un panel privado: textos (ES/EN/PT), redes, mapa, horarios y menú con CRUD. El menú es accesible por QR en tiempo real.

## Stack

- **PHP 8.5** + **Laravel 12**
- **MariaDB** (utf8mb4)
- **chillerlan/php-qrcode** (puro PHP, sin `ext-iconv`)
- Vistas Blade · sin frontend build (Vite opcional, no se usa)
- Zona horaria por defecto: `Europe/Lisbon`

## Arrancar

```bash
php artisan migrate --seed   # crea tablas y datos iniciales
php artisan serve            # http://127.0.0.1:8000
```

### Credenciales admin (seed)

- **URL**: http://127.0.0.1:8000/login
- **Email**: `admin@rg2eat.com`
- **Pass**: `rg2admin`

(Cámbiala en producción desde Tinker o un seeder local.)

## URLs

| URL | Qué hace |
|---|---|
| `/` | Landing completa pintada desde BD (con `?lang=es\|en\|pt`) |
| `/menu` | Página `/menu` en tiempo real — destino del QR |
| `/qr/menu.png` | PNG del QR (apunta a `/menu`) |
| `/login` | Login admin |
| `/admin` | Dashboard CMS |
| `/admin/settings` | Editor de textos/URLs por idioma |
| `/admin/categories` | CRUD de categorías del menú |
| `/admin/items` | CRUD de platos (precio, tags, destacados…) |
| `/admin/hours` | Horarios por día y franja (lunch/dinner) |

## Tablas

- `users` — admin login
- `site_settings` — clave + valor en `value_es / value_en / value_pt`, agrupado por sección (hero, menu, about, social, location, footer, nav)
- `business_hours` — `day_of_week` (0=dom…6=sáb), `shift`, `open_time`, `close_time`, `is_closed`
- `menu_categories` — slug, icon, nombre/descr. en 3 idiomas, orden, activo
- `menu_items` — FK a categoría, nombre/descr. 3 idiomas, precio, moneda, tags JSON, activo/destacado, orden

## Cómo se conecta el QR con el menú

`/qr/menu.png` se genera dinámicamente apuntando a `route('menu')`. La landing lo incrusta como `<img>` en cada tarjeta de categoría, así que al imprimir/escanear el QR el cliente abre la página `/menu` con los platos **leídos de la BD en tiempo real**. Si el admin cambia un precio, el siguiente escaneo ya lo ve.

## Badge "Abierto hoy"

`App\Support\Site::isOpenNow()` consulta `business_hours` con la hora actual (`Europe/Lisbon`) y devuelve si alguna franja del día activa cubre el momento actual. La landing usa eso para mostrar el badge en color y el resumen del día.

## Idiomas

- Detección por `?lang=es|en|pt`, `Accept-Language`, fallback `config('app.locale')`.
- En la landing y `/menu` se usan los campos `*_es / *_en / *_pt`; si EN/PT está vacío, hace fallback a ES.

## Original

La landing estática original quedó archivada en `_legacy/landing-original.html` por referencia.
