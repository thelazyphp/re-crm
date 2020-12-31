<?php echo '<?xml version="1.0" encoding="utf-8"?>' ?>

<realty-feed xmlns="http://webmaster.yandex.ru/schemas/feed/realty/2010-06">
    <generation-date>{{ (new DateTime())->format('c') }}</generation-date>

    @yield('offers')
</realty-feed>
