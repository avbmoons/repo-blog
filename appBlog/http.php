<?php

use Elena\AppBlog\Blog\Repositories\PostsRepository\SqlitePostsRepository;
use Elena\AppBlog\Blog\Repositories\CommentsRepository\SqliteCommentsRepository;
use Elena\AppBlog\Blog\Repositories\UsersRepository\SqliteUsersRepository;
use Elena\AppBlog\Http\Actions\Comments\FindCommentByUuid;
use Elena\AppBlog\Http\Actions\Posts\FindPostByUuid;
use Elena\AppBlog\Http\Actions\Users\FindByUsername;
use Elena\AppBlog\Http\ErrorResponse;
use Elena\AppBlog\Http\HttpException;
use Elena\AppBlog\Http\Request;
use Elena\AppBlog\Http\SuccessfulResponse;

require_once __DIR__ . '/vendor/autoload.php';

//  Создаем объект запроса с глобальными переменными

$request = new Request(
    $_GET,
    $_SERVER,
    // Читаем поток, содержащий тело запроса
    file_get_contents('php://input'),
);

try {
    // Пытаемся получить путь из запроса
    $path = $request->path();
} catch (HttpException) {
    // Отправляем неудачный ответ, если не можем получить путь
    (new ErrorResponse)->send();
    // Выходим из программы
    return;
}

$routes = [
    // Создаём действие, соответствующее пути /users/show
    '/users/show' => new FindByUsername(
        // Действию нужен репозиторий
        new SqliteUsersRepository(
            // Репозиторию нужно подключение к БД
            new PDO('sqlite:' . __DIR__ . '/blog.sqlite')
        )
    ),
    // Второй маршрут
    '/posts/show' => new FindPostByUuid(
        new SqlitePostsRepository(
            new PDO('sqlite:' . __DIR__ . '/blog.sqlite')
        )
    ),
    // Третий маршрут
    '/comments/show' => new FindCommentByUuid(
        new SqliteCommentsRepository(
            new PDO('sqlite:' . __DIR__ . '/blog.sqlite')
        )
    ),
];

// Если у нас нет маршрута для пути из запроса -
// отправляем неуспешный ответ
if (!array_key_exists($path, $routes)) {
    (new ErrorResponse('Not found'))->send();
    return;
}

// Выбираем найденное действие
$action = $routes[$path];

try {
    // Пытаемся выполнить действие,
    // при этом результатом может быть
    // как успешный, так и неуспешный ответ
    $response = $action->handle($request);
} catch (Exception $e) {
    // Отправляем неудачный ответ,
    // если что-то пошло не так
    (new ErrorResponse($e->getMessage()))->send();
}

// //  Получаем данные из объекта запроса (параметр, заголовок, путь)
// $parameter = $request->query('some_parameter');
// $header = $request->header('some_header');
// $path = $request->path();

// // Создаём объект ответа
// $response = new SuccessfulResponse([
//     'message' => 'Hello from PHP',
// ]);

// Отправляем ответ
$response->send();

// // Установка кода ответа
// http_response_code(201);

// // Установка заголовков
// header('Some-Header: some_header');
// header('Another-Header: another_header');

//echo 'Hello from PHP';
