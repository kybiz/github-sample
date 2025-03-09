<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\GithubService;

class GithubController extends Controller
{
    protected $service;
    public function __construct(GithubService $githubService)
    {
        $this->service = $githubService;
    }

    public function getIssues()
    {
        return $this->service->getIssues();
    }
}
