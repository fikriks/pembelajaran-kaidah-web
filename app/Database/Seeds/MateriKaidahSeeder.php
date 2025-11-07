<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MateriKaidahSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'judul_kaidah'      => 'إِسْمٌ الْمُفْرَدُ وَالْجَمْعُ (Isim Mufrad dan Jamak)',
                'deskripsi'        => 'Pembahasan lengkap tentang isim tunggal dan jamak dalam bahasa Arab beserta jenis-jenis, aturan pembentukan, dan contoh penggunaannya dalam kalimat.',
                'penjelasan'       => 'إِسْمٌ الْمُفْرَدُ (Isim Mufrad) adalah kata benda yang menunjukkan satu orang, benda, atau konsep. Bentuknya tidak berubah untuk menunjukkan jumlah tunggal.

أَسْمَاءُ الْجَمْعِ (Isim Jamak) memiliki tiga jenis utama:
1. جَمْعُ سَالِمٍ (Jamak Salim) - jamak yang berubah dengan menambahkan akhiran
   - جَمْعُ مُذَكَّرٍ سَالِمٍ: مُسْلِمٌ → مُسْلِمُونَ/مُسْلِمِينَ
   - جَمْعُ مُؤَنَّثٍ سَالِمٍ: مُسْلِمَةٌ → مُسْلِمَاتٌ
2. جَمْعُ التَّكْسِيرِ (Jamak Taksir) - jamak yang berubah bentuknya
   - كِتَابٌ → كُتُبٌ، رَجُلٌ → رِجَالٌ
3. جَمْعُ الْمُذَكَّرِ وَالْمُؤَنَّثِ (Jamak Mutsanna) - bentuk dualitas/two
   - كِتَابٌ → كِتَابَانِ، مُسْلِمَةٌ → مُسْلِمَتَانِ

قَوَاعِدُ التَّكْسِيرِ (Aturan Pembentukan):
- أَوْزَانُ الْجَمْعِ السَّالِمِ:
  * فُعُلٌ → فُعُولٌ: حُرٌّ → حُرُورٌ
  * فَعَلٌ → فِعَالٌ: عَامِلٌ → عُمَّالٌ
  * فِعْلٌ → أَفْعَالٌ: جِبَالٌ → جِبَالَةٌ

أَنْوَاعُ الْجَمْعِ التَّكْسِيرِ:
- أَوْزَانُ نَادِرَةٌ (bentuk jarang): أَبٌ → آبَاءٌ، أَخٌ → إِخْوَةٌ
- أَوْزَانُ غَالِبَةٌ (bentuk umum): كِتَابٌ → كُتُبٌ، قَلَمٌ → أَقْلَامٌ

أَخْطَاءٌ شَائِعَةٌ (Kesalahan Umum):
- Salah menggunakan وَاو untuk jamak mudzakkar salim
- Lupa mengubah \'ain fiil saat membentuk jamak taksir
- Menggunakan tanwin untuk isim yang diidhafahkan',
                'contoh'           => 'مِثَالٌ مُفْرَدٌ: كِتَابٌ (kitabun) = buku
مِثَالٌ جَمْعٌ: كُتُبٌ (kutubun) = buku-buku

جَمْعٌ سَالِمٌ مُذَكَّرٌ:
- مُسْلِمٌ (muslimun) → مُسْلِمُونَ (muslimuna) = kaum muslimin
- مُعَلِّمٌ (muallimun) → مُعَلِّمُونَ (muallimuna) = para guru

جَمْعٌ سَالِمٌ مُؤَنَّثٌ:
- مُسْلِمَةٌ (muslimatun) → مُسْلِمَاتٌ (muslimatun) = kaum muslimat
- طَالِبَةٌ (thalibatun) → طَالِبَاتٌ (thalibatun) = para mahasiswi

جَمْعُ تَكْسِيرٍ:
- كِتَابٌ (kitabun) → كُتُبٌ (kutubun) = buku-buku
- رَجُلٌ (rajulun) → رِجَالٌ (rijalun) = para pria
- بَيْتٌ (baytun) → بُيُوتٌ (buyutun) = rumah-rumah

جَمْعُ مُثَنَّى:
- كِتَابٌ (kitabun) → كِتَابَانِ (kitaban) = dua buku
- مُسْلِمَةٌ (muslimatun) → مُسْلِمَتَانِ (muslimatan) = dua muslimah

