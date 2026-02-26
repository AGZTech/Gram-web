<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PublicService;
use Illuminate\Support\Str;

class PublicServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'title' => 'जन्म दाखला',
                'slug' => 'janma-dakhla',
                'description' => 'जन्माची नोंदणी आणि जन्म दाखला मिळविण्यासाठी ग्रामपंचायत कार्यालयात अर्ज करावा.',
                'process' => '<ol>
                    <li>ग्रामपंचायत कार्यालयात अर्ज सादर करा</li>
                    <li>आवश्यक कागदपत्रे जोडा</li>
                    <li>शुल्क भरा</li>
                    <li>7 दिवसांत दाखला मिळेल</li>
                </ol>',
                'documents_required' => '<ul>
                    <li>रुग्णालयाचा जन्म रिपोर्ट</li>
                    <li>आई-वडिलांचे आधार कार्ड</li>
                    <li>विवाह प्रमाणपत्र</li>
                </ul>',
                'fees' => 'रु. 10 (21 दिवसांच्या आत), रु. 50 (विलंबित)',
                'time_duration' => '7 कार्यालयीन दिवस',
                'icon' => 'fa-baby',
                'is_published' => true,
                'sort_order' => 1,
            ],
            [
                'title' => 'मृत्यू दाखला',
                'slug' => 'mrityu-dakhla',
                'description' => 'मृत्यूची नोंदणी आणि मृत्यू दाखला मिळविण्यासाठी ग्रामपंचायत कार्यालयात अर्ज करावा.',
                'process' => '<ol>
                    <li>ग्रामपंचायत कार्यालयात अर्ज सादर करा</li>
                    <li>आवश्यक कागदपत्रे जोडा</li>
                    <li>शुल्क भरा</li>
                    <li>7 दिवसांत दाखला मिळेल</li>
                </ol>',
                'documents_required' => '<ul>
                    <li>रुग्णालयाचा मृत्यू रिपोर्ट</li>
                    <li>मृताचे आधार कार्ड</li>
                    <li>अर्जदाराचे आधार कार्ड</li>
                </ul>',
                'fees' => 'रु. 10 (21 दिवसांच्या आत), रु. 50 (विलंबित)',
                'time_duration' => '7 कार्यालयीन दिवस',
                'icon' => 'fa-file-medical',
                'is_published' => true,
                'sort_order' => 2,
            ],
            [
                'title' => 'रहिवासी दाखला',
                'slug' => 'rahivasi-dakhla',
                'description' => 'गावातील रहिवासी असल्याचा दाखला मिळविण्यासाठी ग्रामपंचायत कार्यालयात अर्ज करावा.',
                'process' => '<ol>
                    <li>ग्रामपंचायत कार्यालयात अर्ज सादर करा</li>
                    <li>आवश्यक कागदपत्रे जोडा</li>
                    <li>शुल्क भरा</li>
                    <li>3 दिवसांत दाखला मिळेल</li>
                </ol>',
                'documents_required' => '<ul>
                    <li>आधार कार्ड</li>
                    <li>रेशन कार्ड</li>
                    <li>वीज बिल / पाणी बिल</li>
                </ul>',
                'fees' => 'रु. 20',
                'time_duration' => '3 कार्यालयीन दिवस',
                'icon' => 'fa-home',
                'is_published' => true,
                'sort_order' => 3,
            ],
            [
                'title' => 'उत्पन्नाचा दाखला',
                'slug' => 'utpanncha-dakhla',
                'description' => 'कुटुंबाच्या उत्पन्नाचा दाखला मिळविण्यासाठी तहसील कार्यालयात अर्ज करावा. ग्रामपंचायत शिफारस पत्र देते.',
                'process' => '<ol>
                    <li>ग्रामपंचायत कार्यालयातून शिफारस पत्र घ्या</li>
                    <li>तहसील कार्यालयात अर्ज करा</li>
                    <li>तलाठी चौकशी</li>
                    <li>15 दिवसांत दाखला मिळेल</li>
                </ol>',
                'documents_required' => '<ul>
                    <li>आधार कार्ड</li>
                    <li>रेशन कार्ड</li>
                    <li>7/12 उतारा (शेतकऱ्यांसाठी)</li>
                    <li>पगार स्लिप (नोकरदारांसाठी)</li>
                </ul>',
                'fees' => 'रु. 20 (ग्रामपंचायत), रु. 30 (तहसील)',
                'time_duration' => '15 कार्यालयीन दिवस',
                'icon' => 'fa-rupee-sign',
                'is_published' => true,
                'sort_order' => 4,
            ],
            [
                'title' => 'नळ जोडणी',
                'slug' => 'nal-jodni',
                'description' => 'घरगुती नळ जोडणीसाठी ग्रामपंचायत कार्यालयात अर्ज करावा.',
                'process' => '<ol>
                    <li>ग्रामपंचायत कार्यालयात अर्ज सादर करा</li>
                    <li>आवश्यक कागदपत्रे जोडा</li>
                    <li>जोडणी शुल्क भरा</li>
                    <li>15 दिवसांत जोडणी होईल</li>
                </ol>',
                'documents_required' => '<ul>
                    <li>मालमत्ता कर पावती</li>
                    <li>आधार कार्ड</li>
                    <li>जमिनीचे कागदपत्र</li>
                </ul>',
                'fees' => 'रु. 500 (जोडणी शुल्क)',
                'time_duration' => '15 कार्यालयीन दिवस',
                'icon' => 'fa-tint',
                'is_published' => true,
                'sort_order' => 5,
            ],
            [
                'title' => 'मालमत्ता कर भरणा',
                'slug' => 'property-tax',
                'description' => 'वार्षिक मालमत्ता कर भरणा ग्रामपंचायत कार्यालयात करावा.',
                'process' => '<ol>
                    <li>ग्रामपंचायत कार्यालयात भेट द्या</li>
                    <li>मालमत्ता क्रमांक सांगा</li>
                    <li>कर भरा</li>
                    <li>पावती घ्या</li>
                </ol>',
                'documents_required' => '<ul>
                    <li>मागील वर्षाची कर पावती</li>
                    <li>मालमत्ता क्रमांक</li>
                </ul>',
                'fees' => 'मालमत्तेनुसार बदलते',
                'time_duration' => 'त्वरित',
                'icon' => 'fa-building',
                'is_published' => true,
                'sort_order' => 6,
            ],
        ];

        foreach ($services as $service) {
            PublicService::create($service);
        }
    }
}
