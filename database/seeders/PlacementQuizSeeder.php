<?php

namespace Database\Seeders;

use App\Models\Quiz;
use App\Models\Question;
use App\Models\Answer;
use Illuminate\Database\Seeder;

class PlacementQuizSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Ensure placement quiz exists
        $quiz = Quiz::firstOrCreate(
            ['activity_type' => 'placement'],
            [
                'module_id' => null,
                'level' => 'placement',
                'title' => 'Placement Quiz — Tes Kemampuan Awal',
            ]
        );

        // 2. Clean up existing questions and answers for this quiz to avoid duplicates
        $quiz->questions->each(function ($question) {
            if ($question->answer) {
                $question->answer->delete();
            }
            $question->delete();
        });

        // 3. Define the 10 placement questions and answers
        $qaPairs = [
            [
                'question' => [
                    'quiz_id' => $quiz->id,
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
                    'quiz_id' => $quiz->id,
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
                    'quiz_id' => $quiz->id,
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
                    'quiz_id' => $quiz->id,
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
                    'quiz_id' => $quiz->id,
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
                    'quiz_id' => $quiz->id,
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
                    'quiz_id' => $quiz->id,
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
                    'quiz_id' => $quiz->id,
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
                    'quiz_id' => $quiz->id,
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
                    'quiz_id' => $quiz->id,
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
        ];

        foreach ($qaPairs as $pair) {
            $question = Question::create($pair['question']);
            $answerData = $pair['answer'];
            $answerData['question_id'] = $question->id;
            Answer::create($answerData);
        }
    }
}
