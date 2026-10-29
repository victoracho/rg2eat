<?php

namespace Database\Seeders;

use App\Models\MenuCategory;
use App\Models\MenuItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

// Carta real de La Chilanguinha (fotos/), un grupo por apartado de la carta impresa.
// Borra todo el menú y lo recrea. Orden = orden de lectura por columnas en la carta.
// 'parent' => slug mete el grupo dentro del recuadro de otro.
// Ítem: [nombre, precio|null, desc_es, desc_en, desc_pt, tags, precio_2]
class MenuSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            MenuCategory::query()->delete(); // items caen por cascadeOnDelete

            $ids = [];
            foreach ($this->menu() as $c => $g) {
                $cat = MenuCategory::create([
                    'parent_id' => isset($g['parent']) ? $ids[$g['parent']] : null,
                    'slug' => $g['slug'], 'section' => $g['section'], 'style' => $g['style'], 'icon' => $g['icon'],
                    'price_label' => $g['labels'][0] ?? null, 'price_2_label' => $g['labels'][1] ?? null,
                    'name_es' => $g['name'][0], 'name_en' => $g['name'][1], 'name_pt' => $g['name'][2],
                    'description_es' => $g['desc'][0] ?? null, 'description_en' => $g['desc'][1] ?? null, 'description_pt' => $g['desc'][2] ?? null,
                    'is_active' => true, 'sort_order' => $c,
                ]);
                $ids[$g['slug']] = $cat->id;

                foreach ($g['items'] as $i => $it) {
                    MenuItem::create([
                        'menu_category_id' => $cat->id,
                        'name_es' => $it[0],
                        'price' => $it[1], 'price_2' => $it[6] ?? null, 'currency' => 'EUR',
                        'description_es' => $it[2] ?? null, 'description_en' => $it[3] ?? null, 'description_pt' => $it[4] ?? null,
                        'tags' => $it[5] ?? [],
                        'is_active' => true, 'is_featured' => $g['featured'] ?? false, 'sort_order' => $i,
                    ]);
                }
            }
        });
    }

    private function menu(): array
    {
        $n = null;
        $new = ['new'];

        return [
            // ═════════════ COMIDA ═════════════
            ['slug' => 'first-time', 'section' => 'food', 'style' => 'dotted-red', 'icon' => '⭐', 'featured' => true,
             'name' => ['¿primera vez?', 'first time?', 'primeira vez?'],
             'desc' => ['Sin estrés, güey: pide un DE TODO y prueba un poco de todo.', 'No stress, güey, just grab a DE TODO and try a little bit of everything.', 'Sem stress, güey: pede um DE TODO e prova um pouco de tudo.'],
             'items' => [
                ['De Todo · For the Craving', 15, 'Nachos con cheddar + 2 tacos chilangos (carnitas y pollo a la plancha) + 1 taco chingón de alambre de pollo.', 'Cheddar nachos + 2 chilango tacos (carnitas & grilled chicken) + 1 chingón chicken alambre taco.', 'Nachos com cheddar + 2 tacos chilangos (carnitas e frango grelhado) + 1 taco chingón de alambre de frango.'],
                ['De Todo Nº2 · For the Chingón', 20, '2 tacos chilangos (pastor guacamole y chorizo con queso) + 1 taco chingón de carnitas + nachos con guacamole y una quesadilla.', '2 chilango tacos (pastor guacamole & chorizo cheese) + 1 chingón carnitas taco + guac nachos & a quesadilla.', '2 tacos chilangos (pastor guacamole e chouriço com queijo) + 1 taco chingón de carnitas + nachos com guacamole e uma quesadilla.'],
                ['De Todo Nº3 · For the Whole Crew', 30, '3 tacos chilangos con queso + 2 tacos chingones con guacamole + nachos a elegir + 1 quesabirria con consomé.', '3 cheese chilango tacos + 2 guaca chingón tacos + nachos of your choice + 1 quesabirria with consomé.', '3 tacos chilangos com queijo + 2 tacos chingones com guacamole + nachos à escolha + 1 quesabirria com consomé.'],
             ]],
            ['slug' => 'to-start', 'section' => 'food', 'style' => 'tab-yellow', 'icon' => '🧀',
             'name' => ['Para empezar', 'To Start', 'Para começar'],
             'items' => [
                ['Guacamole Nachos', 6, 'Totopos de maíz con guacamole', 'Corn tortilla chips piled with guac', 'Totopos de milho com guacamole'],
                ['Guac Nachos + Taco Meat', 8],
                ['Cheddar, Cheese & Jalapeño Nachos', 6],
                ['Cheddar & Bacon Nachos', 8],
                ['Pastor, Cheese & Bean Nachos', 9],
                ['Jalapeño Choriqueso Nachos', 7],
                ['Choriqueso with Tortillas (2)', 7],
                ['Tequeños (3)', 6],
                ['Quesadilla, Corn or Flour (2 pcs)', 6],
                ['Special Quesadillas (2 pcs)', 8, 'Frijol-jalapeño · Guacamole · Pico de gallo', 'Bean-jalapeño · Guacamole · Pico de gallo', 'Feijão-jalapeño · Guacamole · Pico de gallo'],
             ]],
            ['slug' => 'on-the-side', 'section' => 'food', 'style' => 'tab-blue', 'icon' => '🥑',
             'name' => ['Acompañamientos', 'On the Side', 'Acompanhamentos'],
             'items' => [
                ['Pickled Jalapeños', 2.50],
                ['Pico de Gallo', 2.50],
                ['Beans (portion)', 2.50],
                ['Guacamole (single portion)', 4.50],
             ]],
            ['slug' => 'los-chilangos', 'section' => 'food', 'style' => 'dotted-red', 'icon' => '🌮',
             'name' => ['los chilangos', 'los chilangos', 'los chilangos'],
             'desc' => ['12 cm · el auténtico sabor callejero de la CDMX', '12 cm · the real CDMX street flavors', '12 cm · o verdadeiro sabor de rua da CDMX'],
             'items' => []],
            ['slug' => 'chilangos-base', 'parent' => 'los-chilangos', 'section' => 'food', 'style' => 'tab-red', 'icon' => '🌮',
             'name' => ['Base', 'Base', 'Base'],
             'items' => [
                ['Taco', 3.50, 'cebolla y cilantro · individual', 'onion & cilantro · single', 'cebola e coentros · individual'],
                ['Queso-Taco', 4, 'queso, cebolla y cilantro', 'cheese, onion & cilantro', 'queijo, cebola e coentros', $new],
                ['Guaca-Taco', 4, 'guacamole, cebolla y cilantro', 'guacamole, onion & cilantro', 'guacamole, cebola e coentros', $new],
                ['Guacaqueso', 4, 'guacamole, queso, cebolla y cilantro', 'guacamole, cheese, onion & cilantro', 'guacamole, queijo, cebola e coentros', $new],
             ]],
            ['slug' => 'chilangos-orders', 'parent' => 'los-chilangos', 'section' => 'food', 'style' => 'fill-orange', 'icon' => '🌮',
             'name' => ['Órdenes', 'Orders', 'Pedidos'],
             'items' => [
                ['Order of 5', 15, '€3 c/u · 1-2 sabores', '€3 each · 1-2 flavors', '€3 cada · 1-2 sabores'],
                ['Order of 10', 28, '€2,80 c/u · 1-2 sabores', '€2.80 each · 1-2 flavors', '€2,80 cada · 1-2 sabores'],
                ['Mixed Order', 1.50, 'suplemento', 'supplement', 'suplemento'],
             ]],
            ['slug' => 'chilangos-fillings', 'parent' => 'los-chilangos', 'section' => 'food', 'style' => 'tab-blue', 'icon' => '🥩',
             'name' => ['Rellenos', 'Fillings', 'Recheios'],
             'items' => [
                ['1 Pastor', $n],
                ['2 Carnitas', $n],
                ['3 Steak & Onion', $n],
                ['4 Chorizo', $n],
                ['5 Suadero', $n, $n, $n, $n, $new],
                ['6 Chicken & Onion', $n],
                ['7 Mushrooms (Veggie)', $n],
             ]],
            ['slug' => 'campechanos', 'parent' => 'los-chilangos', 'section' => 'food', 'style' => 'tab-red', 'icon' => '🌮',
             'name' => ['Campechanos', 'Campechanos', 'Campechanos'],
             'desc' => ['2 carnes, cilantro y cebolla', '2 meats, cilantro & onion', '2 carnes, coentros e cebola'],
             'items' => [
                ['8 Steak-Chorizo', $n],
                ['9 Suadero-Chorizo', $n],
                ['10 Chicken-Chorizo', $n],
             ]],
            ['slug' => 'los-chingones', 'section' => 'food', 'style' => 'dotted-black', 'icon' => '🌮',
             'name' => ['los chingones', 'los chingones', 'los chingones'],
             'desc' => ['15 cm · grandes, potentes y cargados', '15 cm · big, bold & loaded', '15 cm · grandes, intensos e recheados'],
             'items' => []],
            ['slug' => 'chingones-base', 'parent' => 'los-chingones', 'section' => 'food', 'style' => 'tab-red', 'icon' => '🌮',
             'name' => ['Base', 'Base', 'Base'],
             'items' => [
                ['Taco', 6, 'frijoles + proteína · individual', 'beans + protein · single', 'feijão + proteína · individual'],
                ['Queso-Taco', 7, 'queso, frijoles y proteína', 'cheese, beans & protein', 'queijo, feijão e proteína', $new],
                ['Guaca-Taco', 7, 'guacamole, frijoles y proteína', 'guacamole, beans & protein', 'guacamole, feijão e proteína', $new],
                ['Guacaqueso', 7, 'guacamole, queso, frijoles y proteína', 'guacamole, cheese, beans & protein', 'guacamole, queijo, feijão e proteína', $new],
             ]],
            ['slug' => 'chingones-orders', 'parent' => 'los-chingones', 'section' => 'food', 'style' => 'fill-blue', 'icon' => '🌮',
             'name' => ['Órdenes', 'Orders', 'Pedidos'],
             'items' => [
                ['Order of 3', 16.50, '€5,50 c/u · 1-2 sabores', '€5.50 each · 1-2 flavors', '€5,50 cada · 1-2 sabores'],
                ['Order of 5', 24, '€4,80 c/u · 1-2 sabores', '€4.80 each · 1-2 flavors', '€4,80 cada · 1-2 sabores'],
                ['Mix Order', 1.50, 'suplemento', 'supplement', 'suplemento'],
             ]],
            ['slug' => 'chingones-fillings', 'parent' => 'los-chingones', 'section' => 'food', 'style' => 'tab-blue', 'icon' => '🥩',
             'name' => ['Rellenos', 'Fillings', 'Recheios'],
             'items' => [
                ['1 Carnitas', $n],
                ['2 Breaded Chicken', $n],
                ['3 Chicken Alambre', $n, 'pollo, champiñones, tocino y cebolla', 'chicken, mushrooms, bacon & onion', 'frango, cogumelos, bacon e cebola'],
                ['4 Birria', $n, $n, $n, $n, $new],
                ['5 Steak Alambre', $n, 'bistec, tocino y champiñones', 'steak, bacon & mushrooms', 'bife, bacon e cogumelos', $new],
             ]],
            ['slug' => 'chingones-extra', 'parent' => 'los-chingones', 'section' => 'food', 'style' => 'title-red', 'icon' => '🌮',
             'name' => ['¡Que no se rompa!', "Don't let it break!", 'Que não se parta!'],
             'items' => [
                ['Double Tortilla', 0.50, 'suplemento', 'supplement', 'suplemento'],
             ]],
            ['slug' => 'burritos', 'section' => 'food', 'style' => 'tab-blue', 'icon' => '🌯',
             'name' => ['Burritos', 'Burritos', 'Burritos'],
             'items' => [
                ['Bell Pepper, Cheese & Onion', 10],
                ['Mushroom', 10],
                ['Chicken · Carnitas · Pastor · Suadero · Breaded', 10],
                ['Steak · Bacon & Meat Alambre · Quesabirria', 12],
                ['Choriqueso · Jalapeño Chorizo · Steak Chorizo', 12],
                ['Your Way', 13.50, 'queso + tus 2 carnes favoritas', 'cheese + your 2 favorite meats', 'queijo + as tuas 2 carnes favoritas'],
             ]],
            ['slug' => 'gringas', 'section' => 'food', 'style' => 'tab-yellow', 'icon' => '🫓',
             'name' => ['Gringas', 'Gringas', 'Gringas'],
             'desc' => ['Tortilla de harina, queso fundido y carne.', 'Flour tortilla, melted cheese & meat.', 'Tortilha de farinha, queijo derretido e carne.'],
             'items' => [
                ['Mushroom · Chicken · Pastor · Carnitas · Suadero · Choriqueso', 12],
             ]],
            ['slug' => 'chilaquiles', 'section' => 'food', 'style' => 'tab-blue', 'icon' => '🌶️',
             'name' => ['Chilaquiles', 'Chilaquiles', 'Chilaquiles'],
             'desc' => ['Totopos fritos caseros bañados en salsa caliente, con crema, queso fresco y cebolla.', 'Handmade fried chips drenched in hot salsa, topped with crema, fresh cheese & onion.', 'Totopos fritos caseiros com molho quente, natas, queijo fresco e cebola.'],
             'items' => [
                ['Verdes', 10, 'salsa de tomatillo verde, picor medio', 'green tomatillo salsa, medium heat', 'molho de tomatillo verde, picante médio'],
                ['Chipotle Rojos', 10, 'ahumados y picantes', 'smoky & spicy', 'fumados e picantes'],
                ['Birria', 15, 'con caldo y carne de birria encima', 'with birria broth & birria meat on top', 'com caldo e carne de birria por cima'],
                ['Add Egg, Chicken or Steak', 2, 'extra', 'extra', 'extra'],
             ]],
            ['slug' => 'sweets', 'section' => 'food', 'style' => 'tab-yellow', 'icon' => '🍩',
             'name' => ['Dulces', 'Sweets', 'Doces'],
             'items' => [
                ['Churros (4)', 4],
                ['Fruit with Chamoy & Tajín', 3.50],
             ]],
            ['slug' => 'extras', 'section' => 'food', 'style' => 'tab-blue', 'icon' => '🍫',
             'name' => ['Extras', 'Extras', 'Extras'],
             'items' => [
                ['Mexican Cajeta', 2],
                ['Ice Cream', 2],
                ['Condensed Milk', 1],
                ['Chocolate', 1],
             ]],

            // ═════════════ BEBIDAS ═════════════
            ['slug' => 'cervezas-mexicanas', 'section' => 'drinks', 'style' => 'brush-yellow', 'icon' => '🍺',
             'name' => ['Cervezas mexicanas', 'Mexican Beers', 'Cervejas mexicanas'],
             'items' => [
                ['Corona', 4, 'lager ligera y refrescante', 'crisp and refreshing lager', 'lager leve e refrescante'],
                ['Modelo Especial', 5.50, 'lager mexicana equilibrada', 'well-balanced mexican lager', 'lager mexicana equilibrada'],
                ['Pacífico', 5.50, 'lager suave y fácil de tomar', 'smooth and easy-going lager', 'lager suave e fácil de beber'],
                ['Cerveza Mexicana del Mes', 6.50, '¡pregunta al staff!', 'ask our staff!', 'pergunta ao staff!'],
             ]],
            ['slug' => 'chelas-autenticas', 'section' => 'drinks', 'style' => 'brush-yellow', 'icon' => '🍻',
             'name' => ['Chelas auténticas mexicanas', 'Authentic Mexican Chelas', 'Chelas autênticas mexicanas'],
             'desc' => ['Todas de 1 litro · también disponibles en 50 cl.', 'All in 1 liter · also available in 50 cl.', 'Todas de 1 litro · também disponíveis em 50 cl.'],
             'items' => [
                ['Chelitro', 9, 'cerveza con limón y sal', 'beer with lime and salt', 'cerveja com lima e sal'],
                ['Michelitro', 10, 'cerveza con limón, especias y salsa secreta', 'beer with lime, spices & secret sauce', 'cerveja com lima, especiarias e molho secreto'],
                ['Licuachela', 10, 'la michelada viral de Tepito, servida en vaso de licuadora', "Tepito's viral michelada, served in a blender jar", 'a michelada viral de Tepito, servida em copo de liquidificadora', ['hecha en Tepito']],
                ['Gomichela-Litro', 12, 'michelada con chamoy y gomitas mexicanas', 'michelada with chamoy & mexican gummies', 'michelada com chamoy e gomas mexicanas'],
             ]],
            ['slug' => 'cerveza-de-barril', 'section' => 'drinks', 'style' => 'brush-yellow', 'icon' => '🍺',
             'name' => ['Cerveza de barril', 'Draft Beer', 'Cerveja de pressão'],
             'items' => [
                ['Small', 2, '20 cl', '20 cl', '20 cl'],
                ['Big', 4.50, '50 cl', '50 cl', '50 cl'],
             ]],
            ['slug' => 'experiencias', 'section' => 'drinks', 'style' => 'box-black', 'icon' => '✨', 'featured' => true,
             'name' => ['Experiencias', 'Experiences', 'Experiências'],
             'items' => [
                ['Tequila Experience', 15, '5 shots de nuestro mejor tequila + sangrita (tomate, cítricos y chile)', '5 shots of our best tequila + sangrita (tomato citrus & chilli shot)', '5 shots da nossa melhor tequila + sangrita (tomate, citrinos e malagueta)'],
                ['Agave Experience', 20, '5 shots de nuestro mejor mezcal y tequila + Tajín, limón y sal', '5 shots of our best mezcal & tequila + Tajín, lime and salt', '5 shots do nosso melhor mezcal e tequila + Tajín, lima e sal'],
             ]],
            ['slug' => 'whisky', 'section' => 'drinks', 'style' => 'tab-red', 'icon' => '🥃',
             'name' => ["Whisky · Buchanan's 12 años", "Whisky · Buchanan's 12 years", "Whisky · Buchanan's 12 anos"],
             'items' => [
                ['On the Rocks', 10],
                ['With Sparkling Water', 10],
                ['With Ginger Ale', 10],
             ]],
            ['slug' => 'shots', 'section' => 'drinks', 'style' => 'brush-yellow', 'icon' => '🥃', 'labels' => ['25 ml', '50 ml'],
             'name' => ['Los shots', 'Shots', 'Shots'],
             'items' => [
                ['Tequila Cuervo Blanco', 2.50, 'tequila blanco clásico', 'classic silver tequila', 'tequila branca clássica', [], 4],
                ['Tequila Cuervo Reposado', 2.50, 'tequila añejado de final suave', 'aged tequila with a smooth finish', 'tequila envelhecida de final suave', [], 4],
                ['Tequila Don Julio Blanco', 7, 'tequila premium', 'premium tequila', 'tequila premium', [], 10],
                ['Mezcal Cómplice', 4, 'mezcal ahumado, intenso y auténtico', 'smoky mezcal, bold and authentic', 'mezcal fumado, intenso e autêntico', [], 7.50],
                ['Mezcal Recuerdo', 3, 'mezcal tradicional de Oaxaca', 'traditional mezcal from Oaxaca', 'mezcal tradicional de Oaxaca', [], 5],
                ['Ron Blanco', 2.50, 'ron blanco', 'white rum', 'rum branco', [], 3.50],
                ['Vodka Tamarindo', 2.50, 'vodka sabor tamarindo', 'tamarind flavored vodka', 'vodka de tamarindo', [], 3.50],
             ]],
            ['slug' => 'los-cocteles', 'section' => 'drinks', 'style' => 'brush-yellow', 'icon' => '🍹',
             'name' => ['Los cócteles', 'Cocktails', 'Os cocktails'],
             'items' => []],
            ['slug' => 'cocteles-vodka', 'parent' => 'los-cocteles', 'section' => 'drinks', 'style' => 'title-red', 'icon' => '🍹',
             'name' => ['Vodka', 'Vodka', 'Vodka'],
             'items' => [
                ['Tamarindo', 7, 'vodka de tamarindo, chamoy, limón y soda de limón. Escarchado con Tajín', 'tamarind vodka, chamoy, lime & lemon soda. Rimmed with Tajín', 'vodka de tamarindo, chamoy, lima e soda de limão. Copo com Tajín'],
                ['Vodkarita', 9, 'vodka de tamarindo y limón, estilo margarita. Escarchado con Tajín', 'tamarind vodka & lime, margarita-style. Rimmed with Tajín', 'vodka de tamarindo e lima, estilo margarita. Copo com Tajín'],
                ['Mango Tamarindo', 8, 'vodka de tamarindo, mango, chamoy y limón. Escarchado con chamoy y Miguelito', 'tamarind vodka, mango, chamoy & lime. Rimmed with chamoy & Miguelito', 'vodka de tamarindo, manga, chamoy e lima. Copo com chamoy e Miguelito'],
             ]],
            ['slug' => 'cocteles-ron', 'parent' => 'los-cocteles', 'section' => 'drinks', 'style' => 'title-red', 'icon' => '🍹',
             'name' => ['Ron', 'Rum', 'Rum'],
             'items' => [
                ['Cuba Libre', 6, 'ron, cola y limón', 'rum, cola & lime', 'rum, cola e lima'],
                ['Ginger Rum', 7, 'ginger ale y ron', 'ginger ale & rum', 'ginger ale e rum'],
                ['Daiquiri', 7, 'ron y limón con un toque dulce. Servido frío', 'rum, lime with a sweet touch. Served chilled', 'rum e lima com um toque doce. Servido fresco'],
             ]],
            ['slug' => 'cocteles-tequila', 'parent' => 'los-cocteles', 'section' => 'drinks', 'style' => 'title-red', 'icon' => '🍹',
             'name' => ['Tequila', 'Tequila', 'Tequila'],
             'items' => [
                ['Margarita Clásica', 8, 'la favorita de todos, versión clásica de limón. Escarchada con sal', "everyone's favorite cocktail, in its classic lemon version. Salt-rimmed", 'a favorita de todos, versão clássica de limão. Copo com sal'],
                ['Margarita de Mango', 9, 'tequila, mango, limón y jarabe natural. Frozen, con chamoy, Miguelito y Tajín', 'tequila, mango, lime & natural syrup. Frozen, rimmed with chamoy, Miguelito & Tajín', 'tequila, manga, lima e xarope natural. Frozen, com chamoy, Miguelito e Tajín'],
                ['Paloma', 7, 'tequila con cítricos', 'tequila with citrics', 'tequila com citrinos'],
                ['Charro Negro', 7.50, 'tequila, cola y limón', 'tequila, coke & lime', 'tequila, cola e lima'],
             ]],
            ['slug' => 'cocteles-mezcal', 'parent' => 'los-cocteles', 'section' => 'drinks', 'style' => 'title-red', 'icon' => '🍹',
             'name' => ['Mezcal · Mezcalitas', 'Mezcal · Mezcalitas', 'Mezcal · Mezcalitas'],
             'items' => [
                ['Limón', 9.50, 'mezcal y limón', 'mezcal & lime', 'mezcal e lima'],
                ['Mango', 9.50, 'mezcal, mango y limón', 'mezcal, mango & lime', 'mezcal, manga e lima'],
                ['Jamaica', 9.50, 'mezcal, jamaica y limón', 'mezcal, hibiscus & lime', 'mezcal, hibisco e lima'],
                ['Horchata', 10, 'mezcal con arroz, canela y vainilla', 'mezcal with rice, cinnamon & vanilla', 'mezcal com arroz, canela e baunilha'],
             ]],
            ['slug' => 'aguas-frescas', 'section' => 'drinks', 'style' => 'brush-aqua', 'icon' => '🥤', 'labels' => ['250 ml', '450 ml'],
             'name' => ['las aguas frescas', 'aguas frescas', 'as águas frescas'],
             'items' => [
                ['Jamaica', 3, 'hibisco', 'hibiscus', 'hibisco', [], 4],
                ['Horchata', 3, $n, $n, $n, [], 4],
                ['Lime & Chía', 3, 'limón y chía', $n, 'lima e chia', [], 4],
             ]],
            ['slug' => 'refrescos', 'section' => 'drinks', 'style' => 'brush-aqua', 'icon' => '🧃',
             'name' => ['los refrescos', 'soft drinks', 'os refrigerantes'],
             'items' => [
                ['Coca-Cola', 2.50, '33 cl', '33 cl', '33 cl'],
                ['Coca-Cola Zero', 2.50, '33 cl', '33 cl', '33 cl'],
                ['Water Bottle', 2, 'agua · 50 cl', '50 cl', 'água · 50 cl'],
                ['Prepared Sparkling Water', 4, 'agua mineral preparada · 50 cl', '50 cl', 'água com gás preparada · 50 cl'],
             ]],
        ];
    }
}
