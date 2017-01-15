<!DOCTYPE html>
<html>
    <head>
        <title>Test application</title>
        <!-- bootstrap CSS CDN -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
        <!-- /bootstrap CSS CDN -->
    </head>
    <body>
        <main class="container">
            <ul class="list-group col-md-8">
            <?php foreach($data['Products'] as $product): ?>
                <li class="list-group-item">
                    <div class="panel panel-default">
                        <div class="panel-heading"><?= $product['Name'] ?></div>
                        <div class="panel-body">
                            <ul class="list-group">
                            <?php foreach($product['Properties'] as $propertie): ?>
                                <li class="list-group-item">
                                    <span class="product__prop"><?= $propertie['Name'] ?></span>
                                </li>
                            <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </li>
            <?php endforeach; ?>
            </ul>
            <form id="products-filter">
                <ul class="list-group col-md-4">
                    <?php foreach($data['Groups'] as $groupName => $properties): ?>
                    <li class="list-group-item">
                        <div class="panel panel-default">
                            <div class="panel-heading"><?= $groupName ?></div>
                            <div class="panel-body">
                                <ul class="list-group">
                                <?php foreach($properties as $property): ?>
                                    <li class="list-group-item">
                                        <label>
                                            <input
                                                type="checkbox"
                                                name="filter[prop][<?= $property['ID'] ?>]"
                                                value="true"
                                                <?= $property['Available'] ? '': 'disabled="disabled"' ?>
                                                <?= $property['Checked'] ? 'checked="checked"' : '' ?>
                                            >
                                            <?= $property['Name'] ?>
                                        </label>
                                    </li>
                                <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    </li>
                <?php endforeach; ?>
                </ul>
            </div>
        </aside>
        </main>
        <!-- bootstrap JS CDN -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
        <!-- bootstrap JS CDN -->
        <script>
            window.onload = function(){
                document.getElementById('products-filter').onchange = function() {
                    document.getElementById('products-filter').submit();
                }
            }
        </script>
    </body>
</html>