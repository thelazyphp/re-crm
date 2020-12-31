<?php

namespace App\Utils\Formatters;

use App\CrmObject;

class Realt
{
    const DEFAULT_COTTAGES_OBJECT_TYPE = 'дом'; // дом
    const DEFAULT_COMMERCIAL_OBJECT_TYPE = 'помещение'; // Помещение
    const DEFAULT_HOUSE_TYPE = 'п'; // панельный
    const DEFAULT_LAVATORY = 'р'; // раздельный

    /**
     * @param  \App\CrmObject  $crmObject
     * @return string
     */
    public static function cottagesObjectType(CrmObject $crmObject)
    {
        switch ($crmObject->type) {
            case 'Дача':
                // дача
                return 'дача';
            case 'Дачный участок':
                // участок
                return 'уч.';
            case 'Жилой дом':
                // дом
                return 'дом';
            case 'Земельный участок':
                // участок
                return 'уч.';
            case 'Коробка':
                // дом
                return 'дом';
            case 'Хутор':
                // дача
                return 'дача';
            case 'Часть дома':
                // полдома
                return 'п.д.';
            default:
                return self::DEFAULT_COTTAGES_OBJECT_TYPE;
        }
    }

    /**
     * @param  \App\CrmObject  $crmObject
     * @return string
     */
    public static function commercialObjectType(CrmObject $crmObject)
    {
        switch ($crmObject->type) {
            case 'Административное':
                // Офис
                return 'офис';
            case 'Иное':
                return self::DEFAULT_COMMERCIAL_OBJECT_TYPE;
            case 'Машино-место':
                // Машиноместо
                return 'машиноместо';
            case 'Производственно-складское помещение':
                // Производство
                return 'производство';
            case 'Торгово-офисное помещение':
                // Торговое помещение
                return 'торговое помещение';
            default:
                return self::DEFAULT_COMMERCIAL_OBJECT_TYPE;
        }
    }

    /**
     * @param  \App\CrmObject  $crmObject
     * @return string
     */
    public static function houseType(CrmObject $crmObject)
    {
        switch ($crmObject->wall_material) {
            case 'блочные':
                // блок-комнаты
                return 'б';
            case 'деревянные':
                // бревенчатый
                return 'бр';
            case 'ж/б каркас с заполнением г/с блоками':
                // каркасно-блочный
                return 'кб';
            case 'ж/б панели':
                // панельный
                return 'п';
            case 'железобетонные плиты':
                // панельный
                return 'п';
            case 'каркасные':
                // панельный
                return 'п';
            case 'керамзитобетоные блоки':
                // блок-комнаты
                return 'б';
            case 'кирпичные':
                // кирпичный
                return 'к';
            case 'монолитные':
                // монолитный
                return 'м';
            case 'монолитно-каркасные':
                // монолитный
                return 'м';
            case 'каркасно-блочные':
                // каркасно-блочный
                return 'кб';
            case 'панельные':
                // панельный
                return 'п';
            case 'Сборно-щитовые':
                // блок-комнаты
                return 'б';
            case 'Сэндвич-панели':
                // панельный
                return 'п';
            case 'шлакобетонные':
                // блок-комнаты
                return 'б';
            default:
                return self::DEFAULT_HOUSE_TYPE;
        }
    }

    /**
     * @param  \App\CrmObject  $crmObject
     * @return string
     */
    public static function cottagesWallsMaterial(CrmObject $crmObject)
    {
        switch ($crmObject->wall_material) {
            case 'блочные':
                // блочный
                return 'б';
            case 'деревянные':
                // дерево
                return 'д';
            case 'ж/б каркас с заполнением г/с блоками':
                // блок газосиликатный
                return 'гс';
            case 'ж/б панели':
                // панельный
                return 'п';
            case 'железобетонные плиты':
                // панельный
                return 'п';
            case 'каркасные':
                // каркасно-засыпной
                return 'к/з';
            case 'керамзитобетоные блоки':
                // керамзитбетон
                return 'кб';
            case 'кирпичные':
                // кирпич
                return 'к';
            case 'монолитные':
                // монолитный
                return 'м';
            case 'монолитно-каркасные':
                // монолитный
                return 'м';
            case 'каркасно-блочные':
                // каркасно-блочный
                return 'кб';
            case 'панельные':
                // панельный
                return 'п';
            case 'Сборно-щитовые':
                // сборно-щитовой
                return 'щит';
            case 'Сэндвич-панели':
                // панельный
                return 'п';
            case 'шлакобетонные':
                // шлакобетон
                return 'шб';
            default:
                return null;
        }
    }

