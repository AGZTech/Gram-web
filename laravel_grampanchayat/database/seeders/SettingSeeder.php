<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // General Settings
            ['key' => 'site_name', 'value' => 'ग्रामपंचायत आदर्शगाव', 'type' => 'text', 'group' => 'general'],
            ['key' => 'site_tagline', 'value' => 'स्वच्छ गाव, समृद्ध गाव', 'type' => 'text', 'group' => 'general'],
            
            // Contact Settings
            ['key' => 'contact_email', 'value' => 'grampanchayat@gov.in', 'type' => 'email', 'group' => 'contact'],
            ['key' => 'contact_phone', 'value' => '02XX-XXXXXX', 'type' => 'text', 'group' => 'contact'],
            ['key' => 'contact_address', 'value' => 'ग्रामपंचायत कार्यालय, आदर्शगाव, तालुका - xyz, जिल्हा - xyz, महाराष्ट्र - 4XXXXX', 'type' => 'textarea', 'group' => 'contact'],
            ['key' => 'google_map_embed', 'value' => '', 'type' => 'textarea', 'group' => 'contact'],
            
            // Social Settings
            ['key' => 'facebook_url', 'value' => '', 'type' => 'url', 'group' => 'social'],
            ['key' => 'twitter_url', 'value' => '', 'type' => 'url', 'group' => 'social'],
            ['key' => 'instagram_url', 'value' => '', 'type' => 'url', 'group' => 'social'],
            
            // Content Settings
            ['key' => 'sarpanch_message', 'value' => 'आपल्या गावाच्या सर्वांगीण विकासासाठी आम्ही कटिबद्ध आहोत. ग्रामपंचायतीच्या या डिजिटल व्यासपीठाद्वारे आपण सर्व शासकीय योजना, विकासकामे आणि इतर महत्त्वाची माहिती सहजपणे मिळवू शकता. आपल्या सहकार्याने आपण आपले गाव आदर्श गाव बनवू शकतो.', 'type' => 'textarea', 'group' => 'content'],
            ['key' => 'footer_text', 'value' => '© ' . date('Y') . ' ग्रामपंचायत आदर्शगाव. सर्व हक्क राखीव.', 'type' => 'text', 'group' => 'content'],
            
            // SEO Settings
            ['key' => 'meta_title', 'value' => 'ग्रामपंचायत आदर्शगाव | अधिकृत वेबसाईट', 'type' => 'text', 'group' => 'seo'],
            ['key' => 'meta_description', 'value' => 'ग्रामपंचायत आदर्शगाव ची अधिकृत वेबसाईट. येथे शासकीय योजना, विकासकामे, नोटीस आणि इतर माहिती मिळवा.', 'type' => 'textarea', 'group' => 'seo'],
            ['key' => 'meta_keywords', 'value' => 'ग्रामपंचायत, आदर्शगाव, महाराष्ट्र, शासकीय योजना, विकासकामे', 'type' => 'text', 'group' => 'seo'],
        ];

        foreach ($settings as $setting) {
            Setting::create($setting);
        }
    }
}
