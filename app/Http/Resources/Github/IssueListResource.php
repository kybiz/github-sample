<?php

namespace App\Http\Resources\Github;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IssueListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'url' => $this['url'],
            'title' => $this['title'],
            'created_at' => $this['created_at'],
            'avatar_url' => $this['user']['avatar_url'],
            'assignees' => $this['assignees'],
            'repository_name' => $this['repository']['name'],
            'body' => $this['body'],
            'issue_number' => $this['number']
        ];
    }
}
