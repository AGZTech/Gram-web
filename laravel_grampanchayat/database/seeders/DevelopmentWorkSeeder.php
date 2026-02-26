<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DevelopmentWork;
use Carbon\Carbon;

class DevelopmentWorkSeeder extends Seeder
{
    public function run(): void
    {
        $works = [
            [
                'title' => 'ग्रामीण रस्ता डांबरीकरण',
                'description' => 'मुख्य गावठाण ते शिवार रस्त्याचे डांबरीकरण. एकूण लांबी 2.5 किमी.',
                'location' => 'मुख्य गावठाण ते शिवार',
                'budget' => 2500000,
                'spent_amount' => 2200000,
                'start_date' => Carbon::now()->subMonths(3),
                'completion_date' => Carbon::now()->subDays(15),
                'status' => 'completed',
                'progress_percentage' => 100,
                'contractor_name' => 'श्री. विकास बांधकाम',
                'is_published' => true,
            ],
            [
                'title' => 'समाज मंदिर बांधकाम',
                'description' => 'गावात नवीन समाज मंदिराचे बांधकाम. 500 चौ. फूट क्षेत्रफळ.',
                'location' => 'ग्रामपंचायत कार्यालय जवळ',
                'budget' => 1500000,
                'spent_amount' => 900000,
                'start_date' => Carbon::now()->subMonths(2),
                'completion_date' => Carbon::now()->addMonths(2),
                'status' => 'in_progress',
                'progress_percentage' => 60,
                'contractor_name' => 'आदर्श कन्स्ट्रक्शन',
                'is_published' => true,
            ],
            [
                'title' => 'LED पथदिवे बसविणे',
                'description' => 'गावातील मुख्य रस्त्यांवर 50 LED पथदिवे बसविणे.',
                'location' => 'संपूर्ण गाव',
                'budget' => 500000,
                'spent_amount' => 0,
                'start_date' => Carbon::now()->addMonths(1),
                'status' => 'planned',
                'progress_percentage' => 0,
                'is_published' => true,
            ],
            [
                'title' => 'शाळा परिसर भिंत बांधकाम',
                'description' => 'जि. प. प्राथमिक शाळा परिसर भिंत व गेट बांधकाम.',
                'location' => 'जि. प. प्राथमिक शाळा',
                'budget' => 800000,
                'spent_amount' => 400000,
                'start_date' => Carbon::now()->subMonths(1),
                'completion_date' => Carbon::now()->addMonths(1),
                'status' => 'in_progress',
                'progress_percentage' => 50,
                'contractor_name' => 'प्रगती बिल्डर्स',
                'is_published' => true,
            ],
            [
                'title' => 'सार्वजनिक शौचालय बांधकाम',
                'description' => 'गावठाण परिसरात 10 सीट क्षमतेचे सार्वजनिक शौचालय.',
                'location' => 'बस स्टॅन्ड जवळ',
                'budget' => 600000,
                'spent_amount' => 600000,
                'start_date' => Carbon::now()->subMonths(4),
                'completion_date' => Carbon::now()->subMonths(1),
                'status' => 'completed',
                'progress_percentage' => 100,
                'contractor_name' => 'स्वच्छ भारत बांधकाम',
                'is_published' => true,
            ],
        ];

        foreach ($works as $work) {
            DevelopmentWork::create($work);
        }
    }
}
