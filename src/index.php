<?php
require(__DIR__ . '/config/app.php');
$db = new mysqli(
    $config['db']['host'],
    $config['db']['user'],
    $config['db']['password'],
    $config['db']['database']
);

$res = $db->query("
    SELECT `prop`.`Name`, `prop`.`ID`, `group`.`Name` AS `Group`
    FROM   `prop`
    INNER  JOIN `prop_group` AS `group`
             ON `group`.`ID` = `prop`.`Group`
    ORDER BY `group`.`Name`
");

$props = [];
while ($prop = $res->fetch_assoc()) {
    $props[$prop['ID']] = $prop;
}

$where = '';
$filterProps = [];
if (array_key_exists('filter', $_GET) && array_key_exists('prop', $_GET['filter'])) {
    foreach ($_GET['filter']['prop'] as $key => $value) {
        $filterProps[] = (int) $key;
    }
    $propsFilter = '`prop`.`ID` IN (' . implode(', ', $filterProps) . ')';
    $propsCount = count($filterProps);

    $where = "WHERE `product`.`ID` IN (
        SELECT `product`.`ID`
        FROM   `product`
        LEFT   JOIN `product_prop_relation` AS `rel`
                 ON `rel`.`Product` = `product`.`ID`
              INNER  JOIN `prop`
                 ON `prop`.`ID` = `rel`.`Prop`
                AND {$propsFilter}
        GROUP    BY `product`.`ID`
        HAVING   count(*) >= {$propsCount}
    )";
}

$res = $db->query("
    SELECT `product`.`ID`, `product`.`Name`, `prop`.`ID` AS `PropID`
    FROM   `product`
    LEFT   JOIN `product_prop_relation` AS `rel`
             ON `rel`.`Product` = `product`.`ID`
    INNER  JOIN `prop`
             ON `prop`.`ID` = `rel`.`Prop`
    {$where}
");

$data['Products'] = [];
while ($product = $res->fetch_assoc()) {
    $props[$product['PropID']]['Available'] = true;
    if (!array_key_exists($product['ID'], $data['Products'])) {
        $data['Products'][$product['ID']] = $product;
    }

    $data['Products'][$product['ID']]['Properties'][] = $props[$product['PropID']];
}

foreach ($filterProps as $checkedProp) {
    $props[$checkedProp]['Checked'] = true;
}

foreach($props as $prop) {
    $data['Groups'][$prop['Group']][] = $prop;
}

require_once(__DIR__ . '/templates/app.php');
