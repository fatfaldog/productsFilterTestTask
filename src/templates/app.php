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
            <h1 class="text-center">Test <a href="http://telegra.ph/Fullstack-developer-test-01-12">challenge</a> for fullstack developer</h1>
            <div class="col-xs-8">
                <h2>Filtered product list here</h2>
                <?php foreach($data['products'] as $product): ?>
                <div class="panel panel-default">
                    <div class="panel-heading"><?= $product['name'] ?></div>
                    <div class="panel-body">
                        <div class="col-xs-4">
                            <p>Some info about product here:</p>
                            <p>
                                Image: <span class="glyphicon glyphicon-camera"></span><br>
                                Description: some description<br>
                                Price: xxxx $<br>
                                Availability: yy units<br>
                                Anything else
                            </p>
                        </div>
                        <div class="col-xs-8">
                            <p>Product properties here:</p>
                            <ul class="list-group">
                            <?php foreach($product['props'] as $property): ?>
                                <li class="list-group-item">
                                    <span class="product__prop"><?= $property['name'] ?></span>
                                </li>
                            <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <form id="products-filter" class="col-xs-4">
                <h2>Filters here</h2>
                <?php foreach($data['groups'] as $group): ?>
                <div class="panel panel-default">
                    <div class="panel-heading"><?= $group['name'] ?></div>
                    <div class="panel-body">
                        <ul class="list-group">
                        <?php foreach($group['props'] as $property): ?>
                            <li class="list-group-item">
                                <label>
                                    <input
                                        type="checkbox"
                                        name="filter[prop][<?= $property['id'] ?>]"
                                        value="true"
                                        <?= $property['available'] || $group['selected'] ? '': 'disabled="disabled"' ?>
                                        <?= $property['selected'] ? 'checked="checked"' : '' ?>
                                    >
                                    <?= $property['name'] ?>
                                </label>
                            </li>
                        <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
                <?php endforeach; ?>
            </form>
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