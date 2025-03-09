<?php

namespace App\Services;

use App\Http\Resources\Github\IssueListResource;

class GithubService
{
    protected $guzzle;

    public function __construct(GuzzleService $guzzleService)
    {
        $this->guzzle = $guzzleService;
    }

    public function getIssues()
    {
        try {
            $queryParams = [
                'filter' => 'assigned',  // Only issues assigned to the user
                'state' => 'open',       // Only open issues
                'sort' => 'created',     // Sort by creation date
                'direction' => 'desc'    // Descending order
            ];
            $response = $this->guzzle->get('https://api.github.com/issues', ['query' => $queryParams]);

            $data = IssueListResource::collection($response);
            return $data;
        } catch (\Exception $e) {
            throw new \Exception('Please check the token and try again');
        }

    }
}