فِي الْجُمْلَةِ (Dalam kalimat):
- قَرَأْتُ الْكُتُبَ (qara\'tul kutuba) = aku membaca buku-buku
- رَأَيْتُ الْمُسْلِمِينَ (ra\'aitul muslimina) = aku melihat kaum muslimin
- جَاءَ الرِّجَالُ (ja\'ar rijalu) = para pria telah datang',
                                'urutan'           => 1,
                'dibuat_oleh'      => 2, // guru1
                'waktu_dibuat'     => date('Y-m-d H:i:s'),
                'waktu_diubah'     => date('Y-m-d H:i:s'),
            ],
            [
                'judul_kaidah'      => 'إِسْمُ الْمُذَكَّرِ وَالْمُؤَنَّثُ (Isim Mudzakkar dan Muannats)',
                'deskripsi'        => 'Pembahasan lengkap tentang isim maskulin dan feminin dalam bahasa Arab, meliputi tanda-tanda, aturan wanith, dan contoh penggunaan dalam berbagai konteks.',
                'penjelasan'       => 'إِسْمُ الْمُذَكَّرِ (Isim Mudzakkar) adalah kata benda yang menunjukkan jenis kelamin laki-laki atau benda yang dianggap maskulin.

إِسْمُ الْمُؤَنَّثِ (Isim Muannats) adalah kata benda yang menunjukkan jenis kelamin perempuan atau benda yang dianggap feminin.

عَلَامَاتُ التَّذْكِيرِ (Tanda-tanda Mudzakkar):
- Tidak memiliki tanda khusus (bentuk dasar)
- Contoh: وَلَدٌ (anak laki-laki), رَجُلٌ (pria), كِتَابٌ (buku)

عَلَامَاتُ التَّأْنِيثِ (Tanda-tanda Muannats):
- تَاءٌ مَرْبُوطَةٌ (ta marbuthah) di akhir: مُدَرَّسَةٌ (sekolah)
- أَلِفٌ مَقْصُورَةٌ (alif maqshurah): حُبْلَى (hamil)
- أَلِفٌ مَمْدُودَةٌ (alif maddah): صَحْرَاءً (padang pasir)
- تَاءٌ مُتَحَرِّكَةٌ (ta mutaharrikah): أُخْتٌ (saudara perempuan)

قَوَاعِدُ التَّأْنِيثِ (Aturan Feminisasi):
1. Menambah تَاءٌ مَرْبُوطَةٌ:
   - مُعَلِّمٌ → مُعَلِّمَةٌ
   - مُسْلِمٌ → مُسْلِمَةٌ

2. Mengubah huruf terakhir:
   - أَسَدٌ (singa) → أُسْدَةٌ (singa betina)
   - ذِئْبٌ (serigala) → ذِئْبَةٌ (serigala betina)

3. Mengubah bentuk kata:
   - رَجُلٌ (pria) → امْرَأَةٌ (wanita)
   - ابْنٌ (anak laki-laki) → ابْنَةٌ (anak perempuan)

أَسْمَاءٌ تَصْلُحُ لِلْجِنْسَيْنِ (Bisa Juga Keduanya):
- طَبِيبٌ (dokter) bisa laki-laki atau perempuan
- خَادِمٌ (pelayan) bisa laki-laki atau perempuan

أَخْطَاءٌ شَائِعَةٌ:
- Salah menggunakan ta marbuthah untuk kata yang seharusnya maskulin
- Lupa mengubah tanda baca saat feminisasi
- Tidak konsisten dalam penggunaan gender',
                'contoh'           => 'مُذَكَّرٌ (laki-laki):
- وَلَدٌ (waladun) = anak laki-laki
- رَجُلٌ (rajulun) = pria
- مُحَمَّدٌ (Muhammadun) = Muhammad
- أَسَدٌ (asadun) = singa

مُؤَنَّثٌ (perempuan):
- بِنْتٌ (bintun) = anak perempuan
- امْرَأَةٌ (imra\'atun) = wanita
- فَاطِمَةُ (Fatimatun) = Fatimah
- أُسْدَةٌ (usadatun) = singa betina

تَاءٌ مَرْبُوطَةٌ:
- مَدْرَسَةٌ (madrasatun) = sekolah
- شَجَرَةٌ (syajaratun) = pohon
- سَاعَةٌ (sa\'atun) = jam

بِلَا تَاءٍ (tanpa ta):
- سَمَاءٌ (sama\'un) = langit
- أَرْضٌ (ardun) = bumi
- شَمْسٌ (syamsun) = matahari

فِي الْجُمْلَةِ:
- جَاءَ الرَّجُلُ (ja\'ar rajulu) = sang pria telah datang
- ذَهَبَتِ الْمَرْأَةُ (dzahabatil mar\'atu) = sang wanita pergi
- رَأَيْتُ الْوَلَدَ (ra\'aitul walada) = aku melihat anak laki-laki
- قَابَلْتُ الْبِنْتَ (qabaltul binta) = aku bertemu anak perempuan',
                                'urutan'           => 2,
                'dibuat_oleh'      => 2, // guru1
                'waktu_dibuat'     => date('Y-m-d H:i:s'),
                'waktu_diubah'     => date('Y-m-d H:i:s'),
            ],
            [
                'judul_kaidah'      => 'رَفْعٌ وَنَصْبٌ وَخَفْضٌ (Rafa\', Nashab, dan Khafdh)',
                'deskripsi'        => 'Pembahasan lengkap tentang tiga kasus nahwu dalam bahasa Arab beserta tanda-tanda, \'amil penyebab, dan contoh penggunaan dalam berbagai pola kalimat.',
                'penjelasan'       => 'رَفْعٌ (Rafa\') adalah keadaan isim/fiil yang mendapat dhommah (ـُ) atau salah satu tanda rafa\' lainnya.

نَصْبٌ (Nashab) adalah keadaan isim/fiil yang mendapat fathah (ـَ) atau salah satu tanda nashab lainnya.

خَفْضٌ (Khafdh/Jarr) adalah keadaan isim yang mendapat kasroh (ـِ) atau salah satu tanda khafdh lainnya.

عَلَامَاتُ الرَّفْعِ (Tanda-tanda Rafa\'):
1. الضَّمَّةُ (Dhammah): الْوَلَدُُ
2. وَاوُ الْجَمَاعَةِ (Wau Jamak): الْمُسْلِمُونَ
3. أَلِفُ الْمُثَنَّى (Alif Mutsanna): الْوَلَدَانِ
4. نُونُ النِّسْبَةِ (Nun Nisbah): عَلَوِيٌّ
5. نُونُ الْمُذَكَّرِ السَّالِمِ (Nun Mudzakkar Salim): مُسْلِمُونَ

عَلَامَاتُ النَّصْبِ (Tanda-tanda Nashab):
1. الْفَتْحَةُ (Fathah): الْوَلَدَ
2. أَلِفُ الْمُثَنَّى (Alif Mutsanna): الرَّجُلَيْنِ
3. يَاءُ الْجَمْعِ (Ya Jamak): الْمُسْلِمِينَ
4. كَسْرَةٌ مُقَدَّرَةٌ (Kasroh tersembunyi): مَسْجِدَ
5. إِسْقَاطُ النُّونِ (Hilangkan nun): غُلَامًا

عَلَامَاتُ الْخَفْضِ (Tanda-tanda Khafdh):
1. الْكَسْرَةُ (Kasroh): فِي الْبَيْتِ
2. يَاءُ الْمُضَافِ (Ya Milkiyyah): كِتَابِي
3. أَلِفُ الْمُضَافِ (Alif Milkiyyah): أَبِي
4. يَاءُ الْجَمْعِ (Ya Jamak): عَلَى الْمُسْلِمِينَ

أَعْمِلَةُ الرَّفْعِ (Amil Rafa\'):
- الْمُبْتَدَأُ (Mubtada\'): الْكِتَابُ جَمِيلٌ
- نَائِبُ الْفَاعِلِ (Naibul Fa\'): ضُرِبَ اللِّصُّ
- خَبَرُ الْمُبْتَدَإِ (Khabar Mubtada\'): الزَّيْتُ نَافِعٌ

أَعْمِلَةُ النَّصْبِ (Amil Nashab):
- أَنْ وَأَخَوَاتُهَا (an dan saudaranya): أُرِيدُ أَنْ أَذْهَبَ
- كَانَ وَأَخَوَاتُهَا (kana dan saudaranya): كَانَ الطَّالِبُ مُجْتَهِدًا
- لَنْ (lan): لَنْ أَفْعَلَ ذَلِكَ

أَعْمِلَةُ الْخَفْضِ (Amil Khafdh):
- حُرُوفُ الْجَرِّ (Huruf Jar): مِنْ، إِلَى، عَنْ، عَلَى، فِي، رُبَّ، الْبَاءُ، الْكَافُ، اللَّامُ
- الإِضَافَةُ (Idhafah): كِتَابُ اللّٰهِ',
                'contoh'           => 'أَمْثِلَةُ الرَّفْعِ:
- الْوَلَدُ لَاعِبٌ (al-waladu la\'ibun) = anak laki-laki itu bermain
- جَاءَ الْمُسْلِمُونَ (ja\'al muslimuna) = kaum muslimin telah datang
- الْكِتَابَانِ مُفِيدَانِ (al-kitaban mufidan) = dua buku itu bermanfaat

أَمْثِلَةُ النَّصْبِ:
- رَأَيْتُ الْوَلَدَ (ra\'aitul walada) = aku melihat anak laki-laki
- قَابَلْتُ الرَّجُلَيْنِ (qabaltur rajulain) = aku bertemu dua pria
- إِنَّ اللّٰهَ غَفُورٌ رَحِيمٌ (innallaha ghafurun rahimun)

أَمْثِلَةُ الْخَفْضِ:
- ذَهَبْتُ إِلَى الْمَدْرَسَةِ (dzahabtu ilal madrasati) = aku pergi ke sekolah
- هَذَا كِتَابُ مُحَمَّدٍ (hadza kitabu Muhammadin) = ini buku milik Muhammad
- فِي الْبَيْتِ (fil bayti) = di dalam rumah

مُقَارَنَةٌ (Perbandingan):
- الْمُعَلِّمُ مُجْتَهِدٌ (guru itu rajin) - rafa\'
- رَأَيْتُ الْمُعَلِّمَ (aku melihat guru) - nashab
- فِي مَكْتَبِ الْمُعَلِّمِ (di kantor guru) - khafdh',
                                'urutan'           => 3,
                'dibuat_oleh'      => 3, // guru2
                'waktu_dibuat'     => date('Y-m-d H:i:s'),
                'waktu_diubah'     => date('Y-m-d H:i:s'),
            ],
            [
                'judul_kaidah'      => 'الْمَرْفُوعَاتُ (Kata-kata yang di-Rafa\')',
                'deskripsi'        => 'Pembahasan lengkap tentang kata-kata yang selalu dalam keadaan rafa\' beserta jenis-jenis, contoh, dan penjelasan penggunaannya dalam kalimat.',
                'penjelasan'       => 'الْمَرْفُوعَاتُ adalah kata-kata yang karena kedudukannya dalam kalimat selalu mendapatkan rafa\'.

أَنْوَاعُ الْمَرْفُوعَاتِ (Jenis-jenis Marfu\'at):

1. الْمُبْتَدَأُ (Mubtada\')
   - Syarat: isim, dhomir, atau isyaroh
   - Tidak didahului oleh wasilah
   - Contoh: اللّٰهُ رَبُّنَا (Allah adalah Tuhan kita)

2. الْخَبَرُ (Khabar)
   - Menjelaskan mubtada\'
   - Bisa berupa: isim, fiil, jar wa majrur, dhamir
   - Contoh: الْعِلْمُ نَافِعٌ (ilmu itu bermanfaat)

3. اسْمُ كَانَ وَأَخَوَاتُهَا (Isim Kana dan saudaranya)
   - كَانَ، أَصْبَحَ، أَمْسَى، ظَلَّ، بَاتَ، لَيْسَ
   - Contoh: كَانَ النَّبِيُّ صَادِقًا (Nabi itu jujur)

4. نَائِبُ الْفَاعِلِ (Naibul Fa\'il)
   - Pengganti fa\'il yang tidak disebutkan
   - Contoh: ضُرِبَ اللِّصُّ (maling itu dipukul)

5. الْفَاعِلُ (Fa\'il)
   - Pelaku fiil yang dilakukan
   - Contoh: جَاءَ مُحَمَّدٌ (Muhammad telah datang)

6. التَّابِعُ لِلْمَرْفُوعِ (Kata yang mengikuti marfu\'at)
   - النَّعْتُ (Na\'at): طَالِبٌ نَاجِحٌ
   - التَّوْكِيدُ (Taukid): جَاءَ مُحَمَّدٌ نَفْسُهُ
   - الْبَدَلُ (Badal): قَرَأْتُ سُورَةَ الْفَاتِحَةَ
   - عَطْفُ بَيَانٍ (Athaf Bayan): جَاءَ مُحَمَّدٌ صَاحِبُهُ

7. خَبَرُ إِنَّ وَأَخَوَاتِهَا (Khabar Inna dan saudarinya)
   - إِنَّ، أَنَّ، كَأَنَّ، لَكِنَّ، لَعَلَّ
   - Contoh: إِنَّ الْعِلْمَ نَافِعٌ (sesungguhnya ilmu itu bermanfaat)

8. اسْمُ لَا التَّامَّةُ (Isim "La" Tammah)
   - نَفْيُ الْجِنْسِ (penafi seluruh jenis)
   - Contoh: لَا إِلٰهَ إِلَّا اللّٰهُ (tidak ada tuhan selain Allah)

9. الْمُنَادَى (Munada)
   - Yang dipanggil
   - Tanda rafa\': wau atau alif (tergantung jenisnya)
   - Contoh: يَا مُحَمَّدُ (wahai Muhammad!)

10. الْمُبْتَدَأُ لِلْخَبَرِ الْمُقَدَّمِ (Mubtada\' untuk khabar muqaddam)
    - Khabar didahulukan karena ada alasan
    - Contoh: فِي الْبَيْتِ رَجُلٌ (di dalam rumah ada pria)',
                'contoh'           => 'الْمُبْتَدَأُ وَالْخَبَرُ:
- اللّٰهُ غَفُورٌ رَحِيمٌ (Allah Maha Pengampun lagi Penyayang)
- مُحَمَّدٌ نَبِيٌّ (Muhammad adalah Nabi)
- الْكِتَابُ مَفْتُوحٌ (buku itu terbuka)

اسْمُ كَانَ وَأَخَوَاتُهَا:
- كَانَ الطَّالِبُ مُجْتَهِدًا (mahasiswa itu rajin)
- أَصْبَحَ الْجَوُّ مُطْلِقًا (pagi itu cuaca cerah)
- لَيْسَ السَّفَرُ صَعْبًا (perjalanan itu tidak sulit)

الْفَاعِلُ وَنَائِبُ الْفَاعِلِ:
- قَرَأَ الطَّالِبُ (mahasiswa itu membaca)
- ضُرِبَ اللِّصُّ (maling itu dipukul)
- فُتِحَ الْبَابُ (pintu itu dibuka)

خَبَرُ إِنَّ:
- إِنَّ الْعِلْمَ نَافِعٌ (sesungguhnya ilmu itu bermanfaat)
- عَلِمْتُ أَنَّكَ نَاجِحٌ (aku tahu bahwa kamu berhasil)

التَّوَابِعُ:
- رَأَيْتُ طَالِبًا مُجْتَهِدًا (aku melihat mahasiswa yang rajin) - na\'at
- جَاءَ مُحَمَّدٌ صَاحِبُهُ (Muhammad datang bersama temannya) - athaf bayan

الْمُنَادَى:
- يَا مُحَمَّدُ أَقْبِلْ (wahai Muhammad, majulah!)
- يَا عِبَادَ اللّٰهِ (wahai hamba-hamba Allah!)
- يَا رَسُولَ اللّٰهِ (wahai Rasulullah!)',
                                'urutan'           => 4,
                'dibuat_oleh'      => 3, // guru2
                'waktu_dibuat'     => date('Y-m-d H:i:s'),
                'waktu_diubah'     => date('Y-m-d H:i:s'),
            ],
            [
                'judul_kaidah'      => 'الْمَنْصُوبَاتُ (Kata-kata yang di-Nashab)',
                'deskripsi'        => 'Pembahasan lengkap tentang kata-kata yang selalu dalam keadaan nashab beserta jenis-jenis, contoh, dan penjelasan penggunaannya dalam kalimat.',
                'penjelasan'       => 'الْمَنْصُوبَاتُ adalah kata-kata yang karena kedudukannya dalam kalimat selalu mendapatkan nashab.

أَنْوَاعُ الْمَنْصُوبَاتِ (Jenis-jenis Mansubat):

1. الْمَفْعُولُ بِهِ (Maf\'ul Bihi)
   - Objek dari fiil transitive
   - Yang terkena dampak dari perbuatan
   - Contoh: قَرَأْتُ الْكِتَابَ (aku membaca buku)

2. الْمَفْعُولُ الْمُطْلَقُ (Maf\'ul Mutlaq)
   - Keterangan cara/nature dari perbuatan
   - Menjelaskan bagaimana perbuatan dilakukan
   - Contoh: قَامَ الرَّجُلُ وُقُوفًا (pria itu berdiri dengan berdirinya)

3. الْمَفْعُولُ لِأَجْلِهِ (Maf\'ul Lih)
   - Alasan dari perbuatan
   - Menjelaskan mengapa perbuatan dilakukan
   - Contoh: قَامَ الرَّجُلُ إِجْلَالًا لِلْعِلْمِ (pria itu berdiri mengagungkan ilmu)

4. الْمَفْعُولُ فِيهِ (Maf\'ul Fihi)
   - Tempat atau waktu perbuatan terjadi
   - Disebut juga ظَرْفٌ (zharaf)
   - Contoh: جَلَسْتُ عَلَى الْكُرْسِيِّ (aku duduk di kursi)

5. الْحَالُ (Hal)
   - Keterangan keadaan pelaku atau objek
   - Menjelaskan keadaan saat perbuatan terjadi
   - Contoh: جَاءَ مُحَمَّدٌ رَاكِبًا (Muhammad datang dengan naik)

6. التَّمْيِيزُ (Tamyiz)
   - Keterangan yang menjelaskan kata yang tidak jelas
   - Bisa jumlah atau jenis
   - Contoh: عِنْدِي عِشْرُونَ دِينَارًا (aku memiliki dua puluh dinar)

7. اسْمُ إِنَّ وَأَخَوَاتُهَا (Isim Inna dan saudarinya)
   - إِنَّ، أَنَّ، كَأَنَّ، لَكِنَّ، لَعَلَّ
   - Isim dari fiil nashab
   - Contoh: عَلِمْتُ أَنَّكَ مُجْتَهِدٌ (aku tahu bahwa kamu rajin)

8. اسْمُ كَانَ وَأَخَوَاتُهَا (Isim Kana dan saudarinya)
   - كَانَ، أَصْبَحَ، أَمْسَى، ظَلَّ، بَاتَ، لَيْسَ
   - Khabar dari kana yang berbentuk isim
   - Contoh: كَانَ الطَّالِبُ مُجْتَهِدًا (mahasiswa itu rajin)

9. خَبَرُ كَانَ وَأَخَوَاتِهَا الْمُقَدَّمُ (Khabar Kana yang didahulukan)
   - Khabar kana yang didahulukan karena alasan
   - Contoh: فِي الْبَيْتِ كَانَ زَيْدٌ (di dalam rumah ada Zaid)

10. التَّابِعُ لِلْمَنْصُوبِ (Kata yang mengikuti mansubat)
    - النَّعْتُ، التَّوْكِيدُ، الْبَدَلُ، عَطْفُ بَيَانٍ
    - Contoh: رَأَيْتُ رَجُلًا عَالِمًا (aku melihat pria yang alim)

11. اسْمُ لَا النَّاقِصَةُ (Isim "La" Naqishah)
    - نَفْيُ الْجِنْسِ (penafi seluruh jenis)
    - Contoh: لَا رَجُلَ فِي الدَّارِ (tidak ada pria di dalam rumah)

12. الْمُنَادَى النَّكِرَةُ الْمُضَافُ (Munada Nakirah Mudhaf)
    - Panggilan kepada sesuatu yang tidak spesifik dihubungkan
    - Contoh: يَا رَبَّنَا وَلَا تُؤَاخِذْنَا (wahai Tuhan kami, janganlah Engkau siksa kami)

13. الْمَفْعُولُ مِنْ أَجْلِهِ (Maf\'ul Min Ajlih)
    - Yang mendapatkan nashab karena ada min
    - Contoh: قُمْتُ إِجْلَالًا لِلْعِلْمِ (aku berdiri karena mengagungkan ilmu)

14. الْمُسْتَثْنَى (Mustathna)
    - Kata yang dikecualikan
    - Contoh: جَاءَ الطُّلَّابُ إِلَّا عَلِيًّ (semua mahasiswa datang kecuali Ali)',
                'contoh'           => 'الْمَفْعُولُ بِهِ:
- قَرَأْتُ الْكِتَابَ (qara\'tul kitaba) = aku membaca buku
- رَأَيْتُ الرَّجُلَ (ra\'aitur rajula) = aku melihat pria
- أَكَلْتُ الطَّعَامَ (akaltut tha\'ama) = aku makan makanan

الْمَفْعُولُ الْمُطْلَقُ:
- قُمْتُ وُقُوفًا (qumtu wuqufan) = aku berdiri dengan berdirinya
- ذَهَبْتُ سَيْرًا (dzahabtu sayran) = aku pergi dengan berjalan

الْحَالُ:
- جَاءَ مُحَمَّدٌ رَاكِبًا (ja\'a Muhammadun rakiban) = Muhammad datang dengan naik
- قَرَأْتُ الْكِتَابَ مَفْتُوحًا (qara\'tul kitaba maftuhan) = aku membaca buku dalam keadaan terbuka

التَّمْيِيزُ:
- عِنْدِي عِشْرُونَ دِينَارًا (indi \'ishrun dinaran) = aku memiliki dua puluh dinar
- لِي ثَلَاثَةُ أَوْلَادٍ (li tsalathatu awladin) = aku memiliki tiga anak

الظَّرْفُ (الْمَفْعُولُ فِيهِ):
- جَلَسْتُ عَلَى الْكُرْسِيِّ (jalastu \'alal kursiyyi) = aku duduk di kursi
- صُمْتُ يَوْمَ الْجُمُعَةِ (sumtu yawmal jum\'ati) = aku puasa hari Jumat

اسْمُ إِنَّ:
- إِنَّ اللّٰهَ غَفُورٌ رَحِيمٌ (innallaha ghafurun rahimun) = sesungguhnya Allah Maha Pengampun lagi Penyayang
- عَلِمْتُ أَنَّكَ نَاجِحٌ (\'alimtu annaka najihun) = aku tahu bahwa kamu berhasil

الْمُسْتَثْنَى:
- حَضَرَ الطُّلَّابُ إِلَّا عَلِيًّ (hadharuth thullabu illa \'Aliyyan) = semua mahasiswa hadir kecuali Ali
- لَا أُحِبُّ إِلَّا الصِّدْقَ (la uhibbu illash shidqa) = aku tidak suka kecuali kebenaran',
                                'urutan'           => 5,
                'dibuat_oleh'      => 2, // guru1
                'waktu_dibuat'     => date('Y-m-d H:i:s'),
                'waktu_diubah'     => date('Y-m-d H:i:s'),
            ],
            [
                'judul_kaidah'      => 'الْمَجْرُورَاتُ (Kata-kata yang di-Khafdh)',
                'deskripsi'        => 'Pembahasan lengkap tentang kata-kata yang selalu dalam keadaan khafdh/jarr beserta jenis-jenis, contoh, dan penjelasan penggunaannya dalam kalimat.',
                'penjelasan'       => 'الْمَجْرُورَاتُ adalah kata-kata yang karena kedudukannya dalam kalimat selalu mendapatkan khafdh (kasroh atau tanda-tandanya).

أَنْوَاعُ الْمَجْرُورَاتِ (Jenis-jenis Majrurat):

1. اسْمُ الْجَرِّ وَالْمَجْرُورِ (Isim Jar dan Majrur)
   - Isim yang didahului oleh huruf jar
   - Selalu mendapatkan kasroh
   - Contoh: ذَهَبْتُ إِلَى الْمَدْرَسَةِ (aku pergi ke sekolah)

2. الْمُضَافُ إِلَيْهِ (Mudhaf Ilaih)
   - Isim yang diidhafahkan kepadanya
   - Selalu mendapatkan kasroh
   - Contoh: كِتَابُ مُحَمَّدٍ (buku milik Muhammad)

3. التَّابِعُ لِلْمَجْرُورِ (Kata yang mengikuti majrurat)
   - النَّعْتُ، التَّوْكِيدُ، الْبَدَلُ، عَطْفُ بَيَانٍ
   - Contoh: رَأَيْتُ كِتَابَ مُحَمَّدٍ الْجَمِيلِ (aku melihat buku milik Muhammad yang indah)

4. اسْمُ لَا التَّامَّةُ (Isim "La" Tammah)
   - نَفْيُ الْجِنْسِ (penafi seluruh jenis)
   - Contoh: لَا إِلٰهَ إِلَّا اللّٰهُ (tidak ada tuhan selain Allah)

حُرُوفُ الْجَرِّ (Huruf-huruf Jar):
1. مِنْ (min) - dari
2. إِلَى (ila) - kepada/menuju
3. عَنْ (\'an) - tentang/dari
4. عَلَى (\'ala) - di atas
5. فِي (fi) - di dalam
6. رُبَّ (rubba) - kadang-kadang
7. الْبَاءُ (ba) - dengan/oleh
8. الْكَافُ (ka) - seperti/untuk
9. اللَّامُ (lam) - untuk/kepada

أَمْثِلَةٌ بِحُرُوفِ الْجَرِّ:
- مِنَ الْبَيْتِ (minal bayti) = dari rumah
- إِلَى الْمَدْرَسَةِ (ilal madrasati) = ke sekolah
- عَنِ الزَّيْتِ (\'aniz zayti) = tentang minyak
- عَلَى الْكُرْسِيِّ (\'alal kursiyyi) = di atas kursi
- فِي الْبَيْتِ (fil bayti) = di dalam rumah
- بِالْقَلَمِ (bil qalami) = dengan pena
- لِلْوَلَدِ (lil waladi) = untuk anak

الإِضَافَةُ (Idhafah):
- كِتَابُ اللّٰهِ (kitabullahi) = buku milik Allah
- بَيْتُ الرَّجُلِ (baytu rajuli) = rumah milik pria
- مَكْتَبُ الْمُدَرِّسِ (maktabul mudarrisi) = kantor milik guru

قَوَاعِدُ الْإِضَافَةِ:
1. Mudhaf (yang diidhafahkan) selalu rafa\'
2. Mudhaf ilaih (yang diidhafahkan kepadanya) selalu khafdh
3. Idhafah tidak boleh ada alif lam di antara keduanya
4. Idhafah tidak boleh ada tanwin di antara keduanya

النَّعْتُ الْمَجْرُورُ:
- مَرَرْتُ بِرَجُلٍ عَالِمٍ (marartu bi rajulin \'alimin) = aku lewat pria yang alim
- نَظَرْتُ إِلَى شَجَرَةٍ خَضْرَاءَ (nadhartu ila syajaratin khadraa) = aku melihat pohon yang hijau

الْبَدَلُ الْمَجْرُورُ:
- قَرَأْتُ صُورَةَ يُوسُفَ (qara\'tu surata Yusufa) = aku membaca surat Yusuf
- سَلَّمْتُ عَلَى مُحَمَّدٍ (sallamtu \'ala Muhammadin) = aku mengucapkan salam kepada Muhammad',
                'contoh'           => 'الْجَرُّ بِالْحُرُوفِ:
- ذَهَبْتُ مِنَ الْبَيْتِ (dzahabtu minal bayti) = aku pergi dari rumah
- قَرَأْتُ فِي الْكِتَابِ (qara\'tu fil kitabi) = aku membaca di dalam buku
- وَضَعْتُ الْكِتَابَ عَلَى الطَّاوِلَةِ (wadha\'tul kitaba \'alat thawilati) = aku meletakkan buku di atas meja

الإِضَافَةُ:
- هَذَا كِتَابُ مُحَمَّدٍ (hadza kitabu Muhammadin) = ini buku milik Muhammad
- فَتَحْتُ بَابَ الْبَيْتِ (fatahtu babal bayti) = aku membuka pintu rumah
- رَأَيْتُ شَجَرَةَ الْحَدِيقَةِ (ra\'aytu syajaratal hadiqati) = aku melihat pohon kebun

النَّعْتُ الْمَجْرُورُ:
- اشْتَرَيْتُ سَيَّارَةً جَمِيلَةً (isytaraytu sayyaratun jamilatan) = aku membeli mobil yang indah
- زُرْتُ مَدِينَةً قَدِيمَةً (zurtu madinatan qadimatan) = aku mengunjungi kota tua

الْبَدَلُ:
- أَحْبَبْتُ الصَّدَقَ (ahbabtush shidqa) = aku mencintai kebenaran
- نَاجَحْتُ الْفَوْزَ (najahtul fawza) = aku meraih kemenangan

لَا التَّامَّةُ:
- لَا إِلٰهَ إِلَّا اللّٰهُ (la ilaha illallah) = tidak ada tuhan selain Allah
- لَا رَجُلَ فِي الدَّارِ (la rajula fid dari) = tidak ada pria di dalam rumah

التَّوْكِيدُ الْمَجْرُورُ:
- زُرْتُ الْمَدْرَسَةَ نَفْسَهَا (zurtul madrasata nafsaha) = aku mengunjungi sekolah itu sendiri
- الْتَقَيْتُ بِالْمُدَرِّسِ عَيْنِهِ (iltaqaytu bil mudarrisi \'aynihi) = aku bertemu guru itu sendiri',
                                'urutan'           => 6,
                'dibuat_oleh'      => 3, // guru2
                'waktu_dibuat'     => date('Y-m-d H:i:s'),
                'waktu_diubah'     => date('Y-m-d H:i:s'),
            ],
            [
                'judul_kaidah'      => 'الْمُبْتَدَأُ وَالْخَبَرُ (Mubtada\' dan Khabar)',
                'deskripsi'        => 'Pembahasan lengkap tentang mubtada\' dan khabar dalam pembentukan kalimat nominal, meliputi syarat, jenis, dan contoh penggunaannya.',
                'penjelasan'       => 'الْمُبْتَدَأُ (Mubtada\') adalah subjek kalimat nominal yang selalu rafa\', menunjukkan sesuatu yang sedang dibicarakan.

الْخَبَرُ (Khabar) adalah predikat yang menjelaskan mubtada\', selalu rafa\', memberikan informasi tentang mubtada\'.

شُرُوطُ الْمُبْتَدَإِ (Syarat Mubtada\'):
1. أَنْ يَكُونَ اسْمًا (harus berupa isim)
2. أَنْ يَكُونَ مَعْرِفَةً (harus ma\'rifah/jelas)
3. أَنْ يَكُونَ مُتَقَدِّمًا عَلَى الْخَبَرِ (harus didahulukan dari khabar)
4. أَلَّا يَكُونَ لَهُ شَيْءٌ مِنَ التَّوَابِعِيَّةِ (tidak memiliki keterikatan)

شُرُوطُ الْخَبَرِ (Syarat Khabar):
1. أَنْ يَكُونَ مُسْنِدًا إِلَى الْمُبْتَدَإِ (terhubung dengan mubtada\')
2. أَنْ يَكُونَ مُخْبِرًا عَنْهُ بِالْفَائِدَةِ (memberikan manfaat/informasi)
3. أَنْ يَكُونَ غَيْرَ مُشْتَرَكٍ (tidak dipakai bersama mubtada\' lainnya)

أَنْوَاعُ الْخَبَرِ (Jenis-jenis Khabar):

1. الْخَبَرُ الْمُفْرَدُ (Khabar Mufrad)
   - Berupa isim tunggal
   - Contoh: اللّٰهُ رَبُّنَا (Allah adalah Tuhan kita)

2. الْخَبَرُ الْجُمْلَةُ الْإِسْمِيَّةُ (Khabar Jumlah Ismiyyah)
   - Berupa kalimat nominal
   - Contoh: الْعِلْمُ فِي الرَّأْسِ (ilmu itu di kepala)

3. الْخَبَرُ الْجُمْلَةُ الْفِعْلِيَّةُ (Khabar Jumlah Fi\'liyyah)
   - Berupa kalimat verbal
   - Contoh: الْوَقْتُ مِثْلُ السَّيْفِ (waktu itu seperti pedang)

4. الْخَبَرُ الظَّرْفُ (Khabar Zharaf)
   - Berupa keterangan tempat/waktu
   - Contoh: الزَّيْتُ فَوْقَ الشَّجَرَةِ (minyak itu di atas pohon)

5. الْخَبَرُ الْجَارُّ وَالْمَجْرُورُ (Khabar Jar wa Majrur)
   - Berupa jar wa majrur
   - Contoh: الْخَيْلُ لِصَاحِبِهِ (kuda itu milik pemiliknya)

6. خَبَرُ إِنَّ وَأَخَوَاتِهَا (Khabar Inna)
   - Khabar dari inna dan saudarinya
   - Contoh: إِنَّ الْإِسْلَامَ دِينُ اللّٰهِ (sesungguhnya Islam adalah agama Allah)

أَحْوَالُ الْمُبْتَدَإِ وَالْخَبَرِ (Kondisi Mubtada\' dan Khabar):

1. الْأَصْلُ (Kondisi Normal): مُبْتَدَأٌ + خَبَرٌ
2. التَّقْدِيمُ وَالتَّأْخِيرُ (Perpindahan posisi):
   - خَبَرٌ مُقَدَّمٌ (khabar didahulukan): فِي الدَّارِ رَجُلٌ
   - مُبْتَدَأٌ مُؤَخَّرٌ (mubtada\' diakhirkan): مَا فِي الدَّارِ إِلَّا زَيْدٌ

حُكْمُ الْمُبْتَدَإِ وَالْخَبَرِ (Hukum):
- Keduanya harus rafa\'
- Keduanya harus cocok (gender, jumlah)
- Harus memberikan informasi yang lengkap

خُصُوصِيَّاتٌ مُهِمَّةٌ (Hal Penting):
- Mubtada\' tidak boleh didahului oleh wasilah
- Khabar bisa berupa dhomir (kata ganti)
- Bisa memiliki lahan nafi (seperti ما و لا)
- Bisa memiliki lahan istifham (seperti هل)',
                'contoh'           => 'الْخَبَرُ الْمُفْرَدُ:
- اللّٰهُ غَفُورٌ رَحِيمٌ (Allah Maha Pengampun lagi Penyayang)
- مُحَمَّدٌ نَبِيٌّ (Muhammad adalah Nabi)
- الْكِتَابُ مُفِيدٌ (buku itu bermanfaat)

الْخَبَرُ الْجُمْلَةُ:
- الْوَقْتُ ذَهَبَ (waktu itu telah pergi)
- الْعِلْمُ نُورٌ (ilmu itu cahaya)
- الْجَوُّ مُطْلِقٌ (cuaca cerah)

الْخَبَرُ الظَّرْفُ:
- الْكِتَابُ فَوْقَ الطَّاوِلَةِ (buku itu di atas meja)
- الشَّمْسُ خَلْفَ السَّحَابِ (matahari di balik awan)
- الْوَلَدُ فِي الْغُرْفَةِ (anak di dalam kamar)

الْخَبَرُ الْجَارُّ وَالْمَجْرُورُ:
- الْمَالُ لِلْوَلَدِ (harta itu untuk anak)
- الْبَابُ لِلْبَيْتِ (pintu itu untuk rumah)
- الْكِتَابُ مِنَ الْمَكْتَبِ (buku itu dari kantor)

خَبَرٌ مُقَدَّمٌ:
- فِي الدَّارِ رَجُلٌ (di dalam rumah ada pria)
- عِنْدِي مَالٌ (di sisiku ada harta)
- مِنَ الْبَلَدِ شَخْصٌ (dari negeri ada seseorang)

مَعَ نَفْيٍ (dengan penafi):
- مَا فِي الْبَيْتِ أَحَدٌ (tidak ada seorangpun di dalam rumah)
- لَا رَجُلَ فِي الْحَدِيقَةِ (tidak ada pria di kebun)
- لَيْسَ مُحَمَّدٌ حَاضِرًا (Muhammad tidak hadir)

مَعَ اسْتِفْهَامٍ (dengan pertanyaan):
- هَلْ مُحَمَّدٌ حَاضِرٌ؟ (apakah Muhammad hadir?)
- أَيْنَ الْكِتَابُ؟ (di mana buku itu?)
- مَتَى السَّفَرُ؟ (kapan perjalanan itu?)

بِالدَّمِيرِ (dengan dhomir):
- مُحَمَّدٌ قَائِمٌ (Muhammad berdiri) - khabar mufrad
- الطُّلَّابُ فِي الْفَصْلِ (para mahasiswa di kelas) - khabar zharaf
- الْمُسْلِمُونَ قَائِمُونَ (para muslim berdiri) - khabar dhomir muttasil',
                                'urutan'           => 7,
                'dibuat_oleh'      => 2, // guru1
                'waktu_dibuat'     => date('Y-m-d H:i:s'),
                'waktu_diubah'     => date('Y-m-d H:i:s'),
            ],
            [
                'judul_kaidah'      => 'الضَّمَائِرُ (Kata Ganti)',
                'deskripsi'        => 'Pembahasan lengkap tentang berbagai jenis kata ganti (dhamir) dalam bahasa Arab, meliputi dhamir muttasil, munfasil, dan contoh penggunaannya.',
                'penjelasan'       => 'الضَّمَائِرُ (Dhamir) adalah kata ganti yang menggantikan nama orang atau benda untuk menghindari pengulangan dan menjaga keindahan bahasa.

أَقْسَامُ الضَّمَائِرِ (Bagian-bagian Dhamir):

1. الضَّمَائِرُ الْمُتَّصِلَةُ (Dhamir Muttasil/Terikat)
   - Terikat dengan fiil atau isim
   - Tidak bisa dipisahkan
   - Contoh: قَامَ هُوَ (dia laki-laki berdiri)

2. الضَّمَائِرُ الْمُنْفَصِلَةُ (Dhamir Munfasil/Terpisah)
   - Terpisah dari fiil atau isim
   - Bisa berdiri sendiri
   - Contoh: جَاءَ هَذَا (ini telah datang)

الضَّمَائِرُ الْمُتَّصِلَةُ:
- غَائِبٌ مُذَكَّرٌ: هُوَ، هِيَ، هُمَا، هُمْ
- حَاضِرٌ مُذَكَّرٌ: أَنْتَ، أَنْتُمَا، أَنْتُمْ
- مُتَكَلِّمٌ: أَنَا، نَحْنُ
- غَائِبَةٌ مُؤَنَّثَةٌ: هِيَ، هُمَا، هُنَّ
- حَاضِرَةٌ مُؤَنَّثَةٌ: أَنْتِ، أَنْتُمَا، أَنْتُنَّ

الضَّمَائِرُ الْمُنْفَصِلَةُ:
- غَائِبٌ: هَذَا، هَذِهِ، هَؤُلَاءِ، ذَلِكَ، تِلْكَ، أُولَئِكَ
- حَاضِرٌ: أَنْتَ، أَنْتِ، أَنْتُمَا، أَنْتُمْ، أَنْتُنَّ، أَنَا، نَحْنُ
- مُتَكَلِّمٌ: أَنَا، نَحْنُ، هُوَ، هِيَ

ضَمَائِرُ النَّصْبِ (Dhamir Nashab):
- إِيَّاهُ، إِيَّاكَ، إِيَّاهُ، إِيَّاهُ، إِيَّاكِ، إِيَّاهُنَّ
- إِيَّايَ، إِيَّاكَ، إِيَّاهُ، إِيَّاهُ، إِيَّاكِ، إِيَّاهُنَّ
- إِيَّانَا، إِيَّاكُمَا، إِيَّاهُمَا، إِيَّاهُمْ، إِيَّاكُنَّ، إِيَّاهُنَّ

ضَمَائِرُ الْجَرِّ (Dhamir Jar):
- لِي، لَكَ، لَهُ، لَهَا، لَهُمَا، لَهُمْ، لَهُنَّ
- مِنِّي، مِنْكَ، مِنْهُ، مِنْهَا، مِنْهُمَا، مِنْهُمْ، مِنْهُنَّ
- عَنِّي، عَنْكَ، عَنْهُ، عَنْهَا، عَنْهُمَا، عَنْهُمْ، عَنْهُنَّ

قَوَاعِدُ اسْتِعْمَالِ الضَّمَائِرِ (Aturan Penggunaan Dhamir):
1. Sesuaikan gender (laki-laki/perempuan)
2. Sesuaikan jumlah (tunggal/dualitas/jamak)
3. Sesuaikan posisi (orang pertama/kedua/ketiga)
4. Sesuaikan kehadiran (hadir/absen)

الضَّمَائِرُ فِي الْجُمْلَةِ (Dhamir dalam Kalimat):
- هُوَ يَذْهَبُ (dia laki-laki pergi)
- هِيَ تَقْرَأُ (dia perempuan membaca)
- هُمْ يَلْعَبُونَ (mereka laki-laki bermain)
- هُنَّ يَذْهَبْنَ (mereka perempuan pergi)
- أَنَا أُحِبُّ الْقِرَاءَةَ (aku suka membaca)
- نَحْنُ نَدْرُسُ الْعَرَبِيَّةَ (kami belajar bahasa Arab)
- أَنْتَ تَجْتَهِدُ (kamu laki-laki rajin)
- أَنْتِ تَجْتَهِدِينَ (kamu perempuan rajin)

الضَّمَائِرُ الْمُنْفَصِلَةُ:
- هَذَا وَلَدٌ (ini adalah anak laki-laki)
- هَذِهِ بِنْتٌ (ini adalah anak perempuan)
- هَؤُلَاءِ طُلَّابٌ (mereka ini adalah para mahasiswa)
- ذَلِكَ كِتَابٌ (itu adalah buku)
- تِلْكَ شَجَرَةٌ (itu adalah pohon)
- أُولَئِكَ رِجَالٌ (mereka itu adalah para pria)

ضَمَائِرُ النَّصْبِ:
- أَكْرَمْتُ إِيَّاهُ (aku menghormatinya)
- لَقِيتُ إِيَّاكَ (aku bertemu kamu)
- رَأَيْتُ إِيَّاهُمْ (aku melihat mereka)
- أَعْطَيْتُ إِيَّاهَا (aku memberikan kepadanya)

ضَمَائِرُ الْجَرِّ:
- الْكِتَابُ لِي (buku itu untukku)
- الْمَالُ لَكَ (harta itu untukmu)
- الْمَسْكُ مِنْهُ (minyak wangi itu darinya)
- الْهَدِيَّةُ لَهَا (hadiah itu untuknya)',
                'contoh'           => 'الضَّمَائِرُ الْمُتَّصِلَةُ:
- أَحَبُّ هُوَ (aku mencintainya)
- ذَهَبَتْ هِيَ (dia perempuan itu pergi)
- جَاءُوا هُمْ (mereka laki-laki telah datang)
- صَافَحْتُهُمَا (aku menjabat tangan keduanya)
- لَعِبُوا هُنَّ (mereka perempuan bermain)

- أَقُولُ أَنَا (aku mengatakan)
- نَذْهَبُ نَحْنُ (kami pergi)
- تَعْلَمُ أَنْتَ (kamu laki-laki belajar)
- تَكْتُبِينَ أَنْتِ (kamu perempuan menulis)

الضَّمَائِرُ الْمُنْفَصِلَةُ:
- هَذَا مُحَمَّدٌ (ini adalah Muhammad)
- هَذِهِ فَاطِمَةُ (ini adalah Fatimah)
- هَؤُلَاءِ طُلَّابٌ (mereka ini adalah mahasiswa)
- ذَلِكَ كِتَابٌ (itu adalah buku)
- تِلْكَ شَجَرَةٌ (itu adalah pohon)
- أُولَئِكَ مُؤْمِنُونَ (mereka itu adalah orang-orang mukmin)

ضَمَائِرُ النَّصْبِ:
- أَحْتَرِمُ إِيَّاهُ (aku menghormatinya)
- لَقِيتُ إِيَّاكَ (aku bertemu kamu)
- أَعْطَيْتُ إِيَّاهُمْ (aku memberikan kepada mereka)
- رَأَيْتُ إِيَّاهَا (aku melihatnya perempuan)
- أَحْسَنْتُ إِلَيْكَ (aku berbuat baik kepadamu)
- اِشْتَرَيْتُ لَكُمَا (aku membeli untuk kalian berdua)

ضَمَائِرُ الْجَرِّ:
- هَذَا الْكِتَابُ لِي (buku ini untukku)
- الْمَالُ لَكَ (harta itu untukmu)
- الْحَقُّ لَهُ (hak itu untuknya)
- الْوَقْتُ لَهَا (waktu itu untuknya)
- الْبَيْتُ لَهُمَا (rumah itu untuk keduanya)
- الْمَسْكُ مِنْهُ (minyak wangi itu darinya)
- الْهَدِيَّةُ مِنْهُمْ (hadiah itu dari mereka)

فِي الْجُمَلِ الْمُتَرَكِبَةِ (Dalam kalimat majemuk):
- هُوَ وَأَخُوهُ فِي الْبَيْتِ (dia dan saudaranya di dalam rumah)
- هِيَ وَصَدِيقَتُهَا ذَهَبَتَا (dia dan temannya pergi berdua)
- أَنَا وَزَمِيلِي سَنَذْهَبُ (aku dan rekan saya akan pergi)
- نَحْنُ الْيَوْمَ مُجْتَهِدُونَ (kami hari ini rajin)

بِمَعْنَى الْمِلْكِيَّةِ (Dengan arti milik):
- كِتَابِي = bukuku
- قَلَمُكَ = pulammu
- بَيْتُهُ = rumahnya
- مَكْتَبُهُمْ = kantor mereka
- شَجَرَتُهَنَّ = pohon mereka perempuan',
                                'urutan'           => 8,
                'dibuat_oleh'      => 3, // guru2
                'waktu_dibuat'     => date('Y-m-d H:i:s'),
                'waktu_diubah'     => date('Y-m-d H:i:s'),
            ],
            [
                'judul_kaidah'      => 'أَسْمَاءُ الْإِشَارَةِ (Kata Tunjuk)',
                'deskripsi'        => 'Pembahasan lengkap tentang kata tunjuk (isim isyarah) dalam bahasa Arab, meliputi isyarah dekat, jauh, mufrad, dan contoh penggunaannya.',
                'penjelasan'       => 'أَسْمَاءُ الْإِشَارَةِ (Isim Isyarah) adalah kata yang digunakan untuk menunjuk sesuatu, baik dekat maupun jauh, tunggal maupun jamak.

أَنْوَاعُ أَسْمَاءِ الْإِشَارَةِ (Jenis-jenis Isim Isyarah):

1. إِشَارَةُ الْقُرْبِ (Isyarah Dekat)
   - هَذَا (hadza) = ini (maskulin tunggal)
   - هَذِهِ (hadzihi) = ini (feminin tunggal)
   - هَؤُلَاءِ (hula\'i) = ini (jamak)
   - هَذَانِ (hadzani) = ini (dualitas maskulin)
   - هَاتَانِ (hatani) = ini (dualitas feminin)

2. إِشَارَةُ الْبُعْدِ (Isyarah Jauh)
   - ذَلِكَ (dzalika) = itu (maskulin tunggal)
   - تِلْكَ (tilka) = itu (feminin tunggal)
   - أُولَئِكَ (ula\'ika) = itu (jamak)
   - ذَانِكَ (zanika) = itu (dualitas maskulin)
   - تَانِكَ (tanika) = itu (dualitas feminin)

قَوَاعِدُ التَّذْكِيرِ وَالتَّأْنِيثِ (Aturan Gender):
- هَذَا = laki-laki / هَذِهِ = perempuan
- ذَلِكَ = laki-laki / تِلْكَ = perempuan
- هَؤُلَاءِ = campuran (bisa laki-laki, perempuan, atau campuran)
- أُولَئِكَ = campuran

إِشَارَةٌ لِلْمُذَكَّرِ (Isyarah untuk Maskulin):
- هَذَا الرَّجُلُ (pria ini)
- ذَلِكَ الْوَلَدُ (anak laki-laki itu)
- هَذَانِ الْكِتَابَانِ (dua buku ini)
- أُولَئِكَ الرِّجَالُ (para pria itu)

إِشَارَةٌ لِلْمُؤَنَّثِ (Isyarah untuk Feminin):
- هَذِهِ الْمَرْأَةُ (wanita ini)
- تِلْكَ الْبِنْتُ (anak perempuan itu)
- هَاتَانِ الشَّجَرَتَانِ (dua pohon ini)
- أُولَئِكَ النِّسَاءُ (para wanita itu)

عَمَلُ أَسْمَاءِ الْإِشَارَةِ (Kerja Isim Isyarah):
1. مُبْتَدَأٌ (Mubtada\'): هَذَا كِتَابٌ
2. خَبَرٌ (Khabar): الْكِتَابُ هَذَا
3. مُنَادَى (Munada): يَا هَذَا الرَّجُلُ
4. مُبَدَلٌ (Badal): رَأَيْتُ مُحَمَّدًا هَذَا
5. بَدَلُ بَعْضٍ مِنْ كُلٍّ (Badal ba\'d min kull): جَاءَ الْقَوْمُ نِصْفُهُمْ

حُرُوفُ التَّنْبِيهِ (Huruf Pemberitahuan):
Untuk isyarah dekat, bisa ditambah:
- أَمَّا هَذَا (amma hadza) = adapun ini
- أَمَّا هَذِهِ (amma hadzihi) = adapun ini

مَوَاضِعُ اسْتِعْمَالٍ (Penggunaan):
- Menunjuk objek yang terlihat
- Menunjuk konsep abstrak
- Dalam presentasi dan ceramah
- Dalam diskusi dan perdebatan

أَمْثِلَةٌ فِي الْجُمَلِ (Contoh dalam Kalimat):
- هَذَا كِتَابٌ مُفِيدٌ (ini buku yang bermanfaat)
- ذَلِكَ الْبَيْتُ كَبِيرٌ (rumah itu besar)
- هَؤُلَاءِ طُلَّابٌ نُجَبَاءُ (mereka ini adalah mahasiswa pilihan)
- تِلْكَ شَجَرَةٌ وَارِفَةٌ (pohon itu rindang)
- أُولَئِكَ مُجْتَهِدُونَ (mereka itu rajin)

تَوْظِيفٌ خَاصٌّ (Fungsi Khusus):
- هَذَا = penekanan/penting
- أُولَئِكَ = pengagungan
- ذَلِكَ = penjelasan rinci
- هَؤُلَاءِ = presentasi kelompok',
                'contoh'           => 'إِشَارَةُ الْقُرْبِ:
- هَذَا كِتَابٌ (hadza kitabun) = ini adalah buku
- هَذِهِ شَجَرَةٌ (hadzihi syajaratun) = ini adalah pohon
- هَذَانِ وَلَدَانِ (hadzani waladan) = ini adalah dua anak laki-laki
- هَاتَانِ بِنْتَانِ (hatani bintan) = ini adalah dua anak perempuan
- هَؤُلَاءِ رِجَالٌ (hula\'i rijalun) = mereka ini adalah para pria
- هَؤُلَاءِ نِسَاءٌ (hula\'i nisa\'un) = mereka ini adalah para wanita

إِشَارَةُ الْبُعْدِ:
- ذَلِكَ كِتَابٌ (dzalika kitabun) = itu adalah buku
- تِلْكَ شَجَرَةٌ (tilka syajaratun) = itu adalah pohon
- ذَانِكَ وَلَدَانِ (zanika waladan) = itu adalah dua anak laki-laki
- تَانِكَ بِنْتَانِ (tanika bintan) = itu adalah dua anak perempuan
- أُولَئِكَ رِجَالٌ (ula\'ika rijalun) = mereka itu adalah para pria
- أُولَئِكَ نِسَاءٌ (ula\'ika nisa\'un) = mereka itu adalah para wanita

فِي الْجُمَلِ الْمُرَكَّبَةِ:
- هَذَا مُحَمَّدٌ وَذَلِكَ أَخُوهُ (ini Muhammad dan itu saudaranya)
- هَؤُلَاءِ الطُّلَّابُ أُولَئِكَ الْمُدَرِّسُونَ (mereka ini adalah mahasiswa, mereka itu adalah guru)
- هَذِهِ الْمَدْرَسَةُ وَتِلْكَ الْجَامِعَةُ (ini sekolah dan itu universitas)
- أَمَّا هَذَا فَقَدْ ذَهَبَ (adapun ini, maka ia telah pergi)
- أَمَّا ذَلِكَ فَلَمْ يَحْضُرْ (adapun itu, maka ia tidak hadir)

بِمَعْنَى التَّفْضِيلِ (Dengan arti keutamaan):
- هَذَا أَفْضَلُ مِنْ ذَلِكَ (ini lebih baik dari itu)
- أُولَئِكَ خَيْرٌ مِنْ هَؤُلَاءِ (mereka itu lebih baik dari mereka ini)
- تِلْكَ الشَّجَرَةُ أَجْمَلُ مِنْ هَذِهِ (pohon itu lebih indah dari pohon ini)

لِلتَّفْصِيلِ (Untuk penjelasan rinci):
- هَذَا كَانَ سَبَبَ الْمَشْكَلَةِ (ini adalah penyebab masalah)
- ذَلِكَ هُوَ الْحَلُّ (itu adalah solusinya)
- أُولَئِكَ هُمُ الْمَسْؤُولُونَ (mereka itulah yang bertanggung jawab)

فِي السُّؤَالِ وَالْجَوَابِ (Dalam pertanyaan dan jawaban):
- هَلْ هَذَا صَحِيحٌ؟ (apakah ini benar?)
- مَاذَا عَنْ ذَلِكَ؟ (bagaimana tentang itu?)
- مَنْ أُولَئِكَ؟ (siapa mereka itu?)
- أَيْنَ تِلْكَ الْمَدِينَةُ؟ (di mana kota itu?)

تَوْظِيفٌ خَاصٌّ (Fungsi khusus):
- هَذَا يَعْنِي (ini berarti) - penjelasan
- ذَلِكَ لِأَنَّ (itu karena) - alasan
- أُولَئِكَ الَّذِينَ (mereka yang) - identifikasi
- هَؤُلَاءِ قَدْ قَالُوا (mereka ini telah mengatakan) - kutipan',
                                'urutan'           => 9,
                'dibuat_oleh'      => 2, // guru1
                'waktu_dibuat'     => date('Y-m-d H:i:s'),
                'waktu_diubah'     => date('Y-m-d H:i:s'),
            ],
            [
                'judul_kaidah'      => 'الْمَصْدَرُ (Kata Kerja Nomina)',
                'deskripsi'        => 'Pembahasan lengkap tentang masdar dalam bahasa Arab, meliputi jenis-jenis, aturan pembentukan, fungsi, dan contoh penggunaannya.',
                'penjelasan'       => 'الْمَصْدَرُ (Masdar) adalah bentuk nominal dari fiil yang menunjukkan perbuatan tanpa menunjukkan waktu, sering disebut "kata kerja nomina" atau "gerundium" dalam bahasa Indonesia.

أَنْوَاعُ الْمَصْدَرِ (Jenis-jenis Masdar):

1. الْمَصْدَرُ الْأَصْلِيُّ (Masdar Asli)
   - Bentuk dasar dari wazan fiil tsulasi (tiga huruf)
   - Pattern: فَعْلٌ (fa\'alun)
   - Contoh: نَصَرَ → نَصْرٌ (menolong → pertolongan)

2. الْمَصْدَرُ الْمِيمِيُّ (Masdar Miimiyy)
   - Dimulai dengan mim
   - Dibentuk dari fiil ghairu tsulasi
   - Pattern: إِفْعَالٌ، فِعَالٌ، تَفْعِلٌ
   - Contoh: أَكْرَمَ → إِكْرَامٌ (menghormati → penghormatan)

3. الْمَصْدَرُ الْغَيْرُ الْأَصْلِيِّ (Masdar Ghairu Asli)
   - Bentuk yang tidak mengikuti pattern standar
   - Biasanya dari fiil mu\'all atau fiil yang memiliki arti khusus
   - Contoh: عَلِمَ → عِلْمٌ (mengetahui → ilmu)

قَوَاعِدُ تَكْوِينِ الْمَصْدَرِ (Aturan Pembentukan Masdar):

مِنَ الْفِعْلِ الثُّلَاثِيِّ (Dari fiil tsulasi):
- فَعَلَ → فَعْلٌ: نَصَرَ → نَصْرٌ، جَلَسَ → جُلُوسٌ
- فَعِلَ → فِعْلٌ: عَلِمَ → عِلْمٌ، فَهِمَ → فَهْمٌ
- فَعُلَ → فُعُولٌ: كَرُمَ → كَرَامَةٌ، شَرُفَ → شَرَافَةٌ

مِنَ الْفِعْلِ الرُّبَاعِيِّ (Dari fiil ruba\'):
- أَفْعَلَ → إِفْعَالٌ: أَكْرَمَ → إِكْرَامٌ
- فَعَّلَ → تَفْعِلٌ: قَرَّبَ → تَقْرِيبٌ
- فَاعَلَ → مُفَاعَلَةٌ: قَاتَلَ → مُقَاتَلَةٌ
- تَفَاعَلَ → تَفَاعُلٌ: تَحَارَبَ → تَحَارُبٌ

مِنَ الْفِعْلِ الْخُمَاسِيِّ (Dari fiil khumasi):
- تَفَعَّلَ → تَفَعُّلٌ: تَعَلَّمَ → تَعَلُّمٌ
- افْتَعَلَ → افْتِعَالٌ: اجْتَهَدَ → اجْتِهَادٌ
- انْفَعَلَ → انْفِعَالٌ: انْشَقَّ → انْشِقَاقٌ
- اسْتَفْعَلَ → اسْتِفْعَالٌ: اسْتَغْفَرَ → اسْتِغْفَارٌ

وَظَائِفُ الْمَصْدَرِ (Fungsi Masdar):

1. مَفْعُولٌ مُطْلَقٌ (Maf\'ul Mutlaq)
   - قَامَ الرَّجُلُ وُقُوفًا (pria itu berdiri dengan berdirinya)

2. مُبْتَدَأٌ (Mubtada\')
   - الْقِرَاءَةُ نَافِعَةٌ (membaca itu bermanfaat)

3. خَبَرُ إِنَّ (Khabar Inna)
   - إِنَّ الْعِلْمَ نَافِعٌ (sesungguhnya ilmu itu bermanfaat)

4. مَفْعُولٌ بِهِ (Maf\'ul Bihi)
   - أُحِبُّ الْقِرَاءَةَ (aku suka membaca)

5. مَفْعُولٌ لِأَجْلِهِ (Maf\'ul Lih)
   - جِئْتُ طَلَبًا لِلْعِلْمِ (aku datang mencari ilmu)

6. بَدَلٌ (Badal)
   - أُحِبُّ الْقِرَاءَةَ (aku suka membaca)

أَمْثِلَةٌ مُتَنَوِّعَةٌ (Contoh Beragam):

الْمَصْدَرُ الْأَصْلِيُّ:
- دَخَلَ → دُخُولٌ (masuk → pemasukan)
- خَرَجَ → خُرُوجٌ (keluar → pengeluaran)
- قَالَ → قَوْلٌ (berkata → ucapan)
- فَعَلَ → فِعْلٌ (melakukan → perbuatan)

الْمَصْدَرُ الْمِيمِيُّ:
- ضَرَبَ → ضَرْبٌ (memukul)
- قَاتَلَ → قِتَالٌ (berperang)
- حَاوَلَ → مُحَاوَلَةٌ (mencoba)
- ذَكَرَ → ذِكْرٌ (mengingat)

الْمَصْدَرُ الْغَيْرُ الْأَصْلِيِّ:
- سَافَرَ → سَفَرٌ (bepergian)
- حَجَّ → حَجٌّ (haji)
- صَامَ → صَوْمٌ (puasa)
- صَلَّى → صَلَاةٌ (shalat)

فِعْلٌ مُعَلٌّ وَمَصْدَرُهُ:
- أَخْرَجَ → إِخْرَاجٌ (mengeluarkan → pengeluaran)
- أَدْخَلَ → إِدْخَالٌ (memasukkan → pemasukan)
- أَكْلَ → أَكْلٌ (makan → makanan)
- شَرِبَ → شُرْبٌ (minum → minuman)',
                'contoh'           => 'الْمَصْدَرُ فِي الْجُمَلِ:

بِمَعْنَى الْفِعْلِ (Sebagai arti fiil):
- أُحِبُّ الْقِرَاءَةَ (aku suka membaca)
- نَبْغِي النَّجَاحَ (kami menginginkan keberhasilan)
- الصَّبْرُ مِفْتَاحُ الْفَرَجِ (kesabaran adalah kunci kemenangan)

مَفْعُولٌ مُطْلَقٌ:
- قُمْتُ قِيَامًا (aku berdiri dengan berdirinya)
- سَافَرْتُ سَفَرًا (aku bepergian dengan bepergian)
- صُمْتُ صَوْمًا (aku puasa dengan berpuasanya)

مُبْتَدَأٌ وَخَبَرٌ:
- الْعِلْمُ نُورٌ (ilmu itu cahaya)
- الْصِّدْقُ أَمَانَةٌ (kejujuran itu amanah)
- الْعَمَلُ صَلَاةٌ (amalan itu shalat)

خَبَرُ إِنَّ وَأَخَوَاتِهَا:
- إِنَّ الْقِرَاءَةَ رَحْمَةٌ (sesungguhnya membaca itu rahmat)
- لَكِنَّ الصَّبْرَ فَضِيلَةٌ (akan tetapi kesabaran itu kemuliaan)
- لَعَلَّ اللّٰهَ يَرْحَمُنَا (mudah-mudahan Allah mengasihi kita)

مَفْعُولٌ لِأَجْلِهِ:
- جِئْتُ طَلَبًا لِلْعِلْمِ (aku datang mencari ilmu)
- صُمْتُ إِجْلَالًا لِلّٰهِ (aku puasa karena mengagungkan Allah)
- قَاتَلُوا دِفَاعًا عَنِ الْوَطَنِ (mereka berperang mempertahankan tanah air)

مَفْعُولٌ فِيهِ:
- جَلَسْتُ عِنْدَ الْبَابِ (aku duduk di dekat pintu)
- صَلَّيْتُ فِي الْمَسْجِدِ (aku shalat di masjid)
- نِمْتُ لَيْلًا (aku tidur di malam hari)

بَدَلٌ:
- أُحِبُّ الْقِرَاءَةَ (aku suka membaca)
- أَكْرَهُ الْكَذِبَ (aku benci berbohong)
- أَرْجُو الْخَيْرَ (aku mengharapkan kebaikan)

تَعْلِيقٌ (Keterangan):
- يَا بُنَيَّ، أَقِمِ الصَّلَاةَ (wahai anakku, dirikanlah shalat)
- اِذْهَبْ وَجِئْ بِالْخَبَرِ (pergilah dan bawalah kabar itu)
- قَرَأْتُ الْقُرْآنَ تِلَاوَةً (aku membaca Al-Qur\'an dengan bacaan)',
                                'urutan'           => 10,
                'dibuat_oleh'      => 3, // guru2
                'waktu_dibuat'     => date('Y-m-d H:i:s'),
                'waktu_diubah'     => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('materi_kaidah')->insertBatch($data);
    }
}