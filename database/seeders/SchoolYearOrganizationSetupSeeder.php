<?php

namespace Database\Seeders;

use App\Models\OrgMembership;
use App\Models\Organization;
use App\Models\SchoolYear;
use App\Models\User;
use App\Models\OfficerEntry;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SchoolYearOrganizationSetupSeeder extends Seeder
{
    public function run(): void
    {
        $password = Hash::make('genpass20');

        /*
        |--------------------------------------------------------------------------
        | SCHOOL YEAR
        |--------------------------------------------------------------------------
        */
        $schoolYear = SchoolYear::updateOrCreate(
            ['name' => '2026-2027'],
            [
                'start_date' => '2026-08-01',
                'end_date'   => '2027-05-31',
                'is_active'  => 0,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | ORGANIZATIONS
        |--------------------------------------------------------------------------
        */
        $orgs = [
            ['name' => 'XU Coding Society', 'acronym' => 'XUCS'],
            ['name' => 'XU Tech Innovators', 'acronym' => 'XUTI'],
            ['name' => 'Xavier Circle of Information Technology', 'acronym' => 'XCITeS'], // no moderator
            ['name' => 'Association of Digital Builders', 'acronym' => 'ADB'],
            ['name' => 'Society of Student Developers', 'acronym' => 'SSD'],
            ['name' => 'Future IT Leaders Guild', 'acronym' => 'FITLG'],
        ];

        $studentNumber = 20260010001;

        foreach ($orgs as $index => $orgData) {

            /*
            |--------------------------------------------------------------------------
            | CREATE ORGANIZATION
            |--------------------------------------------------------------------------
            */
            $org = Organization::updateOrCreate(
                ['name' => $orgData['name']],
                ['acronym' => $orgData['acronym']]
            );

            /*
            |--------------------------------------------------------------------------
            | PRESIDENT (REALISTIC)
            |--------------------------------------------------------------------------
            */
            $presidentName = $this->generateName();

            $studentId = (string)$studentNumber;
            $email = $studentId . '@my.xu.edu.ph';

            $president = User::updateOrCreate(
                ['email' => $email],
                [
                    'name' => $presidentName,
                    'password' => $password,
                    'must_change_password' => 1,
                ]
            );

            /*
            |--------------------------------------------------------------------------
            | OFFICER ENTRY (PRESIDENT)
            |--------------------------------------------------------------------------
            */
            $officerEntry = OfficerEntry::updateOrCreate(
                [
                    'organization_id' => $org->id,
                    'school_year_id' => $schoolYear->id,
                    'major_officer_role' => 'president',
                ],
                [
                    'user_id' => $president->id,
                    'full_name' => $president->name,
                    'email' => $president->email,
                    'position' => 'President',
                    'major_officer_role' => 'president',
                    'is_major_officer' => 1,
                    'student_id_number' => $studentId,
                    'course_and_year' => collect(['BSIT-4', 'BSCS-3', 'BSIS-2', 'BSEMC-2'])->random(),
                    'latest_qpi' => rand(200, 350) / 100,
                    'mobile_number' => '09' . rand(100000000, 999999999),
                    'sort_order' => 1,
                ]
            );

            /*
            |--------------------------------------------------------------------------
            | MEMBERSHIP (PRESIDENT)
            |--------------------------------------------------------------------------
            */
            OrgMembership::updateOrCreate(
                [
                    'organization_id' => $org->id,
                    'school_year_id' => $schoolYear->id,
                    'role' => 'president',
                ],
                [
                    'user_id' => $president->id,
                    'officer_entry_id' => $officerEntry->id,
                    'is_under_probation' => 0,
                    'is_suspended' => 0,
                    'archived_at' => null,
                ]
            );

            /*
            |--------------------------------------------------------------------------
            | MODERATOR (skip org #3)
            |--------------------------------------------------------------------------
            */
            if ($index !== 2) {

                $moderatorName = $this->generateName();

                $parts = explode(' ', $moderatorName);
                $firstLetter = strtolower(substr($parts[0], 0, 1));
                $lastName = strtolower(preg_replace('/[^a-zA-Z]/', '', end($parts)));

                $moderatorEmail = $firstLetter . $lastName . '@xu.edu.ph';

                $moderator = User::updateOrCreate(
                    ['email' => $moderatorEmail],
                    [
                        'name' => $moderatorName,
                        'password' => $password,
                        'must_change_password' => 1,
                    ]
                );

                OrgMembership::updateOrCreate(
                    [
                        'organization_id' => $org->id,
                        'school_year_id' => $schoolYear->id,
                        'role' => 'moderator',
                    ],
                    [
                        'user_id' => $moderator->id,
                        'officer_entry_id' => null,
                        'is_under_probation' => 0,
                        'is_suspended' => 0,
                        'archived_at' => null,
                    ]
                );
            }

            $studentNumber++;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | NAME GENERATOR
    |--------------------------------------------------------------------------
    */
    private function generateName(): string
    {
        $firstNames = [
            'Adrian', 'Bianca', 'Carlo', 'Danica', 'Ethan', 'Faith',
            'Joshua', 'Kimberly', 'Luis', 'Maria', 'Nathan', 'Paolo'
        ];

        $lastNames = [
            'Vega', 'Flores', 'Reyes', 'Lim', 'Navarro', 'Mendoza',
            'Cruz', 'Santos', 'Garcia', 'Torres', 'DelaCruz', 'Ramos'
        ];

        return $firstNames[array_rand($firstNames)] . ' ' . $lastNames[array_rand($lastNames)];
    }
}