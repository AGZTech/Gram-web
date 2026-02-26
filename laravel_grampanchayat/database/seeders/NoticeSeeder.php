<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Notice;
use Carbon\Carbon;

class NoticeSeeder extends Seeder
{
    public function run(): void
    {
        $notices = [
            [
                'title' => 'ग्रामसभा बैठक सूचना - जानेवारी 2025',
                'description' => 'सर्व ग्रामस्थांना कळविण्यात येते की, दिनांक 26 जानेवारी 2025 रोजी सकाळी 10 वाजता ग्रामपंचायत कार्यालयात ग्रामसभा बैठक आयोजित करण्यात आली आहे. सर्वांनी उपस्थित राहावे.',
                'notice_date' => Carbon::now()->subDays(2),
                'expiry_date' => Carbon::now()->addDays(30),
                'is_important' => true,
                'show_in_ticker' => true,
                'is_published' => true,
            ],
            [
                'title' => 'पाणीपुरवठा वेळापत्रक बदल',
                'description' => 'उन्हाळी हंगामामुळे पाणीपुरवठा वेळापत्रकात बदल करण्यात आला आहे. नवीन वेळापत्रक: सकाळी 6 ते 8 वाजता आणि संध्याकाळी 5 ते 7 वाजता.',
                'notice_date' => Carbon::now()->subDays(5),
                'is_important' => false,
                'show_in_ticker' => true,
                'is_published' => true,
            ],
            [
                'title' => 'जन्म-मृत्यू नोंदणी शिबीर',
                'description' => 'विलंबित जन्म-मृत्यू नोंदणीसाठी विशेष शिबीर आयोजित करण्यात आले आहे. तारीख: 15 जानेवारी 2025, वेळ: सकाळी 10 ते संध्याकाळी 5 वाजेपर्यंत.',
                'notice_date' => Carbon::now()->subDays(10),
                'expiry_date' => Carbon::now()->addDays(5),
                'is_important' => true,
                'show_in_ticker' => true,
                'is_published' => true,
            ],
            [
                'title' => 'मालमत्ता कर भरणा अंतिम तारीख',
                'description' => 'सर्व मालमत्ता धारकांना कळविण्यात येते की, मालमत्ता कर भरणा करण्याची अंतिम तारीख 31 मार्च 2025 आहे. विलंबित भरणासाठी दंड आकारण्यात येईल.',
                'notice_date' => Carbon::now(),
                'expiry_date' => Carbon::parse('2025-03-31'),
                'is_important' => true,
                'show_in_ticker' => true,
                'is_published' => true,
            ],
            [
                'title' => 'आरोग्य तपासणी शिबीर',
                'description' => 'प्राथमिक आरोग्य केंद्राच्या वतीने मोफत आरोग्य तपासणी शिबीर आयोजित करण्यात आले आहे. सर्व ग्रामस्थांनी लाभ घ्यावा.',
                'notice_date' => Carbon::now()->subDays(1),
                'is_important' => false,
                'show_in_ticker' => true,
                'is_published' => true,
            ],
        ];

        foreach ($notices as $notice) {
            Notice::create($notice);
        }
    }
}
