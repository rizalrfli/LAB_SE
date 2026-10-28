<?php

namespace Database\Seeders;

use App\Models\Anggota;
use App\Models\KontenPage;
use App\Models\Publikasi;
use Illuminate\Database\Seeder;

/**
 * Sumber data: https://jti.polinema.ac.id/research/laboratory/laboratorium-rekayasa-perangkat-lunak/
 *
 * Semua teks di sini disalin dari halaman tersebut. Tidak ada deskripsi, angka,
 * atau klaim yang ditambahkan. Kolom yang tidak ada di sumber dibiarkan kosong.
 */
class LabProfileSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedKonten();
        $this->seedAnggota();
        $this->seedPublikasi();
    }

    private function seedKonten(): void
    {
        $rows = [
            'lab_nama' => [
                'title' => 'Nama Laboratorium',
                'content' => 'Laboratorium Rekayasa Perangkat Lunak',
            ],
            'lab_kode' => [
                'title' => 'Kode Laboratorium',
                'content' => 'RPL',
            ],
            'lab_induk' => [
                'title' => 'Unit Induk',
                'content' => 'Jurusan Teknologi Informasi, Politeknik Negeri Malang',
            ],
            'tentang_kami' => [
                'title' => 'Profil Laboratorium',
                'content' => 'Laboratorium Rekayasa Perangkat Lunak merupakan fasilitas akademik di bawah naungan Jurusan Teknologi Informasi yang berfokus pada bidang rekayasa pengembangan perangkat lunak. Laboratorium ini diharapkan tumbuh menjadi pusat aktivitas penelitian dan pengabdian masyarakat yang berorientasi pada pengembangan teknologi perangkat lunak.',
            ],
            'lab_ringkas' => [
                'title' => 'Ringkasan',
                'content' => 'Fasilitas penunjang akademik di Jurusan Teknologi Informasi berfokus pada dua pilar utama: keamanan siber dan pengembangan perangkat lunak. Melalui Laboratorium Jaringan & Keamanan Siber serta Laboratorium Rekayasa Perangkat Lunak, jurusan ini memfasilitasi kegiatan praktikum, riset, dan pengabdian masyarakat guna menghasilkan inovasi teknologi dan lulusan yang kompeten di bidangnya.',
            ],
            'visi_deskripsi' => [
                'title' => 'Visi',
                'content' => 'Menjadi pusat unggulan dalam pengembangan ilmu pengetahuan, teknologi, dan inovasi di bidang Rekayasa Perangkat Lunak yang berdaya saing global, dengan kontribusi nyata pada kemajuan akademik, industri, dan masyarakat.',
            ],
            'misi_item_1' => ['title' => 'Misi 1', 'content' => 'Mengembangkan kompetensi mahasiswa.'],
            'misi_item_2' => ['title' => 'Misi 2', 'content' => 'Mendorong penelitian fundamental dan terapan.'],
            'misi_item_3' => ['title' => 'Misi 3', 'content' => 'Mengintegrasikan kolaborasi multi-disiplin.'],
            'misi_item_4' => ['title' => 'Misi 4', 'content' => 'Mengoptimalkan pemanfaatan teknologi terkini.'],
            'misi_item_5' => ['title' => 'Misi 5', 'content' => 'Mewujudkan pengabdian masyarakat berbasis riset.'],
            'fokus_riset_1' => [
                'title' => 'Fokus Riset 1',
                'content' => 'Emerging Technologies in Software Engineering',
            ],
            'fokus_riset_2' => [
                'title' => 'Fokus Riset 2',
                'content' => 'Domain-Specific Software Engineering Applications',
            ],
            'fokus_riset_3' => [
                'title' => 'Fokus Riset 3',
                'content' => 'Software Engineering Methodologies and Architecture',
            ],
            'focus_qa' => [
                'title' => 'Emerging Technologies in Software Engineering',
                'content' => '{"title":"Emerging Technologies in Software Engineering","content":"Fokus riset laboratorium pada penerapan teknologi baru dalam rekayasa perangkat lunak.","icon":"lni lni-technology"}',
            ],
            'focus_hci' => [
                'title' => 'Domain-Specific Software Engineering Applications',
                'content' => '{"title":"Domain-Specific Software Engineering Applications","content":"Fokus riset laboratorium pada penerapan rekayasa perangkat lunak di bidang tertentu.","icon":"lni lni-network"}',
            ],
            'focus_ml' => [
                'title' => 'Software Engineering Methodologies and Architecture',
                'content' => '{"title":"Software Engineering Methodologies and Architecture","content":"Fokus riset laboratorium pada metodologi dan arsitektur rekayasa perangkat lunak.","icon":"lni lni-stats-up"}',
            ],
            'tentang_kami_feature_rekayasa' => [
                'title' => 'Rekayasa Perangkat Lunak',
                'content' => 'Laboratorium berfokus pada bidang rekayasa pengembangan perangkat lunak.',
            ],
            'tentang_kami_feature_publikasi' => [
                'title' => 'Penelitian Fundamental dan Terapan',
                'content' => 'Mendorong penelitian fundamental dan terapan di bidang rekayasa perangkat lunak.',
            ],
            'tentang_kami_feature_kemitraan' => [
                'title' => 'Kolaborasi Multi-Disiplin',
                'content' => 'Mengintegrasikan kolaborasi multi-disiplin dalam kegiatan laboratorium.',
            ],
            'tentang_kami_feature_pembinaan' => [
                'title' => 'Kompetensi Mahasiswa',
                'content' => 'Mengembangkan kompetensi mahasiswa melalui kegiatan laboratorium.',
            ],
            'layanan_lokasi' => [
                'title' => 'Lokasi',
                'content' => 'Gedung Jurusan Teknologi Informasi, Lantai 8 Barat',
            ],
            'layanan_jam' => [
                'title' => 'Jam Layanan',
                'content' => 'Senin sampai Jumat, 08.00 sampai 16.00',
            ],
            'sinta_url' => [
                'title' => 'Profil SINTA Laboratorium',
                'content' => 'https://sinta.kemdiktisaintek.go.id/authors/profile/6005739',
            ],
            'sumber_data' => [
                'title' => 'Sumber Data',
                'content' => 'https://jti.polinema.ac.id/research/laboratory/laboratorium-rekayasa-perangkat-lunak/',
            ],
        ];

        foreach ($rows as $key => $row) {
            KontenPage::updateOrCreate(
                ['page_key' => $key],
                ['title' => $row['title'], 'content' => $row['content'], 'last_updated' => now()]
            );
        }
    }

    private function seedAnggota(): void
    {
        $anggota = [
            [
                'nama' => 'Imam Fahrur Rozi, ST., MT.',
                'jabatan' => 'Kepala Laboratorium',
                'nip' => '198406102008121004',
                'sinta_url' => 'https://sinta.kemdiktisaintek.go.id/authors/profile/6005739',
                'foto' => 'assets/img/anggota/imam-fahrur-rozi.jpg',
                'urutan' => 1,
            ],
            [
                'nama' => 'Ridwan Rismanto, SST., M.Kom.',
                'jabatan' => 'Peneliti',
                'nip' => '198603182012121001',
                'sinta_url' => 'https://sinta.kemdiktisaintek.go.id/authors/profile/6018829',
                'foto' => 'assets/img/anggota/ridwan-rismanto.jpg',
                'urutan' => 2,
            ],
            [
                'nama' => 'Dian Hanifudin Subhi, S.Kom., M.Kom.',
                'jabatan' => 'Peneliti',
                'nip' => '198806102019031018',
                'sinta_url' => null,
                'foto' => 'assets/img/anggota/dian-hanifudin-subhi.jpg',
                'urutan' => 3,
            ],
            [
                'nama' => 'Moch. Zawaruddin Abdullah, S.ST., M.Kom',
                'jabatan' => 'Peneliti',
                'nip' => '198902102019031019',
                'sinta_url' => 'https://sinta.kemdiktisaintek.go.id/authors/profile/6714037',
                'foto' => 'assets/img/anggota/moch-zawaruddin-abdullah.jpg',
                'urutan' => 4,
            ],
            [
                'nama' => 'Ariadi Retno Ririd, S.Kom., M.Kom.',
                'jabatan' => 'Peneliti',
                'nip' => '198108102005012002',
                'sinta_url' => 'https://sinta.kemdiktisaintek.go.id/authors/profile/6018827/',
                'foto' => 'assets/img/anggota/ariadi-retno-ririd.jpg',
                'urutan' => 5,
            ],
            [
                'nama' => 'Elok Nur Hamdana, S.T., M.T',
                'jabatan' => 'Peneliti',
                'nip' => '198610022019032011',
                'sinta_url' => 'https://sinta.kemdiktisaintek.go.id/authors/profile/6754038',
                'foto' => 'assets/img/anggota/elok-nur-hamdana.jpg',
                'urutan' => 6,
            ],
        ];

        foreach ($anggota as $row) {
            Anggota::updateOrCreate(['nama' => $row['nama']], $row);
        }
    }

    private function seedPublikasi(): void
    {
        $publikasi = [
            ['Integrasi Sistem Informasi Geografis dan Sistem Informasi Manajemen Keanggotaan untuk Meningkatkan Aksesibilitas Layanan Kesehatan pada Ikatan Dokter Indonesia (IDI) Cabang ...', 1, 'HE397vMXCloC', 'WwrDWnEAAAAJ'],
            ['Analyzing the Application of Optical Character Recognition: A Case Study in International Standard Book Number Detection', 2, 'WA5NYHcadZ8C', 'WwrDWnEAAAAJ'],
            ['Analisis Performa Metode Extreme Learning Machine dan Multiple Linear Regression dalam Prediksi Produksi Gula', 0, 'Mojj43d5GZwC', 'WwrDWnEAAAAJ'],
            ['A Predictive Approach to Managing University Faculty Budgets in Response to Applicant Trends using Multiple Linear Regression', 1, '_B80troHkn4C', 'WwrDWnEAAAAJ'],
            ['Comparison of feature extraction in support vector machine (SVM) based sentiment analysis system', 0, 'tkaPQYYpVKoC', 'WwrDWnEAAAAJ'],
            ['A Study on Process Evaluation of Concept Map Recomposition', 0, 'R3hNpaxXUhUC', 'fJc_GegAAAAJ'],
            ['Inovasi Pengelolaan Lomba Pramuka Berbasis Website dalam Rangka Penerapan Konsep Smart School', 0, 'ULOm3_A8WrAC', 'pR2Dn7MAAAAJ'],
            ['Pelatihan Pembuatan Konten Digital menggunakan Canva sebagai Media Informasi bagi Perangkat Kelurahan Tasikmadu, Kota Malang', 0, 'dhFuZR0502QC', '0uPC_KcAAAAJ'],
            ['APLIKASI PERMAINAN \'FIX YOU\' SEBAGAI PENGENALAN MENTAL HEALTH TERHADAP ANAK PANTI ASUHAN', 0, 'L8Ckcad2t8MC', '0uPC_KcAAAAJ'],
            ['Pelatihan Desain Kreatif untuk Pengembangan Landing Page Website Interaktif PRM Arbuka', 0, 'ZeXyd9-uunAC', '0uPC_KcAAAAJ'],
            ['Media Informasi Masjid Sebagai Sarana Standarisasi Informasi Masjid Attharuf Kepada Masyarakat', 0, 'IWHjjKOFINEC', '0uPC_KcAAAAJ'],
            ['Building Accounting Cost Production Using Analytic Network Process and Full Costing Method for Leather Industry Craft in Malang', 0, '4DMP91E08xMC', 'qoWiXaQAAAAJ'],
            ['PENILAIAN E-COMMERCE TERBAIK MENGGUNAKAN METODE KOMBINASI SMART DAN TOPSIS BERBASIS WEB', 1, 'mB3voiENLucC', 'cduv_fAAAAAJ'],
            ['PENERAPAN METODE ELECTRE UNTUK OPTIMALISASI SISTEM REKOMENDASI DALAM PEMILIHAN TOPIK SKRIPSI', 0, '_Qo2XoVZTnwC', 'cduv_fAAAAAJ'],
            ['Pengembangan Aplikasi Absensi Berbasis Website sebagai Solusi Administrasi di SMK Widyagama', 0, 'RHpTSmoSYBkC', 'cduv_fAAAAAJ'],
            ['DASAR SISTEM INFORMASI MODERN', 0, 'r0BpntZqJG4C', 'cduv_fAAAAAJ'],
            ['Pengembangan Sistem Pendukung Keputusan Pemilihan Headline Berita Menggunakan ROC dan MOORA', 3, 'HDshCWvjkbEC', 'cduv_fAAAAAJ'],
            ['Sentiment Analysis of Visitor Reviews on Google Maps at Kampung Coklat Tourism', 5, 'R3hNpaxXUhUC', 'cduv_fAAAAAJ'],
        ];

        foreach ($publikasi as [$judul, $sitasi, $citationId, $scholarUser]) {
            $tautan = 'https://scholar.google.com/citations?view_op=view_citation&hl=en'
                . '&user=' . $scholarUser
                . '&pagesize=100&sortby=pubdate'
                . '&citation_for_view=' . $scholarUser . ':' . $citationId;

            Publikasi::updateOrCreate(
                ['judul' => $judul],
                [
                    'tahun' => 2025,
                    'tipe' => null,
                    'status' => 'published',
                    'sitasi' => $sitasi,
                    'tautan_scholar' => $tautan,
                ]
            );
        }
    }
}
