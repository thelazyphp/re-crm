<?php

use App\CrmObject;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Uri;
use App\Utils\Formatters\Realt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

define('LOGIN', 'bugrealt');
define('PASSWORD', 'B80297358779b');
define('DELAY', 10); // 10 секунд
define('CONTRACT_CLOSE_INTERVAL', 'P14D'); // 14 дней
define('DEFAULT_HOUSE_TOTAL_AREA', 150.0);
define('DEFAULT_HOUSE_NUMBER', '1');
define('DEFAULT_FLAT_STREET', 'Советская ул.');
define('DEFAULT_HOUSE_OFFICE_STREET', 'Центральная ул.');

$http = new Client([
    'base_uri' => 'https://realt.by',
]);

$token = '';
$userId = null;

/****************************************************************************************************/
/* Авторизация на realt.by **************************************************************************/
/****************************************************************************************************/
try {
    $res = $http->post('/api/login/?login='.LOGIN.'&password='.PASSWORD);
    $data = json_decode((string) $res->getBody());

    $token = $data->token;
    $userId = (int) $data->user_id;
} catch (Throwable $e) {
    // Вторая попытка авторизоваться на realt.by
    try {
        sleep(DELAY);

        $res = $http->post('/api/login/?login='.LOGIN.'&password='.PASSWORD);
        $data = json_decode((string) $res->getBody());

        $token = $data->token;
        $userId = (int) $data->user_id;
    } catch (Throwable $e) { Log::channel('bots_realt')->critical('Login error!'); exit(-1); }
}

/****************************************************************************************************/
/* Удаление неактивных объявлений из базы realt.by **************************************************/
/****************************************************************************************************/
foreach ([/* 'rent.apartments-long-term', */ 'sale.apartments', 'sale.cottages', 'commercial'] as $type) {
    // Получаем список объявлений из базы realt.by
    try {
        $res = $http->get("/api/ads/list/?ad_type={$type}", ['headers' => ['X-Realt-Token' => $token]]);
    } catch (Throwable $e) { Log::channel('bots_realt')->error("Error getting [{$type}] ads from realt.by account!"); continue; }

    $data = json_decode((string) $res->getBody());

    foreach ($data as $ad) {
        // Получаем ID объявления в базе CRM
        $crm_id = $ad->crm_id;

        // Если ID объявления в базе CRM указан,
        // значит объявление было размещено через API
        // (данную проверку можно пропустить, если необходимо удалять объявления, размещенные как через API, так и вручную)
        if ($crm_id) {
            $code = $ad->realt->code; // Получаем ID объявления в базе realt.by
            $crmObject = CrmObject::find($crm_id); // Получаем объявление из базы CRM

            // Если объявление существует в базе CRM и неактивно, удаляем его из базы realt.by
            if ($crmObject && $crmObject->disabled) {
                try {
                    $http->delete("/api/ads/{$code}/?code_type=realt", ['headers' => ['X-Realt-Token' => $token]]);

                    echo "<pre>";
                    echo "CRM object [{$crm_id} (lot {$crmObject->lot})] is successfully deleted."; echo "</pre>";

                    Log::channel('bots_realt')->info("CRM object [{$crm_id} (lot {$crmObject->lot})] is successfully deleted.");
                } catch (Throwable $e) { Log::channel('bots_realt')->error("Error deleting CRM object [{$crm_id} (lot {$crmObject->lot})]!"); }
            }
        }
    }
}

/****************************************************************************************************/
/* Размещение активных объявлений на realt.by *******************************************************/
/****************************************************************************************************/

// Получаем все активные объявления из базы CRM
$crmObjects = CrmObject::where('disabled', false)
    ->whereIn('lot', ['400167'])
    ->get();

