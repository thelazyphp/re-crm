@php
    use App\Utils\Formatters\Yrl;
    use Illuminate\Support\Facades\Log;
@endphp

@extends('stubs.feeds.layouts.yrl')

@section('offers')
    @foreach ($crmObjects as $crmObject)
        @php
            try {
                $siteData = json_decode(
                    file_get_contents("https://bugrealt.by/local/api/getProduct/{$crmObject->lot}")
                );

                $url = $siteData->link;
                $images = $siteData->images;
        @endphp

        @if (in_array($crmObject->category, ['flat', 'house']))
            {{-- Жилая недвижимость --}}
            <offer internal-id="{{ $crmObject->id }}">
                <type>{{ Yrl::type($crmObject) }}</type>
                <property-type>жилая</property-type>
                <category>{{ $category = Yrl::category($crmObject) }}</category>

                @if ($category == 'гараж')
                    <garage-type>{{ Yrl::garageType($crmObject) }}</garage-type>
                @endif

                <lot-number>{{ $crmObject->lot }}</lot-number>
                <url>{{ $url }}</url>
                <creation-date>{{ (new DateTime($crmObject->created_at))->format('c') }}</creation-date>

                <location>
                    @if ($crmObject->jandexGeolocation['country'])
                        <country>{{ $crmObject->jandexGeolocation['country'] }}</country>
                    @endif

                    @if ($crmObject->jandexGeolocation['province'])
                        <region>{{ $crmObject->jandexGeolocation['province'] }}</region>
                    @endif

                    @if ($crmObject->jandexGeolocation['area'])
                        @if ($crmObject->city && (mb_strpos($crmObject->city, 'район') !== false || mb_strpos($crmObject->city, 'р-н') !== false) && mb_strpos($crmObject->jandexGeolocation['area'], 'район') === false)
                            <district>{{ str_replace('район', 'р-н', $crmObject->city) }}</district>
                        @else
                            <district>{{ str_replace('район', 'р-н', $crmObject->jandexGeolocation['area']) }}</district>
                        @endif
                    @endif

                    @if ($crmObject->jandexGeolocation['locality'])
                        <locality-name>{{ $crmObject->jandexGeolocation['locality'] }}</locality-name>
                    @endif

                    @if ($crmObject->jandexGeolocation['district'])
                        <sub-locality-name>{{ $crmObject->jandexGeolocation['district'] }}</sub-locality-name>
                    @endif

                    @if ($crmObject->jandexGeolocation['street'])
                        <address>{{ $crmObject->jandexGeolocation['street'].($crmObject->jandexGeolocation['house'] ? ', '.$crmObject->jandexGeolocation['house'] : '') }}</address>
                    @endif

                    <latitude>{{ $crmObject->jandexGeolocation['lat'] }}</latitude>
                    <longitude>{{ $crmObject->jandexGeolocation['long'] }}</longitude>
                </location>

                <sales-agent>
                    @if ($crmObject->manager && $crmObject->manager->name)
                        <name>{{ $crmObject->manager->name }}</name>
                    @endif

                    @if ($crmObject->manager)
                        @foreach ($crmObject->manager->phones as $phone)
                            @if (substr($phone, 0, 3) == '375')
                                <phone>+{{ $phone }}</phone>
                            @endif
                        @endforeach
                    @endif

                    <category>агентство</category>
                    <organization>ООО "Бугриэлт"</organization>
                    <url>https://bugrealt.by/</url>

                    @if ($crmObject->manager && $crmObject->manager->email)
                        <email>{{ $crmObject->manager->email }}</email>
                    @endif

                    <photo>https://bugrealt.by/local/images/logo.png</photo>
                </sales-agent>

                @if ($crmObject->price)
                    <price>
                        <value>{{ $crmObject->pricenow ? $crmObject->pricenow : $crmObject->price }}</value>
                        <currency>USD</currency>
                    </price>
                @endif

                <deal-status>прямая продажа</deal-status>

                @if ($crmObject->area || $crmObject->area_snb)
                    <area>
                        @if ($crmObject->category == 'house')
                            @if ($crmObject->area)
                                <value>{{ $crmObject->area }}</value>
                            @elseif ($crmObject->area_snb <= 250.0)
                                <value>{{ $crmObject->area_snb }}</value>
                            @else
                                <value>{{ $crmObject->area_snb - 50.0 }}</value>
                            @endif
                        @else
                            <value>{{ $crmObject->area ? $crmObject->area : $crmObject->area_snb }}</value>
                        @endif

                        <unit>кв. м</unit>
                    </area>
                @endif

                @if ($crmObject->living_space)
                    <living-space>
                        <value>{{ $crmObject->living_space }}</value>
                        <unit>кв. м</unit>
                    </living-space>
                @endif

                @if ($crmObject->kitchen_area)
                    <kitchen-space>
                        <value>{{ $crmObject->kitchen_area }}</value>
                        <unit>кв. м</unit>
                    </kitchen-space>
                @endif

                @if ($crmObject->land_area)
                    <lot-area>
                        <value>{{ $crmObject->land_area > 1.0 ? $crmObject->land_area : ($crmObject->land_area * 100.0) }}</value>
                        <unit>cотка</unit>
                    </lot-area>
                @endif

                @foreach ($images as $image)
                    <image>{{ $image }}</image>
                @endforeach

                @if ($crmObject->description)
                    <description>{{ "Лот {$crmObject->lot}. ".preg_replace('/youtube_[_A-Za-z0-9\-]+_youtube/', '', $crmObject->description) }}</description>
                @endif

                @if ($crmObject->rooms)
                    <rooms>{{ $crmObject->rooms }}</rooms>
                    <rooms-offered>{{ $crmObject->rooms }}</rooms-offered>
                @endif

                @if ($crmObject->floor_apartment)
                    <floor>{{ $crmObject->floor_apartment }}</floor>
                @endif

                @if ($balcony = Yrl::balcony($crmObject))
                    <balcony>{{ $balcony }}</balcony>
                @endif

                @if ($bathroomUnit = Yrl::bathroomUnit($crmObject))
                    <bathroom-unit>{{ $bathroomUnit }}</bathroom-unit>
                @endif

                @if ($crmObject->number_of_floors)
                    <floors-total>{{ $crmObject->number_of_floors }}</floors-total>
                @endif

                @if ($buildingType = Yrl::buildingType($crmObject))
                    <building-type>{{ $buildingType }}</building-type>
                @endif

                @if ($crmObject->year_built)
                    <built-year>{{ $crmObject->year_built }}</built-year>
                @endif

                @if ($crmObject->electricity)
                    <electricity-supply>да</electricity-supply>
                @endif

                @if ($crmObject->water_supply)
                    <water-supply>да</water-supply>
                @endif

                @if ($crmObject->sewerage)
                    <sewerage-supply>да</sewerage-supply>
                @endif

                @if ($crmObject->heating)
                    <heating-supply>да</heating-supply>
                @endif
            </offer>
        @else
            {{-- Коммерческая недвижимость --}}
            <offer internal-id="{{ $crmObject->id }}">
                <type>{{ $type = Yrl::type($crmObject) }}</type>
                <category>коммерческая</category>
                <commercial-type>{{ Yrl::commercialType($crmObject) }}</commercial-type>
                <lot-number>{{ $crmObject->lot }}</lot-number>
                <url>{{ $url }}</url>
                <creation-date>{{ (new DateTime($crmObject->created_at))->format('c') }}</creation-date>

                <location>
                    @if ($crmObject->jandexGeolocation['country'])
                        <country>{{ $crmObject->jandexGeolocation['country'] }}</country>
                    @endif

                    @if ($crmObject->jandexGeolocation['province'])
                        <region>{{ $crmObject->jandexGeolocation['province'] }}</region>
                    @endif

                    @if ($crmObject->jandexGeolocation['area'])
                        @if ($crmObject->city && (mb_strpos($crmObject->city, 'район') !== false || mb_strpos($crmObject->city, 'р-н') !== false) && mb_strpos($crmObject->jandexGeolocation['area'], 'район') === false)
                            <district>{{ str_replace('район', 'р-н', $crmObject->city) }}</district>
                        @else
                            <district>{{ str_replace('район', 'р-н', $crmObject->jandexGeolocation['area']) }}</district>
                        @endif
                    @endif

                    @if ($crmObject->jandexGeolocation['locality'])
                        <locality-name>{{ $crmObject->jandexGeolocation['locality'] }}</locality-name>
                    @endif

                    @if ($crmObject->jandexGeolocation['district'])
                        <sub-locality-name>{{ $crmObject->jandexGeolocation['district'] }}</sub-locality-name>
                    @endif

                    @if ($crmObject->jandexGeolocation['street'])
                        <address>{{ $crmObject->jandexGeolocation['street'].($crmObject->jandexGeolocation['house'] ? ', '.$crmObject->jandexGeolocation['house'] : '') }}</address>
                    @endif

                    <latitude>{{ $crmObject->jandexGeolocation['lat'] }}</latitude>
                    <longitude>{{ $crmObject->jandexGeolocation['long'] }}</longitude>
                </location>

                <sales-agent>
                    @if ($crmObject->manager && $crmObject->manager->name)
                        <name>{{ $crmObject->manager->name }}</name>
                    @endif

                    @if ($crmObject->manager)
                        @foreach ($crmObject->manager->phones as $phone)
                            @if (substr($phone, 0, 3) == '375')
                                <phone>+{{ $phone }}</phone>
                            @endif
                        @endforeach
                    @endif

                    <category>агентство</category>
                    <organization>ООО "Бугриэлт"</organization>
                    <url>https://bugrealt.by/</url>

                    @if ($crmObject->manager && $crmObject->manager->email)
                        <email>{{ $crmObject->manager->email }}</email>
                    @endif

                    <photo>https://bugrealt.by/local/images/logo.png</photo>
                </sales-agent>

                @if ($type == 'аренда' && $crmObject->price_per_sqm)
                    <price>
                        <value>{{ (float) str_replace(',', '.', $crmObject->price_per_sqm) }}</value>
                        <currency>USD</currency>
                        <unit>кв. м</unit>
                    </price>
                @elseif ($crmObject->price)
                    <price>
                        <value>{{ $crmObject->pricenow ? $crmObject->pricenow : $crmObject->price }}</value>
                        <currency>USD</currency>
                    </price>
                @endif

                @if ($type == 'аренда')
                    <deal-status>direct rent</deal-status>
                @endif

                @if ($crmObject->area || $crmObject->area_snb)
                    <area>
                        <value>{{ $crmObject->area ? $crmObject->area : $crmObject->area_snb }}</value>
                        <unit>кв. м</unit>
                    </area>
                @endif

                @foreach ($images as $image)
                    <image>{{ $image }}</image>
                @endforeach

                @if ($crmObject->description)
                    <description>{{ "Лот {$crmObject->lot}. ".preg_replace('/youtube_[_A-Za-z0-9\-]+_youtube/', '', $crmObject->description) }}</description>
                @endif

                @if ($crmObject->rooms)
                    <rooms>{{ $crmObject->rooms }}</rooms>
                @endif

                @if ($crmObject->floor_apartment)
                    <floor>{{ $crmObject->floor_apartment }}</floor>
                @endif

                @if ($crmObject->water_supply)
                    <water-supply>да</water-supply>
                @endif

                @if ($crmObject->sewerage)
                    <sewerage-supply>да</sewerage-supply>
                @endif

                @if ($crmObject->electricity)
                    <electricity-supply>да</electricity-supply>
                @endif
            </offer>
        @endif

        @php
            } catch (Throwable $e) {
                Log::channel('feeds_byrealty')->error(
                    "Error generating offer {$crmObject->id} (lot {$crmObject->lot}): {$e->getMessage()}!"
                );
            }

            $feedGenerateStatus->increment('generated');
            $feedGenerateStatus->save();
        @endphp
    @endforeach
@endsection
