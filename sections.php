<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */

$this->setFrameMode(true);
$this->addExternalCss("/bitrix/css/main/bootstrap.css");

$dbBasketItems = CSaleBasket::GetList(
    array(
        "NAME" => "ASC",
        "ID" => "ASC"
    ),
    array(
        "FUSER_ID" => CSaleBasket::GetBasketUserID(),
        "LID" => SITE_ID,
        "ORDER_ID" => "NULL",
        "DELAY" => "Y",
    ),
    false,
    false,
    array("PRODUCT_ID")
);

$inwishlist = [];
if($dbBasketItems->arResult){
    foreach ($dbBasketItems->arResult as $value){
        $inwishlist[] = $value['PRODUCT_ID'];
    }
}

?>

    <div class="col-xl-2 col-lg-12 col-md-12 col-sm-12 order-lg-2 order-xl-1 no-padding-col wow slideInLeft">

        <?

        if(empty($arResult["VARIABLES"]["SMART_FILTER_PATH"])){
            $re = '/^\/.*\/filter\/(.*)\/apply\//';
            $str = Bitrix\Main\Context::getCurrent()->getRequest()->getRequestedPage();
            preg_match($re, $str, $matches);
            $arResult["VARIABLES"]["SMART_FILTER_PATH"] =$matches[1];
        }


        $APPLICATION->IncludeComponent(
            "bitrix:catalog.smart.filter",
            "tb_smart_filter",
            array(
                "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
                "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                "SECTION_ID" => 0,
                "FILTER_NAME" => $arParams["FILTER_NAME"],
                "PRICE_CODE" => $arParams["PRICE_CODE"],
                "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                "CACHE_TIME" => $arParams["CACHE_TIME"],
                "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
                "SAVE_IN_SESSION" => "N",
                "FILTER_VIEW_MODE" => $arParams["FILTER_VIEW_MODE"],
                "SECTION_TITLE" => "NAME",
                "SECTION_DESCRIPTION" => "DESCRIPTION",
                'HIDE_NOT_AVAILABLE' => $arParams["HIDE_NOT_AVAILABLE"],
                "TEMPLATE_THEME" => $arParams["TEMPLATE_THEME"],
                'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
                'CURRENCY_ID' => $arParams['CURRENCY_ID'],
                "SEF_MODE" => $arParams["SEF_MODE"],
                "SEF_RULE" => "/catalog/filter/#SMART_FILTER_PATH#/apply/",
//                "SMART_FILTER_PATH" => $_REQUEST["SMART_FILTER_PATH"],
                "SMART_FILTER_PATH" => $arResult["VARIABLES"]["SMART_FILTER_PATH"],
                "PAGER_PARAMS_NAME" => $arParams["PAGER_PARAMS_NAME"],
                "INSTANT_RELOAD" => $arParams["INSTANT_RELOAD"],
                "SHOW_ALL_WO_SECTION" => "Y"
            ),
            $component,
            array('HIDE_ICONS' => 'Y')
        );; ?>

    </div>
    <div class="col-xl-10 col-lg-12 col-md-12 col-sm-12 order-lg-1 order-xl-2 no-padding-col wow slideInLeft">
        <div class="row p-0 m-0">
            <?

