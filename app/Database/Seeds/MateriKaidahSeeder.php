<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MateriKaidahSeeder extends Seeder
{
    public function run()
    {
        $data = [
            // BAB 1: KALAM (10 Materi)
            [
                'judul_kaidah'      => 'Pengenalan Kalam',
                'deskripsi'        => 'Pengenalan dasar tentang kalam (ucapan) dalam bahasa Arab dan pengertiannya menurut ilmu nahwu.',
                'penjelasan'       => 'كَلاَمٌ (Kalam) adalah ucapan yang tersusun dari kata-kata yang bermakna. Kalam merupakan dasar dari bahasa Arab dan dibedakan menjadi beberapa jenis:

1. كَلاَمٌ (Kalam) - Ucapan yang bermakna
2. لَفْظٌ (Lafadz) - Kata yang diucapkan
3. مَعْنًى (Ma\'na) - Arti dari ucapan
4. تَرْكِيْبٌ (Tarkeeb) - Susunan kata

Jenis-jenis Kalam:
- كَلاَمٌ مُرَكَّبٌ (Kalam Murakkab) - Ucapan tersusun
- كَلاَمٌ مُفْرَدٌ (Kalam Mufrad) - Ucapan tunggal
- كَلاَمٌ جُمْلَةٌ (Kalam Jumlah) - Kalimat lengkap

Syarat Kalam:
1. Terdiri dari kata-kata
2. Mempunyai arti yang jelas
3. Mengikuti aturan bahasa Arab
4. Dapat dimengerti oleh pendengar',
                'contoh'           => 'مِثَالُ الْكَلاَمِ:
- مُحَمَّدٌ طَالِبٌ (Muhammad adalah pelajar)
- الْكِتَابُ جَمِيْلٌ (Buku itu indah)
- يَذْهَبُ أَحْمَدُ إِلَى الْمَدْرَسَةِ (Ahmad pergi ke sekolah)
- اَلْوَلَدُ يَلْعَبُ فِي الْحَدِيْقَةِ (Anak laki-laki bermain di taman)',
                'urutan'           => 1,
                'dibuat_oleh'      => 2, // KM. Muhammad Faiz, S.Ag.
                'waktu_dibuat'     => date('Y-m-d H:i:s'),
                'waktu_diubah'     => date('Y-m-d H:i:s'),
            ],
            [
                'judul_kaidah'      => 'Huruf-huruf Kalam',
                'deskripsi'        => 'Pembahasan lengkap tentang huruf-huruf hijaiyyah yang membentuk kalam dalam bahasa Arab.',
                'penjelasan'       => 'حُرُوفُ الْهِجَائِيَّةِ (Huruf Hijaiyyah) adalah 28 huruf yang menjadi dasar bahasa Arab.

Daftar Huruf Hijaiyyah:
1. ا (Alif) 2. ب (Ba) 3. ت (Ta) 4. ث (Tsā) 5. ج (Jīm)
6. ح (Ḥā) 7. خ (Khā) 8. د (Dāl) 9. ذ (Dzāl) 10. ر (Rā)
11. ز (Zāy) 12. س (Sīn) 13. ش (Syīn) 14. ص (Ṣād) 15. ض (Ḍād)
16. ط (Ṭā) 17. ظ (Ẓā) 18. ع (‘Ayn) 19. غ (Ghayn) 20. ف (Fā)
21. ق (Qāf) 22. ك (Kāf) 23. ل (Lām) 24. م (Mīm) 25. ن (Nūn)
26. هـ (Hā) 27. و (Wāw) 28. ي (Yā)

Jenis Huruf:
- حُرُوفٌ مُهْمَلَةٌ (Huruf Mudhmalah) - Huruf tanpa titik (ا د ذ ر ز و)
- حُرُوفٌ مُنَقَّطَةٌ (Huruf Munagghathah) - Huruf bbertitik (23 huruf)
- حُرُوفٌ شَمْسِيَّةٌ (Huruf Syamsiyyah) - 14 huruf
- حُرُوفٌ قَمَرِيَّةٌ (Huruf Qamariyyah) - 14 huruf',
                'contoh'           => 'مِثَالُ الْحُرُوفِ:
- اِسْمٌ (Isim) dimulai dengan alif
- بَابٌ (Bab) dimulai dengan ba
- تَاجِرٌ (Tājir) dimulai dengan ta
- مَدْرَسَةٌ (Madrasah) mengandung beberapa huruf',
                'urutan'           => 2,
                'dibuat_oleh'      => 2, // KM. Muhammad Faiz, S.Ag.
                'waktu_dibuat'     => date('Y-m-d H:i:s'),
                'waktu_diubah'     => date('Y-m-d H:i:s'),
            ],
            [
                'judul_kaidah'      => 'Alif dan Lam',
                'deskripsi'        => 'Pembahasan tentang penggunaan alif dan lam (ال) sebagai definite article dalam bahasa Arab.',
                'penjelasan'       => 'أَلِفٌ وَلاَمٌ (Alif dan Lam) adalah definite article yang menunjukkan kata benda tertentu.

Jenis-jenis Alif Lam:
1. الْقَمَرِيَّةُ (Al-Qamariyyah) - Lam qamariyyah, alif dibaca jelas
   Contoh: الْقَمَرُ, الْكِتَابُ, الْبَابُ

2. الشَّمْسِيَّةُ (Asy-Syamsiyyah) - Lam syamsiyyah, alif tidak dibaca (idgham)
   Contoh: الشَّمْسُ, التَّاجِرُ, النُّورُ

Huruf Syamsiyyah (14):
ت ث د ذ ر ز س ش ص ض ط ظ ل ن

Huruf Qamariyyah (14):
ا ب ج ح خ ع غ ف ق ك م هـ و ي

Aturan Penggunaan:
- ال untuk menunjukkan benda tertentu (definite)
- Tanpa ال untuk benda tidak tentu (indefinite)
- ال tidak digunakan untuk isim dhomir',
                'contoh'           => 'مِثَالُ أَلِفٍ وَلاَمٍ:
- الْبَيْتُ (Rumah tersebut) - qamariyyah
- السَّمَاءُ (Langit tersebut) - syamsiyyah
- وَلَدٌ (Seorang anak) - tanpa alif lam
- الْوَلَدُ (Anak tersebut) - dengan alif lam',
                'urutan'           => 3,
                'dibuat_oleh'      => 2, // KM. Muhammad Faiz, S.Ag.
                'waktu_dibuat'     => date('Y-m-d H:i:s'),
                'waktu_diubah'     => date('Y-m-d H:i:s'),
            ],
            [
                'judul_kaidah'      => 'Ta Marbutah (ة)',
                'deskripsi'        => 'Pembahasan lengkap tentang ta marbutah (ة) dalam bahasa Arab, cara membaca, dan penulisannya.',
                'penjelasan'       => 'تَاءٌ مَرْبُوطَةٌ (Ta Marbutah) adalah ta yang ditulis dengan dua titik di atasnya tetapi dibaca "h" apabila berada di akhir kalimat.

Cara Membaca Ta Marbutah:
1. Ketika waqaf (berhenti): dibaca "h" (هاء)
   Contoh: فَاطِمَةُ (dibaca: Fāṭimah)

2. Ketika washal (bersambung): dibaca "t" (تاء)
   Contoh: فَاطِمَةُ الْمُسْلِمَةُ (dibaca: Fāṭimatul Muslimah)

Jenis Ta Marbutah:
1. تَاءٌ مَرْبُوطَةٌ (Ta Marbutah) - ة
2. تَاءٌ مَبْسُوْطَةٌ (Ta Mabsuthah) - ت

Kata dengan Ta Marbutah:
- فَاطِمَةٌ (Fatimah)
- مُدَرَّسَةٌ (Madrasah)
- سَاعَةٌ (Sā\'ah)
- حَدِيْقَةٌ (Ḥadīqah)

Perubahan Ta Marbutah:
- ة + ـ = اتِ (ketika menjadi mudhaf ilaih)
- ة + ي = تِ (ketika tanwin)',
                'contoh'           => 'مِثَالُ التَّاءِ الْمَرْبُوطَةِ:
- حَدِيْقَةٌ (Taman) - waqaf: ḥadīqah
- حَدِيْقَةٌ جَمِيْلَةٌ (Taman yang indah) - washal: ḥadīqatun jamīlah
- شَجَرَةٌ (Pohon) - waqaf: syajarah
- بَيْتُ (Rumah) - tanpa ta marbutah',
                'urutan'           => 4,
                'dibuat_oleh'      => 2, // KM. Muhammad Faiz, S.Ag.
                'waktu_dibuat'     => date('Y-m-d H:i:s'),
                'waktu_diubah'     => date('Y-m-d H:i:s'),
            ],
            [
                'judul_kaidah'      => 'Waw (و)',
                'deskripsi'        => 'Pembahasan lengkap tentang huruf waw (و) dalam bahasa Arab, macam-macam, dan penggunaannya.',
                'penjelasan'       => 'وَاوٌ (Waw) adalah huruf hijaiyyah ke-27 yang memiliki beberapa fungsi dalam bahasa Arab.

Jenis-jenis Waw:
1. وَاوُ الْعَطْفِ (Wawu \'Athaf) - Waw athaf (dan)
   Contoh: مُحَمَّدٌ وَأَحْمَدُ (Muhammad dan Ahmad)

2. وَاوُ الْحَالِ (Wawu Ḥāl) - Waw keadaan
   Contoh: جَاءَ زَيْدٌ رَاكِبًا (Zaid datang dalam keadaan berkendaraan)

3. وَاوُ مَعْنَوِيَّةٌ (Waw Ma\'nawiyah) - Waw yang artinya "bersama"
   Contoh: اذْهَبْ وَصَاحِبَكَ (Pergilah bersama temanmu)

4. وَاوُ الْقَسَمِ (Wawu Qasam) - Waw sumpah
   Contoh: وَاللهِ لَأَفْعَلَنَّ (Demi Allah, pasti akan aku lakukan)

5. وَاوُ التَّأْنِيْثِ (Wawu Ta\'nīts) - Waw feminin
   Contoh: طَالِبَاتٌ (para pelajar perempuan)

6. وَاوُ الْإِشْبَاعِ (Wawu Isybā\') - Waw penguat
   Contoh: كَاتِبُوْنَ (mereka para penulis)',
                'contoh'           => 'مِثَالُ الْوَاوِ:
- وَاللهِ (Demi Allah) - qasam
- أَبُوْكَ وَأُمُّكَ (Ayahmu dan ibumu) - athaf
- اِجْلِسْ وَتَوَاضِعًا (Duduklah dengan tenang) - ḥāl
- مُؤْمِنُوْنَ (orang-orang mukmin) - ta\'nīts jamak',
                'urutan'           => 5,
                'dibuat_oleh'      => 2, // KM. Muhammad Faiz, S.Ag.
                'waktu_dibuat'     => date('Y-m-d H:i:s'),
                'waktu_diubah'     => date('Y-m-d H:i:s'),
            ],
            [
                'judul_kaidah'      => 'Ya (ي)',
                'deskripsi'        => 'Pembahasan lengkap tentang huruf ya (ي) dalam bahasa Arab, macam-macam, dan penggunaannya.',
                'penjelasan'       => 'يَاءٌ (Yā) adalah huruf hijaiyyah ke-28 yang memiliki beberapa fungsi dalam bahasa Arab.

Jenis-jenis Ya:
1. يَاءُ الْمُتَكَلِّمِ (Ya Mutakallim) - Ya penutur pertama
   - يَاءُ الْإِفْرَادِ: أَذْهَبُ (aku pergi)
   - يَاءُ التَّثْنِيَةِ: ذَهَبْتُمَا (kalian berdua pergi)
   - يَاءُ الْجَمْعِ: ذَهَبْنَا (kami pergi)

2. يَاءُ الْمُخَاطَبِ (Ya Mukhāthab) - Ya orang kedua
   - مُخَاطَبٌ مُفْرَدٌ: تَذْهَبُ (kamu pergi)
   - مُخَاطَبٌ مُثَنًّى: تَذْهَبَانِ (kalian berdua pergi)
   - مُخَاطَبٌ جَمْعٌ: تَذْهَبُوْنَ (kalian semua pergi)

3. يَاءُ الْغَائِبِ (Ya Ghaib) - Ya orang ketiga
   - غَائِبٌ مُفْرَدٌ: يَذْهَبُ (dia pergi)
   - غَائِبٌ مُثَنًّى: يَذْهَبَانِ (mereka berdua pergi)
   - غَائِبَةٌ مُثَنَّاةٌ: تَذْهَبَانِ (mereka berdua pergi - perempuan)

4. يَاءُ النِّسْبَةِ (Ya Nisbah) - Ya nisbah
   Contoh: مِصْرِيّ (orang Mesir), دِمَشْقِيّ (orang Damaskus)

5. يَاءُ التَّأْنِيْثِ (Ya Ta\'nīts) - Ya feminin
   Contoh: مُسْلِمَةٌ (wanita muslim), طَالِبَةٌ (pelajar perempuan)',
                'contoh'           => 'مِثَالُ الْيَاءِ:
- أَنَا طَالِبٌ (Aku seorang pelajar) - mutakallim
- أَنْتَ مُعَلِّمٌ (Kamu seorang guru) - mukhathab
- هُوَ طَبِيْبٌ (Dia seorang dokter) - ghaib
- بَيْتِي (rumahku) - dhamir milik',
                'urutan'           => 6,
                'dibuat_oleh'      => 2, // KM. Muhammad Faiz, S.Ag.
                'waktu_dibuat'     => date('Y-m-d H:i:s'),
                'waktu_diubah'     => date('Y-m-d H:i:s'),
            ],
            [
                'judul_kaidah'      => 'Nun (ن)',
                'deskripsi'        => 'Pembahasan lengkap tentang nun (ن) dalam bahasa Arab, jenis-jenis, dan penggunaannya.',
                'penjelasan'       => 'نُوْنٌ (Nūn) adalah huruf hijaiyyah ke-25 yang memiliki beberapa fungsi dalam bahasa Arab.

Jenis-jenis Nun:
1. نُوْنٌ الْإِفْرَادِ (Nūn Ifrād) - Nun tunggal
   Contoh: رَجُلٌ (seorang pria), كِتَابٌ (sebuah buku)

2. نُوْنُ التَّثْنِيَةِ (Nūn Tatsniyah) - Nun tasniah
   Contoh: رَجُلَانِ (dua pria), كِتَابَانِ (dua buku)

3. نُوْنُ النِّسْبَةِ (Nūn Nisbah) - Nun nisbah
   Contoh: بَصْرِيّ (orang Basra), شَامِيّ (orang Syam)

4. نُوْنُ النُّوْبَةِ (Nūn Nūbah) - Nun pengganti
   Contoh: أَنْتُ (engkau), هُمْ (mereka)

5. نُوْنُ التَّوْكِيْدِ (Nūn Tawkīd) - Nun penekanan
   Contoh: لَأَفْعَلَنَّ (pasti akan aku lakukan)

Perubahan Nun:
- ن + ا = ا (idgham)
- ن + ي = ي (idgham)
- ن + و = و (idgham)
- ن + م = م (idgham)

Tanwin:
- فَتْحَةٌ + نً = اً
- كَسْرَةٌ + نٍ = اٍ
- ضَمَّةٌ + نٌ = اٌ',
                'contoh'           => 'مِثَالُ النُّوْنِ:
- وَلَدٌ (anak laki-laki) - tanwin
- وَلَدَانِ (dua anak laki-laki) - tatsniyah
- مُسْلِمُوْنَ (orang-orang muslim) - jamak mudzakkar
- مُسْلِمَاتٌ (wanita-wanita muslim) - jamak muannats',
                'urutan'           => 7,
                'dibuat_oleh'      => 2, // KM. Muhammad Faiz, S.Ag.
                'waktu_dibuat'     => date('Y-m-d H:i:s'),
                'waktu_diubah'     => date('Y-m-d H:i:s'),
            ],
            [
                'judul_kaidah'      => 'Ta Mabsuthah (ت)',
                'deskripsi'        => 'Pembahasan lengkap tentang ta mabsuthah (ت) dalam bahasa Arab, perbedaan dengan ta marbutah, dan penggunaannya.',
                'penjelasan'       => 'تَاءٌ مَبْسُوْطَةٌ (Ta Mabsuthah) adalah ta yang selalu dibaca "t" baik dalam keadaan waqaf maupun washal.

Perbedaan Ta Mabsuthah dan Ta Marbutah:
1. Ta Mabsuthah (ت) - selalu dibaca "t"
2. Ta Marbutah (ة) - dibaca "h" saat waqaf, "t" saat washal

Penggunaan Ta Mabsuthah:
1. كِتَابَةٌ (Kitabah) - penulisan
2. مَدْرَسَتُكَ (Madrasatuka) - sekolahmu
3. فَاطِمَتُكَ (Fatimatuka) - Fatimahmu
4. تَائِبٌ (Tāib) - yang bertaubat

Kata dengan Ta Mabsuthah di akhir:
- بَنَاتُ (bānātu) - putri-putri
- كِتَبُ (kutubu) - kitab-kitab
- أَخَوَاتُ (ikhwātu) - saudara-saudari perempuan

Perubahan Ta Mabsuthah:
- ت + ي = تِ
- ت + ك = تَكَ
- ت + هـ = تَهُ

Aturan Bacaan:
- Selalu dibaca "t" tidak peduli waqaf atau washal
- Tidak mengalami perubahan bunyi seperti ta marbutah',
                'contoh'           => 'مِثَالُ التَّاءِ الْمَبْسُوْطَةِ:
- بَنَاتُ (putri-putri) - selalu dibaca "bānātu"
- أَخَوَاتُ (saudara perempuan) - selalu dibaca "ikhwātu"
- مُؤْمِنَاتُ (wanita-wanita mukmin) - selalu dibaca "mu\'minātu"
- طَالِبَاتُ (pelajar perempuan) - selalu dibaca "ṭālibātu"',
                'urutan'           => 8,
                'dibuat_oleh'      => 2, // KM. Muhammad Faiz, S.Ag.
                'waktu_dibuat'     => date('Y-m-d H:i:s'),
                'waktu_diubah'     => date('Y-m-d H:i:s'),
            ],
            [
                'judul_kaidah'      => 'Ta Mabsuthah di Akhir Kata',
                'deskripsi'        => 'Pembahasan khusus tentang penggunaan ta mabsuthah di akhir kata dan perubahannya.',
                'penjelasan'       => 'تَاءٌ مَبْسُوْطَةٌ فِي آخِرِ الْكَلِمَةِ (Ta Mabsuthah di akhir kata) memiliki aturan khusus.

Jenis-jenis Ta di Akhir Kata:
1. تَاءُ الْمُفْرَدِ (Ta Mufrad) - untuk kata tunggal
   Contoh: أُسْتَاذَةُ (guru perempuan)

2. تَاءُ التَّثْنِيَةِ (Ta Tatsniyah) - untuk kata ganda
   Contoh: أُسْتَاذَتَانِ (dua guru perempuan)

3. تَاءُ الْجَمْعِ (Ta Jam\') - untuk kata jamak
   Contoh: أُسْتَاذَاتٌ (para guru perempuan)

4. تَاءُ الْمُضَافِ (Ta Mudhaf) - ta milik
   Contoh: مُدَرَّسَتُ (guru perempuan)

5. تَاءُ التَّأْنِيْثِ (Ta Ta\'nīts) - ta feminin
   Contoh: عَالِمَةٌ (ahli perempuan)

Perubahan Makna:
- مُدَرِّسٌ (guru laki-laki)
- مُدَرِّسَةٌ (guru perempuan)
- كَاتِبٌ (penulis laki-laki)
- كَاتِبَةٌ (penulis perempuan)

Aturan Khusus:
- Ta di akhir kata menunjukkan jenis kelamin feminin
- Dalam bahasa Arab, semua kata yang berakhiran ta adalah feminin
- Ta tidak mengalami perubahan bunyi',
                'contoh'           => 'مِثَالُ التَّاءِ فِي الآخِرِ:
- طَبِيْبَةٌ (dokter perempuan)
- مُهَنْدِسَةٌ (insinyur perempuan)
- فَنَّانَةٌ (seniman perempuan)
- رَئِيْسَةٌ (pemimpin perempuan)',
                'urutan'           => 9,
                'dibuat_oleh'      => 2, // KM. Muhammad Faiz, S.Ag.
                'waktu_dibuat'     => date('Y-m-d H:i:s'),
                'waktu_diubah'     => date('Y-m-d H:i:s'),
            ],
            [
                'judul_kaidah'      => 'Sukun ( ْ )',
                'deskripsi'        => 'Pembahasan lengkap tentang sukun ( ْ ) dalam bahasa Arab, pengertian, jenis-jenis, dan penggunaannya.',
                'penjelasan'       => 'سُكُونٌ (Sukun) adalah tanda baca yang menunjukkan bahwa huruf tersebut mati (tidak memiliki harakat).

Pengertian Sukun:
- سُكُونٌ = keadaan diam/matinya huruf
- Ditandai dengan simbol: ْ
- Huruf dengan sukun tidak memiliki vokal

Jenis-jenis Sukun:
1. سُكُونٌ أَصْلِيّ (Sukun Asli) - sukun asli
   Contoh: يَرْمُ (dia melempar), يَدْخُلُ (dia masuk)

2. سُكُونٌ عَارِضٌ (Sukun \'Aridh) - sukun sementara
   Contoh: مِنَ الْبَيْتِ (dari rumah), فِي الْمَدْرَسَةِ (di sekolah)

3. سُكُونٌ جَائِزٌ (Sukun Jaiz) - sukun yang diperbolehkan
   Contoh: الْحَمْدُ (segala puji)

4. سُكُونٌ لَازِمٌ (Sukun Lazim) - sukun yang tetap
   Contoh: لَمْ يَذْهَبْ (tidak pergi)

Penggunaan Sukun:
1. Di akhir kata: بَابٌ (pintu), كِتَابٌ (buku)
2. Di tengah kata: مَدْرَسَةٌ (sekolah), سَمَاءٌ (langit)
3. Dalam waqaf: قَالَ (berkata - waqaf)

Aturan Membaca Sukun:
- Huruf dengan sukun dibaca mati
- Tidak ada vokal (fathah, kasrah, dammah)
- Lama bacaan sesuai dengan konteks kalimat',
                'contoh'           => 'مِثَالُ السُّكُونِ:
- بَابٌ (pintu) - ba dengan sukun
- كِتَابٌ (buku) - ta dengan sukun
- يَرْمُ (melempar) - ra dengan sukun
- فِي الْبَيْتِ (di rumah) - ba dengan sukun',
                'urutan'           => 10,
                'dibuat_oleh'      => 2, // KM. Muhammad Faiz, S.Ag.
                'waktu_dibuat'     => date('Y-m-d H:i:s'),
                'waktu_diubah'     => date('Y-m-d H:i:s'),
            ],

            // BAB 2: I'RAB (10 Materi)
            [
                'judul_kaidah'      => 'I\'rab (إِعْرَابٌ)',
                'deskripsi'        => 'Pembahasan lengkap tentang i\'rab (perubahan akhir kata) dalam bahasa Arab beserta macam-macamnya.',
                'penjelasan'       => 'إِعْرَابٌ (I\'rab) adalah perubahan akhir kata karena perubahan faktor grammar.

Pengertian I\'rab:
- Perubahan di akhir kata isim
- Disebabkan oleh \'amil (faktor grammar)
- Menunjukkan status gramatikal kata

Macam-macam I\'rab:
1. رَفْعٌ (Rafa\') - status naik
   - Dhammah ( ُ ) atau tanwin dhammah ( ٌ )
   - Contoh: مُحَمَّدٌ طَالِبٌ

2. نَصْبٌ (Nashb) - status turun
   - Fathah ( َ ) atau tanwin fathah (ً )
   - Contoh: رَأَيْتُ مُحَمَّدًا

3. خَفْضٌ (Khafdh) atau جَرٌ (Jarr) - status rendah
   - Kasrah ( ِ ) atau tanwin kasrah (ٍ )
   - Contoh: ذَهَبْتُ إِلَى مُحَمَّدٍ

4. جَزْمٌ (Jazm) - status potong
   - Sukun ( ْ )
   - Contoh: لَمْ يَذْهَبْ

Tanda-tanda I\'rab:
- Tanda asli: dhammah, fathah, kasrah, sukun
- Tanda pengganti: alif, ya, waw, tanwin',
                'contoh'           => 'مِثَالُ الْإِعْرَابِ:
- رَفْعٌ: الْوَلَدُ يَلْعَبُ (anak laki-laki bermain)
- نَصْبٌ: رَأَيْتُ الْوَلَدَ (aku melihat anak laki-laki)
- خَفْضٌ: مَرَرْتُ بِالْوَلَدِ (aku melewati anak laki-laki)
- جَزْمٌ: لَمْ يَلْعَبِ الْوَلَدُ (anak laki-laki tidak bermain)',
                'urutan'           => 11,
                'dibuat_oleh'      => 2, // KM. Muhammad Faiz, S.Ag.
                'waktu_dibuat'     => date('Y-m-d H:i:s'),
                'waktu_diubah'     => date('Y-m-d H:i:s'),
            ],
            [
                'judul_kaidah'      => 'I\'rab Khosus (إِعْرَابٌ خَاصٌّ)',
                'deskripsi'        => 'Pembahasan tentang i\'rab khusus, yaitu perubahan akhir kata yang tidak mengikuti aturan biasa.',
                'penjelasan'       => 'إِعْرَابٌ خَاصٌّ (I\'rab Khosus) adalah i\'rab yang tidak mengikuti aturan standar atau memiliki pengecualian.

Jenis-jenis I\'rab Khosus:
1. إِعْرَابُ الْأَسْمَاءِ الْخَمْسَةِ (I\'rab 5 isim)
   - أَبٌ, أَخٌ, حَمٌ, فُوْ, ذُوْ
   - Rafa\': واو (ذُوْ), نَصْبٌ: ا (أَبَا), خَفْضٌ: ي (أَبِيْ)

2. إِعْرَابُ الْأَفْعَالِ الْخَمْسَةِ (I\'rab 5 fi\'il)
   - لَيْسَ, كَانَ, أَمْسَى, أَصْبَحَ, أَضْحَى
   - Rafa\': نُونُ النِّسْوَةِ, نَصْبٌ: فَتْحَةٌ, خَفْضٌ: كَسْرَةٌ

3. إِعْرَابُ الْمُثَنَّى (I\'rab tasniah)
   - أَلِفٌ (rafa\'), يَاءٌ (nashb/khafdh)
   - Contoh: مُسْلِمَانِ, مُسْلِمَيْنِ, مُسْلِمَيْنِ

4. إِعْرَابُ جَمْعِ الْمُذَكَّرِ السَّالِمِ (I\'rab jam mudzakkar salim)
   - واوٌ (rafa\'), يَاءً (nashb), يَاءٍ (khafdh)
   - Contoh: مُسْلِمُوْنَ, مُسْلِمِيْنَ, مُسْلِمِيْنَ

Pengecualian Khusus:
- Isim-isim yang tidak di-i\'rabkan
- Fi\'il-fi\'il madhi
- Fi\'il-fi\'il amr',
                'contoh'           => 'مِثَالُ الْإِعْرَابِ الْخَاصِّ:
- أَبٌ (ayah): رَفْعٌ - ذُوْ, نَصْبٌ - أَبَا, خَفْضٌ - أَبِيْ
- مُسْلِمَانِ (dua muslim): رَفْعٌ - ا, نَصْبٌ - ي, خَفْضٌ - ي
- مُسْلِمُوْنَ (para muslim): رَفْعٌ - و, نَصْبٌ - ي, خَفْضٌ - ي',
                'urutan'           => 12,
                'dibuat_oleh'      => 2, // KM. Muhammad Faiz, S.Ag.
                'waktu_dibuat'     => date('Y-m-d H:i:s'),
                'waktu_diubah'     => date('Y-m-d H:i:s'),
            ],
            [
                'judul_kaidah'      => 'I\'rab Majmu\' (إِعْرَابٌ مَجْمُوْعٌ)',
                'deskripsi'        => 'Pembahasan tentang i\'rab jamak (majmu\'), yaitu perubahan akhir kata untuk bentuk jamak.',
                'penjelasan'       => 'إِعْرَابٌ مَجْمُوْعٌ (I\'rab Majmu\') adalah i\'rab untuk kata-kata dalam bentuk jamak/plural.

Jenis-jenis Jamak dan I\'rabnya:
1. جَمْعُ الْمُذَكَّرِ السَّالِمِ (Jamak Mudzakkar Salim)
   - رَفْعٌ: واوٌ + نُوْنٌ (مُسْلِمُوْنَ)
   - نَصْبٌ: يَاءً + نُوْنٌ (مُسْلِمِيْنَ)
   - خَفْضٌ: يَاءٍ + نُوْنٌ (مُسْلِمِيْنَ)

2. جَمْعُ الْمُؤَنَّثِ السَّالِمِ (Jamak Muannats Salim)
   - رَفْعٌ/نَصْبٌ/خَفْضٌ: ا + تَاءٌ (مُسْلِمَاتٌ)

3. جَمْعُ التَّكْسِيْرِ (Jamak Taksir)
   - Mengikuti pola isim biasa
   - Contoh: رِجَالٌ (rafa\'), رِجَالًا (nashb), رِجَالٍ (khafdh)

4. جَمْعُ الْقِلَّةِ (Jamak Qillah)
   - Mengikuti pola masing-masing
   - Contoh: أَفْضَالٌ, أَفْضَالًا, أَفْضَالٍ

Aturan Khusus Jamak:
- Jamak mudzakkar salim: wawu, ya, ya + nun
- Jamak muannats salim: alif + ta untuk semua kasus
- Jamak taksir: mengikuti aturan isim tunggal
- Nun dihilangkan saat nashb dan khafdh untuk jamak mudzakkar',
                'contoh'           => 'مِثَالُ الْإِعْرَابِ الْمَجْمُوْعِ:
- مُسْلِمُوْنَ (para muslim): رَفْعٌ - واوٌ, نَصْبٌ - يَاءً, خَفْضٌ - يَاءٍ
- مُسْلِمَاتٌ (para muslimah): رَفْعٌ/نَصْبٌ/خَفْضٌ - ا + تَاءٌ
- رِجَالٌ (para pria): رَفْعٌ - ضَمَّةٌ, نَصْبٌ - فَتْحَةٌ, خَفْضٌ - كَسْرَةٌ
- أَكْمِلَةٌ (orang-orang bijak): رَفْعٌ - ضَمَّةٌ, نَصْبٌ - فَتْحَةٌ, خَفْضٌ - كَسْرَةٌ',
                'urutan'           => 13,
                'dibuat_oleh'      => 2, // KM. Muhammad Faiz, S.Ag.
                'waktu_dibuat'     => date('Y-m-d H:i:s'),
                'waktu_diubah'     => date('Y-m-d H:i:s'),
            ],
            [
                'judul_kaidah'      => 'I\'rab Mutsanna (إِعْرَابٌ مُثَنًّى)',
                'deskripsi'        => 'Pembahasan lengkap tentang i\'rab kata ganda (mutsanna) dalam bahasa Arab.',
                'penjelasan'       => 'إِعْرَابٌ مُثَنًّى (I\'rab Mutsanna) adalah i\'rab untuk kata-kata dalam bentuk ganda/dual.

Tanda-tanda I\'rab Mutsanna:
1. رَفْعٌ (Rafa\')
   - ا (alif) di akhir kata
   - Contoh: مُسْلِمَانِ (dua muslim)

2. نَصْبٌ (Nashb)
   - ي (ya) di akhir kata
   - Contoh: رَأَيْتُ مُسْلِمَيْنِ (aku melihat dua muslim)

3. خَفْضٌ (Khafdh)
   - ي (ya) di akhir kata
   - Contoh: سَلَّمْتُ عَلَى مُسْلِمَيْنِ (aku memberi salam kepada dua muslim)

Pola Pembentukan Mutsanna:
1. Untuk isim maskulin:
   - Tambahkan ا ن setelah menghilangkan tanwin
   - كِتَابٌ → كِتَابَانِ

2. Untuk isim muannats:
   - Ubah ta marbutah menjadi ta, lalu tambahkan ا ن
   - فَاطِمَةٌ → فَاطِمَتَانِ

3. Kata tidak beraturan:
   - أَبٌ → أَبَوَانِ
   - أَخٌ → أَخَوَانِ
   - إِسْمٌ → اسْمَانِ

Pengecualian:
- Isim dhomir tidak memiliki bentuk ganda
- Beberapa isim memiliki bentuk khusus
- Fi\'il madhi dan amr memiliki aturan tersendiri',
                'contoh'           => 'مِثَالُ الْإِعْرَابِ الْمُثَنَّى:
- رَفْعٌ: مُسْلِمَانِ مُجْتَهِدَانِ (dua muslim yang rajin)
- نَصْبٌ: رَأَيْتُ مُسْلِمَيْنِ (aku melihat dua muslim)
- خَفْضٌ: ذَهَبْتُ إِلَى مُسْلِمَيْنِ (aku pergi kepada dua muslim)
- كِتَابَانِ (dua buku), طَالِبَتَانِ (dua pelajar perempuan)',
                'urutan'           => 14,
                'dibuat_oleh'      => 2, // KM. Muhammad Faiz, S.Ag.
                'waktu_dibuat'     => date('Y-m-d H:i:s'),
                'waktu_diubah'     => date('Y-m-d H:i:s'),
            ],
            [
                'judul_kaidah'      => 'Isim Mufrad (إِسْمٌ مُفْرَدٌ)',
                'deskripsi'        => 'Pembahasan lengkap tentang isim mufrad (kata tunggal) dalam bahasa Arab.',
                'penjelasan'       => 'إِسْمٌ مُفْرَدٌ (Isim Mufrad) adalah kata benda tunggal yang menunjukkan satu orang, benda, atau konsep.

Jenis-jenis Isim Mufrad:
1. إِسْمٌ عَلَمٌ (Isim \'Alam) - nama proper
   Contoh: مُحَمَّدٌ, فَاطِمَةُ, مَكَّةُ

2. إِسْمٌ جِنْسٌ (Isim Jins) - nama generik
   Contoh: رَجُلٌ (pria), اِمْرَأَةٌ (wanita), كِتَابٌ (buku)

3. إِسْمٌ مَصْدَرٌ (Isim Mashdar) - kata benda dari fi\'il
   Contoh: قِرَاءَةٌ (membaca), كِتَابَةٌ (menulis), ذَهَابٌ (pergi)

4. إِسْمٌ مَكَانٌ (Isim Makan) - nama tempat
   Contoh: مَدْرَسَةٌ (sekolah), مَسْجِدٌ (masjid), بَيْتٌ (rumah)

5. إِسْمٌ آلَةٌ (Isim Alat) - nama alat
   Contoh: مِفْتَاحٌ (kunci), سَيْفٌ (pedang), قَلَمٌ (pena)

Jenis Kelamin Isim Mufrad:
1. مُذَكَّرٌ (Mudzakkar) - maskulin
   - Tanpa tanda khusus atau dengan ة
   - Contoh: رَجُلٌ, مُحَمَّدٌ, مَدْرَسٌ

2. مُؤَنَّثٌ (Muannats) - feminin
   - Dengan tanda ة atau tanda lain
   - Contoh: اِمْرَأَةٌ, فَاطِمَةُ, مَدْرَسَةٌ

I\'rab Isim Mufrad:
- رَفْعٌ: ضَمَّةٌ atau tanwin dhammah
- نَصْبٌ: فَتْحَةٌ atau tanwin fathah
- خَفْضٌ: كَسْرَةٌ atau tanwin kasrah',
                'contoh'           => 'مِثَالُ الْإِسْمِ الْمُفْرَدِ:
- عَلَمٌ: مُحَمَّدٌ, فَاطِمَةُ
- جِنْسٌ: وَلَدٌ, بِنْتٌ
- مَصْدَرٌ: قِرَاءَةٌ, كِتَابَةٌ
- مَكَانٌ: بَيْتٌ, مَدْرَسَةٌ
- آلَةٌ: مِفْتَاحٌ, قَلَمٌ',
                'urutan'           => 15,
                'dibuat_oleh'      => 2, // KM. Muhammad Faiz, S.Ag.
                'waktu_dibuat'     => date('Y-m-d H:i:s'),
                'waktu_diubah'     => date('Y-m-d H:i:s'),
            ],
            [
                'judul_kaidah'      => 'Fi\'il Madhi (فِعْلٌ مَاضِيٌ)',
                'deskripsi'        => 'Pembahasan lengkap tentang fi\'il madhi (kata kerja lampau) dalam bahasa Arab.',
                'penjelasan'       => 'فِعْلٌ مَاضِيٌ (Fi\'il Madhi) adalah kata kerja yang menunjukkan perbuatan yang sudah terjadi di masa lalu.

Tanda-tanda Fi\'il Madhi:
1. Dhamir mutakallim pertama:
   - وَ (aku): كَتَبْتُ, ذَهَبْتُ
   - نَا (kami): كَتَبْنَا, ذَهَبْنَا

2. Dhamir mukhathab:
   - تَ (kamu laki-laki): كَتَبْتَ, ذَهَبْتَ
   - تِ (kamu perempuan): كَتَبْتِ, ذَهَبْتِ
   - تُمَا (kalian berdua): كَتَبْتُمَا, ذَهَبْتُمَا
   - تُنَّا (kalian berdua perempuan): كَتَبْتُنَّا, ذَهَبْتُنَّا
   - تُمْ (kalian semua laki-laki): كَتَبْتُمْ, ذَهَبْتُمْ
   - تُنَّ (kalian semua perempuan): كَتَبْتُنَّ, ذَهَبْتُنَّ

3. Dhamir ghaib:
   - َ (dia laki-laki): كَتَبَ, ذَهَبَ
   - َتْ (dia perempuan): كَتَبَتْ, ذَهَبَتْ
   - ا (mereka berdua): كَتَبَا, ذَهَبَا
   - تَا (mereka berdua perempuan): كَتَبَتَا, ذَهَبَتَا
   - وا (mereka semua laki-laki): كَتَبُوا, ذَهَبُوا
   - نَ (mereka semua perempuan): كَتَبْنَ, ذَهَبْنَ

Jenis-jenis Fi\'il Madhi:
1. سَالِمٌ (Salim) - tidak berubah
2. مُعْتَلٌ (Mu\'tall) - ada huruf illat
3. مَزِيْدٌ (Mazid) - ada tambahan huruf
4. مُهْمَلٌ (Mahmūl) - tidak ada huruf illat
5. أَجْوَفٌ (Ajwaf) - ada huruf illat di tengah
6. نَاقِصٌ (Nāqis) - ada huruf illat di akhir',
                'contoh'           => 'مِثَالُ الْفِعْلِ الْمَاضِي:
- كَتَبَ (dia menulis), كَتَبَتْ (dia perempuan menulis)
- ذَهَبَ (dia pergi), ذَهَبَتْ (dia perempuan pergi)
- فَعَلَ (dia melakukan), فَعَلَتْ (dia perempuan melakukan)
- قَرَأَ (dia membaca), قَرَأَتْ (dia perempuan membaca)',
                'urutan'           => 16,
                'dibuat_oleh'      => 2, // KM. Muhammad Faiz, S.Ag.
                'waktu_dibuat'     => date('Y-m-d H:i:s'),
                'waktu_diubah'     => date('Y-m-d H:i:s'),
            ],
            [
                'judul_kaidah'      => 'Fi\'il Mudhari\' (فِعْلٌ مُضَارِعٌ)',
                'deskripsi'        => 'Pembahasan lengkap tentang fi\'il mudhari\' (kata kerja present/future) dalam bahasa Arab.',
                'penjelasan'       => 'فِعْلٌ مُضَارِعٌ (Fi\'il Mudhari\') adalah kata kerja yang menunjukkan perbuatan yang sedang terjadi atau akan terjadi.

Tanda-tanda Fi\'il Mudhari\':
1. يَ (ya) di awal kata
2. أَ (alif) untuk dhamir mutakallim
3. تُ (tu) untuk dhamir mukhathab
4. تَ (ta) untuk dhamir ghaib feminin
5. نُ (nu) untuk jamak mudzakkar
6. نَ (na) untuk jamak muannats

Pola Fi\'il Mudhari\':
- كَتَبَ (madhi) → يَكْتُبُ (mudhari\')
- ذَهَبَ (madhi) → يَذْهَبُ (mudhari\')
- فَتَحَ (madhi) → يَفْتَحُ (mudhari\')
- قَالَ (madhi) → يَقُوْلُ (mudhari\')

Jenis-jenis I\'rab Fi\'il Mudhari\':
1. رَفْعٌ (Rafa\'): ا, نُ, نَ
   - يَكْتُبُ, يَكْتُبَانِ, يَكْتُبُوْنَ

2. نَصْبٌ (Nashb): ا, نَ, نَ
   - لَنْ يَكْتُبَ, لَنْ يَكْتُبَا, لَنْ يَكْتُبُوْا

3. جَزْمٌ (Jazm): تَ, تَا, يَا
   - لَمْ يَكْتُبْ, لَمْ يَكْتُبَا, لَمْ يَكْتُبُوْا

Contoh Penggunaan:
- يَذْهَبُ زَيْدٌ (Zaid sedang pergi)
- سَأَذْهَبُ غَدًا (Aku akan pergi besok)
- هُمْ يَقْرَأُوْنَ (Mereka sedang membaca)',
                'contoh'           => 'مِثَالُ الْفِعْلِ الْمُضَارِعِ:
- يَكْتُبُ (dia menulis), يَكْتُبَانِ (mereka berdua menulis)
- يَذْهَبُ (dia pergi), يَذْهَبُوْنَ (mereka semua pergi)
- يَفْعَلُ (dia melakukan), تَفْعَلِيْنَ (kalian semua perempuan melakukan)
- يَقُوْلُ (dia berkata), نَقُوْلُ (kami berkata)',
                'urutan'           => 17,
                'dibuat_oleh'      => 2, // KM. Muhammad Faiz, S.Ag.
                'waktu_dibuat'     => date('Y-m-d H:i:s'),
                'waktu_diubah'     => date('Y-m-d H:i:s'),
            ],
            [
                'judul_kaidah'      => 'Fi\'il Amr (فِعْلٌ أَمْرٌ)',
                'deskripsi'        => 'Pembahasan lengkap tentang fi\'il amr (kata kerja perintah) dalam bahasa Arab.',
                'penjelasan'       => 'فِعْلٌ أَمْرٌ (Fi\'il Amr) adalah kata kerja perintah yang digunakan untuk meminta atau menyuruh seseorang melakukan sesuatu.

Tanda-tanda Fi\'il Amr:
1. يَ (ya) dihilangkan dari awal mudhari\'
2. Dhamir mukhathab ditambahkan
3. Mengalami jazm (sukun di akhir)

Pola Pembentukan Fi\'il Amr:
- يَكْتُبُ (mudhari\') → اكْتُبْ (amr)
- يَذْهَبُ (mudhari\') → اذْهَبْ (amr)
- يَفْعَلُ (mudhari\') → اِفْعَلْ (amr)
- يَقُوْلُ (mudhari\') → قُلْ (amr)

Dhamir Fi\'il Amr:
1. أَنْتَ (kamu laki-laki): اكْتُبْ, اذْهَبْ, اِفْعَلْ
2. أَنْتِ (kamu perempuan): اكْتُبِي, اذْهَبِي, اِفْعَلِي
3. أَنْتُمَا (kalian berdua): اكْتُبَا, اذْهَبَا, اِفْعَلَا
4. أَنْتُنَّ (kalian berdua perempuan): اكْتُبَا, اذْهَبَا, اِفْعَلَا
5. أَنْتُمْ (kalian semua laki-laki): اكْتُبُوا, اذْهَبُوا, اِفْعَلُوا
6. أَنْتُنَّ (kalian semua perempuan): اكْتُبْنَ, اذْهَبْنَ, اِفْعَلْنَ

Jenis-jenis Fi\'il Amr:
1. سَالِمٌ (Salim) - tidak berubah
2. مُعْتَلٌ (Mu\'tall) - ada huruf illat
3. مَزِيْدٌ (Mazid) - ada tambahan huruf
4. مَهْمُوْزٌ (Mahmūz) - ada hamzah

Penggunaan Fi\'il Amr:
- اِقْرَأْ (Bacalah!)
- اُكْتُبْ (Tulislah!)
- اُذْهَبْ (Pergilah!)
- اِجْلِسْ (Duduklah!)
- فَتَحِ (Bukalah!)',
                'contoh'           => 'مِثَالُ الْفِعْلِ الْأَمْرِ:
- اِقْرَأْ (bacalah - untuk laki-laki)
- اِقْرَئِي (bacalah - untuk perempuan)
- اِقْرَآ (bacalah - untuk dua orang)
- اِقْرَأُوا (bacalah - untuk beberapa laki-laki)
- اِقْرَأْنَ (bacalah - untuk beberapa perempuan)',
                'urutan'           => 18,
                'dibuat_oleh'      => 2, // KM. Muhammad Faiz, S.Ag.
                'waktu_dibuat'     => date('Y-m-d H:i:s'),
                'waktu_diubah'     => date('Y-m-d H:i:s'),
            ],
            [
                'judul_kaidah'      => 'Mudzakkar dan Muannats (مُذَكَّرٌ وَمُؤَنَّثٌ)',
                'deskripsi'        => 'Pembahasan lengkap tentang kata maskulin dan feminin dalam bahasa Arab beserta tanda-tandanya.',
                'penjelasan'       => 'مُذَكَّرٌ وَمُؤَنَّثٌ (Mudzakkar dan Muannats) adalah pembagian gender dalam bahasa Arab.

Isim Mudzakkar (Maskulin):
1. Tidak memiliki tanda khusus
2. Biasanya merujuk pada laki-laki
3. Contoh: رَجُلٌ (pria), وَلَدٌ (anak laki-laki), مُدَرِّسٌ (guru laki-laki)

Isim Muannats (Feminin):
1. Memiliki tanda ة (ta marbutah)
2. Bisa juga dengan alif maqsurah (ى)
3. Contoh: اِمْرَأَةٌ (wanita), بِنْتٌ (anak perempuan), مُدَرِّسَةٌ (guru perempuan)

Tanda-tanda Muannats:
1. ة (ta marbutah) di akhir kata
   - فَاطِمَةٌ, مَدْرَسَةٌ, سَاعَةٌ

2. ا (alif maqsurah) di akhir kata
   - حُبْلَى (hamil), كُبْرَى (besar), صُغْرَى (kecil)

3. أ (alif) di tengah kata
   - أُسْتَاذَةٌ (guru perempuan), عَالِمَةٌ (ahli perempuan)

4. تاء mula (ta mula\'ah)
   - أَرْضٌ (bumi), شَمْسٌ (matahari), نَارٌ (api)

Dhamir Muannats:
- هِيَ (dia perempuan)
- هِيَا (mereka berdua perempuan)
- هُنَّ (mereka semua perempuan)

Fi\'il Muannats:
- تَ (ta) di akhir fi\'il madhi
- تِ (ti) di akhir fi\'il amr
- تَةً (tatan) di akhir fi\'il mudhari\'',
                'contoh'           => 'مِثَالُ الْمُذَكَّرِ وَالْمُؤَنَّثِ:
- مُذَكَّرٌ: وَلَدٌ (anak laki-laki), رَجُلٌ (pria), مُعَلِّمٌ (guru laki-laki)
- مُؤَنَّثٌ: بِنْتٌ (anak perempuan), اِمْرَأَةٌ (wanita), مُعَلِّمَةٌ (guru perempuan)
- بَيْتٌ (rumah - muannats), بَابٌ (pintu - muannats)
- قَلَمٌ (pena - muannats), كِتَابٌ (buku - muannats)',
                'urutan'           => 19,
                'dibuat_oleh'      => 2, // KM. Muhammad Faiz, S.Ag.
                'waktu_dibuat'     => date('Y-m-d H:i:s'),
                'waktu_diubah'     => date('Y-m-d H:i:s'),
            ],
            [
                'judul_kaidah'      => 'Tanda-tanda I\'rab (عَلَامَاتُ الْإِعْرَابِ)',
                'deskripsi'        => 'Pembahasan lengkap tentang tanda-tanda i\'rab dalam bahasa Arab beserta penggunaannya.',
                'penjelasan'       => 'عَلَامَاتُ الْإِعْرَابِ (Tanda-tanda I\'rab) adalah simbol-simbol yang menunjukkan status gramatikal kata.

Tanda-tanda I\'rab Asli:
1. رَفْعٌ (Rafa\'): ضَمَّةٌ ( ُ ) atau تَنْوِيْنُ الضَّمِّ ( ٌ )
   - Contoh: مُحَمَّدٌ, كِتَابٌ, يَذْهَبُ

2. نَصْبٌ (Nashb): فَتْحَةٌ ( َ ) atau تَنْوِيْنُ الْفَتْحِ (ً )
   - Contoh: مُحَمَّدًا, كِتَابًا, لَنْ يَذْهَبَ

3. خَفْضٌ (Khafdh): كَسْرَةٌ ( ِ ) atau تَنْوِيْنُ الْكَسْرِ (ٍ )
   - Contoh: مُحَمَّدٍ, كِتَابٍ, بِمُحَمَّدٍ

4. جَزْمٌ (Jazm): سُكُونٌ ( ْ )
   - Contoh: لَمْ يَذْهَبْ, لَمْ يَكْتُبْ

Tanda-tanda I\'rab Pengganti:
1. Untuk Rafa\':
   - ا (alif): untuk isim muannats salim dan fi\'il mutakallim
   - و (waw): untuk jamak mudzakkar salim dan 5 isim khusus
   - ن (nun): untuk jamak muannats salim

2. Untuk Nashb:
   - ا (alif): untuk isim muannats salim
   - ي (ya): untuk jamak mudzakkar salim dan 5 isim khusus
   - ك (kaf): untuk fi\'il mudhari\' dengan nafi

3. Untuk Khafdh:
   - ي (ya): untuk jamak mudzakkar salim dan 5 isim khusus
   - ك (kaf): untuk fi\'il mudhari\' dengan nafi

4. Untuk Jazm:
   - ح (ha): untuk fi\'il mudhari\' dengan nafi
   - ت (ta): untuk fi\'il mudhari\' dengan nafi wanita
   - ن (nun): untuk fi\'il mudhari\' dengan nafi jamak wanita',
                'contoh'           => 'مِثَالُ عَلَامَاتِ الْإِعْرَابِ:
- أَصْلِيَّةٌ: بَابٌ (rafa\'), بَابًا (nashb), بَابٍ (khafdh), اِجْلِسْ (jazm)
- مُعَوَّضَةٌ رَفْعٌ: مُسْلِمَاتُ (alif), مُسْلِمُوْنَ (waw), أَبُوْ (waw)
- مُعَوَّضَةٌ نَصْبٌ: مُسْلِمَاتٍ (tanwin), مُسْلِمِيْنَ (ya), أَبَا (alif)
- مُعَوَّضَةٌ خَفْضٌ: مُسْلِمَاتٍ (tanwin), مُسْلِمِيْنَ (ya), أَبِيْ (ya)',
                'urutan'           => 20,
                'dibuat_oleh'      => 2, // KM. Muhammad Faiz, S.Ag.
                'waktu_dibuat'     => date('Y-m-d H:i:s'),
                'waktu_diubah'     => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('materi_kaidah')->insertBatch($data);
    }
}