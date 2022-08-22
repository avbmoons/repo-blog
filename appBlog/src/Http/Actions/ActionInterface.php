<?php

namespace Elena\AppBlog\Http\Actions;

use Elena\AppBlog\Http\Request;
use Elena\AppBlog\Http\Response;

interface ActionInterface
{
    public function handle(Request $request): Response;
}
