<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Scheme;
use Illuminate\Support\Str;

class SchemeSeeder extends Seeder
{
    public function run(): void
    {
        $schemes = [
            [
                'title' => 'प्रधानमंत्री आवास योजना (ग्रामीण)',
                'slug' => 'pmay-gramin-' . time(),
                'description' => 'ग्रामीण भागातील गरीब कुटुंबांना पक्के घर बांधण्यासाठी आर्थिक सहाय्य देणारी केंद्र शासनाची योजना.',
                'eligibility' => '<ul>
                    <li>कुटुंबाचे उत्पन्न 3 लाखांपेक्षा कमी असावे</li>
                    <li>कुटुंबाकडे पक्के घर नसावे</li>
                    <li>SECC 2011 यादीत नाव असावे</li>
                    <li>बीपीएल कुटुंब असावे</li>
                </ul>',
                'benefits' => '<ul>
                    <li>मैदानी भागासाठी रु. 1,20,000</li>
                    <li>डोंगराळ भागासाठी रु. 1,30,000</li>
                    <li>स्वच्छ भारत अंतर्गत शौचालयासाठी रु. 12,000</li>
                    <li>मनरेगा अंतर्गत 90 दिवसांचे मजुरी</li>
                </ul>',
                'documents_required' => '<ul>
                    <li>आधार कार्ड</li>
                    <li>रेशन कार्ड</li>
                    <li>उत्पन्नाचा दाखला</li>
                    <li>जमिनीचा 7/12 उतारा</li>
                    <li>बँक पासबुक</li>
                </ul>',
                'how_to_apply' => 'ग्रामपंचायत कार्यालयात अर्ज करावा. ग्रामसभेच्या मान्यतेनंतर लाभार्थी निवड होते.',
                'gr_link' => 'https://pmayg.nic.in/',
                'department' => 'ग्रामविकास विभाग',
                'is_active' => true,
                'is_published' => true,
            ],
            [
                'title' => 'महात्मा गांधी राष्ट्रीय ग्रामीण रोजगार हमी योजना (मनरेगा)',
                'slug' => 'mgnrega-' . time(),
                'description' => 'ग्रामीण भागातील कुटुंबांना वर्षभरात 100 दिवसांच्या रोजगाराची हमी देणारी योजना.',
                'eligibility' => '<ul>
                    <li>ग्रामीण भागातील रहिवासी असावा</li>
                    <li>वय 18 वर्षांपेक्षा जास्त असावे</li>
                    <li>अकुशल काम करण्यास तयार असावे</li>
                </ul>',
                'benefits' => '<ul>
                    <li>वर्षाला 100 दिवसांचा रोजगार</li>
                    <li>दररोज रु. 273 मजुरी (महाराष्ट्र)</li>
                    <li>15 दिवसांत रोजगार न मिळाल्यास बेरोजगारी भत्ता</li>
                </ul>',
                'documents_required' => '<ul>
                    <li>आधार कार्ड</li>
                    <li>रहिवासी दाखला</li>
                    <li>पासपोर्ट साईज फोटो</li>
                    <li>बँक पासबुक</li>
                </ul>',
                'how_to_apply' => 'ग्रामपंचायत कार्यालयात जॉब कार्डसाठी अर्ज करावा.',
                'department' => 'ग्रामविकास विभाग',
                'is_active' => true,
                'is_published' => true,
            ],
            [
                'title' => 'लाडकी बहीण योजना',
                'slug' => 'ladki-bahin-' . time(),
                'description' => 'महाराष्ट्र शासनाची महिला सक्षमीकरण योजना. पात्र महिलांना दरमहा आर्थिक सहाय्य.',
                'eligibility' => '<ul>
                    <li>महाराष्ट्राची रहिवासी महिला</li>
                    <li>वय 21 ते 65 वर्षे</li>
                    <li>कुटुंबाचे वार्षिक उत्पन्न 2.5 लाखांपेक्षा कमी</li>
                    <li>शासकीय नोकरी नसावी</li>
                </ul>',
                'benefits' => '<ul>
                    <li>दरमहा रु. 1,500 आर्थिक सहाय्य</li>
                    <li>थेट बँक खात्यात जमा</li>
                </ul>',
                'documents_required' => '<ul>
                    <li>आधार कार्ड</li>
                    <li>रेशन कार्ड / उत्पन्नाचा दाखला</li>
                    <li>बँक पासबुक</li>
                    <li>पासपोर्ट साईज फोटो</li>
                    <li>मोबाईल नंबर</li>
                </ul>',
                'how_to_apply' => 'narisakti.maharashtra.gov.in या वेबसाईटवर ऑनलाइन अर्ज करावा किंवा ग्रामपंचायत कार्यालयात भेट द्यावी.',
                'gr_link' => 'https://narisakti.maharashtra.gov.in/',
                'department' => 'महिला व बालविकास विभाग',
                'is_active' => true,
                'is_published' => true,
            ],
            [
                'title' => 'प्रधानमंत्री किसान सन्मान निधी योजना',
                'slug' => 'pm-kisan-' . time(),
                'description' => 'शेतकऱ्यांना दरवर्षी रु. 6,000 आर्थिक सहाय्य देणारी केंद्र शासनाची योजना.',
                'eligibility' => '<ul>
                    <li>शेतजमीन असलेला शेतकरी</li>
                    <li>आधार कार्ड बँक खात्याशी लिंक असावे</li>
                    <li>शासकीय नोकरदार नसावे</li>
                </ul>',
                'benefits' => '<ul>
                    <li>दरवर्षी रु. 6,000 (3 हप्त्यात)</li>
                    <li>दर 4 महिन्यांनी रु. 2,000</li>
                    <li>थेट बँक खात्यात जमा</li>
                </ul>',
                'documents_required' => '<ul>
                    <li>आधार कार्ड</li>
                    <li>7/12 उतारा</li>
                    <li>8-अ उतारा</li>
                    <li>बँक पासबुक</li>
                </ul>',
                'how_to_apply' => 'pmkisan.gov.in वर ऑनलाइन नोंदणी करावी किंवा ग्रामपंचायत कार्यालयात भेट द्यावी.',
                'gr_link' => 'https://pmkisan.gov.in/',
                'department' => 'कृषी विभाग',
                'is_active' => true,
                'is_published' => true,
            ],
        ];

        foreach ($schemes as $scheme) {
            Scheme::create($scheme);
        }
    }
}
