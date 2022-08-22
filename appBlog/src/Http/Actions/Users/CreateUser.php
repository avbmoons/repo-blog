<?php

namespace Elena\AppBlog\http\Actions\Users;

use Elena\AppBlog\http\Request;
use Elena\AppBlog\Blog\Repositories\UsersRepository\UsersRepositoryInterface;
use Elena\AppBlog\Blog\User;
use Elena\AppBlog\Blog\UUID;
use Elena\AppBlog\http\Actions\ActionInterface;
use Elena\AppBlog\Person\Name;
use Elena\AppBlog\Blog\Exceptions\HttpException;
use Elena\AppBlog\http\ErrorResponse;
use Elena\AppBlog\http\SuccessfulResponse;
use Elena\AppBlog\http\Response;

class CreateUser implements ActionInterface
{
    public function __construct(
        private UsersRepositoryInterface $usersRepository,
    ) {
    }

    public function handle(Request $request): Response
    {
        try {
            $newUserUuid = UUID::random();

            $user = new User(
                $newUserUuid,
                $request->jsonBodyField('username'),
                new Name(
                    $request->jsonBodyField('first_name'),
                    $request->jsonBodyField('last_name')
                )
            );
        } catch (HttpException $e) {
            return new ErrorResponse($e->getMessage());
        }
        $this->usersRepository->save($user);

        return new SuccessfulResponse([
            'uuid' => (string)$newUserUuid,
        ]);
    }
}
