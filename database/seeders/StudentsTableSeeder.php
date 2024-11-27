<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StudentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


        $data = [
            // Information Technology
            [
                'first_name' => 'Mohammed',
                'last_name' => 'Nassar',
                'email' => 'MohammedNassar@gmail.com',
                'department_id' => 1
            ],
            [
                'first_name' => 'Mohammed',
                'last_name' => 'Mubarak',
                'email' => 'MohammedMubarak@gmail.com',
                'department_id' => 1
            ],
            [
                'first_name' => 'Hussein',
                'last_name' => 'Al-Ashwal',
                'email' => 'HusseinAlAshwal@gmail.com',
                'department_id' => 1
            ],
            [
                'first_name' => 'Ahmed',
                'last_name' => 'Al-Yemeni',
                'email' => 'AhmedAlYemeni@gmail.com',
                'department_id' => 1
            ],
            [
                'first_name' => 'Abdullah',
                'last_name' => 'Al-Tamimi',
                'email' => 'AbdullahAlTamimi@gmail.com',
                'department_id' => 1
            ],
            [
                'first_name' => 'Saad',
                'last_name' => 'Al-Harrani',
                'email' => 'SaadAlHarrani@gmail.com',
                'department_id' => 1
            ],
            [
                'first_name' => 'Moaz',
                'last_name' => 'Al-Shaibani',
                'email' => 'MoazAlShaibani@gmail.com',
                'department_id' => 1
            ],
            [
                'first_name' => 'Ali',
                'last_name' => 'Al-Ansari',
                'email' => 'AliAlAnsari@gmail.com',
                'department_id' => 1
            ],
            [
                'first_name' => 'Abdulrahman',
                'last_name' => 'Al-Ansari',
                'email' => 'AbdulrahmanAlAnsari@gmail.com',
                'department_id' => 1
            ],
            [
                'first_name' => 'Omar',
                'last_name' => 'Al-Qurashi',
                'email' => 'OmarAlQurashi@gmail.com',
                'department_id' => 1
            ],
            [
                'first_name' => 'Ammar',
                'last_name' => 'Al-Shami',
                'email' => 'AmmarAlShami@gmail.com',
                'department_id' => 1
            ],
            [
                'first_name' => 'Aktham',
                'last_name' => 'Al-Najdi',
                'email' => 'AkthamAlNajdi@gmail.com',
                'department_id' => 1
            ],
            [
                'first_name' => 'Mohammed',
                'last_name' => 'Al-Taimi',
                'email' => 'MohammedAlTaimi@gmail.com',
                'department_id' => 1
            ],
            [
                'first_name' => 'Aslam',
                'last_name' => 'Al-Taie',
                'email' => 'AslamAlTaie@gmail.com',
                'department_id' => 1
            ],
            [
                'first_name' => 'Zuhair',
                'last_name' => 'Al-Taghlabi',
                'email' => 'ZuhairAlTaghlabi@gmail.com',
                'department_id' => 1
            ],
            [
                'first_name' => 'Salem',
                'last_name' => 'Al-Bakri',
                'email' => 'SalemAlBakri@gmail.com',
                'department_id' => 1
            ],
            [
                'first_name' => 'Jashm',
                'last_name' => 'Al-Qahtani',
                'email' => 'JashmAlQahtani@gmail.com',
                'department_id' => 1
            ],
            [
                'first_name' => 'Hisham',
                'last_name' => 'Al-Hijazi',
                'email' => 'HishamAlHijazi@gmail.com',
                'department_id' => 1
            ],
            [
                'first_name' => 'Hussein',
                'last_name' => 'Al-Najmi',
                'email' => 'HusseinAlNajmi@gmail.com',
                'department_id' => 1
            ],
            [
                'first_name' => 'Qais',
                'last_name' => 'Al-Kufi',
                'email' => 'QaisAlKufi@gmail.com',
                'department_id' => 1
            ],
            [
                'first_name' => 'Badr',
                'last_name' => 'Al-Baghdadi',
                'email' => 'BadrAlBaghdadi@gmail.com',
                'department_id' => 1
            ],

            // Multimedia
            [
                'first_name' => 'Ahmed',
                'last_name' => 'Al-Khateeb',
                'email' => 'AhmedAlKhateeb@gmail.com',
                'department_id' => 2
            ],
            [
                'first_name' => 'Khaled',
                'last_name' => 'Al-Jabari',
                'email' => 'KhaledAlJabari@gmail.com',
                'department_id' => 2
            ],
            [
                'first_name' => 'Tariq',
                'last_name' => 'Al-Muqbil',
                'email' => 'TariqAlMuqbil@gmail.com',
                'department_id' => 2
            ],
            [
                'first_name' => 'Samir',
                'last_name' => 'Al-Omari',
                'email' => 'SamirAlOmari@gmail.com',
                'department_id' => 2
            ],
            [
                'first_name' => 'Youssef',
                'last_name' => 'Al-Mahmoud',
                'email' => 'YoussefAlMahmoud@gmail.com',
                'department_id' => 2
            ],
            [
                'first_name' => 'Fawaz',
                'last_name' => 'Al-Salhi',
                'email' => 'FawazAlSalhi@gmail.com',
                'department_id' => 2
            ],
            [
                'first_name' => 'Jamal',
                'last_name' => 'Al-Rashidi',
                'email' => 'JamalAlRashidi@gmail.com',
                'department_id' => 2
            ],
            [
                'first_name' => 'Rami',
                'last_name' => 'Al-Khalil',
                'email' => 'RamiAlKhalil@gmail.com',
                'department_id' => 2
            ],
            [
                'first_name' => 'Mustafa',
                'last_name' => 'Al-Zahrani',
                'email' => 'MustafaAlZahrani@gmail.com',
                'department_id' => 2
            ],
            [
                'first_name' => 'Ziad',
                'last_name' => 'Al-Dosari',
                'email' => 'ZiadAlDosari@gmail.com',
                'department_id' => 2
            ],
            [
                'first_name' => 'Omar',
                'last_name' => 'Al-Qadi',
                'email' => 'OmarAlQadi@gmail.com',
                'department_id' => 2
            ],
            [
                'first_name' => 'Issam',
                'last_name' => 'Al-Hamidi',
                'email' => 'IssamAlHamidi@gmail.com',
                'department_id' => 2
            ],
            [
                'first_name' => 'Hasan',
                'last_name' => 'Al-Shehri',
                'email' => 'HasanAlShehri@gmail.com',
                'department_id' => 2
            ],
            [
                'first_name' => 'Nabil',
                'last_name' => 'Al-Saadi',
                'email' => 'NabilAlSaadi@gmail.com',
                'department_id' => 2
            ],
            [
                'first_name' => 'Faisal',
                'last_name' => 'Al-Haifi',
                'email' => 'FaisalAlHaifi@gmail.com',
                'department_id' => 2
            ],

            // Computer Science
            [
                'first_name' => 'Walid',
                'last_name' => 'Al-Rashid',
                'email' => 'WalidAlRashid@gmail.com',
                'department_id' => 3
            ],
            [
                'first_name' => 'Nasser',
                'last_name' => 'Al-Mansouri',
                'email' => 'NasserAlMansouri@gmail.com',
                'department_id' => 3
            ],
            [
                'first_name' => 'Tamer',
                'last_name' => 'Al-Dabbagh',
                'email' => 'TamerAlDabbagh@gmail.com',
                'department_id' => 3
            ],
            [
                'first_name' => 'Firas',
                'last_name' => 'Al-Mahfouz',
                'email' => 'FirasAlMahfouz@gmail.com',
                'department_id' => 3
            ],
            [
                'first_name' => 'Ahmad',
                'last_name' => 'Al-Farsi',
                'email' => 'AhmadAlFarsi@gmail.com',
                'department_id' => 3
            ],
            [
                'first_name' => 'Anwar',
                'last_name' => 'Al-Qurashi',
                'email' => 'AnwarAlQurashi@gmail.com',
                'department_id' => 3
            ],
            [
                'first_name' => 'Riyad',
                'last_name' => 'Al-Mazrouei',
                'email' => 'RiyadAlMazrouei@gmail.com',
                'department_id' => 3
            ],
            [
                'first_name' => 'Sulaiman',
                'last_name' => 'Al-Rubaie',
                'email' => 'SulaimanAlRubaie@gmail.com',
                'department_id' => 3
            ],
            [
                'first_name' => 'Baha',
                'last_name' => 'Al-Hamdan',
                'email' => 'BahaAlHamdan@gmail.com',
                'department_id' => 3
            ],
            [
                'first_name' => 'Nader',
                'last_name' => 'Al-Zoubi',
                'email' => 'NaderAlZoubi@gmail.com',
                'department_id' => 3
            ],
            [
                'first_name' => 'Ayman',
                'last_name' => 'Al-Khobar',
                'email' => 'AymanAlKhobar@gmail.com',
                'department_id' => 3
            ],
            [
                'first_name' => 'Bassam',
                'last_name' => 'Al-Turki',
                'email' => 'BassamAlTurki@gmail.com',
                'department_id' => 3
            ],
            [
                'first_name' => 'Ibrahim',
                'last_name' => 'Al-Saidi',
                'email' => 'IbrahimAlSaidi@gmail.com',
                'department_id' => 3
            ],
            [
                'first_name' => 'Samer',
                'last_name' => 'Al-Khattab',
                'email' => 'SamerAlKhattab@gmail.com',
                'department_id' => 3
            ],
            [
                'first_name' => 'Muneer',
                'last_name' => 'Al-Ajmi',
                'email' => 'MuneerAlAjmi@gmail.com',
                'department_id' => 3
            ],

            // Information Systems
            [
                'first_name' => 'Hani',
                'last_name' => 'Al-Khatab',
                'email' => 'HaniAlKhatab@gmail.com',
                'department_id' => 4
            ],
            [
                'first_name' => 'Salah',
                'last_name' => 'Al-Sayed',
                'email' => 'SalahAlSayed@gmail.com',
                'department_id' => 4
            ],
            [
                'first_name' => 'Fadi',
                'last_name' => 'Al-Hasani',
                'email' => 'FadiAlHasani@gmail.com',
                'department_id' => 4
            ],
            [
                'first_name' => 'Hamza',
                'last_name' => 'Al-Qarawi',
                'email' => 'HamzaAlQarawi@gmail.com',
                'department_id' => 4
            ],
            [
                'first_name' => 'Tarek',
                'last_name' => 'Al-Masri',
                'email' => 'TarekAlMasri@gmail.com',
                'department_id' => 4
            ],
            [
                'first_name' => 'Bilal',
                'last_name' => 'Al-Mubarak',
                'email' => 'BilalAlMubarak@gmail.com',
                'department_id' => 4
            ],
            [
                'first_name' => 'Zain',
                'last_name' => 'Al-Nasser',
                'email' => 'ZainAlNasser@gmail.com',
                'department_id' => 4
            ],
            [
                'first_name' => 'Kamel',
                'last_name' => 'Al-Shami',
                'email' => 'KamelAlShami@gmail.com',
                'department_id' => 4
            ],
            [
                'first_name' => 'Hossam',
                'last_name' => 'Al-Fayez',
                'email' => 'HossamAlFayez@gmail.com',
                'department_id' => 4
            ],
            [
                'first_name' => 'Riad',
                'last_name' => 'Al-Mazur',
                'email' => 'RiadAlMazur@gmail.com',
                'department_id' => 4
            ],
        ];
        foreach ($data as $userData) {
            // Create a user
            $userId = DB::table('users')->insertGetId([
                'email' => $userData['email'],
                'password' => bcrypt('12345678'), // Default password
                'status' => 1,
                'role_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Create student entry
            DB::table('students')->insert([
                'first_name' => $userData['first_name'],
                'last_name' => $userData['last_name'],
                'user_id' => $userId,
                'department_id' => $userData['department_id'],
                'phone' => 777777777,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
