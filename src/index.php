<?php
require(__DIR__ . '/config/app.php');
$db = new mysqli(
    $config['db']['host'],
    $config['db']['user'],
    $config['db']['password'],
    $config['db']['database']
);

$res = $db->query("
    SELECT `prop`.`name`, `prop`.`id`, `group`.`ID` AS `groupId`, `group`.`name` AS `groupName`
    FROM   `prop`
    INNER  JOIN `prop_group` AS `group`
             ON `group`.`ID` = `prop`.`Group`
");

$filters = array_key_exists('filter', $_GET) ? $_GET['filter'] : [];
$propsFilter = array_key_exists('prop', $filters) ? $filters['prop'] : [];

$groups = [];
$props = [];
while ($prop = $res->fetch_assoc()) {
    $groupId = $prop['groupId'];
    $propId = $prop['id'];
    if (!array_key_exists($groupId, $groups)) {
        $groups[$groupId] = [
            'id' => $groupId,
            'name' => $prop['groupName'],
            'selected' => false,
            'props' => []
        ];
    }

    $prop['selected'] = array_key_exists($propId, $propsFilter);
    if ($prop['selected']) {
        $groups[$groupId]['selected'] = true;
    }
    $prop['available'] = false;
    $groups[$groupId]['props'][$propId] = $prop;
    $props[$propId] = &$groups[$groupId]['props'][$propId];
}

$where = '';
if (!empty($propsFilter)) {
    $selectedProps = [];
    $groupsCount = 0;
    foreach ($groups as $group) {
        $groupUsed = false;
        foreach ($group['props'] as $propId => $prop) {
            if ($prop['selected']) {
                $selectedProps[] = $propId;
                $groupUsed = true;
            }
        }
        if ($groupUsed) {
            $groupsCount += 1;
        }
    }

    if (!empty($selectedProps)) {
        $sqlFilter = '`prop`.`id` IN (' . implode(',', $selectedProps) . ')';

        $where = "WHERE `product`.`id` IN (
            SELECT DISTINCT `product`.`id`
            FROM   `product`
            LEFT   JOIN `product_prop_relation` AS `rel`
                    ON `rel`.`product` = `product`.`id`
            INNER  JOIN `prop`
                    ON `prop`.`id` = `rel`.`prop`
                    AND {$sqlFilter}
            GROUP    BY `product`.`id`
            HAVING      count(DISTINCT `prop`.`group`) = {$groupsCount}
        )";
    }
}

$res = $db->query("
    SELECT `product`.`id`, `product`.`name`, `prop`.`id` AS `propId`
    FROM   `product`
    LEFT   JOIN `product_prop_relation` AS `rel`
             ON `rel`.`product` = `product`.`id`
    INNER  JOIN `prop`
             ON `prop`.`ID` = `rel`.`prop`
    {$where}
");

$products = [];
while ($product = $res->fetch_assoc()) {
    $productId = $product['id'];
    $propId = $product['propId'];
    if (!array_key_exists($productId, $products)) {
        $products[$productId] = $product;
    }

    $props[$propId]['available'] = true;
    $products[$productId]['props'][] = $props[$propId];
}

$data = [
    'products' => $products,
    'groups' => $groups
];

require_once(__DIR__ . '/templates/app.php');