//            $APPLICATION->IncludeComponent("bitrix:catalog.section", "td_design_cataloggg", Array(
//                "ACTION_VARIABLE" => "action",	// Название переменной, в которой передается действие
//                "ADD_PICT_PROP" => "-",	// Дополнительная картинка основного товара
//                "ADD_PROPERTIES_TO_BASKET" => "Y",	// Добавлять в корзину свойства товаров и предложений
//                "ADD_SECTIONS_CHAIN" => "N",	// Включать раздел в цепочку навигации
//                "ADD_TO_BASKET_ACTION" => "ADD",	// Показывать кнопку добавления в корзину или покупки
//                "AJAX_MODE" => "N",	// Включить режим AJAX
//                "AJAX_OPTION_ADDITIONAL" => "",	// Дополнительный идентификатор
//                "AJAX_OPTION_HISTORY" => "N",	// Включить эмуляцию навигации браузера
//                "AJAX_OPTION_JUMP" => "N",	// Включить прокрутку к началу компонента
//                "AJAX_OPTION_STYLE" => "Y",	// Включить подгрузку стилей
//                "BACKGROUND_IMAGE" => "-",	// Установить фоновую картинку для шаблона из свойства
//                "BASKET_URL" => "/personal/basket.php",	// URL, ведущий на страницу с корзиной покупателя
//                "BROWSER_TITLE" => "-",	// Установить заголовок окна браузера из свойства
//                "CACHE_FILTER" => "N",	// Кешировать при установленном фильтре
//                "CACHE_GROUPS" => "Y",	// Учитывать права доступа
//                "CACHE_TIME" => "36000000",	// Время кеширования (сек.)
//                "CACHE_TYPE" => "A",	// Тип кеширования
//                "COMPATIBLE_MODE" => "Y",	// Включить режим совместимости
//                "COMPONENT_TEMPLATE" => ".default",
//                "CONVERT_CURRENCY" => "N",	// Показывать цены в одной валюте
//                "CUSTOM_FILTER" => "",
//                "DETAIL_URL" => "",	// URL, ведущий на страницу с содержимым элемента раздела
//                "DISABLE_INIT_JS_IN_COMPONENT" => "N",	// Не подключать js-библиотеки в компоненте
//                "DISPLAY_BOTTOM_PAGER" => "Y",	// Выводить под списком
//                "DISPLAY_COMPARE" => "N",	// Разрешить сравнение товаров
//                "DISPLAY_TOP_PAGER" => "N",	// Выводить над списком
//                "ELEMENT_SORT_FIELD" => "sort",	// По какому полю сортируем элементы
//                "ELEMENT_SORT_FIELD2" => "id",	// Поле для второй сортировки элементов
//                "ELEMENT_SORT_ORDER" => "asc",	// Порядок сортировки элементов
//                "ELEMENT_SORT_ORDER2" => "desc",	// Порядок второй сортировки элементов
//                "ENLARGE_PRODUCT" => "STRICT",	// Выделять товары в списке
//                "FILTER_NAME" => "arrFilter",	// Имя массива со значениями фильтра для фильтрации элементов
//                "HIDE_NOT_AVAILABLE" => "N",	// Недоступные товары
//                "HIDE_NOT_AVAILABLE_OFFERS" => "N",	// Недоступные торговые предложения
//                "IBLOCK_ID" => "10",	// Инфоблок
//                "IBLOCK_TYPE" => "catalog",	// Тип инфоблока
//                "INCLUDE_SUBSECTIONS" => "Y",	// Показывать элементы подразделов раздела
//                "LABEL_PROP" => "",	// Свойства меток товара
//                "LAZY_LOAD" => "N",	// Показать кнопку ленивой загрузки Lazy Load
//                "LINE_ELEMENT_COUNT" => "3",	// Количество элементов выводимых в одной строке таблицы
//                "LOAD_ON_SCROLL" => "N",	// Подгружать товары при прокрутке до конца
//                "MESSAGE_404" => "",	// Сообщение для показа (по умолчанию из компонента)
//                "MESS_BTN_ADD_TO_BASKET" => "В корзину",	// Текст кнопки "Добавить в корзину"
//                "MESS_BTN_BUY" => "Купить",	// Текст кнопки "Купить"
//                "MESS_BTN_DETAIL" => "Подробнее",	// Текст кнопки "Подробнее"
//                "MESS_BTN_SUBSCRIBE" => "Подписаться",	// Текст кнопки "Уведомить о поступлении"
//                "MESS_NOT_AVAILABLE" => "Нет в наличии",	// Сообщение об отсутствии товара
//                "META_DESCRIPTION" => "-",	// Установить описание страницы из свойства
//                "META_KEYWORDS" => "-",	// Установить ключевые слова страницы из свойства
//                "OFFERS_CART_PROPERTIES" => "",	// Свойства предложений, добавляемые в корзину
//                "OFFERS_FIELD_CODE" => array(	// Поля предложений
//                    0 => "",
//                    1 => "",
//                ),
//                "OFFERS_LIMIT" => "0",	// Максимальное количество предложений для показа (0 - все)
//                "OFFERS_PROPERTY_CODE" => array(	// Свойства предложений
//                    0 => "",
//                    1 => "",
//                ),
//                "OFFERS_SORT_FIELD" => "sort",	// По какому полю сортируем предложения товара
//                "OFFERS_SORT_FIELD2" => "id",	// Поле для второй сортировки предложений товара
//                "OFFERS_SORT_ORDER" => "asc",	// Порядок сортировки предложений товара
//                "OFFERS_SORT_ORDER2" => "desc",	// Порядок второй сортировки предложений товара
//                "PAGER_BASE_LINK_ENABLE" => "N",	// Включить обработку ссылок
//                "PAGER_DESC_NUMBERING" => "N",	// Использовать обратную навигацию
//                "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",	// Время кеширования страниц для обратной навигации
//                "PAGER_SHOW_ALL" => "N",	// Показывать ссылку "Все"
//                "PAGER_SHOW_ALWAYS" => "N",	// Выводить всегда
//                "PAGER_TEMPLATE" => ".default",	// Шаблон постраничной навигации
//                "PAGER_TITLE" => "Товары",	// Название категорий
//                "PAGE_ELEMENT_COUNT" => "12",	// Количество элементов на странице
//                "PARTIAL_PRODUCT_PROPERTIES" => "N",	// Разрешить добавлять в корзину товары, у которых заполнены не все характеристики
//                "PRICE_CODE" => "",	// Тип цены
//                "PRICE_VAT_INCLUDE" => "Y",	// Включать НДС в цену
//                "PRODUCT_BLOCKS_ORDER" => "price,props,sku,quantityLimit,quantity,buttons",	// Порядок отображения блоков товара
//                "PRODUCT_DISPLAY_MODE" => "N",	// Схема отображения
//                "PRODUCT_ID_VARIABLE" => "id",	// Название переменной, в которой передается код товара для покупки
//                "PRODUCT_PROPERTIES" => "",	// Характеристики товара
//                "PRODUCT_PROPS_VARIABLE" => "prop",	// Название переменной, в которой передаются характеристики товара
//                "PRODUCT_QUANTITY_VARIABLE" => "quantity",	// Название переменной, в которой передается количество товара
//                "PRODUCT_ROW_VARIANTS" => "[{'VARIANT':'3','BIG_DATA':false},{'VARIANT':'3','BIG_DATA':false},{'VARIANT':'3','BIG_DATA':false}]",	// Вариант отображения товаров
//                "PRODUCT_SUBSCRIPTION" => "Y",	// Разрешить оповещения для отсутствующих товаров
//                "PROPERTY_CODE" => array(	// Свойства
//                    0 => "",
//                    1 => "",
//                ),
//                "PROPERTY_CODE_MOBILE" => "",	// Свойства товаров, отображаемые на мобильных устройствах
//                "RCM_PROD_ID" => $_REQUEST["PRODUCT_ID"],	// Параметр ID продукта (для товарных рекомендаций)
//                "RCM_TYPE" => "personal",	// Тип рекомендации
//                "SECTION_CODE" => "",	// Код раздела
//                "SECTION_ID" => "",	// ID раздела
//                "SECTION_ID_VARIABLE" => "SECTION_ID",	// Название переменной, в которой передается код группы
//                "SECTION_URL" => "",	// URL, ведущий на страницу с содержимым раздела
//                "SECTION_USER_FIELDS" => array(	// Свойства раздела
//                    0 => "",
//                    1 => "",
//                ),
//                "SEF_MODE" => "N",	// Включить поддержку ЧПУ
//                "SET_BROWSER_TITLE" => "Y",	// Устанавливать заголовок окна браузера
//                "SET_LAST_MODIFIED" => "N",	// Устанавливать в заголовках ответа время модификации страницы
//                "SET_META_DESCRIPTION" => "Y",	// Устанавливать описание страницы
//                "SET_META_KEYWORDS" => "Y",	// Устанавливать ключевые слова страницы
//                "SET_STATUS_404" => "N",	// Устанавливать статус 404
//                "SET_TITLE" => "Y",	// Устанавливать заголовок страницы
//                "SHOW_404" => "N",	// Показ специальной страницы
//                "SHOW_ALL_WO_SECTION" => "Y",	// Показывать все элементы, если не указан раздел
//                "SHOW_CLOSE_POPUP" => "N",	// Показывать кнопку продолжения покупок во всплывающих окнах
//                "SHOW_DISCOUNT_PERCENT" => "N",	// Показывать процент скидки
//                "SHOW_FROM_SECTION" => "N",	// Показывать товары из раздела
//                "SHOW_MAX_QUANTITY" => "N",	// Показывать остаток товара
//                "SHOW_OLD_PRICE" => "N",	// Показывать старую цену
//                "SHOW_PRICE_COUNT" => "1",	// Выводить цены для количества
//                "SHOW_SLIDER" => "Y",	// Показывать слайдер для товаров
//                "SLIDER_INTERVAL" => "3000",	// Интервал смены слайдов, мс
//                "SLIDER_PROGRESS" => "N",	// Показывать полосу прогресса
//                "TEMPLATE_THEME" => "blue",	// Цветовая тема
//                "USE_ENHANCED_ECOMMERCE" => "N",	// Отправлять данные электронной торговли в Google и Яндекс
//                "USE_MAIN_ELEMENT_SECTION" => "N",	// Использовать основной раздел для показа элемента
//                "USE_PRICE_COUNT" => "N",	// Использовать вывод цен с диапазонами
//                "USE_PRODUCT_QUANTITY" => "N",	// Разрешить указание количества товара
//            ),
//                false
//            );


            $all_element_list = [];
            $arSelect = Array();
            $arFilter = Array("IBLOCK_ID" => IntVal($arParams["IBLOCK_ID"]), "ACTIVE_DATE" => "Y", "ACTIVE" => "Y");
            $res = CIBlockElement::GetList(Array("NAME" => "ASC"), $arFilter, false, Array("nPageSize" => 12), $arSelect);

            while ($ob = $res->GetNextElement()) {
                $all_element = '';

                $all_element = $ob->GetFields();

                $db_props = CIBlockElement::GetProperty($all_element['IBLOCK_ID'], $all_element['ID'], array("sort" => "asc"), Array("CODE" => "MINIMUM_PRICE"));
                if ($ar_props = $db_props->Fetch()) {
                    $all_element['PRICE'] = $ar_props["VALUE"];
                }

                $res1 = CIBlockElement::GetProperty($all_element['IBLOCK_ID'], $all_element['ID']);
                if ($property = $res1->GetNext()) {
                    $arOrder = Array();
                    $arSelect = Array();
                    $arFilter = Array(
                        "IBLOCK_ID" => $property['LINK_IBLOCK_ID'],
                        "ACTIVE_DATE" => "Y",
                        "ACTIVE" => "Y",
                        "ID" => $property["VALUE"]
                    );

                    $resqq = CIBlockElement::GetList($arOrder, $arFilter, false, Array(), $arSelect);

                    $pictManuf = '';
                    $nameManuf = '';
                    while ($obqq = $resqq->GetNext()) {
                        $FILE = CFile::GetFileArray($obqq['DETAIL_PICTURE']);
                        $pictManuf = $FILE["SRC"];
                        $nameManuf = $obqq["NAME"];
                    }
                }

                $all_element['MANUFACTURER_PICTURE'] = $pictManuf;
                $all_element['MANUFACTURER_NAME'] = $nameManuf;

                $all_element_list[] = $all_element;
            }

            //echo '<pre>';
            //print_r($all_element_list);
            //echo '</pre>';

            foreach ($all_element_list as $value => $item) { ?>

                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 p-0">
                    <div class="product-item transition-3s wow zoomIn" data-wow-delay="0.1s">

                        <!-- Иконка бренда -->
                        <a href="/category.php" class="product-item-brand-icon">
                            <img src="<?= $item['MANUFACTURER_PICTURE'] ?>">
                            <!---svg><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#niche_icon"></use></svg--->
                        </a>

                        <?
                        $classActiveVsbl = '';
                        $classNoActiveVsbl = ' style="display:none"';

                        if(in_array($item["ID"], $inwishlist)){
                            $classActiveVsbl =' style="display:none"';
                            $classNoActiveVsbl = '';
                        }

                        ?>

                        <div href="javascript:void(0)"  class="product-item-like like-unactive transition bt-reboot wishbtn" <?=$classActiveVsbl?>
                             onclick="add2wish(
                                     '<?=$item["ID"]?>',
                                     '<?=$item["PRICE"]?>',
                                     '<?=$item["PRICE"]?>',
                                     '<?=$item["NAME"]?>',
                                     '<?=$item["DETAIL_PAGE_URL"]?>',
                                     this)">
                            <svg>
                                <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#like"></use>
                            </svg>
                        </div>

                        <div href="javascript:void(0)"  class="product-item-like like-active transition bt-reboot wishbtn in_wishlist" <?=$classNoActiveVsbl?>
                             onclick="rm2wish(
                                     '<?=$item["ID"]?>',
                                     '<?=$item["PRICE"]?>',
                                     '<?=$item["PRICE"]?>',
                                     '<?=$item["NAME"]?>',
                                     '<?=$item["DETAIL_PAGE_URL"]?>',
                                     this)">
                            <svg>
                                <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#like"></use>
                            </svg>
                        </div>

                        <!-- Фото товара -->
                        <div class="product-item-image-block mx-auto"><a href="<?= $item['DETAIL_PAGE_URL'] ?>">
                                <?
                                if ($item['DETAIL_PICTURE']) { ?>
                                    <img class="product-item-image transition-3s"
                                         src="<?= CFile::GetPath($item["DETAIL_PICTURE"]) ?>" alt="">
                                <? } else { ?>
                                    <img class="product-item-image transition-3s"
                                         src="/bitrix/templates/td_design/images/no_photo.png" alt="">
                                <? } ?>
                            </a>
                        </div>

                        <!-- Название и цена -->
                        <div class="product-item-bottom">

                            <!-- Название товара -->
                            <a href="<?= $item['DETAIL_PAGE_URL'] ?>" class="product-item-name"><span
                                        class="b-b"><?= $item['MANUFACTURER_NAME'] ?> <?= $item['NAME'] ?></span></a>

                            <!-- Стоимость товара -->
                            <div class="product-item-price"><?= $item['PRICE'] ?></div>

                        </div>

                        <!-- Все размеры -->
                        <a href="<?= $item['DETAIL_PAGE_URL'] ?>#wheels_size" class="product-item-all">
                            <div class="product-item-all-container">
                                <span class="wheels-icon"><svg><use xmlns:xlink="http://www.w3.org/1999/xlink"
                                                                    xlink:href="#wheels-icon-2"></use></svg></span>
                                <span class="product-item-all-name b-b-2">Все типоразмеры</span>
                            </div>
                        </a>

                    </div>
                </div>

            <?
            }
            //
            //?>
        </div>
    </div>

