<?php

namespace App;

use App\Models\Address;
use App\Models\AddressComponent;
use App\Models\YandexGeocoderCache;

class YandexGeocoder
{
    /**
     * @param  string  $geocode
     * @param  string|null  $kind
     * @return object
     */
    public static function request($geocode, $kind = null)
    {
        $cache = YandexGeocoderCache::where('geocode', $geocode)->where('kind', $kind)->first();

        if (! $cache) {
            $apiKey = config('services.yandex.default_api_key');

            $cache = YandexGeocoderCache::create([
                'geocode' => $geocode,
                'kind' => $kind,

                'response' => json_decode(
                    file_get_contents(
                        'https://geocode-maps.yandex.ru/1.x?format=json&apikey='
                            .$apiKey
                            .'&geocode='
                            .rawurlencode($geocode)
                            .($kind ? '&kind='.$kind : '')
                    )
                ),
            ]);
        }

        return $cache->response;
    }

    /**
     * @param  string  $geocode
     * @param  string|null  $kind
     * @return \App\Models\Address|null
     */
    public function searchAddress($geocode, $kind = null): ?Address
    {
        $response = $this->request($geocode, $kind);

        if (
            ! $response
                ->response
                ->GeoObjectCollection
                ->metaDataProperty
                ->GeocoderResponseMetaData
                ->found
        ) {
            return null;
        }

        $geoObject = $response
            ->response
            ->GeoObjectCollection
            ->featureMember[0]
            ->GeoObject;

        $geocoderMetaData = $geoObject->metaDataProperty->GeocoderMetaData;

        $address = new Address([
            'full_address' => $geocoderMetaData->text,
        ]);

        $components = [];

        foreach ($geocoderMetaData->Address->Components as $component) {
            // Пропускаем названия сельсоветов.
            if (
                $component->kind == 'area'
                && mb_strpos($component->name, 'район') === false
            ) {
                continue;
            }

            $components[$component->kind] = $component->name;
        }

        // Если найден крупный нас. пункт,
        // то геокодер не всегда возвращает его район.
        // В таком случае,
        // присваеваем названию района название самого нас. пункта.
        if (
            ! isset($components['area'])
            && isset($components['locality'])
        ) {
            $components['area'] = $components['locality'];
        }

        [$lng, $lat] = explode(' ', $geoObject->Point->pos, 2);

        $address->lat = $lat;
        $address->lng = $lng;

        // Делаем дополнительный запрос к геокодеру
        // для нахождения ближайщей к дому или улице линии и станции метро.
        if (
            isset($components['locality'])
            && (isset($components['house']) || isset($components['street']))
            && ! isset($components['metro'])
            && in_array($components['locality'], ['Минск'])
        ) {
            $response = $this->request($lng.','.$lat, 'metro');

            if (
                $response
                    ->response
                    ->GeoObjectCollection
                    ->metaDataProperty
                    ->GeocoderResponseMetaData
                    ->found
            ) {
                foreach (
                    $response
                        ->response
                        ->GeoObjectCollection
                        ->featureMember[0]
                        ->GeoObject
                        ->metaDataProperty
                        ->GeocoderMetaData
                        ->Address
                        ->Components as $component
                ) {
                    if (in_array($component->kind, ['route', 'metro'])) {
                        $components[$component->kind] = $component->name;
                    }
                }
            }
        }

        foreach ([
            'country',
            'province',
            'area',
            'locality',
            'district',
            'route',
            'metro',
            'street',
            'house',
            'entrance'] as $kind
        ) {
            if (isset($components[$kind])) {
                $attributes = [
                    'kind' => $kind,
                    'name' => $components[$kind],
                    'country_id' => $address->country_id,
                    'province_id' => $address->province_id,
                    'area_id' => $address->area_id,
                    'locality_id' => $address->locality_id,
                    'district_id' => $address->district_id,
                    'route_id' => $address->route_id,
                    'metro_id' => $address->metro_id,
                    'street_id' => $address->street_id,
                    'house_id' => $address->house_id,
                    'entrance_id' => $address->entrance_id,
                ];

                $address->{$kind.'_id'} = AddressComponent::firstOrCreate($attributes)->id;
            }
        }

        return $address;
    }
}