    /**
     * @param  \App\CrmObject  $crmObject
     * @return string
     */
    public static function commercialWallsMaterial(CrmObject $crmObject)
    {
        switch ($crmObject->wall_material) {
            case 'блочные':
                // блочный
                return 'б';
            case 'деревянные':
                return null;
            case 'ж/б каркас с заполнением г/с блоками':
                // блочный
                return 'б';
            case 'ж/б панели':
                // панельный
                return 'п';
            case 'железобетонные плиты':
                return null;
            case 'каркасные':
                return null;
            case 'керамзитобетоные блоки':
                // блочный
                return 'б';
            case 'кирпичные':
                // кирпичный
                return 'к';
            case 'монолитные':
                // монолитный
                return 'м';
            case 'монолитно-каркасные':
                // монолитный
                return 'м';
            case 'каркасно-блочные':
                // каркасно-блочный
                return 'кб';
            case 'панельные':
                // панельный
                return 'п';
            case 'Сборно-щитовые':
                return null;
            case 'Сэндвич-панели':
                // панельный
                return 'п';
            case 'шлакобетонные':
                return null;
            default:
                return null;
        }
    }

    /**
     * @param  \App\CrmObject  $crmObject
     * @return string
     */
    public static function lavatory(CrmObject $crmObject)
    {
        switch ($crmObject->bathroom) {
            case '2-санузла':
                // 2 сан.узла
                return '2с/у';
            case '3-санузла':
                // 3 сан.узла
                return '3с/у';
            case '4-санузла':
                // 4 сан.узла
                return '4с/у';
            case '7-санузла':
                // 4 сан.узла
                return '4с/у';
            case 'нет':
                return self::DEFAULT_LAVATORY;
            case 'нет санузла':
                return self::DEFAULT_LAVATORY;
            case 'раздельный':
                // раздельный
                return 'p';
            case 'Санузел на этаже':
                // раздельный
                return 'p';
            case 'совмещенный':
                // совмещенный
                return 'c';
            default:
                return self::DEFAULT_LAVATORY;
        }
    }

    /**
     * @param  \App\CrmObject  $crmObject
     * @return string
     */
    public static function balcony(CrmObject $crmObject)
    {
        switch ($crmObject->balcony) {
            case '2 лоджии, 2 балкона':
                // 2 лоджии
                return '2л';
            case '2-балкона':
                // 2 балкона
                return '2б';
            case '3 балкона':
                // 3 балкона
                return '3б';
            case '3-лоджии':
                // 3 лоджии
                return '3л';
            case '4 лоджии':
                // 3 лоджии
                return '3л';
            case 'балкон':
                // балкон
                return 'б';
            case 'балкон застеклен':
                // балкон застекленный
                return 'бз';
            case 'балкон и лоджия':
                // балкон и лоджия
                return 'б+л';
            case 'без балкона.':
                // нет
                return '-';
            case 'лоджии 2-е':
                // 2 лоджии
                return '2л';
            case 'лоджия':
                // лоджия
                return 'л';
            case 'лоджия 5м':
                // лоджия
                return 'л';
            case 'лоджия застекл.':
                // лоджия застекленная
                return 'лз';
            case 'лоджия из кухни':
                // лоджия из кухни
                return 'лк';
            case 'нет':
                // нет
                return '-';
            case 'терасса':
                // терраса
                return 'т';
            case 'французкий':
                // балкон
                return 'б';
            default:
                // нет
                return '-';
        }
    }
}