<?
//$APPLICATION->IncludeComponent(
//	"bitrix:catalog.section.list",
//	"",
//	array(
//		"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
//		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
//		"CACHE_TYPE" => $arParams["CACHE_TYPE"],
//		"CACHE_TIME" => $arParams["CACHE_TIME"],
//		"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
//		"COUNT_ELEMENTS" => $arParams["SECTION_COUNT_ELEMENTS"],
//		"TOP_DEPTH" => $arParams["SECTION_TOP_DEPTH"],
//		"SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
//		"VIEW_MODE" => $arParams["SECTIONS_VIEW_MODE"],
//		"SHOW_PARENT_NAME" => $arParams["SECTIONS_SHOW_PARENT_NAME"],
//		"HIDE_SECTION_NAME" => (isset($arParams["SECTIONS_HIDE_SECTION_NAME"]) ? $arParams["SECTIONS_HIDE_SECTION_NAME"] : "N"),
//		"ADD_SECTIONS_CHAIN" => (isset($arParams["ADD_SECTIONS_CHAIN"]) ? $arParams["ADD_SECTIONS_CHAIN"] : '')
//	),
//	$component,
//	($arParams["SHOW_TOP_ELEMENTS"] !== "N" ? array("HIDE_ICONS" => "Y") : array())
//);


