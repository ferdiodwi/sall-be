<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Module;
use App\Models\Level;
use App\Models\VocabWord;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Answer;
use App\Models\Badge;
use App\Models\Challenge;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. SEED: Users & Portals
        $teacherUser = User::create([
            'name' => 'Guru SALL',
            'email' => 'guru@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'teacher',
        ]);

        Teacher::create([
            'id' => $teacherUser->id,
            'name' => 'Guru SALL',
            'subjects' => ['English for Fashion', 'Garment Construction'],
            'classes' => ['X-Tata Busana 1', 'X-Tata Busana 2'],
        ]);

        $studentUser = User::create([
            'name' => 'Siswa SALL',
            'email' => 'siswa@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'student',
            'class_id' => 'X-Tata Busana 1',
            'level' => 'beginner',
        ]);

        Student::create([
            'id' => $studentUser->id,
            'xp' => 120,
            'streak' => 3,
            'last_active' => Carbon::now(),
            'level' => 'beginner',
            'placement_score' => 80,
            'placement_date' => Carbon::now()->subDays(2),
            'modules_completed' => [],
            'vocab_mastered' => 5,
            'badges' => ['first_step'],
        ]);

        // 2. SEED: 5 Modules
        $modules = [
            [
                'number' => 1,
                'title' => 'Fashion Vocabulary Builder',
                'tagline' => 'Kuasai kosakata fashion dasar dalam bahasa Inggris',
                'emoji' => '👗',
                'order' => 1,
                'published' => true,
            ],
            [
                'number' => 2,
                'title' => 'Reading Station',
                'tagline' => 'Latih kemampuan membaca teks fashion umum',
                'emoji' => '📖',
                'order' => 2,
                'published' => true,
            ],
            [
                'number' => 3,
                'title' => 'Fashion Label Reader',
                'tagline' => 'Pahami setiap simbol dan tulisan di label pakaian',
                'emoji' => '🏷️',
                'order' => 3,
                'published' => true,
            ],
            [
                'number' => 4,
                'title' => 'Catalogue & Product Description Reader',
                'tagline' => 'Baca dan pahami katalog serta deskripsi produk fashion',
                'emoji' => '📋',
                'order' => 4,
                'published' => true,
            ],
            [
                'number' => 5,
                'title' => 'Technical Instructions Reader',
                'tagline' => 'Kuasai instruksi teknis menjahit dalam bahasa Inggris',
                'emoji' => '🔧',
                'order' => 5,
                'published' => true,
            ],
        ];

        foreach ($modules as $mod) {
            Module::create($mod);
        }

        // 3. SEED: Levels (Beginner & Intermediate content per module)
        $levels = [
            [
                'module_id' => 1,
                'level' => 'beginner',
                'content_html' => '<h3>Garment Construction Basics</h3><p>Let\'s learn the core parts of a piece of clothing. Every shirt, dress, and jacket is made up of distinct parts. The <b>collar</b> is the band of fabric around the neck. The <b>sleeve</b> is the part that covers your arm. The <b>hemline</b> is the bottom edge of the garment. Knowing these terms helps you understand garment construction.</p>',
            ],
            [
                'module_id' => 1,
                'level' => 'intermediate',
                'content_html' => '<h3>Fabric Properties & Composition</h3><p>Understanding fabric properties is essential for fashion design. Fabric <b>composition</b> describes the fibers used, such as cotton, polyester, or rayon. The <b>weave</b> refers to how the threads are interlaced (plain, twill, satin). The <b>drape</b> is how the fabric hangs or flows on a model. High drape fabrics like silk flow softly, while low drape fabrics like heavy denim are stiff.</p>',
            ],
            [
                'module_id' => 2,
                'level' => 'beginner',
                'content_html' => '<h3>Selecting Cotton Clothes</h3><p>Cotton is a popular choice for daily clothing. It is a <b>natural</b> fiber harvested from plants. Cotton fabric is highly <b>breathable</b>, which means it allows air to circulate, keeping you cool. It is also <b>durable</b> and can withstand many washes without wearing out easily.</p>',
            ],
            [
                'module_id' => 2,
                'level' => 'intermediate',
                'content_html' => '<h3>Sustainable Fashion and Textiles</h3><p>The fashion industry is shifting towards sustainability. Designers are choosing <b>organic</b> fibers grown without synthetic pesticides. Using <b>sustainable</b> practices helps reduce textile waste. Many modern clothes use eco-friendly <b>dye</b> to color fabrics without using harmful chemicals that harm the environment.</p>',
            ],
            [
                'module_id' => 3,
                'level' => 'beginner',
                'content_html' => '<h3>Understanding Care Labels</h3><p>Before you wash your new shirt, look at the care label. It tells you the proper <b>temperature</b> to use (e.g. wash cold at 30°C). It warns you if you should not use <b>bleach</b>, which can discolor the fabric. Always check if you need to <b>iron</b> the garment to remove wrinkles.</p>',
            ],
            [
                'module_id' => 3,
                'level' => 'intermediate',
                'content_html' => '<h3>Specialized Garment Care</h3><p>Delicate garments made of wool or silk require specialized care. Some blends cannot be washed in water and must be cleaned with a chemical <b>solvent</b> via professional dry cleaning. When machine washing, use a <b>delicate</b> cycle and avoid high heat in the <b>tumble</b> dryer to prevent shrinkage.</p>',
            ],
            [
                'module_id' => 4,
                'level' => 'beginner',
                'content_html' => '<h3>Denim Jacket Specifications</h3><p>This classic denim jacket is made of sturdy cotton <b>denim</b>. It features two chest <b>pockets</b> for storing small items. It has a regular <b>fit</b> that is comfortable for daily wear. Refer to the size chart to find your correct <b>chest</b> size.</p>',
            ],
            [
                'module_id' => 4,
                'level' => 'intermediate',
                'content_html' => '<h3>High-Performance Activewear Specs</h3><p>Performance activewear utilizes technical materials. The fabric is <b>moisture-wicking</b>, keeping the athlete dry by pulling sweat away from the body. It blends polyester with <b>spandex</b>, a highly <b>elastic</b> synthetic fiber that provides maximum stretch and flexibility during exercise.</p>',
            ],
            [
                'module_id' => 5,
                'level' => 'beginner',
                'content_html' => '<h3>How to Sew a Straight Stitch</h3><p>To start sewing, set up your sewing <b>machine</b>. Thread the <b>needle</b> with a strong sewing <b>thread</b>. Place the fabric under the foot and press the pedal gently to create a clean, <b>straight</b> line of stitches.</p>',
            ],
            [
                'module_id' => 5,
                'level' => 'intermediate',
                'content_html' => '<h3>Inserting a Hidden Zipper</h3><p>To insert a hidden <b>zipper</b>, first press the <b>seam-allowance</b> open with an iron. Align the zipper teeth along the seam line. Cut the fabric on the <b>bias</b> if you need extra flexibility in curved seams. Always check the <b>alignment</b> before sewing the final stitch.</p>',
            ],
        ];

        foreach ($levels as $lvl) {
            Level::create($lvl);
        }

        // 4. SEED: Badges
        $badges = [
            [
                'name' => 'first_step',
                'emoji' => '🎯',
                'description' => 'Langkah Pertama — Selesaikan Placement Quiz',
                'requirement' => ['type' => 'placement_quiz', 'value' => 1],
            ],
            [
                'name' => 'on_fire',
                'emoji' => '🔥',
                'description' => 'On Fire — Streak belajar 7 hari berturut-turut',
                'requirement' => ['type' => 'streak', 'value' => 7],
            ],
            [
                'name' => 'bookworm',
                'emoji' => '📖',
                'description' => 'Kutu Buku — Selesaikan 3 modul pembelajaran',
                'requirement' => ['type' => 'modules_completed', 'value' => 3],
            ],
            [
                'name' => 'vocabulary_master',
                'emoji' => '💎',
                'description' => 'Vocabulary Master — Tambahkan 50 kata ke Word Wall',
                'requirement' => ['type' => 'word_wall_count', 'value' => 50],
            ],
            [
                'name' => 'quiz_champion',
                'emoji' => '🏅',
                'description' => 'Quiz Champion — Raih skor sempurna di 1 kuis',
                'requirement' => ['type' => 'perfect_quiz', 'value' => 1],
            ],
            [
                'name' => 'journaling_pro',
                'emoji' => '✍️',
                'description' => 'Journaling Pro — Tulis jurnal sebanyak 10 kali',
                'requirement' => ['type' => 'journal_count', 'value' => 10],
            ],
        ];

        foreach ($badges as $bg) {
            Badge::create($bg);
        }

        // 5. SEED: 50 Vocabulary Words
        $vocabWords = [
            // Modul 1 Beginner
            [ 'module_id' => 1, 'level' => 'beginner', 'word' => 'collar', 'meaning' => 'kerah pakaian', 'example' => 'The shirt has a white collar around the neck.', 'emoji' => '👔', 'category' => 'Parts', 'order' => 1],
            [ 'module_id' => 1, 'level' => 'beginner', 'word' => 'sleeve', 'meaning' => 'lengan baju', 'example' => 'The long sleeve covers my arm completely.', 'emoji' => '🧥', 'category' => 'Parts', 'order' => 2],
            [ 'module_id' => 1, 'level' => 'beginner', 'word' => 'hemline', 'meaning' => 'keliman bawah', 'example' => 'She adjusted the hemline of the dress.', 'emoji' => '👗', 'category' => 'Parts', 'order' => 3],
            [ 'module_id' => 1, 'level' => 'beginner', 'word' => 'pocket', 'meaning' => 'saku, kantong', 'example' => 'I put my keys inside my front pocket.', 'emoji' => '👖', 'category' => 'Parts', 'order' => 4],
            [ 'module_id' => 1, 'level' => 'beginner', 'word' => 'seam', 'meaning' => 'garis jahitan', 'example' => 'The seam where the fabrics join is straight.', 'emoji' => '🧵', 'category' => 'Construction', 'order' => 5],

            // Modul 1 Intermediate
            [ 'module_id' => 1, 'level' => 'intermediate', 'word' => 'composition', 'meaning' => 'komposisi bahan', 'example' => 'Check the tag for the composition of the fabric.', 'emoji' => '🏷️', 'category' => 'Properties', 'order' => 1],
            [ 'module_id' => 1, 'level' => 'intermediate', 'word' => 'weave', 'meaning' => 'tenunan', 'example' => 'Twill weave creates diagonal lines on denim.', 'emoji' => '🕸️', 'category' => 'Structure', 'order' => 2],
            [ 'module_id' => 1, 'level' => 'intermediate', 'word' => 'drape', 'meaning' => 'jatuh kain', 'example' => 'Silk fabric has a beautiful drape when worn.', 'emoji' => '🧣', 'category' => 'Properties', 'order' => 3],
            [ 'module_id' => 1, 'level' => 'intermediate', 'word' => 'shrinkage', 'meaning' => 'penyusutan kain', 'example' => 'Hot water causes high shrinkage in wool clothes.', 'emoji' => '🔥', 'category' => 'Properties', 'order' => 4],
            [ 'module_id' => 1, 'level' => 'intermediate', 'word' => 'thread', 'meaning' => 'benang jahit', 'example' => 'The sewing thread is made of polyester.', 'emoji' => '🧵', 'category' => 'Materials', 'order' => 5],

            // Modul 2 Beginner
            [ 'module_id' => 2, 'level' => 'beginner', 'word' => 'natural', 'meaning' => 'alami', 'example' => 'Cotton is a natural fiber from plants.', 'emoji' => '🌱', 'category' => 'General', 'order' => 1],
            [ 'module_id' => 2, 'level' => 'beginner', 'word' => 'breathable', 'meaning' => 'tembus udara, adem', 'example' => 'Linen is a breathable fabric for hot weather.', 'emoji' => '💨', 'category' => 'Properties', 'order' => 2],
            [ 'module_id' => 2, 'level' => 'beginner', 'word' => 'durable', 'meaning' => 'awet, tahan lama', 'example' => 'Leather is a durable material for jackets.', 'emoji' => '🛡️', 'category' => 'Properties', 'order' => 3],
            [ 'module_id' => 2, 'level' => 'beginner', 'word' => 'cotton', 'meaning' => 'katun', 'example' => 'This cotton t-shirt is very soft.', 'emoji' => '👕', 'category' => 'Materials', 'order' => 4],
            [ 'module_id' => 2, 'level' => 'beginner', 'word' => 'fiber', 'meaning' => 'serat kain', 'example' => 'Wool is a warm animal fiber.', 'emoji' => '🐑', 'category' => 'Materials', 'order' => 5],

            // Modul 2 Intermediate
            [ 'module_id' => 2, 'level' => 'intermediate', 'word' => 'organic', 'meaning' => 'organik', 'example' => 'We use organic cotton grown without chemicals.', 'emoji' => '🌿', 'category' => 'Materials', 'order' => 1],
            [ 'module_id' => 2, 'level' => 'intermediate', 'word' => 'sustainable', 'meaning' => 'ramah lingkungan', 'example' => 'Eco-friendly fashion supports sustainable production.', 'emoji' => '🌍', 'category' => 'General', 'order' => 2],
            [ 'module_id' => 2, 'level' => 'intermediate', 'word' => 'textile', 'meaning' => 'tekstil', 'example' => 'The factory produces high-quality textile goods.', 'emoji' => '🏭', 'category' => 'Materials', 'order' => 3],
            [ 'module_id' => 2, 'level' => 'intermediate', 'word' => 'dye', 'meaning' => 'pewarna pakaian', 'example' => 'Natural dye is used to color the organic cloth.', 'emoji' => '🎨', 'category' => 'Chemicals', 'order' => 4],
            [ 'module_id' => 2, 'level' => 'intermediate', 'word' => 'biodegrade', 'meaning' => 'terurai secara alami', 'example' => 'Natural fibers biodegrade easily in soil.', 'emoji' => '🍂', 'category' => 'Properties', 'order' => 5],

            // Modul 3 Beginner
            [ 'module_id' => 3, 'level' => 'beginner', 'word' => 'temperature', 'meaning' => 'suhu cuci', 'example' => 'Wash the shirt at a low temperature of 30°C.', 'emoji' => '🌡️', 'category' => 'Care', 'order' => 1],
            [ 'module_id' => 3, 'level' => 'beginner', 'word' => 'bleach', 'meaning' => 'pemutih pakaian', 'example' => 'Do not use bleach on colored clothing.', 'emoji' => '🧪', 'category' => 'Care', 'order' => 2],
            [ 'module_id' => 3, 'level' => 'beginner', 'word' => 'iron', 'meaning' => 'setrika', 'example' => 'Use a hot iron to remove fabric wrinkles.', 'emoji' => '💨', 'category' => 'Care', 'order' => 3],
            [ 'module_id' => 3, 'level' => 'beginner', 'word' => 'wash', 'meaning' => 'mencuci', 'example' => 'You should wash dark colors separately.', 'emoji' => '🧼', 'category' => 'Care', 'order' => 4],
            [ 'module_id' => 3, 'level' => 'beginner', 'word' => 'dry', 'meaning' => 'mengeringkan', 'example' => 'Hang the wet dress outside to dry.', 'emoji' => '☀️', 'category' => 'Care', 'order' => 5],

            // Modul 3 Intermediate
            [ 'module_id' => 3, 'level' => 'intermediate', 'word' => 'solvent', 'meaning' => 'cairan pelarut kimia', 'example' => 'Dry cleaning uses a chemical solvent instead of water.', 'emoji' => '🧪', 'category' => 'Dry Cleaning', 'order' => 1],
            [ 'module_id' => 3, 'level' => 'intermediate', 'word' => 'delicate', 'meaning' => 'halus, rapuh', 'example' => 'Lace is a delicate fabric that tears easily.', 'emoji' => '🕸️', 'category' => 'Properties', 'order' => 2],
            [ 'module_id' => 3, 'level' => 'intermediate', 'word' => 'tumble', 'meaning' => 'putaran mesin pengering', 'example' => 'Do not tumble dry this woolen sweater.', 'emoji' => '🔄', 'category' => 'Care', 'order' => 3],
            [ 'module_id' => 3, 'level' => 'intermediate', 'word' => 'blend', 'meaning' => 'campuran serat', 'example' => 'This fabric is a cotton-polyester blend.', 'emoji' => '🌾', 'category' => 'Materials', 'order' => 4],
            [ 'module_id' => 3, 'level' => 'intermediate', 'word' => 'chemical', 'meaning' => 'bahan kimia', 'example' => 'Synthetic dyes contain strong chemical compounds.', 'emoji' => '⚗️', 'category' => 'General', 'order' => 5],

            // Modul 4 Beginner
            [ 'module_id' => 4, 'level' => 'beginner', 'word' => 'denim', 'meaning' => 'denim, jeans', 'example' => 'Blue jeans are made of denim fabric.', 'emoji' => '👖', 'category' => 'Materials', 'order' => 1],
            [ 'module_id' => 4, 'level' => 'beginner', 'word' => 'pockets', 'meaning' => 'kantong-kantong', 'example' => 'This jacket has four pockets on the front.', 'emoji' => '🧥', 'category' => 'Parts', 'order' => 2],
            [ 'module_id' => 4, 'level' => 'beginner', 'word' => 'fit', 'meaning' => 'kesesuaian pakaian', 'example' => 'The shirt has a slim fit style.', 'emoji' => '👕', 'category' => 'Style', 'order' => 3],
            [ 'module_id' => 4, 'level' => 'beginner', 'word' => 'chest', 'meaning' => 'lingkar dada', 'example' => 'Measure your chest circumference before ordering.', 'emoji' => '📏', 'category' => 'Sizing', 'order' => 4],
            [ 'module_id' => 4, 'level' => 'beginner', 'word' => 'size', 'meaning' => 'ukuran', 'example' => 'What size of pants do you wear?', 'emoji' => '🏷️', 'category' => 'Sizing', 'order' => 5],

            // Modul 4 Intermediate
            [ 'module_id' => 4, 'level' => 'intermediate', 'word' => 'moisture-wicking', 'meaning' => 'menyerap keringat', 'example' => 'Athletic wear uses moisture-wicking technology.', 'emoji' => '💦', 'category' => 'Properties', 'order' => 1],
            [ 'module_id' => 4, 'level' => 'intermediate', 'word' => 'spandex', 'meaning' => 'spandeks, serat elastis', 'example' => 'Sportswear has spandex for high flexibility.', 'emoji' => '🏃', 'category' => 'Materials', 'order' => 2],
            [ 'module_id' => 4, 'level' => 'intermediate', 'word' => 'elastic', 'meaning' => 'elastis', 'example' => 'The waistband has an elastic band inside.', 'emoji' => '🎗️', 'category' => 'Materials', 'order' => 3],
            [ 'module_id' => 4, 'level' => 'intermediate', 'word' => 'stretch', 'meaning' => 'meregang', 'example' => 'This fabric can stretch in four directions.', 'emoji' => '↔️', 'category' => 'Properties', 'order' => 4],
            [ 'module_id' => 4, 'level' => 'intermediate', 'word' => 'synthetic', 'meaning' => 'sintetis, buatan', 'example' => 'Nylon is a synthetic polymer material.', 'emoji' => '🧬', 'category' => 'Materials', 'order' => 5],

            // Modul 5 Beginner
            [ 'module_id' => 5, 'level' => 'beginner', 'word' => 'machine', 'meaning' => 'mesin jahit', 'example' => 'Turn on the sewing machine before starting.', 'emoji' => '🔌', 'category' => 'Tools', 'order' => 1],
            [ 'module_id' => 5, 'level' => 'beginner', 'word' => 'needle', 'meaning' => 'jarum jahit', 'example' => 'Insert the thread through the needle eye.', 'emoji' => '📍', 'category' => 'Tools', 'order' => 2],
            [ 'module_id' => 5, 'level' => 'beginner', 'word' => 'thread', 'meaning' => 'benang jahit', 'example' => 'The sewing thread is wound on a bobbin.', 'emoji' => '🧵', 'category' => 'Materials', 'order' => 3],
            [ 'module_id' => 5, 'level' => 'beginner', 'word' => 'straight', 'meaning' => 'lurus', 'example' => 'Sew a straight line along the edge.', 'emoji' => '📏', 'category' => 'Technique', 'order' => 4],
            [ 'module_id' => 5, 'level' => 'beginner', 'word' => 'stitch', 'meaning' => 'tusuk jahitan', 'example' => 'Make a tight stitch to join the fabrics.', 'emoji' => '🧵', 'category' => 'Technique', 'order' => 5],

            // Modul 5 Intermediate
            [ 'module_id' => 5, 'level' => 'intermediate', 'word' => 'zipper', 'meaning' => 'risleting', 'example' => 'The dress has a hidden zipper at the back.', 'emoji' => '🤐', 'category' => 'Parts', 'order' => 1],
            [ 'module_id' => 5, 'level' => 'intermediate', 'word' => 'seam-allowance', 'meaning' => 'kampuh jahitan', 'example' => 'Leave a 1.5 cm seam-allowance when cutting.', 'emoji' => '📏', 'category' => 'Technique', 'order' => 2],
            [ 'module_id' => 5, 'level' => 'intermediate', 'word' => 'hem', 'meaning' => 'kelim bawah', 'example' => 'Fold the hem twice and sew it.', 'emoji' => '👗', 'category' => 'Technique', 'order' => 3],
            [ 'module_id' => 5, 'level' => 'intermediate', 'word' => 'bias', 'meaning' => 'potongan serong', 'example' => 'Cutting on the bias makes the fabric stretchier.', 'emoji' => '✂️', 'category' => 'Technique', 'order' => 4],
            [ 'module_id' => 5, 'level' => 'intermediate', 'word' => 'alignment', 'meaning' => 'keselarasan jahitan', 'example' => 'Ensure proper alignment of the collar before sewing.', 'emoji' => '📐', 'category' => 'Technique', 'order' => 5],
        ];

        foreach ($vocabWords as $vocab) {
            VocabWord::create($vocab);
        }

        // 6. SEED: Quizzes
        $quizzes = [
            // Placement
            [ 'module_id' => null, 'level' => 'placement', 'title' => 'Placement Quiz — Tes Kemampuan Awal', 'activity_type' => 'placement'],

            // Modul 1
            [ 'module_id' => 1, 'level' => 'beginner', 'title' => 'Kuis Modul 1: Bagian Pakaian Dasar', 'activity_type' => 'quiz'],
            [ 'module_id' => 1, 'level' => 'intermediate', 'title' => 'Kuis Modul 1: Sifat & Tenunan Kain', 'activity_type' => 'quiz'],

            // Modul 2
            [ 'module_id' => 2, 'level' => 'beginner', 'title' => 'Kuis Modul 2: Serat Alami & Kain', 'activity_type' => 'quiz'],
            [ 'module_id' => 2, 'level' => 'intermediate', 'title' => 'Kuis Modul 2: Eco-Fashion & Tekstil', 'activity_type' => 'quiz'],

            // Modul 3
            [ 'module_id' => 3, 'level' => 'beginner', 'title' => 'Kuis Modul 3: Instruksi Label Cuci', 'activity_type' => 'quiz'],
            [ 'module_id' => 3, 'level' => 'intermediate', 'title' => 'Kuis Modul 3: Perawatan Khusus Wool & Sutra', 'activity_type' => 'quiz'],

            // Modul 4
            [ 'module_id' => 4, 'level' => 'beginner', 'title' => 'Kuis Modul 4: Membaca Detail Katalog', 'activity_type' => 'quiz'],
            [ 'module_id' => 4, 'level' => 'intermediate', 'title' => 'Kuis Modul 4: Spesifikasi Activewear Teknis', 'activity_type' => 'quiz'],

            // Modul 5
            [ 'module_id' => 5, 'level' => 'beginner', 'title' => 'Kuis Modul 5: Dasar Jahitan Mesin', 'activity_type' => 'quiz'],
            [ 'module_id' => 5, 'level' => 'intermediate', 'title' => 'Kuis Modul 5: Pemasangan Zipper Tersembunyi', 'activity_type' => 'quiz'],
        ];

        foreach ($quizzes as $qz) {
            Quiz::create($qz);
        }

        // 7. SEED: Questions & Answers (Placement and Module Quizzes)
        $qaPairs = [
            // --- PLACEMENT QUIZ ---
            [
                'question' => [
                    'quiz_id' => 1,
                    'type' => 'vocab',
                    'prompt' => 'What does the word "cotton" mean in fashion?',
                    'options' => ['A. Wool fabric', 'B. Natural fiber from plants', 'C. Synthetic material', 'D. Silk material'],
                    'topic' => 'fabric_types',
                    'order' => 1,
                ],
                'answer' => [
                    'answer_index' => 1,
                    'explanation_correct' => 'Benar! Cotton (kapas) adalah serat alami yang berasal dari tanaman kapas.',
                    'explanation_wrong' => 'Salah. Cotton bukan wol, bukan sintetis, dan bukan sutra.',
                    'related_vocab' => [['word' => 'cotton', 'meaning' => 'kapas, serat alami']],
                ]
            ],
            [
                'question' => [
                    'quiz_id' => 1,
                    'type' => 'vocab',
                    'prompt' => 'Which of the following is a synthetic fabric?',
                    'options' => ['A. Silk', 'B. Linen', 'C. Polyester', 'D. Wool'],
                    'topic' => 'fabric_types',
                    'order' => 2,
                ],
                'answer' => [
                    'answer_index' => 2,
                    'explanation_correct' => 'Benar! Polyester adalah kain sintetis (buatan manusia) yang terbuat dari bahan kimia.',
                    'explanation_wrong' => 'Salah. Silk (sutra), Linen (rami), dan Wool (wol) adalah serat alami.',
                    'related_vocab' => [['word' => 'synthetic', 'meaning' => 'sintetis'], ['word' => 'polyester', 'meaning' => 'poliester']],
                ]
            ],
            [
                'question' => [
                    'quiz_id' => 1,
                    'type' => 'reading',
                    'prompt' => 'Based on the label, what temperature should this garment be washed at?',
                    'passage' => 'Care Instructions: Machine wash cold (30°C). Do not bleach. Tumble dry low. Iron on low heat. Do not dry clean.',
                    'options' => ['A. 60°C', 'B. 40°C', 'C. 30°C', 'D. 50°C'],
                    'topic' => 'label_reading',
                    'order' => 3,
                ],
                'answer' => [
                    'answer_index' => 2,
                    'explanation_correct' => 'Benar! Label tersebut menyatakan "Machine wash cold (30°C)".',
                    'explanation_wrong' => 'Salah. Label menyatakan "Machine wash cold (30°C)" — suhunya adalah 30°C.',
                    'related_vocab' => [['word' => 'garment', 'meaning' => 'pakaian']],
                ]
            ],
            [
                'question' => [
                    'quiz_id' => 1,
                    'type' => 'true_false',
                    'prompt' => 'The label says this garment can be dry cleaned.',
                    'passage' => 'Care Instructions: Machine wash cold (30°C). Do not bleach. Tumble dry low. Iron on low heat. Do not dry clean.',
                    'options' => ['A. True', 'B. False'],
                    'topic' => 'label_reading',
                    'order' => 4,
                ],
                'answer' => [
                    'answer_index' => 1,
                    'explanation_correct' => 'Benar! Pernyataan tersebut SALAH (False). Label jelas menyatakan "Do not dry clean".',
                    'explanation_wrong' => 'Salah. Baca label lagi — tertulis "Do not dry clean".',
                    'related_vocab' => [['word' => 'dry clean', 'meaning' => 'cuci kering']],
                ]
            ],
            [
                'question' => [
                    'quiz_id' => 1,
                    'type' => 'vocab',
                    'prompt' => 'What is the meaning of "hemline" in fashion?',
                    'options' => ['A. The collar of a shirt', 'B. The bottom edge of a garment', 'C. The sleeve of a jacket', 'D. The waistband of pants'],
                    'topic' => 'vocabulary_general',
                    'order' => 5,
                ],
                'answer' => [
                    'answer_index' => 1,
                    'explanation_correct' => 'Benar! Hemline adalah tepi bawah dari sebuah pakaian (gaun, rok, atau celana).',
                    'explanation_wrong' => 'Salah. Hemline bukan kerah, lengan, atau ikat pinggang. Hemline adalah tepi bawah pakaian.',
                    'related_vocab' => [['word' => 'hemline', 'meaning' => 'tepi bawah pakaian']],
                ]
            ],
            [
                'question' => [
                    'quiz_id' => 1,
                    'type' => 'reading',
                    'prompt' => 'What material is this jacket made of?',
                    'passage' => 'Product: Classic Denim Jacket. Material: 100% Cotton Denim. Color: Indigo Blue. Features: Button front closure, two chest pockets, two side pockets. Care: Machine wash cold.',
                    'options' => ['A. Polyester', 'B. Silk', 'C. Cotton Denim', 'D. Linen'],
                    'topic' => 'catalogue_reading',
                    'order' => 6,
                ],
                'answer' => [
                    'answer_index' => 2,
                    'explanation_correct' => 'Benar! Deskripsi produk menyatakan "Material: 100% Cotton Denim".',
                    'explanation_wrong' => 'Salah. Bacalah bagian "Material" pada deskripsi produk. Tertulis "100% Cotton Denim".',
                    'related_vocab' => [['word' => 'denim', 'meaning' => 'denim, kain jeans']],
                ]
            ],
            [
                'question' => [
                    'quiz_id' => 1,
                    'type' => 'vocab',
                    'prompt' => 'What does "seam" mean in sewing instructions?',
                    'options' => ['A. The pattern printed on fabric', 'B. The line where two pieces of fabric are sewn together', 'C. The button on a shirt', 'D. The zipper of a jacket'],
                    'topic' => 'technical_instructions',
                    'order' => 7,
                ],
                'answer' => [
                    'answer_index' => 1,
                    'explanation_correct' => 'Benar! Seam adalah garis jahitan di mana dua potongan kain dijahit bersama.',
                    'explanation_wrong' => 'Salah. Seam bukan motif kain, kancing, atau risleting.',
                    'related_vocab' => [['word' => 'seam', 'meaning' => 'jahitan, sambungan']],
                ]
            ],
            [
                'question' => [
                    'quiz_id' => 1,
                    'type' => 'reading',
                    'prompt' => 'What is the seam allowance mentioned in the instructions?',
                    'passage' => 'Sewing Instructions: Step 1 — Cut fabric pieces according to pattern. Step 2 — Pin pieces right sides together. Step 3 — Sew along marked line with 1.5 cm seam allowance. Step 4 — Press seams open with iron.',
                    'options' => ['A. 1 cm', 'B. 2 cm', 'C. 1.5 cm', 'D. 2.5 cm'],
                    'topic' => 'technical_instructions',
                    'order' => 8,
                ],
                'answer' => [
                    'answer_index' => 2,
                    'explanation_correct' => 'Benar! Instruksi menyatakan "with 1.5 cm seam allowance".',
                    'explanation_wrong' => 'Salah. Bacalah instruksi dengan teliti. Tertulis "1.5 cm seam allowance".',
                    'related_vocab' => [['word' => 'seam allowance', 'meaning' => 'kampuh jahitan']],
                ]
            ],
            [
                'question' => [
                    'quiz_id' => 1,
                    'type' => 'vocab',
                    'prompt' => 'What is a "collar" in fashion terminology?',
                    'options' => ['A. The part that covers the shoulders', 'B. The part around the neck of a garment', 'C. The bottom part of a shirt', 'D. The front opening of a jacket'],
                    'topic' => 'vocabulary_general',
                    'order' => 9,
                ],
                'answer' => [
                    'answer_index' => 1,
                    'explanation_correct' => 'Benar! Collar (kerah) adalah bagian pakaian yang melingkari leher.',
                    'explanation_wrong' => 'Salah. Collar bukan bahu, hemline, atau bukaan depan.',
                    'related_vocab' => [['word' => 'collar', 'meaning' => 'kerah pakaian']],
                ]
            ],
            [
                'question' => [
                    'quiz_id' => 1,
                    'type' => 'true_false',
                    'prompt' => 'According to the product description, this jacket has four pockets in total.',
                    'passage' => 'Product: Classic Denim Jacket. Material: 100% Cotton Denim. Color: Indigo Blue. Features: Button front closure, two chest pockets, two side pockets. Care: Machine wash cold.',
                    'options' => ['A. True', 'B. False'],
                    'topic' => 'reading_comprehension',
                    'order' => 10,
                ],
                'answer' => [
                    'answer_index' => 0,
                    'explanation_correct' => 'Benar! Deskripsi menyebutkan "two chest pockets, two side pockets" — total 4 kantong.',
                    'explanation_wrong' => 'Salah. Hitung kantong yang disebutkan: "two chest pockets" + "two side pockets" = 4 kantong total.',
                    'related_vocab' => [['word' => 'pocket', 'meaning' => 'saku']],
                ]
            ],

            // --- MODUL 1 BEGINNER QUIZ ---
            [
                'question' => [
                    'quiz_id' => 2,
                    'type' => 'vocab',
                    'prompt' => 'Which part of a shirt wraps around the neck?',
                    'options' => ['A. Sleeve', 'B. Hemline', 'C. Collar', 'D. Pocket'],
                    'topic' => 'garment_parts',
                    'order' => 1,
                ],
                'answer' => [
                    'answer_index' => 2,
                    'explanation_correct' => 'Benar! Collar (kerah) adalah bagian kain yang melingkari leher.',
                    'explanation_wrong' => 'Salah. Sleeve membungkus lengan, Hemline di ujung bawah, Pocket adalah saku.',
                    'related_vocab' => [['word' => 'collar', 'meaning' => 'kerah pakaian']],
                ]
            ],
            [
                'question' => [
                    'quiz_id' => 2,
                    'type' => 'vocab',
                    'prompt' => 'What is the part of a garment that covers the arm?',
                    'options' => ['A. Collar', 'B. Sleeve', 'C. Seam', 'D. Hemline'],
                    'topic' => 'garment_parts',
                    'order' => 2,
                ],
                'answer' => [
                    'answer_index' => 1,
                    'explanation_correct' => 'Benar! Sleeve (lengan baju) adalah bagian pakaian yang menutupi lengan.',
                    'explanation_wrong' => 'Salah. Collar adalah kerah, Seam adalah garis jahitan, Hemline adalah ujung bawah.',
                    'related_vocab' => [['word' => 'sleeve', 'meaning' => 'lengan baju']],
                ]
            ],
            [
                'question' => [
                    'quiz_id' => 2,
                    'type' => 'true_false',
                    'prompt' => 'The bottom edge of a dress is called the hemline.',
                    'options' => ['A. True', 'B. False'],
                    'topic' => 'garment_parts',
                    'order' => 3,
                ],
                'answer' => [
                    'answer_index' => 0,
                    'explanation_correct' => 'Benar! Ujung garis bawah pakaian disebut hemline.',
                    'explanation_wrong' => 'Salah. Pernyataan tersebut BENAR. Ujung bawah pakaian memang disebut hemline.',
                    'related_vocab' => [['word' => 'hemline', 'meaning' => 'keliman bawah']],
                ]
            ],

            // --- MODUL 1 INTERMEDIATE QUIZ ---
            [
                'question' => [
                    'quiz_id' => 3,
                    'type' => 'vocab',
                    'prompt' => 'Which word refers to the percentage of different fibers used to make a fabric?',
                    'options' => ['A. Weave', 'B. Composition', 'C. Drape', 'D. Shrinkage'],
                    'topic' => 'fabric_properties',
                    'order' => 1,
                ],
                'answer' => [
                    'answer_index' => 1,
                    'explanation_correct' => 'Benar! Composition adalah persentase jenis serat pembentuk kain (misal: 60% katun, 40% poliester).',
                    'explanation_wrong' => 'Salah. Weave adalah tenunan, Drape adalah kejatuhan kain, Shrinkage adalah penyusutan.',
                    'related_vocab' => [['word' => 'composition', 'meaning' => 'komposisi bahan']],
                ]
            ],
            [
                'question' => [
                    'quiz_id' => 3,
                    'type' => 'vocab',
                    'prompt' => 'How a fabric flows and hangs on a body or mannequin is called its _____.',
                    'options' => ['A. Weave', 'B. Drape', 'C. Thread', 'D. Composition'],
                    'topic' => 'fabric_properties',
                    'order' => 2,
                ],
                'answer' => [
                    'answer_index' => 1,
                    'explanation_correct' => 'Benar! Drape mengacu pada kelenturan kain saat digantung atau dipakai.',
                    'explanation_wrong' => 'Salah. Weave adalah pola silangan benang, Thread adalah benang jahit, Composition adalah persentase kandungan serat.',
                    'related_vocab' => [['word' => 'drape', 'meaning' => 'jatuh kain']],
                ]
            ],
            [
                'question' => [
                    'quiz_id' => 3,
                    'type' => 'true_false',
                    'prompt' => 'Wool garments usually have lower shrinkage rates than synthetic materials when washed in hot water.',
                    'options' => ['A. True', 'B. False'],
                    'topic' => 'fabric_properties',
                    'order' => 3,
                ],
                'answer' => [
                    'answer_index' => 1,
                    'explanation_correct' => 'Benar! Wool memiliki tingkat penyusutan (shrinkage) yang sangat tinggi di air panas dibanding serat sintetis.',
                    'explanation_wrong' => 'Salah. Pernyataan tersebut SALAH. Serat wol menyusut jauh lebih banyak daripada serat sintetis.',
                    'related_vocab' => [['word' => 'shrinkage', 'meaning' => 'penyusutan kain']],
                ]
            ],

            // --- MODUL 2 BEGINNER QUIZ ---
            [
                'question' => [
                    'quiz_id' => 4,
                    'type' => 'vocab',
                    'prompt' => 'Which type of fiber comes directly from plants or animals?',
                    'options' => ['A. Synthetic', 'B. Polyester', 'C. Natural', 'D. Nylon'],
                    'topic' => 'fiber_origins',
                    'order' => 1,
                ],
                'answer' => [
                    'answer_index' => 2,
                    'explanation_correct' => 'Benar! Serat Natural (alami) berasal langsung dari alam (tanaman/hewan).',
                    'explanation_wrong' => 'Salah. Synthetic, Polyester, dan Nylon adalah serat buatan manusia dari zat kimia.',
                    'related_vocab' => [['word' => 'natural', 'meaning' => 'alami']],
                ]
            ],
            [
                'question' => [
                    'quiz_id' => 4,
                    'type' => 'vocab',
                    'prompt' => 'What fabric quality allows air to pass through easily, keeping you cool?',
                    'options' => ['A. Durable', 'B. Stiff', 'C. Breathable', 'D. Heavy'],
                    'topic' => 'fabric_properties',
                    'order' => 2,
                ],
                'answer' => [
                    'answer_index' => 2,
                    'explanation_correct' => 'Benar! Breathable (tembus udara/adem) memungkinkan sirkulasi udara yang baik.',
                    'explanation_wrong' => 'Salah. Durable berarti awet, Stiff berarti kaku, Heavy berarti berat.',
                    'related_vocab' => [['word' => 'breathable', 'meaning' => 'tembus udara, adem']],
                ]
            ],
            [
                'question' => [
                    'quiz_id' => 4,
                    'type' => 'true_false',
                    'prompt' => 'A durable fabric is one that wears out very quickly after one wash.',
                    'options' => ['A. True', 'B. False'],
                    'topic' => 'fabric_properties',
                    'order' => 3,
                ],
                'answer' => [
                    'answer_index' => 1,
                    'explanation_correct' => 'Benar! Pernyataan tersebut SALAH. Durable berarti kuat dan tahan lama, tidak mudah rusak.',
                    'explanation_wrong' => 'Salah. Durable berarti awet, bukan cepat rusak.',
                    'related_vocab' => [['word' => 'durable', 'meaning' => 'awet, tahan lama']],
                ]
            ],

            // --- MODUL 2 INTERMEDIATE QUIZ ---
            [
                'question' => [
                    'quiz_id' => 5,
                    'type' => 'vocab',
                    'prompt' => 'Fashion practices that support environmental safety and reuse of resources are called _____.',
                    'options' => ['A. Chemical', 'B. Sustainable', 'C. Synthetic', 'D. Fast fashion'],
                    'topic' => 'eco_fashion',
                    'order' => 1,
                ],
                'answer' => [
                    'answer_index' => 1,
                    'explanation_correct' => 'Benar! Sustainable fashion adalah gerakan mode ramah lingkungan berkelanjutan.',
                    'explanation_wrong' => 'Salah. Fast fashion dan bahan kimia merusak lingkungan.',
                    'related_vocab' => [['word' => 'sustainable', 'meaning' => 'ramah lingkungan']],
                ]
            ],
            [
                'question' => [
                    'quiz_id' => 5,
                    'type' => 'vocab',
                    'prompt' => 'Fibers grown without using harmful chemicals or synthetic fertilizers are called _____.',
                    'options' => ['A. Polyester', 'B. Organic', 'C. Acrylic', 'D. Nylon'],
                    'topic' => 'eco_materials',
                    'order' => 2,
                ],
                'answer' => [
                    'answer_index' => 1,
                    'explanation_correct' => 'Benar! Organic (organik) ditanam secara alami bebas pupuk kimia buatan.',
                    'explanation_wrong' => 'Salah. Polyester, Nylon, and Acrylic adalah serat buatan berbasis plastik kimia.',
                    'related_vocab' => [['word' => 'organic', 'meaning' => 'organik']],
                ]
            ],
            [
                'question' => [
                    'quiz_id' => 5,
                    'type' => 'true_false',
                    'prompt' => 'Natural fibers such as cotton and wool cannot biodegrade when discarded.',
                    'options' => ['A. True', 'B. False'],
                    'topic' => 'eco_properties',
                    'order' => 3,
                ],
                'answer' => [
                    'answer_index' => 1,
                    'explanation_correct' => 'Benar! Serat alami seperti kapas dan wol BISA terurai secara hayati (biodegrade) dengan mudah.',
                    'explanation_wrong' => 'Salah. Pernyataan tersebut SALAH. Serat alami dapat hancur menyatu dengan tanah.',
                    'related_vocab' => [['word' => 'biodegrade', 'meaning' => 'terurai secara alami']],
                ]
            ],

            // --- MODUL 3 BEGINNER QUIZ ---
            [
                'question' => [
                    'quiz_id' => 6,
                    'type' => 'vocab',
                    'prompt' => 'What does the number like "30°C" or "40°C" on care labels indicate?',
                    'options' => ['A. Iron heat', 'B. Washing temperature', 'C. Air drying time', 'D. Bleaching level'],
                    'topic' => 'care_labels',
                    'order' => 1,
                ],
                'answer' => [
                    'answer_index' => 1,
                    'explanation_correct' => 'Benar! Suhu tersebut menunjukkan batas temperatur air maksimum saat mencuci.',
                    'explanation_wrong' => 'Salah. Angka derajat Celsius menunjukkan temperatur air pencuci, bukan menyetrika.',
                    'related_vocab' => [['word' => 'temperature', 'meaning' => 'suhu cuci']],
                ]
            ],
            [
                'question' => [
                    'quiz_id' => 6,
                    'type' => 'vocab',
                    'prompt' => 'Which chemical is used to whiten garments but can damage colored fabric?',
                    'options' => ['A. Vinegar', 'B. Softener', 'C. Bleach', 'D. Starch'],
                    'topic' => 'care_chemicals',
                    'order' => 2,
                ],
                'answer' => [
                    'answer_index' => 2,
                    'explanation_correct' => 'Benar! Bleach (pemutih/klorin) merusak zat warna kain non-putih.',
                    'explanation_wrong' => 'Salah. Softener melembutkan, Bleach adalah pemutih pakaian.',
                    'related_vocab' => [['word' => 'bleach', 'meaning' => 'pemutih pakaian']],
                ]
            ],
            [
                'question' => [
                    'quiz_id' => 6,
                    'type' => 'true_false',
                    'prompt' => '"Hang to dry" means you should put the wet garment in the washing machine spin-dryer.',
                    'options' => ['A. True', 'B. False'],
                    'topic' => 'care_methods',
                    'order' => 3,
                ],
                'answer' => [
                    'answer_index' => 1,
                    'explanation_correct' => 'Benar! Pernyataan tersebut SALAH. "Hang to dry" berarti menggantung pakaian basah di tempat berangin sampai kering alami.',
                    'explanation_wrong' => 'Salah. Hang to dry berarti digantung, bukan dikeringkan di mesin putar.',
                    'related_vocab' => [['word' => 'dry', 'meaning' => 'mengeringkan']],
                ]
            ],

            // --- MODUL 3 INTERMEDIATE QUIZ ---
            [
                'question' => [
                    'quiz_id' => 7,
                    'type' => 'vocab',
                    'prompt' => 'Professional dry cleaning uses a liquid chemical _____ instead of water.',
                    'options' => ['A. Bleach', 'B. Solvent', 'C. Softener', 'D. Starch'],
                    'topic' => 'dry_cleaning',
                    'order' => 1,
                ],
                'answer' => [
                    'answer_index' => 1,
                    'explanation_correct' => 'Benar! Solvent (pelarut kimia non-air) melarutkan noda tanpa merusak serat sensitif.',
                    'explanation_wrong' => 'Salah. Bleach memutihkan, Solvent digunakan untuk dry cleaning.',
                    'related_vocab' => [['word' => 'solvent', 'meaning' => 'cairan pelarut kimia']],
                ]
            ],
            [
                'question' => [
                    'quiz_id' => 7,
                    'type' => 'vocab',
                    'prompt' => 'Lace, silk, and wool are examples of _____ fabrics that require low agitation cycles.',
                    'options' => ['A. Durable', 'B. Stiff', 'C. Heavy', 'D. Delicate'],
                    'topic' => 'care_methods',
                    'order' => 2,
                ],
                'answer' => [
                    'answer_index' => 3,
                    'explanation_correct' => 'Benar! Delicate (sensitif/halus) mudah sobek dan menyusut jika dicuci terlalu kuat.',
                    'explanation_wrong' => 'Salah. Serat-serat tersebut tipis dan halus (delicate).',
                    'related_vocab' => [['word' => 'delicate', 'meaning' => 'halus, rapuh']],
                ]
            ],
            [
                'question' => [
                    'quiz_id' => 7,
                    'type' => 'true_false',
                    'prompt' => '"Do not tumble dry" means you can dry the garment in a hot spinning dryer.',
                    'options' => ['A. True', 'B. False'],
                    'topic' => 'care_labels',
                    'order' => 3,
                ],
                'answer' => [
                    'answer_index' => 1,
                    'explanation_correct' => 'Benar! Pernyataan tersebut SALAH. "Do not tumble dry" melarang penggunaan mesin pengering berputar.',
                    'explanation_wrong' => 'Salah. Do not tumble dry melarang penggunaan pengering mesin jahit / mesin cuci.',
                    'related_vocab' => [['word' => 'tumble', 'meaning' => 'putaran mesin pengering']],
                ]
            ],

            // --- MODUL 4 BEGINNER QUIZ ---
            [
                'question' => [
                    'quiz_id' => 8,
                    'type' => 'vocab',
                    'prompt' => 'Which feature is used to store small objects like coins or keys in a jacket?',
                    'options' => ['A. Collar', 'B. Sleeve', 'C. Pockets', 'D. Buttons'],
                    'topic' => 'product_details',
                    'order' => 1,
                ],
                'answer' => [
                    'answer_index' => 2,
                    'explanation_correct' => 'Benar! Pockets (saku/kantong) digunakan untuk menyimpan benda-benda kecil.',
                    'explanation_wrong' => 'Salah. Collar adalah kerah, Sleeve lengan, Pockets kantong.',
                    'related_vocab' => [['word' => 'pockets', 'meaning' => 'kantong-kantong']],
                ]
            ],
            [
                'question' => [
                    'quiz_id' => 8,
                    'type' => 'vocab',
                    'prompt' => 'The style or shape that dictates how clothing sits closely or loosely on a body is its _____.',
                    'options' => ['A. Size', 'B. Fit', 'C. Color', 'D. Print'],
                    'topic' => 'product_details',
                    'order' => 2,
                ],
                'answer' => [
                    'answer_index' => 1,
                    'explanation_correct' => 'Benar! Fit (pola potongan baju, misal: regular fit, slim fit) menentukan kelonggaran pakaian.',
                    'explanation_wrong' => 'Salah. Size menentukan dimensi ukuran angka/huruf.',
                    'related_vocab' => [['word' => 'fit', 'meaning' => 'kesesuaian pakaian']],
                ]
            ],
            [
                'question' => [
                    'quiz_id' => 8,
                    'type' => 'true_false',
                    'prompt' => '"S, M, L, XL" are examples of fabric weave styles.',
                    'options' => ['A. True', 'B. False'],
                    'topic' => 'sizing',
                    'order' => 3,
                ],
                'answer' => [
                    'answer_index' => 1,
                    'explanation_correct' => 'Benar! S, M, L, XL adalah ukuran pakaian (size), bukan tipe tenunan kain.',
                    'explanation_wrong' => 'Salah. Itu adalah kode ukuran (size) pakaian.',
                    'related_vocab' => [['word' => 'size', 'meaning' => 'ukuran']],
                ]
            ],

            // --- MODUL 4 INTERMEDIATE QUIZ ---
            [
                'question' => [
                    'quiz_id' => 9,
                    'type' => 'vocab',
                    'prompt' => 'Activewear fabric designed to pull sweat away from your body is described as _____.',
                    'options' => ['A. Heavyweight', 'B. Moisture-wicking', 'C. Stiff', 'D. Non-elastic'],
                    'topic' => 'activewear_specs',
                    'order' => 1,
                ],
                'answer' => [
                    'answer_index' => 1,
                    'explanation_correct' => 'Benar! Moisture-wicking menyerap keringat dari kulit dan menguapkannya dengan cepat.',
                    'explanation_wrong' => 'Salah. Heavyweight membuat berat, Moisture-wicking menjaga tetap kering.',
                    'related_vocab' => [['word' => 'moisture-wicking', 'meaning' => 'menyerap keringat']],
                ]
            ],
            [
                'question' => [
                    'quiz_id' => 9,
                    'type' => 'vocab',
                    'prompt' => 'Which synthetic fiber is blended in sportswear to provide elastic stretch?',
                    'options' => ['A. Linen', 'B. Spandex', 'C. Wool', 'D. Cotton'],
                    'topic' => 'activewear_materials',
                    'order' => 2,
                ],
                'answer' => [
                    'answer_index' => 1,
                    'explanation_correct' => 'Benar! Spandex (juga dikenal sebagai elastane/lycra) memberikan elastisitas tinggi.',
                    'explanation_wrong' => 'Salah. Linen, Wool, dan Cotton adalah serat alami minim kelenturan.',
                    'related_vocab' => [['word' => 'spandex', 'meaning' => 'spandeks, serat elastis']],
                ]
            ],
            [
                'question' => [
                    'quiz_id' => 9,
                    'type' => 'true_false',
                    'prompt' => 'Nylon is a synthetic fiber, meaning it is made from petrochemicals.',
                    'options' => ['A. True', 'B. False'],
                    'topic' => 'materials_origin',
                    'order' => 3,
                ],
                'answer' => [
                    'answer_index' => 0,
                    'explanation_correct' => 'Benar! Nylon adalah serat buatan sintetis hasil olahan polimer minyak bumi.',
                    'explanation_wrong' => 'Salah. Pernyataan tersebut BENAR. Nylon bukan serat tanaman/hewan.',
                    'related_vocab' => [['word' => 'synthetic', 'meaning' => 'sintetis, buatan']],
                ]
            ],

            // --- MODUL 5 BEGINNER QUIZ ---
            [
                'question' => [
                    'quiz_id' => 10,
                    'type' => 'vocab',
                    'prompt' => 'The sharp metal tool that carries thread through fabric in a machine is the _____.',
                    'options' => ['A. Pedal', 'B. Needle', 'C. Bobbin', 'D. Scissors'],
                    'topic' => 'sewing_tools',
                    'order' => 1,
                ],
                'answer' => [
                    'answer_index' => 1,
                    'explanation_correct' => 'Benar! Needle (jarum jahit) mengantarkan benang menembus lembaran kain.',
                    'explanation_wrong' => 'Salah. Pedal mengontrol kecepatan, Bobbin menyimpan benang bawah.',
                    'related_vocab' => [['word' => 'needle', 'meaning' => 'jarum jahit']],
                ]
            ],
            [
                'question' => [
                    'quiz_id' => 10,
                    'type' => 'vocab',
                    'prompt' => 'Which mechanical device is used to stitch fabric pieces together?',
                    'options' => ['A. Loom', 'B. Spinning wheel', 'C. Sewing machine', 'D. Iron'],
                    'topic' => 'sewing_tools',
                    'order' => 2,
                ],
                'answer' => [
                    'answer_index' => 2,
                    'explanation_correct' => 'Benar! Sewing machine (mesin jahit) mempercepat proses penyambungan kain.',
                    'explanation_wrong' => 'Salah. Loom untuk menenun benang menjadi kain, Sewing machine untuk menjahit.',
                    'related_vocab' => [['word' => 'machine', 'meaning' => 'mesin jahit']],
                ]
            ],
            [
                'question' => [
                    'quiz_id' => 10,
                    'type' => 'true_false',
                    'prompt' => 'A straight stitch creates wavy and zig-zag lines on fabric.',
                    'options' => ['A. True', 'B. False'],
                    'topic' => 'sewing_techniques',
                    'order' => 3,
                ],
                'answer' => [
                    'answer_index' => 1,
                    'explanation_correct' => 'Benar! Pernyataan tersebut SALAH. Straight stitch menghasilkan jahitan lurus sempurna.',
                    'explanation_wrong' => 'Salah. Straight stitch berarti jahitan lurus, bukan zig-zag.',
                    'related_vocab' => [['word' => 'straight', 'meaning' => 'lurus']],
                ]
            ],

            // --- MODUL 5 INTERMEDIATE QUIZ ---
            [
                'question' => [
                    'quiz_id' => 11,
                    'type' => 'vocab',
                    'prompt' => 'The distance between the raw edge of fabric and the sewing stitch line is the _____.',
                    'options' => ['A. Hemline', 'B. Seam-allowance', 'C. Bias', 'D. Neckline'],
                    'topic' => 'sewing_terminology',
                    'order' => 1,
                ],
                'answer' => [
                    'answer_index' => 1,
                    'explanation_correct' => 'Benar! Seam-allowance (kampuh jahitan) adalah batas sisa guntingan di samping garis jahit.',
                    'explanation_wrong' => 'Salah. Hemline adalah tepi bawah, Seam-allowance adalah ruang kelonggaran jahit.',
                    'related_vocab' => [['word' => 'seam-allowance', 'meaning' => 'kampuh jahitan']],
                ]
            ],
            [
                'question' => [
                    'quiz_id' => 11,
                    'type' => 'vocab',
                    'prompt' => 'Which slide fastener device is used to close clothing openings, often hidden in dresses?',
                    'options' => ['A. Button', 'B. Hook and eye', 'C. Zipper', 'D. Velcro'],
                    'topic' => 'garment_closures',
                    'order' => 2,
                ],
                'answer' => [
                    'answer_index' => 2,
                    'explanation_correct' => 'Benar! Zipper (risleting) digunakan untuk menutup bukaan bagian belakang rok/gaun.',
                    'explanation_wrong' => 'Salah. Button adalah kancing, Zipper adalah gesper pengunci geser.',
                    'related_vocab' => [['word' => 'zipper', 'meaning' => 'risleting']],
                ]
            ],
            [
                'question' => [
                    'quiz_id' => 11,
                    'type' => 'true_false',
                    'prompt' => 'Cutting fabric on the bias means cutting it at a 45-degree angle to the grainline.',
                    'options' => ['A. True', 'B. False'],
                    'topic' => 'cutting_techniques',
                    'order' => 3,
                ],
                'answer' => [
                    'answer_index' => 0,
                    'explanation_correct' => 'Benar! Cutting on the bias (potong serong) memotong miring kain 45 derajat agar lebih elastis.',
                    'explanation_wrong' => 'Salah. Pernyataan tersebut BENAR. Arah bias dibentuk dengan sudut serong 45 derajat.',
                    'related_vocab' => [['word' => 'bias', 'meaning' => 'potongan serong']],
                ]
            ],
        ];

        foreach ($qaPairs as $pair) {
            $question = Question::create($pair['question']);
            $answerData = $pair['answer'];
            $answerData['question_id'] = $question->id;
            Answer::create($answerData);
        }

        // 8. SEED: Weekly Challenge
        Challenge::create([
            'title' => 'Mulai Perjalananmu! 🚀',
            'description' => 'Selesaikan Placement Quiz dan 1 modul pembelajaran minggu ini untuk mendapatkan bonus XP!',
            'target_type' => 'modules_complete',
            'target_value' => 1,
            'bonus_xp' => 50,
            'week_id' => Carbon::now()->format('o-\\WW'),
        ]);
    }
}
