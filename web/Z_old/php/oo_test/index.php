<?php
// phpinfo();

require "Banana.php";
require "Monkey.php";

$john = new Monkey("John", new Banana(90) );
$wilco = new Monkey("Wilco", null);

echo $john->hasBanana();
echo $wilco->hasBanana();

br2();

echo $john->giveBanana($wilco);

br2();

echo $john->hasBanana();
echo $wilco->hasBanana();
echo $wilco->getBananaInfo();

br2();

echo $john->eatBanana();
echo $wilco->eatBanana();

br2();

echo $john->findBanana(new Banana(2));
echo $wilco->findBanana(new Banana(5));

br2();

echo $john->getBananaInfo();
echo $wilco->getBananaInfo();


function br() {
    echo '<br />';
}

function br2() {
    br();
    br();
}

?>