/*if ($arParams["USE_COMPARE"] === "Y")
{
	$APPLICATION->IncludeComponent(
		"bitrix:catalog.compare.list",
		"",
		array(
			"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
			"IBLOCK_ID" => $arParams["IBLOCK_ID"],
			"NAME" => $arParams["COMPARE_NAME"],
			"DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["element"],
			"COMPARE_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["compare"],
			"ACTION_VARIABLE" => (!empty($arParams["ACTION_VARIABLE"]) ? $arParams["ACTION_VARIABLE"] : "action"),
			"PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
			'POSITION_FIXED' => isset($arParams['COMPARE_POSITION_FIXED']) ? $arParams['COMPARE_POSITION_FIXED'] : '',
			'POSITION' => isset($arParams['COMPARE_POSITION']) ? $arParams['COMPARE_POSITION'] : ''
		),
		$component,
		array("HIDE_ICONS" => "Y")
	);
}*/

/*if ($arParams["SHOW_TOP_ELEMENTS"] !== "N")
{
	if (isset($arParams['USE_COMMON_SETTINGS_BASKET_POPUP']) && $arParams['USE_COMMON_SETTINGS_BASKET_POPUP'] === 'Y')
	{
		$basketAction = isset($arParams['COMMON_ADD_TO_BASKET_ACTION']) ? $arParams['COMMON_ADD_TO_BASKET_ACTION'] : '';
	}
	else
	{
		$basketAction = isset($arParams['TOP_ADD_TO_BASKET_ACTION']) ? $arParams['TOP_ADD_TO_BASKET_ACTION'] : '';
	}

	$APPLICATION->IncludeComponent(
		"bitrix:catalog.top",
		"",
		array(
			"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
			"IBLOCK_ID" => $arParams["IBLOCK_ID"],
			"ELEMENT_SORT_FIELD" => $arParams["TOP_ELEMENT_SORT_FIELD"],
			"ELEMENT_SORT_ORDER" => $arParams["TOP_ELEMENT_SORT_ORDER"],
			"ELEMENT_SORT_FIELD2" => $arParams["TOP_ELEMENT_SORT_FIELD2"],
			"ELEMENT_SORT_ORDER2" => $arParams["TOP_ELEMENT_SORT_ORDER2"],
			"SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
			"DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["element"],
			"BASKET_URL" => $arParams["BASKET_URL"],
			"ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
			"PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
			"PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
			"PRODUCT_PROPS_VARIABLE" => $arParams["PRODUCT_PROPS_VARIABLE"],
			"DISPLAY_COMPARE" => $arParams["USE_COMPARE"],
			"ELEMENT_COUNT" => $arParams["TOP_ELEMENT_COUNT"],
			"LINE_ELEMENT_COUNT" => $arParams["TOP_LINE_ELEMENT_COUNT"],
			"PROPERTY_CODE" => $arParams["TOP_PROPERTY_CODE"],
			"PROPERTY_CODE_MOBILE" => $arParams["TOP_PROPERTY_CODE_MOBILE"],
			"PRICE_CODE" => $arParams["~PRICE_CODE"],
			"USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
			"SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],
			"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
			"PRICE_VAT_SHOW_VALUE" => $arParams["PRICE_VAT_SHOW_VALUE"],
			"USE_PRODUCT_QUANTITY" => $arParams['USE_PRODUCT_QUANTITY'],
			"ADD_PROPERTIES_TO_BASKET" => (isset($arParams["ADD_PROPERTIES_TO_BASKET"]) ? $arParams["ADD_PROPERTIES_TO_BASKET"] : ''),
			"PARTIAL_PRODUCT_PROPERTIES" => (isset($arParams["PARTIAL_PRODUCT_PROPERTIES"]) ? $arParams["PARTIAL_PRODUCT_PROPERTIES"] : ''),
			"PRODUCT_PROPERTIES" => $arParams["PRODUCT_PROPERTIES"],
			"CACHE_TYPE" => $arParams["CACHE_TYPE"],
			"CACHE_TIME" => $arParams["CACHE_TIME"],
			"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
			"OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
			"OFFERS_FIELD_CODE" => $arParams["TOP_OFFERS_FIELD_CODE"],
			"OFFERS_PROPERTY_CODE" => $arParams["TOP_OFFERS_PROPERTY_CODE"],
			"OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
			"OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],
			"OFFERS_SORT_FIELD2" => $arParams["OFFERS_SORT_FIELD2"],
			"OFFERS_SORT_ORDER2" => $arParams["OFFERS_SORT_ORDER2"],
			"OFFERS_LIMIT" => $arParams["TOP_OFFERS_LIMIT"],
			'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
			'CURRENCY_ID' => $arParams['CURRENCY_ID'],
			'HIDE_NOT_AVAILABLE' => $arParams['HIDE_NOT_AVAILABLE'],
			'VIEW_MODE' => (isset($arParams['TOP_VIEW_MODE']) ? $arParams['TOP_VIEW_MODE'] : ''),
			'ROTATE_TIMER' => (isset($arParams['TOP_ROTATE_TIMER']) ? $arParams['TOP_ROTATE_TIMER'] : ''),
			'TEMPLATE_THEME' => (isset($arParams['TEMPLATE_THEME']) ? $arParams['TEMPLATE_THEME'] : ''),

			'LABEL_PROP' => $arParams['LABEL_PROP'],
			'LABEL_PROP_MOBILE' => $arParams['LABEL_PROP_MOBILE'],
			'LABEL_PROP_POSITION' => $arParams['LABEL_PROP_POSITION'],
			'ADD_PICT_PROP' => $arParams['ADD_PICT_PROP'],
			'PRODUCT_DISPLAY_MODE' => $arParams['PRODUCT_DISPLAY_MODE'],
			'PRODUCT_BLOCKS_ORDER' => $arParams['TOP_PRODUCT_BLOCKS_ORDER'],
			'PRODUCT_ROW_VARIANTS' => $arParams['TOP_PRODUCT_ROW_VARIANTS'],
			'ENLARGE_PRODUCT' => $arParams['TOP_ENLARGE_PRODUCT'],
			'ENLARGE_PROP' => isset($arParams['TOP_ENLARGE_PROP']) ? $arParams['TOP_ENLARGE_PROP'] : '',
			'SHOW_SLIDER' => $arParams['TOP_SHOW_SLIDER'],
			'SLIDER_INTERVAL' => isset($arParams['TOP_SLIDER_INTERVAL']) ? $arParams['TOP_SLIDER_INTERVAL'] : '',
			'SLIDER_PROGRESS' => isset($arParams['TOP_SLIDER_PROGRESS']) ? $arParams['TOP_SLIDER_PROGRESS'] : '',

			'OFFER_ADD_PICT_PROP' => $arParams['OFFER_ADD_PICT_PROP'],
			'OFFER_TREE_PROPS' => $arParams['OFFER_TREE_PROPS'],
			'PRODUCT_SUBSCRIPTION' => $arParams['PRODUCT_SUBSCRIPTION'],
			'SHOW_DISCOUNT_PERCENT' => $arParams['SHOW_DISCOUNT_PERCENT'],
			'DISCOUNT_PERCENT_POSITION' => $arParams['DISCOUNT_PERCENT_POSITION'],
			'SHOW_OLD_PRICE' => $arParams['SHOW_OLD_PRICE'],
			'MESS_BTN_BUY' => $arParams['~MESS_BTN_BUY'],
			'MESS_BTN_ADD_TO_BASKET' => $arParams['~MESS_BTN_ADD_TO_BASKET'],
			'MESS_BTN_SUBSCRIBE' => $arParams['~MESS_BTN_SUBSCRIBE'],
			'MESS_BTN_DETAIL' => $arParams['~MESS_BTN_DETAIL'],
			'MESS_NOT_AVAILABLE' => $arParams['~MESS_NOT_AVAILABLE'],
			'ADD_TO_BASKET_ACTION' => $basketAction,
			'SHOW_CLOSE_POPUP' => isset($arParams['COMMON_SHOW_CLOSE_POPUP']) ? $arParams['COMMON_SHOW_CLOSE_POPUP'] : '',
			'COMPARE_PATH' => $arResult['FOLDER'].$arResult['URL_TEMPLATES']['compare'],
			'USE_COMPARE_LIST' => 'Y',

			'COMPATIBLE_MODE' => (isset($arParams['COMPATIBLE_MODE']) ? $arParams['COMPATIBLE_MODE'] : '')
		),
		$component
	);
	unset($basketAction);
}*/