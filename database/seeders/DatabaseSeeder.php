<?php

namespace Database\Seeders;

use App\Models\Question;
use App\Models\Quiz;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@quiz.test'],
            [
                'name' => 'Admin Quiz',
                'password' => Hash::make('password123'),
                'role' => 'admin',
            ]
        );

        DB::transaction(function (): void {
            $quiz = Quiz::updateOrCreate(
                ['code' => '123456'],
                [
                    'title' => 'Kuis Pancasila dan Kewarganegaraan',
                    'description' => 'Terdiri dari 10 soal: 2 mudah, 3 sedang, dan 5 sulit. Setiap soal memiliki timer dan poin sesuai tingkat kesulitan.',
                    'is_active' => true,
                    'default_timer' => 30,
                    'show_leaderboard' => true,
                ]
            );

            // Menghapus hasil lama agar skor peserta tetap konsisten setelah bank soal diganti.
            $quiz->participants()->delete();
            $quiz->questions()->delete();

            $questions = [
                [
                    'difficulty' => 'mudah',
                    'question' => 'Dasar negara Republik Indonesia adalah...',
                    'option_a' => 'Pancasila',
                    'option_b' => 'Bhinneka Tunggal Ika',
                    'option_c' => 'UUD 1945',
                    'option_d' => 'Proklamasi Kemerdekaan',
                    'correct_option' => 'A',
                    'time_limit' => 20,
                    'points' => 10,
                ],
                [
                    'difficulty' => 'mudah',
                    'question' => 'Lambang sila ke-3 Pancasila adalah...',
                    'option_a' => 'Bintang',
                    'option_b' => 'Rantai',
                    'option_c' => 'Pohon beringin',
                    'option_d' => 'Kepala banteng',
                    'correct_option' => 'C',
                    'time_limit' => 20,
                    'points' => 10,
                ],
                [
                    'difficulty' => 'sedang',
                    'question' => 'Pemilihan ketua kelas yang dilakukan melalui musyawarah merupakan penerapan sila ke...',
                    'option_a' => 'Satu',
                    'option_b' => 'Dua',
                    'option_c' => 'Tiga',
                    'option_d' => 'Empat',
                    'correct_option' => 'D',
                    'time_limit' => 30,
                    'points' => 15,
                ],
                [
                    'difficulty' => 'sedang',
                    'question' => 'Perilaku yang paling sesuai dengan sila kedua Pancasila di lingkungan sekolah adalah...',
                    'option_a' => 'Menolong teman tanpa membedakan suku, agama, atau kondisi ekonominya',
                    'option_b' => 'Memilih teman hanya dari kelompok yang sama',
                    'option_c' => 'Membiarkan teman mengalami perundungan',
                    'option_d' => 'Memaksakan pendapat kepada teman',
                    'correct_option' => 'A',
                    'time_limit' => 30,
                    'points' => 15,
                ],
                [
                    'difficulty' => 'sedang',
                    'question' => 'Hubungan yang benar antara hak dan kewajiban warga negara adalah...',
                    'option_a' => 'Hak harus diterima tanpa perlu menjalankan kewajiban',
                    'option_b' => 'Kewajiban hanya berlaku bagi pemerintah',
                    'option_c' => 'Hak dan kewajiban harus dilaksanakan secara seimbang dan bertanggung jawab',
                    'option_d' => 'Hak dapat digunakan untuk merugikan orang lain',
                    'correct_option' => 'C',
                    'time_limit' => 30,
                    'points' => 15,
                ],
                [
                    'difficulty' => 'sulit',
                    'question' => 'Dalam rapat kelompok terjadi perbedaan pendapat tentang pembagian tugas. Tindakan yang paling sesuai dengan nilai Pancasila adalah...',
                    'option_a' => 'Ketua menentukan keputusan sendiri agar cepat selesai',
                    'option_b' => 'Setiap anggota mempertahankan pendapatnya tanpa mendengar orang lain',
                    'option_c' => 'Melakukan musyawarah, mendengarkan alasan setiap anggota, lalu menyepakati pembagian yang adil',
                    'option_d' => 'Membubarkan kelompok karena tidak ada pendapat yang sama',
                    'correct_option' => 'C',
                    'time_limit' => 45,
                    'points' => 20,
                ],
                [
                    'difficulty' => 'sulit',
                    'question' => 'Seorang teman meminta izin untuk menjalankan ibadah saat kegiatan kelompok berlangsung. Sikap yang paling tepat adalah...',
                    'option_a' => 'Melarangnya karena kegiatan kelompok lebih penting',
                    'option_b' => 'Memberikan kesempatan beribadah dan mengatur kembali pembagian waktu kelompok',
                    'option_c' => 'Menyuruhnya mengikuti cara ibadah anggota kelompok lain',
                    'option_d' => 'Mengeluarkannya dari kelompok',
                    'correct_option' => 'B',
                    'time_limit' => 45,
                    'points' => 20,
                ],
                [
                    'difficulty' => 'sulit',
                    'question' => 'Sekolah memiliki bantuan perlengkapan belajar yang jumlahnya terbatas. Kebijakan yang paling mencerminkan keadilan sosial adalah...',
                    'option_a' => 'Membagikannya hanya kepada siswa yang dekat dengan pengurus',
                    'option_b' => 'Membagikannya sama rata tanpa melihat kebutuhan',
                    'option_c' => 'Membagikannya berdasarkan kebutuhan dengan kriteria yang jelas dan transparan',
                    'option_d' => 'Menyimpannya agar tidak menimbulkan perdebatan',
                    'correct_option' => 'C',
                    'time_limit' => 45,
                    'points' => 20,
                ],
                [
                    'difficulty' => 'sulit',
                    'question' => 'Kamu menerima pesan media sosial yang belum terbukti kebenarannya dan berisi tuduhan terhadap kelompok suku tertentu. Tindakan paling bertanggung jawab adalah...',
                    'option_a' => 'Langsung membagikannya agar orang lain waspada',
                    'option_b' => 'Menambahkan komentar yang menghina kelompok tersebut',
                    'option_c' => 'Memeriksa kebenaran informasi, tidak menyebarkannya, dan melaporkan konten yang memecah persatuan',
                    'option_d' => 'Menyimpannya lalu menyebarkannya pada waktu lain',
                    'correct_option' => 'C',
                    'time_limit' => 45,
                    'points' => 20,
                ],
                [
                    'difficulty' => 'sulit',
                    'question' => 'Musyawarah kelas sudah dilakukan dengan sungguh-sungguh, tetapi mufakat belum tercapai. Langkah yang paling tepat adalah...',
                    'option_a' => 'Memaksakan keputusan ketua kelas',
                    'option_b' => 'Menghentikan pembahasan tanpa keputusan',
                    'option_c' => 'Melakukan pemungutan suara secara jujur dan adil, kemudian menghormati hasilnya',
                    'option_d' => 'Meminta kelompok yang kalah meninggalkan kelas',
                    'correct_option' => 'C',
                    'time_limit' => 45,
                    'points' => 20,
                ],
            ];

            foreach ($questions as $index => $data) {
                Question::create(array_merge($data, [
                    'quiz_id' => $quiz->id,
                    'position' => $index + 1,
                ]));
            }
        });
    }
}
