<?php

//use Elena\AppLoader\Common\Unit;

use Exceptions\GuestNotFoundException;
use Units\{Member, Guest};
//use Units\Guest;

use Repositories\InMemoryGuestsRepo;


spl_autoload_register('loadClass');

function loadClass($className)
{
    include __DIR__ . "/src/" . $className . ".php";
    $fileName = str_replace('\\', '/', $className);
    $fileName = str_replace('_', '/', $fileName);
    $fileName = str_replace("__DIR__", "/src/", $fileName) . ".php";
    //var_dump($className);
    var_dump($fileName);
    //var_dump(__DIR__ . "/src/" . $className . ".php");
    //var_dump("src/" . $className . ".php");
    if (file_exists($fileName)) {
        include $fileName;
        // include __DIR__ . "/src/" . $className . ".php";

    }
}

try {
    $member = new Member(1, "John", random_int(20, 70), ["Auto", "Badge"]);

    $repo = new InMemoryGuestsRepo();

    for ($i = 0; $i < 5; $i++) {
        $guest = new Guest($i, "Mary-" . $i, random_int(20, 70));
        $guest->orga = "Orga - " . $i;
        $repo->save($guest);
    }

    //print_r($member);

    //print_r($repo);

    echo $repo->get(3);
} catch (GuestNotFoundException $exception) {
    echo $exception->getMessage();
} catch (Exception $exception) {
    print_r($exception->getTrace());
}
