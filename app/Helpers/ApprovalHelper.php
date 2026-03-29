<?php

use App\Models\User;
use App\Models\Project;



if (!function_exists('getSacdevApprover')) {
    function getSacdevApprover(Project $project)
    {
        
        if (!$project->relationLoaded('organization')) {
            $project->load('organization');
        }

        $clusterId = $project->organization->cluster_id ?? null;

        if (!$clusterId) {
            return getFallbackAdmin();
        }

        $user = User::whereHas('role', function ($q) {
                $q->where('name', 'sacdev');
            })
            ->whereHas('clusters', function ($q) use ($clusterId) {
                $q->where('clusters.id', $clusterId);
            })
            ->first();

        return $user ?? getFallbackAdmin();
    }
}



if (!function_exists('getOsaApprover')) {
    function getOsaApprover()
    {
        $user = User::whereHas('role', function ($q) {
            $q->where('name', 'osa_head');
        })->first();

        return $user ?? getFallbackAdmin();
    }
}



if (!function_exists('getFallbackAdmin')) {
    function getFallbackAdmin()
    {
        return User::whereHas('role', function ($q) {
            $q->where('name', 'sacdev_admin');
        })->first();
    }
}