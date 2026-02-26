<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Member;

class MemberSeeder extends Seeder
{
    public function run(): void
    {
        $members = [
            [
                'name' => 'श्री. राजेंद्र पाटील',
                'designation' => 'sarpanch',
                'phone' => '9876543201',
                'email' => 'sarpanch@grampanchayat.gov.in',
                'bio' => 'श्री. राजेंद्र पाटील हे गेल्या 10 वर्षांपासून सामाजिक कार्यात सक्रिय आहेत. त्यांच्या नेतृत्वाखाली गावाने अनेक पुरस्कार मिळवले आहेत.',
                'ward_number' => '-',
                'term_start' => '2024-01-01',
                'term_end' => '2029-01-01',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'श्रीमती. सुनीता शिंदे',
                'designation' => 'up_sarpanch',
                'phone' => '9876543202',
                'bio' => 'श्रीमती. सुनीता शिंदे या महिला सक्षमीकरण आणि शिक्षण क्षेत्रात विशेष रुची घेतात.',
                'ward_number' => '-',
                'term_start' => '2024-01-01',
                'term_end' => '2029-01-01',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'श्री. अमित जाधव',
                'designation' => 'gram_sevak',
                'phone' => '9876543203',
                'email' => 'gramsevak@grampanchayat.gov.in',
                'bio' => 'ग्रामसेवक म्हणून गावाच्या प्रशासकीय कामकाजाची जबाबदारी सांभाळतात.',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'श्री. प्रकाश गायकवाड',
                'designation' => 'member',
                'phone' => '9876543204',
                'ward_number' => 'वॉर्ड क्र. 1',
                'term_start' => '2024-01-01',
                'term_end' => '2029-01-01',
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'श्रीमती. माया कदम',
                'designation' => 'member',
                'phone' => '9876543205',
                'ward_number' => 'वॉर्ड क्र. 2',
                'term_start' => '2024-01-01',
                'term_end' => '2029-01-01',
                'is_active' => true,
                'sort_order' => 5,
            ],
            [
                'name' => 'श्री. संतोष भोसले',
                'designation' => 'member',
                'phone' => '9876543206',
                'ward_number' => 'वॉर्ड क्र. 3',
                'term_start' => '2024-01-01',
                'term_end' => '2029-01-01',
                'is_active' => true,
                'sort_order' => 6,
            ],
        ];

        foreach ($members as $member) {
            Member::create($member);
        }
    }
}
