<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cluster;
use App\Models\Organization;

class ClusterSeeder extends Seeder
{
    public function run(): void
    {
        $clusters = [
            'Business',
            'Environment',
            'Food and Agriculture',
            'Governance and Policy-Making',
            'Media and Arts',
            'Natural Sciences, Engineering, and Technology',
            'Program-based',
            'Service-learning',
            'Socio-cultural',
            'Sports and Recreation',
        ];

        $created = [];

        foreach ($clusters as $index => $name) {
            $cluster = Cluster::firstOrCreate(
                ['name' => $name],
                [
                    'acronym' => 'C' . ($index + 1), // C1, C2, etc.
                ]
            );

            $created[] = $cluster;
        }



        $defaultCluster = $created[0] ?? null;

        if ($defaultCluster) {
            Organization::query()->update([
                'cluster_id' => $defaultCluster->id
            ]);
        }
    }
}