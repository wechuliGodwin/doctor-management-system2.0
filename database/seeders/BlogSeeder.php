<?php

// database/seeders/BlogSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Blog;

class BlogSeeder extends Seeder
{
    public function run()
    {
        Blog::create([
            'title' => "Dr. Dimingo's Inspiring Journey",
            'image' => 'https://kijabehospital.org/login/uploads/dr%20dimingo.jpg',
            'content' => "Dr. Dimingo's journey to becoming a pediatric surgeon began with a deep love for children and a natural ability to connect with them. It was the urgent shortage of paediatric surgeons in his home country of Gambia that truly fueled his determination. Like many low-income countries, Gambia faces a significant shortage of paediatric surgeons, with potentially only one or two specialists available nationwide (Global Initiative for Children’s Surgery, 2020). \"To be honest, I initially wanted to be a teacher,\" he reflects, \"but the need for skilled paediatricians in Gambia redirected my path.\" His passion for research blossomed under the influence of his mentors and colleagues here at the hospital. “While I’m naturally self-motivated, the support from the team at Kijabe has been incredibly encouraging and has made my journey much more manageable,” he says. In February, Dr. Dimingo was honored as the best presenter at the 2nd PAACS Senior Residents Annual Conference for his groundbreaking research on reducing Nil Per Os (NPO) time for pre-operative children. “Our goal was to safely shorten the fasting period, and this recognition validates our efforts,” he adds. Dr. Dimingo expresses heartfelt gratitude for the award, acknowledging the pivotal roles of Drs. Jason Axt, Matt Kynes, and Alex Mina. “Their contributions were crucial to this success!” he emphasizes. Looking ahead, Dr. Dimingo is excited about his future. “I’ve developed a strong interest in research and plan to continue on this path, especially when I relocate to Gambia, Liberia, or Sierra Leone. There is so much to study and too few people involved in research,” he shares with enthusiasm. We’re immensely proud of his achievements and dedication to pediatric surgery. We wish him continued success, confident that he will continue making significant impact in Kijabe, The Gambia, and beyond! #WeDoResearch #1millionlives",
            'meta_title' => "Dr. Dimingo's Journey - Kijabe Hospital",
            'meta_description' => "Discover the inspiring journey of Dr. Dimingo, a dedicated pediatric surgeon, and his impact in Gambia, Kijabe, and beyond.",
            'meta_keywords' => 'Pediatric Surgery, Dr. Dimingo, Kijabe Hospital, Gambia, Research'
        ]);

        Blog::create([
            'title' => 'Impacting lives beyond our borders',
            'image' => 'https://kijabehospital.org/login/uploads/beyondborders.jpg',
            'content' => "“When we arrived, we witnessed compassion. From the gate to the doctor who attended to her, this is unique. We haven’t seen this before, even though we’ve been to other facilities before finally coming to Kijabe,” shared Magdalene, Zipporah’s older sister. Zipporah is a young lady who had been enduring severe lower back pain for over a month. The family traveled from Tana River, seeking a second opinion after she was advised at a local facility that her condition required surgery. “I learned about your services from my mother and my physiotherapist. He (the physiotherapist) told me that I should seek a second opinion at Kijabe because they have the best specialists,” said Zipporah. Dr. Kitua, one of our orthopedic specialists, examined Zipporah and concluded that surgery was not necessary. “We suspected a deep infection of the vertebral body and disc. After tests, we decided to treat her for that. This did not require surgery,” Dr. Kitua explained. After treatment, Zipporah was discharged feeling much better! “I am happy God led me here. I thought I was never going to walk again. Thank you, Kijabe, for being keen on giving proper diagnoses and treatment,” Zipporah concluded with a smile. Happy to be impacting lives and putting smiles back on our patients' faces! #1millionlives #CompassionateHealthcare",
            'meta_title' => 'Impacting Lives Beyond Borders - Kijabe Hospital',
            'meta_description' => 'Learn how Kijabe Hospital is impacting lives beyond borders through compassionate healthcare and expert diagnoses.',
            'meta_keywords' => 'Compassionate Healthcare, Kijabe Hospital, Orthopedics, Second Opinion, Tana River'
        ]);

        Blog::create([
            'title' => 'We do research!',
            'image' => 'https://kijabehospital.org/login/uploads/Dr%20muse.jpg',
            'content' => "From a young age, I always knew I wanted to pursue a career where I could make a meaningful difference in people’s lives,” shares Dr. Muse, one of our paediatric surgery residents. Following in the footsteps of his Pan-African Academy of Christian Surgeons mentors, Dr. Muse chose a career in medicine, eventually specializing in paediatric surgery, while being active in research. “I have been involved in research since my medical school days. When I joined Kijabe Hospital for my residency, I was pleased to find an environment that supported and nurtured my research interests,” he says. Dr. Muse has been part of various research projects, including a recent one presented during our Research Day, where his team won first place with a paper on ‘Addressing the problem of paediatric central line-associated bloodstream infections at AIC Kijabe Hospital.’ In May this year, he did it again! His research on ‘Central venous catheter-associated bloodstream infections in pediatric patients’ received international recognition, winning the first-place prize at the prestigious Judah Folkman Award for Outstanding Research at the 2024 American Pediatric Surgery Association conference. “Alongside Dr Britney, we identified a critical issue and implemented interventions that reduced infections by almost half within a year,” he shares. When asked about his future goals, Dr. Muse humbly says, “I hope to help children across the East African region, train future pediatric surgeons, and improve patient care through research.” We’re incredibly proud of his achievements and look forward to his continued impact in pediatric care, both at our hospital and beyond. #1millionLives",
            'meta_title' => 'We Do Research - Kijabe Hospital',
            'meta_description' => 'Discover how Dr. Muse and Kijabe Hospital are advancing pediatric care through research and innovation.',
            'meta_keywords' => 'Research, Pediatric Surgery, Dr. Muse, Kijabe Hospital, Central Line Infections'
        ]);
    }
}