foreach ($crmObjects as $crmObject) {
    // Если объявление уже размещено на realt.by
    // и не модифицировано в базе CRM, пропускаем его
    if (
        $crmObject->realt_published_at
        && $crmObject->realt_published_at > $crmObject->updated_at
    ) {
        continue;
    }

    Log::channel('bots_realt')->info("Posting CRM object [{$crmObject->id} (lot {$crmObject->lot})]...");

    // Получаем ссылку на страницу и фотографии объявления с сайта bugrealt.by
    try {
        $siteData = json_decode(
            file_get_contents("https://bugrealt.by/local/api/getProduct/{$crmObject->lot}")
        );

        $url = $siteData->link;
        $images = $siteData->images;
        $manager = $siteData->manager;
    } catch (Throwable $e) { Log::channel('bots_realt')->error("Error getting link and images of CRM object [{$crmObject->id} (lot {$crmObject->lot})]!"); continue; }

    // Дата открытия и закрытия договора
    $open = new DateTime();
    $close = (new DateTime())->add(new DateInterval(CONTRACT_CLOSE_INTERVAL));

    // Формируем тело запроса на размещение объявления:
    $data = [
        'ad_type' => $crmObject->category == 'flat'
            ? ($crmObject->vid == 'АРЕНДА' ? 'rent.apartments-long-term' : 'sale.apartments') : ($crmObject->category == 'house' ? 'sale.cottages' : 'commercial'),

        'crm_id' => $crmObject->id,
        'user_id' => $userId,

        'contract' => [
            'number' => substr($crmObject->lot, 0, 30),
            'open' => $open->format('Y-m-d'),
            'close' => $close->format('Y-m-d'),
        ],

        'contacts' => [
            'name' => $crmObject->manager ? mb_substr($crmObject->manager->name, 0, 30) : null,
            'call_time' => [9, 18],
            'email' => $crmObject->manager ? substr($crmObject->manager->email, 0, 50) : null,
        ],

        'url' => $url,
        'title' => mb_substr($crmObject->title, 0, 100),
    ];

    if ($crmObject->description) {
        $description = $crmObject->description;

        // Если в описании к объявлению указана ссылка на youtube,
        // удаляем ее из описания и добавляем в тело запроса на размещение
        if (preg_match('/youtube_([_A-Za-z0-9\-]+)_youtube/', $description, $matches)) {
            $data['video'] = substr("https://www.youtube.com/watch?v={$matches[1]}", 0, 100);
            $description = preg_replace('/youtube_[_A-Za-z0-9\-]+_youtube/', '', $description);
        }

        // Указываем номер лота в начале описания для удобного поиска объявления на площадках
        $data['comments'] = mb_substr("{$crmObject->lot}. ".$description, 0, 500);
        $data['headline'] = mb_substr("{$crmObject->lot}. ".$description, 0, 500);

        $baseUrl = (string) (new Uri($url))->withPath('');

        // Формируем HTML-описание с указанием номера лота
        // в начале описания для удобного поиска объявления на площадках
        $html = "<p>№ {$crmObject->lot}</p>";
        $html .= "<p>{$description}</p>";
        $html .= "<p><a href='{$baseUrl}' target='_blank'>Приглашаем дружить с «Бугриэлт»</a></p>";
        $html .= "<p><a href='{$manager}' target='_blank'>Ваш покупатель ещё не пришёл к вам? Бесплатная консультация здесь.</a></p>";

        if ($manager == 'https://minsk.bugrealt.by/sotrudniki/18988/') {
            $html .= "<p><a href='https://t.me/ProNedvizimostMinsk' target='_blank'>Заходи в Телеграм канал.</a></p>";
        }

        $data['description'] = mb_substr($html, 0, 5000);
    }

    // Добавляем телефоны продавца
    if ($crmObject->manager && count($crmObject->manager->phones)) {
        $data['contacts']['phone1'] = substr($crmObject->manager->phones[0], 0, 12);

        // Добавляем второй телефон продавца, если указан
        if (isset($crmObject->manager->phones[1])) {
            $data['contacts']['phone2'] = substr($crmObject->manager->phones[1], 0, 12);
        }
    }

    // Проверяем, является ли объект новостройкой
    if (
        $crmObject->newflat
        || ($crmObject->year_built && $crmObject->year_built >= ((int) date('Y') - 2))
    ) {
        $data['extra_info'][] = 30; // новостройка
    }

    // Название области
    if ($crmObject->jandexGeolocation['province']) {
        $data['address']['region'] = mb_substr($crmObject->jandexGeolocation['province'], 0, 20);
    }

    // Название района
    if ($crmObject->jandexGeolocation['area']) {
        // Иногда продавцы указывают название района вместо названия нас. пункта.
        // Если это так, то при отсутствии названия района в данных геокодера jandex, передаем тот, который указан продавцами
        if (
            $crmObject->city
            && mb_strpos($crmObject->jandexGeolocation['area'], 'район') === false
            && (mb_strpos($crmObject->city, 'район') !== false || mb_strpos($crmObject->city, 'р-н') !== false)
        ) {
            $data['address']['district'] = mb_substr(str_replace([' р-н', ' район'], '', $crmObject->city), 0, 20);
        } else {
            $data['address']['district'] = mb_substr(str_replace([' р-н', ' район'], '', $crmObject->jandexGeolocation['area']), 0, 20);
        }
    }

    // Название нас. пункта
    if ($crmObject->jandexGeolocation['locality']) {
        // Префиксы названий нас. пунктов, возвращаемые геокодером jandex
        $prefixes = [
            'СТ ',
            'хутор ',
            'городской посёлок ',
            'рабочий посёлок ',
            'посёлок ',
            'деревня ',
            'агрогородок ',
            'садоводческое товарищество ',
            'садовое товарищество ',
        ];

        // На realt.by необходимо передавать только названия нас. пунктов (без префиксов)
        $data['address']['town'] = mb_substr(str_replace($prefixes, '', $crmObject->jandexGeolocation['locality']), 0, 50);
    }

    // Название улицы
    if ($crmObject->jandexGeolocation['street']) {
        if ($crmObject->jandexGeolocation['street'] == 'улица Жукова') {
            $data['address']['street'] = mb_substr('Жукова ул.', 0, 50);
        } else if ($crmObject->jandexGeolocation['street'] == 'переулок Жукова') {
            $data['address']['street'] = mb_substr('Жукова пер.', 0, 50);
        } else if ($crmObject->jandexGeolocation['street'] == 'улица Лейтенанта Рябцева') {
            $data['address']['street'] = mb_substr('Лейтенанта Рябцева ул.', 0, 50);
        } else {
            $data['address']['street'] = mb_substr($crmObject->jandexGeolocation['street'], 0, 50);
        }
    } else {
        // Название улицы по умолчанию
        if ($crmObject->category == 'flat') {
            $data['address']['street'] = mb_substr(DEFAULT_FLAT_STREET, 0, 50);
        } else {
            $data['address']['street'] = mb_substr(DEFAULT_HOUSE_OFFICE_STREET, 0, 50);
        }
    }

    // Номер дома
    if ($crmObject->num_home) {
        // Указанный продавцами номер дома более приоритетный
        $data['address']['house'] = $crmObject->num_home;
    } else if ($crmObject->jandexGeolocation['house']) {
        $data['address']['house'] = $crmObject->jandexGeolocation['house'];
    } else {
        // Номер дома по умолчанию
        if ($crmObject->category == 'house') {
            $data['address']['house'] = DEFAULT_HOUSE_NUMBER;
        }
    }

    // Географические координаты
    if (count($crmObject->gps_coordinats) > 1) {
        $data['location'] = [
            'lat' => (string) round($crmObject->gps_coordinats[0], 6),
            'lng' => (string) round($crmObject->gps_coordinats[1], 6),
        ];
    } else {
        $data['location'] = [
            'lat' => (string) round($crmObject->jandexGeolocation['lat'], 6),
            'lng' => (string) round($crmObject->jandexGeolocation['long'], 6),
        ];
    }

    // Добавляем фотографии объявления
    foreach ($images as $image) {
        $data['photos'][] = ['url' => substr($image, 0, 500)];
    }

    // Если фотографий больше одной,
    // то назначаем первую из них заглавной
    if (isset($data['photos']) && count($data['photos']) > 1) {
        $data['photos'][0]['primary'] = 1;
    }

    $count = count($images);
    $i = $count - 1;

    for (; $i > 0; $i--) {
        $data['photos'][$i]['priority'] = $count - $i - 1;
    }

    if ($crmObject->category == 'flat') {
        $data['rooms'] = ($crmObject->type == 'к' || $crmObject->type == 'К' || $crmObject->rooms == 'к' || $crmObject->rooms == 'К') ? 0 : $crmObject->rooms;
        $data['separate_rooms'] = ($crmObject->type == 'к' || $crmObject->type == 'К' || $crmObject->rooms == 'к' || $crmObject->rooms == 'К') ? 1 : $crmObject->rooms;
        $data['building_year'] = $crmObject->year_built;
        $data['storey'] = $crmObject->floor_apartment;
        $data['storeys'] = $crmObject->number_of_floors;
        $data['house_type'] = Realt::houseType($crmObject);
        $data['layout'] = 'ст/пр'; // стандартный проект

        $data['area'] = [
            'total' => $crmObject->area ? $crmObject->area : $crmObject->area_snb,
            'living' => $crmObject->living_space,
            'kitchen' => $crmObject->kitchen_area,
            'snb' => $crmObject->area_snb,
        ];

        $data['lavatory'] = Realt::lavatory($crmObject);
        $data['balcony'] = Realt::balcony($crmObject);
        $data['repair_state'] = 'хор.'; // хороший ремонт
        $data['owner'] = 'гп'; // гос.-приватизированная
        $data['terms'] = 'ч'; // чистая продажа

        $data['price'] = [
            'amount' => $crmObject->price_now !== null ? $crmObject->price_now : $crmObject->price,
            'currency' => 'USD',
        ];
    } else if ($crmObject->category == 'house') {
        $data['object_type'] = Realt::cottagesObjectType($crmObject);

        $data['building'] = [
            'levels' => $crmObject->number_of_floors,
            'year' => $crmObject->year_built,
        ];

        $data['area'] = [
            'living' => $crmObject->living_space,
            'kitchen' => $crmObject->kitchen_area,
        ];

        if ($crmObject->land_area) {
            // Площадь участка указана в сотках
            if ($crmObject->land_area > 1.0) {
                $data['area']['ground'] = $crmObject->land_area;
            } else {
                // Площадь участка указана в гектарах
                $data['area']['ground'] = $crmObject->land_area * 100.0;
            }
        }

        if ($data['object_type'] != 'уч.') {
            if ($crmObject->area) {
                $data['area']['total'] = $crmObject->area;
            } else if ($crmObject->area_snb) {
                if ($crmObject->area_snb <= 250.0) {
                    $data['area']['total'] = $crmObject->area_snb;
                } else {
                    $data['area']['total'] = $crmObject->area_snb - 50.0;
                }
            } else {
                $data['area']['total'] = DEFAULT_HOUSE_TOTAL_AREA;
            }
        }

        if ($wallsMaterial = Realt::cottagesWallsMaterial($crmObject)) {
            $data['walls_material'] = $wallsMaterial;
        }

        $data['owner'] = 'с'; // собственность
        $data['terms'] = 'ч'; // чистая продажа

        $data['price'] = [
            'amount' => $crmObject->price_now !== null ? $crmObject->price_now : $crmObject->price,
            'currency' => 'USD',
        ];
    } else if ($crmObject->category == 'office') {
        $data['object_type'] = Realt::commercialObjectType($crmObject);
        $data['terms'] = ($crmObject->vid == 'АРЕНДА' || $crmObject->transaction == 'аренда') ? 'а' : 'пр'; // аренда / продажа

        if ($crmObject->land_area) {
            // Площадь участка указана в сотках
            if ($crmObject->land_area > 1.0) {
                $data['area']['ground'] = $crmObject->land_area;
            } else {
                // Площадь участка указана в гектарах
                $data['area']['ground'] = $crmObject->land_area * 100.0;
            }
        }

        if ($crmObject->area || $crmObject->area_snb) {
            $data['area']['min'] = $crmObject->area ? $crmObject->area : $crmObject->area_snb;
        }

        $data['storey'] = $crmObject->floor_apartment;
        $data['storeys'] = $crmObject->number_of_floors;
        $data['rooms_min'] = $crmObject->rooms;

        if ($wallsMaterial = Realt::commercialWallsMaterial($crmObject)) {
            $data['walls_material'] = $wallsMaterial;
        }

        $data['building_year'] = $crmObject->year_built;

        $data['price'] = [
            'amount_min' => $crmObject->price_now !== null ? $crmObject->price_now : $crmObject->price,
            'currency' => 'USD',
            'nds' => 2, // НДС не включен
        ];

        if ($crmObject->price_per_sqm) {
            $data['price']['m2_min'] = (float) str_replace(',', '.', $crmObject->price_per_sqm);
        }
    }

    echo '<pre>';
    echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); echo '</pre>';

    // Делаем запрос на размещение объявления
    try {
        $res = $http->post(
            '/api/ads/', ['headers' => ['X-Realt-Token' => $token], 'json' => $data]
        );

        // Помечаем что объявление размещено на realt.by
        DB::table('crm_objects')
            ->where('id', $crmObject->id)
            ->update(['realt_published_at' => date($crmObject->getDateFormat())]);

        Log::channel('bots_realt')->info("CRM object [{$crmObject->id} (lot {$crmObject->lot})] is successfully posted.");
    } catch (Throwable $e) {
        $messages = [];

        // Получаем все критические ошибки размещения
        if ($e->hasResponse()) {
            $data = json_decode((string) $e->getResponse()->getBody());

            if (isset($data->errors->critical)) {
                foreach ($data->errors->critical as $error) {
                    $messages[] = $error;
                }
            }

            echo '<pre>';
            echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); echo '</pre>';
        }

        if (empty($messages)) {
            Log::channel('bots_realt')->error("Undefined error posting CRM object [{$crmObject->id} (lot {$crmObject->lot})]!");
        } else {
            $messages = implode('. ', $messages);
            Log::channel('bots_realt')->error("Error posting CRM object [{$crmObject->id} (lot {$crmObject->lot})]: {$messages}!");
        }

        continue;
    }

    $data = json_decode((string) $res->getBody());

    echo '<pre>';
    echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); echo '</pre>'.PHP_EOL.PHP_EOL;

    sleep(DELAY);
}
