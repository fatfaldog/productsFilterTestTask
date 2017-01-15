<?php

Class Migration {
    const PRODUCTS = 10000;
    const PROPS = 100;
    const GROUPS = 10;

    private $db;

    /**
     * @param mysqli $db Connection to database
     */
    public function __construct($db)
    {
        $this->db = $db;
    }

    public function up()
    {
        $this->createProductTable();
        $this->createPropGroupTable();
        $this->createPropTable();
        $this->createProductPropRelationTable();
        $this->fillProducts();
        $this->fillPropGroups();
        $this->fillProps();
        $this->fillProductPropsRelation();
    }

    public function down()
    {
        $this->db->query('DROP TABLE `product_prop_relation`');
        $this->db->query('DROP TABLE `product`');
        $this->db->query('DROP TABLE `prop_group`');
        $this->db->query('DROP TABLE `prop`');
    }

    /**
     * Create table for products
     *
     * @return bool
     */
    private function createProductTable()
    {
        $sql = 'CREATE TABLE `product`(`ID` INT NOT NULL AUTO_INCREMENT, `Name` VARCHAR(256) NOT NULL, `Price` INT NOT NULL, PRIMARY KEY (`ID`)) ENGINE = InnoDB;';
        return $this->db->query($sql);
    }

    /**
     * Create table for groups of props
     *
     * @return bool
     */
    private function createPropGroupTable()
    {
        $sql = 'CREATE TABLE `prop_group`(`ID` INT NOT NULL AUTO_INCREMENT, `Name` VARCHAR(256), PRIMARY KEY (`ID`)) ENGINE = InnoDB;';
        return $this->db->query($sql);
    }

    /**
     * Create table for props of products
     *
     * @return bool
     */
    private function createPropTable()
    {
        $sql = 'CREATE TABLE `prop`(`ID` INT NOT NULL AUTO_INCREMENT, `Group` INT NOT NULL, `Name` VARCHAR(256) NOT NULL, PRIMARY KEY (`ID`)) ENGINE = InnoDB;';
        return $this->db->query($sql);
    }

    /**
     * Create table for relation between products and props
     *
     * @return bool
     */
    private function createProductPropRelationTable()
    {
        $sql = 'CREATE TABLE `product_prop_relation`(`ID` INT NOT NULL AUTO_INCREMENT, `Product` INT NOT NULL, `Prop` INT NOT NULL, PRIMARY KEY (`ID`)) ENGINE = InnoDB;';
        return $this->db->query($sql);
    }

    /**
     * Fill products with some data
     *
     * @return bool
     */
    private function fillProducts()
    {
        $products = [];
        for ($i = 1; $i <= self::PRODUCTS; $i++) {
            $name = "product_{$i}";
            $price = rand(100, 100000);
            $products[] = "('{$name}', {$price})";
        }

        $values = implode(',', $products);

        $sql = "INSERT INTO `product` (`Name`, `Price`) VALUES {$values}";
        return $this->db->query($sql);
    }

    /**
     * Fill props groups with some data
     *
     * @return bool
     */
    private function fillPropGroups()
    {
        $groups = [];
        for ($i = 1; $i <= self::GROUPS; $i++) {
            $name = "group_{$i}";
            $groups[] = "('{$name}')";
        }

        $values = implode(',', $groups);

        $sql = "INSERT INTO `prop_group` (`Name`) VALUES {$values}";
        return $this->db->query($sql);
    }

    /**
     * Fill props with some data
     *
     * @return bool
     */
    private function fillProps()
    {
        $props = [];
        for ($i = 1; $i <= self::PROPS; $i++) {
            $name = "prop_{$i}";
            $group = rand(1, self::GROUPS);
            $props[] = "({$group}, '{$name}')";
        }

        $values = implode(',', $props);

        $sql = "INSERT INTO `prop` (`Group`, `Name`) VALUES {$values}";
        return $this->db->query($sql);
    }

    /**
     * Generate relations between props and products
     *
     * @return bool
     */
    private function fillProductPropsRelation()
    {
        $relations = [];
        // Actually, in real applications, we MUST implement complex logic
        // to really know products and props id's. And NEVER rely on our assumptions
        $propsIds = range(1, self::PROPS);
        for ($productId = 1; $productId <= self::PRODUCTS; $productId++) {
            $connectToPropsCount = rand(2, floor(self::PROPS/5));
            $productProps = array_rand($propsIds, $connectToPropsCount);
            foreach ($productProps as $propArrayId) {
                $propId = $propsIds[$propArrayId];
                $relations[] = "({$productId}, {$propId})";
            }
        }

        $values = implode(',', $relations);

        $sql = "INSERT INTO `product_prop_relation` (`Product`, `Prop`) VALUES {$values}";
        return $this->db->query($sql);
    }
}
