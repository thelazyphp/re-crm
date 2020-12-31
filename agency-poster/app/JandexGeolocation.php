<?php

namespace App;

use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;

class JandexGeolocation extends Model
{
    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'crm_object_id';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'crm_object_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'long' => 'float',
        'lat' => 'float',
    ];

    /**
     * @param  \App\CrmObject  $crmObject
     * @return void
     */
    public function findFor(CrmObject $crmObject)
    {
        $geocode = 'Беларусь';

        switch ($crmObject->region) {
            case 'Брест':
            case 'Пинск':
                $geocode .= ', Брестская область';
                break;
            case 'Минск':
                $geocode .= ', Минская область';
                break;
            case 'Гродно':
                $geocode .= ', Гродненская область';
        }

        if (
            $crmObject->city
            && (
                mb_strpos($crmObject->city, 'р-н') !== false
                || mb_strpos($crmObject->city, 'район') !== false
            )
        ) {
            $geocode .= ", {$crmObject->city}";
        }

        if (
            $crmObject->realcity
            && mb_strpos($crmObject->realcity, 'р-н') === false
            && mb_strpos($crmObject->realcity, 'район') === false
        ) {
            $city = trim(
                preg_replace(
                    ['/^\(\w+\)/u', '/(?:\w+\.)+$/u'],
                    '',
                    $crmObject->realcity
                )
            );

            $city = mb_strtoupper(
                mb_substr($city, 0, 1)
            ).mb_strtolower(
                mb_substr($city, 1)
            );

            $geocode .= ", {$city}";
        }

        if ($crmObject->microdistrict) {
            $geocode .= ", {$crmObject->microdistrict}";
        }

        if ($crmObject->street) {
            $geocode .= ", {$crmObject->street}";
        }

        if ($crmObject->num_home) {
            $geocode .= ", {$crmObject->num_home}";
        }

        Log::channel('jandex_geolocations')->info(
            ">>> geocoding location for CRM object {$crmObject->id} (lot {$crmObject->lot}): \"{$geocode}\"..."
        );

        // DEBUG:
        // return;

        $apikey = env('JANDEX_API_KEY');
        $geocode = rawurlencode($geocode);

        $data = json_decode(
            file_get_contents(
                "https://geocode-maps.yandex.ru/1.x?format=json&apikey={$apikey}&geocode={$geocode}"
            )
        );

        if (
            $data
                ->response
                ->GeoObjectCollection
                ->metaDataProperty
                ->GeocoderResponseMetaData
                ->found > 0
        ) {
            $geoObject = $data
                ->response
                ->GeoObjectCollection
                ->featureMember[0]
                ->GeoObject;

            $addressComponents = [];

            foreach (
                $geoObject
                    ->metaDataProperty
                    ->GeocoderMetaData
                    ->Address
                    ->Components as $component
            ) {
                if (
                    $component->kind == 'area'
                    && mb_strpos($component->name, 'район') === false
                ) {
                    continue;
                }

                $addressComponents[
                    $component->kind
                ] = $component->name;
            }

            [$long, $lat] = explode(
                ' ', $geoObject->Point->pos, 2
            );

            if (
                isset($addressComponents['locality'])
                && ! isset($addressComponents['area'])
            ) {
                $districts = json_decode(
                    file_get_contents(
                        'https://realt.by/api/ads/dictionary/?geo=districts'
                    )
                );

                foreach ($districts as $district) {
                    if (
                        mb_stripos(
                            $district->state_district_name,
                            mb_substr($addressComponents['locality'], 0, 4)
                        ) !== false
                    ) {
                        $addressComponents['area'] = "{$district->state_district_name} район";
                    }
                }
            }

            $this->lat = $lat;
            $this->long = $long;

            $geocoded = "[{$lat}, {$long}]";

            if (isset($addressComponents['country'])) {
                $this->country = $addressComponents['country'];
                $geocoded .= " {$addressComponents['country']}";
            }

            if (isset($addressComponents['province'])) {
                $this->province = $addressComponents['province'];
                $geocoded .= ", {$addressComponents['province']}";
            }

            if (isset($addressComponents['area'])) {
                $this->area = $addressComponents['area'];
                $geocoded .= ", {$addressComponents['area']}";
            }

            if (isset($addressComponents['locality'])) {
                $this->locality = $addressComponents['locality'];
                $geocoded .= ", {$addressComponents['locality']}";
            }

            if (isset($addressComponents['district'])) {
                $this->district = $addressComponents['district'];
                $geocoded .= ", {$addressComponents['district']}";
            }

            if (isset($addressComponents['street'])) {
                $this->street = $addressComponents['street'];
                $geocoded .= ", {$addressComponents['street']}";
            }

            if (isset($addressComponents['house'])) {
                $this->house = $addressComponents['house'];
                $geocoded .= ", {$addressComponents['house']}";
            }

            Log::channel('jandex_geolocations')->info(
                "<<< geocoded location for CRM object {$crmObject->id} (lot {$crmObject->lot}): \"{$geocoded}\"."
            );
        }
    }
}
