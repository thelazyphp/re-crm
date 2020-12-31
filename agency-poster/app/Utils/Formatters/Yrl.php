<?php

namespace App\Utils\Formatters;

use App\CrmObject;

/**
 * @see https://yandex.ru/support/realty/rules/content-requirements.html
 */
class Yrl
{
    const DEFAULT_TYPE = 'продажа';
    const DEFAULT_CATEGORY = 'квартира';
    const DEFAULT_COMMERICAL_TYPE = 'free purpose';
    const DEFAULT_GARAGE_TYPE = 'машиноместо';
    const DEFAULT_REGION = 'Брестская область';

    /**
     * @param  \App\CrmObject  $crmObject
     * @return string
     */
    public static function type(CrmObject $crmObject)
    {
        switch ($crmObject->transaction) {
            case 'аренда':
                return 'аренда';
            case 'продажа':
                return 'продажа';
            default:
                return self::DEFAULT_TYPE;
        }
    }

    /**
     * @param  \App\CrmObject  $crmObject
     * @return string
     */
    public static function category(CrmObject $crmObject)
    {
        if (is_numeric($crmObject->type)) {
            return 'квартира';
        }

        switch ($crmObject->type) {
            case '2к':
                return 'квартира';
            case 'Дача':
                return 'дача';
            case 'Дачный участок':
                return 'участок';
            case 'Жилой дом':
                return 'дом';
            case 'Земельный участок':
                return 'участок';
            case 'Иное':
                return self::DEFAULT_CATEGORY;
            case 'К':
                return 'дом';
            case 'Коробка':
                return 'дом';
            case 'Машино-место':
                return 'гараж';
            case 'Хутор':
                return 'дача';
            case 'Часть дома':
                return 'часть дома';
            default:
                return self::DEFAULT_CATEGORY;
        }
    }

    /**
     * @param  \App\CrmObject  $crmObject
     * @return string
     */
    public static function commercialType(CrmObject $crmObject)
    {
        switch ($crmObject->type) {
            case 'Административное':
                return 'legal address';
            case 'Производственно-складское помещение':
                return 'manufacturing';
            case 'СФЕРА УСЛУГ':
                return 'office';
            case 'Торгово-офисное помещение':
                return 'office';
            default:
                return self::DEFAULT_COMMERICAL_TYPE;
        }
    }

    /**
     * @param  \App\CrmObject  $crmObject
     * @return string
     */
    public static function garageType(CrmObject $crmObject)
    {
        return self::DEFAULT_GARAGE_TYPE;
    }

    /**
     * @param  \App\CrmObject  $crmObject
     * @return string|null
     */
    public static function balcony(CrmObject $crmObject)
    {
        switch ($crmObject->balcony) {
            case '2 лоджии, 2 балкона':
                return '2 лоджии';
            case '2-балкона':
                return '2 балкона';
            case '3 балкона':
                return '3 балкона';
            case '3-лоджии':
                return '3 лоджии';
            case '4 лоджии':
                return '4 лоджии';
            case 'балкон':
                return 'балкон';
            case 'балкон застеклен':
                return 'балкон';
            case 'балкон и лоджия':
                return 'балкон';
            case 'без балкона.':
                return null;
            case 'лоджии 2-е':
                return '2 лоджии';
            case 'лоджия':
                return 'лоджия';
            case 'лоджия 5м':
                return 'лоджия';
            case 'лоджия застекл.':
                return 'лоджия';
            case 'лоджия из кухни':
                return 'лоджия';
            case 'нет':
                return null;
            case 'терасса':
                return 'балкон';
            case 'французкий':
                return 'балкон';
            default:
                return null;
        }
    }

    /**
     * @param  \App\CrmObject  $crmObject
     * @return string|null
     */
    public static function bathroomUnit(CrmObject $crmObject)
    {
        switch ($crmObject->bathroom) {
            case '2-санузла':
                return '2';
            case '3-санузла':
                return '3';
            case '4-санузла':
                return '4';
            case '7-санузла':
                return '7';
            case 'нет':
                return null;
            case 'нет санузла':
                return null;
            case 'раздельный':
                return 'раздельный';
            case 'Санузел на этаже':
                return '1';
            case 'совмещенный':
                return 'совмещенный';
            default:
                return null;
        }
    }

    /**
     * @param  \App\CrmObject  $crmObject
     * @return string|null
     */
    public static function buildingType(CrmObject $crmObject)
    {
        switch ($crmObject->wall_material) {
            case 'блочные':
                return 'блочный';
            case 'деревянные':
                return 'деревянный';
            case 'ж/б каркас с заполнением г/с блоками':
                return 'блочный';
            case 'ж/б панели':
                return 'панельный';
            case 'железобетонные плиты':
                return 'панельный';
            case 'каркасные':
                return 'панельный';
            case 'керамзитобетоные блоки':
                return 'блочный';
            case 'кирпичные':
                return 'кирпичный';
            case 'монолитные':
                return 'монолит';
            case 'панельные':
                return 'панельный';
            case 'Сборно-щитовые':
                return 'блочный';
            case 'Сэндвич-панели':
                return 'панельный';
            case 'шлакобетонные':
                return 'блочный';
            default:
                return null;
        }
    }
}
