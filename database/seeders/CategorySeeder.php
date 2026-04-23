<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            'FINANCE' => ['CA', 'CS', 'CMA', 'CFA'],
            'MANAGEMENT' => ['MBA', 'PGDM', 'IAS', 'IPS', 'DM', 'Other Administrator in Civil or Administrative Services', 'Masters in Hotel Management'],
            'ENGINEERING' => ['BE/B. Tech', 'ME/M. Tech', 'B. Arch'],
            'EDUCATION' => ['M. Ed', 'PhD (Economists, Statisticians, Mathematicians, Scientists, Geologists, Actuaries, etc.)'],
            'MEDICAL' => ['MBBS', 'BHMS', 'BAMS', 'BDS', 'BNYS', 'MS', 'MD'],
            'PHARMACY' => ['B. Pharma', 'M. Pharma', 'Pharma.D'],
            'RESEARCH' => ['Masters in Biotechnology', 'Microbiology', 'Biochemistry', 'Wild Life etc.'],
            'DESIGNING' => ['Masters in Fashion', 'Interior', 'Web', 'Jewellery', 'Visual Arts', 'Animation'],
            'COMPUTER APPLICATION' => ['MCA', 'M.Sc (CS/IT)'],
            'COMMUNICATION' => ['Masters in Mass Communication', 'Journalism'],
            'LEGAL' => ['LLB', 'LLM', 'Registered Advocate', 'Masters in Cyber Law'],
            'TRAINER' => ['Certified Motivational', 'Professional Trainer/Coach with Masters degree', 'Masters from Foreign University'],
            'OTHER' => ['10+2+5 years professional degree course other than academic courses like M.A/M. Com/M. Sc'],
            'PARAMEDICAL' => ['BPT', 'MPT'],
            'APPLIED & LIFE SCIENCES' => ['M.Sc (Psychology, Biochemistry, Anatomy, Physiology, Pathology, Medical Pharmacology)', 'MPH (Masters in Public Health)']
        ];

        foreach ($categories as $categoryName => $subCategories) {
            // Pehle category insert karein
            $categoryId = DB::table('categories')->insertGetId([
                'category_name' => $categoryName,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Phir us category ke subcategories insert karein
            foreach ($subCategories as $subCategoryName) {
                DB::table('sub_categories')->insert([
                    'category_id' => $categoryId,
                    'sub_category_name' => $subCategoryName,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
