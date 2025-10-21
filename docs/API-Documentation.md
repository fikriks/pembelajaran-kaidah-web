# API Documentation - Aplikasi Pembelajaran Kaidah Bahasa Arab

## Base URL
```
Development: http://localhost:8080/api/v1
Production: https://yourdomain.com/api/v1
```

## Authentication
API menggunakan **Simple Token Authentication** untuk skripsi. Token dikirim di header:

```
Authorization: Bearer <token>
```

Token berlaku selama 30 hari.

## Response Format
Semua response menggunakan format JSON:

### Success Response
```json
{
    "status": "success",
    "message": "Success message",
    "data": { ... }
}
```

### Error Response
```json
{
    "status": "error",
    "message": "Error message",
    "errors": { ... } // optional, untuk validation errors
}
```

## API Endpoints

### 1. Authentication

#### Login Siswa
**POST** `/auth/login`

Login untuk siswa melalui aplikasi mobile.

**Request Body:**
```json
{
    "nama_pengguna": "siswa01",
    "kata_sandi": "password123"
}
```

**Response Success (200):**
```json
{
    "status": "success",
    "message": "Login berhasil",
    "data": {
        "user": {
            "id_pengguna": 5,
            "nama_pengguna": "siswa01",
            "email": "siswa01@example.com",
            "nama_lengkap": "Ahmad Rizki",
            "hak_akses": "siswa",
            "status": "aktif",
            "foto_profil": null
        },
        "token": "eyJ1c2VyX2lkIjo1LCJleHAiOjE3Mzg3NTY0MDAsImlhdCI6MTczNjE2NDQwMH0="
    }
}
```

**Error Response (401):**
```json
{
    "status": "error",
    "message": "Nama pengguna atau kata sandi salah"
}
```

#### Register Siswa Baru
**POST** `/auth/register`

Mendaftarkan siswa baru.

**Request Body:**
```json
{
    "nama_pengguna": "siswa02",
    "kata_sandi": "password123",
    "email": "siswa02@example.com",
    "nama_lengkap": "Fatimah Azzahra"
}
```

**Response Success (201):**
```json
{
    "status": "success",
    "message": "Pendaftaran berhasil",
    "data": {
        "user": {
            "id_pengguna": 6,
            "nama_pengguna": "siswa02",
            "email": "siswa02@example.com",
            "nama_lengkap": "Fatimah Azzahra",
            "hak_akses": "siswa",
            "status": "aktif",
            "foto_profil": null
        },
        "token": "eyJ1c2VyX2lkIjo2LCJleHAiOjE3Mzg3NTY0MDAsImlhdCI6MTczNjE2NDQwMH0="
    }
}
```

#### Get Profile
**GET** `/auth/profile`

Mendapatkan profile siswa yang sedang login.

**Headers:**
```
Authorization: Bearer <token>
```

**Response Success (200):**
```json
{
    "status": "success",
    "message": "Profile berhasil diambil",
    "data": {
        "id_pengguna": 5,
        "nama_pengguna": "siswa01",
        "email": "siswa01@example.com",
        "nama_lengkap": "Ahmad Rizki",
        "hak_akses": "siswa",
        "status": "aktif",
        "foto_profil": null,
        "waktu_dibuat": "2025-01-15 10:30:00",
        "waktu_diubah": "2025-01-20 14:25:00"
    }
}
```

#### Update Profile
**PUT** `/auth/profile`

Update profile siswa.

**Headers:**
```
Authorization: Bearer <token>
```

**Request Body:**
```json
{
    "email": "ahmad.rizki@example.com",
    "nama_lengkap": "Ahmad Rizki Abdullah"
}
```

#### Logout
**POST** `/auth/logout`

Logout dari aplikasi (clear token di client side).

**Headers:**
```
Authorization: Bearer <token>
```

### 2. Materi Kaidah

#### Get All Kaidah
**GET** `/kaidah`

Mendapatkan semua materi kaidah dengan progress siswa.

**Headers:**
```
Authorization: Bearer <token>
```

**Query Parameters (optional):**
- `status` = Filter by status (`belum_dimulai`, `sedang_belajar`, `selesai`)
- `tingkat_kesulitan` = Filter by difficulty (`mudah`, `sedang`, `sulit`)
- `search` = Search by judul kaidah
- `page` = Page number (default: 1)
- `limit` = Items per page (default: 20)

**Response Success (200):**
```json
{
    "status": "success",
    "message": "Daftar kaidah berhasil diambil",
    "data": {
        "kaidah": [
            {
                "id_materi": 1,
                "judul_kaidah": "إسم مفرد وجمع",
                "deskripsi": "Pengenalan isim mufrad dan jamak dalam bahasa Arab",
                "tingkat_kesulitan": "mudah",
                "urutan": 1,
                "user_progress": {
                    "status": "sedang_belajar",
                    "persentase": 65.5,
                    "waktu_akses_terakhir": "2025-01-20 15:30:00"
                },
                "total_soal": 15,
                "dibuat_oleh": {
                    "nama_lengkap": "Umar Abdul Aziz"
                }
            }
        ],
        "pagination": {
            "current_page": 1,
            "per_page": 20,
            "total": 5,
            "total_pages": 1
        }
    }
}
```

#### Get Detail Kaidah
**GET** `/kaidah/{id}`

Mendapatkan detail materi kaidah.

**Headers:**
```
Authorization: Bearer <token>
```

**Response Success (200):**
```json
{
    "status": "success",
    "message": "Detail kaidah berhasil diambil",
    "data": {
        "id_materi": 1,
        "judul_kaidah": "إسم مفرد وجمع",
        "deskripsi": "Pengenalan isim mufrad dan jamak dalam bahasa Arab",
        "penjelasan": "Isim mufrad adalah kata benda tunggal...",
        "contoh": "كتاب (kitab) - buku, كُتُب (kutub) - buku-buku",
        "tingkat_kesulitan": "mudah",
        "urutan": 1,
        "user_progress": {
            "status": "sedang_belajar",
            "persentase": 65.5,
            "waktu_akses_terakhir": "2025-01-20 15:30:00"
        },
        "total_soal": 15,
        "dibuat_oleh": {
            "id_pengguna": 2,
            "nama_lengkap": "Umar Abdul Aziz",
            "hak_akses": "guru"
        },
        "waktu_dibuat": "2025-01-10 09:00:00",
        "waktu_diubah": "2025-01-18 14:30:00"
    }
}
```

#### Get Progress Kaidah
**GET** `/kaidah/{id}/progress`

Mendapatkan progress belajar untuk kaidah tertentu.

**Headers:**
```
Authorization: Bearer <token>
```

**Response Success (200):**
```json
{
    "status": "success",
    "message": "Progress berhasil diambil",
    "data": {
        "status": "sedang_belajar",
        "persentase": 65.5,
        "waktu_akses_terakhir": "2025-01-20 15:30:00"
    }
}
```

#### Start Learning Kaidah
**POST** `/kaidah/{id}/start`

Memulai pembelajaran kaidah.

**Headers:**
```
Authorization: Bearer <token>
```

**Response Success (200):**
```json
{
    "status": "success",
    "message": "Pembelajaran dimulai",
    "data": {
        "riwayat_id": 12,
        "kaidah": {
            "id_materi": 1,
            "judul_kaidah": "إسم مفرد وجمع",
            "deskripsi": "Pengenalan isim mufrad dan jamak..."
        },
        "total_soal": 15
    }
}
```

#### Search Kaidah
**GET** `/kaidah/search`

Mencari kaidah berdasarkan keyword.

**Headers:**
```
Authorization: Bearer <token>
```

**Query Parameters:**
- `q` = Keyword pencarian (required)

**Response Success (200):**
```json
{
    "status": "success",
    "message": "Hasil pencarian berhasil diambil",
    "data": [
        {
            "id_materi": 1,
            "judul_kaidah": "إسم مفرد وجمع",
            "deskripsi": "Pengenalan isim mufrad dan jamak...",
            "user_progress": {
                "status": "sedang_belajar",
                "persentase": 65.5
            }
        }
    ]
}
```

#### Get Filter Options
**GET** `/kaidah/filters`

Mendapatkan opsi filter untuk kaidah.

**Headers:**
```
Authorization: Bearer <token>
```

**Response Success (200):**
```json
{
    "status": "success",
    "message": "Filter options berhasil diambil",
    "data": {
        "tingkat_kesulitan": [
            {"value": "mudah", "label": "Mudah"},
            {"value": "sedang", "label": "Sedang"},
            {"value": "sulit", "label": "Sulit"}
        ],
        "status": [
            {"value": "belum_dimulai", "label": "Belum Dimulai"},
            {"value": "sedang_belajar", "label": "Sedang Belajar"},
            {"value": "selesai", "label": "Selesai"}
        ]
    }
}
```

### 3. Sesi Pembelajaran

#### Start New Session
**POST** `/sesi/start`

Memulai sesi pembelajaran baru dengan soal-soal yang diacak menggunakan LCM.

**Headers:**
```
Authorization: Bearer <token>
```

**Request Body:**
```json
{
    "kaidah_id": 1,
    "jumlah_soal": 10
}
```

**Response Success (200):**
```json
{
    "status": "success",
    "message": "Sesi pembelajaran berhasil dimulai",
    "data": {
        "sesi_id": 25,
        "kaidah_id": 1,
        "total_soal": 10,
        "total_poin": 100,
        "waktu_mulai": "2025-01-20 16:00:00",
        "seed_used": 1738080000123,
        "soal": [
            {
                "id_soal": 45,
                "nomor": 1,
                "pertanyaan": "أي مما يلي هو جمع من كلمة 'كتاب'؟",
                "tipe_soal": "pilihan_ganda",
                "poin": 10,
                "tingkat_kesulitan": "mudah",
                "jawaban": [
                    {
                        "id_pilihan": 123,
                        "teks_jawaban": "كُتُب",
                        "urutan": 1
                    },
                    {
                        "id_pilihan": 124,
                        "teks_jawaban": "كَتَبَ",
                        "urutan": 2
                    },
                    {
                        "id_pilihan": 125,
                        "teks_jawaban": "مَكْتَبَة",
                        "urutan": 3
                    },
                    {
                        "id_pilihan": 126,
                        "teks_jawaban": "كَاتِب",
                        "urutan": 4
                    }
                ]
            }
        ]
    }
}
```

#### Get Active Sessions
**GET** `/sesi/active`

Mendapatkan sesi pembelajaran yang sedang aktif.

**Headers:**
```
Authorization: Bearer <token>
```

**Response Success (200):**
```json
{
    "status": "success",
    "message": "Sesi aktif berhasil diambil",
    "data": [
        {
            "id_sesi": 25,
            "id_materi": 1,
            "judul_kaidah": "إسم مفرد وجمع",
            "total_soal": 10,
            "soal_benar": 0,
            "skor": 0,
            "waktu_mulai": "2025-01-20 16:00:00",
            "status": "sedang_berjalan"
        }
    ]
}
```

#### Get Session Detail
**GET** `/sesi/{id}`

Mendapatkan detail sesi pembelajaran.

**Headers:**
```
Authorization: Bearer <token>
```

**Response Success (200):**
```json
{
    "status": "success",
    "message": "Detail sesi berhasil diambil",
    "data": {
        "sesi": {
            "id_sesi": 25,
            "id_materi": 1,
            "total_soal": 10,
            "soal_benar": 7,
            "skor": 70.0,
            "waktu_mulai": "2025-01-20 16:00:00",
            "waktu_selesai": null,
            "status": "sedang_berjalan"
        },
        "answers": [
            {
                "id_detail": 123,
                "id_soal": 45,
                "id_pilihan": 123,
                "urutan_soal": 1,
                "is_benar": true,
                "waktu_jawab": "2025-01-20 16:02:30"
            }
        ],
        "stats": {
            "total_dijawab": 7,
            "total_benar": 5,
            "total_salah": 2,
            "persentase_benar": 71.43
        }
    }
}
```

#### Continue Session
**POST** `/sesi/{id}/continue`

Melanjutkan sesi pembelajaran yang sudah ada.

**Headers:**
```
Authorization: Bearer <token>
```

**Response Success (200):**
```json
{
    "status": "success",
    "message": "Sesi berhasil dilanjutkan",
    "data": {
        "sesi_id": 25,
        "kaidah_id": 1,
        "total_soal": 10,
        "waktu_mulai": "2025-01-20 16:00:00",
        "seed_used": 1738080000123,
        "soal": [...],
        "answered_questions": [45, 46, 47]
    }
}
```

#### Submit Answer
**POST** `/sesi/{id}/jawab`

Mengirim jawaban untuk soal tertentu.

**Headers:**
```
Authorization: Bearer <token>
```

**Request Body:**
```json
{
    "soal_id": 45,
    "pilihan_id": 123,
    "urutan_soal": 1
}
```

**Response Success (200):**
```json
{
    "status": "success",
    "message": "Jawaban berhasil disimpan",
    "data": {
        "is_correct": true,
        "message": "Jawaban benar!"
    }
}
```

#### Finish Session
**POST** `/sesi/{id}/finish`

Menyelesaikan sesi pembelajaran dan menghitung hasil akhir.

**Headers:**
```
Authorization: Bearer <token>
```

**Response Success (200):**
```json
{
    "status": "success",
    "message": "Sesi pembelajaran berhasil diselesaikan",
    "data": {
        "sesi_id": 25,
        "total_soal": 10,
        "soal_benar": 8,
        "skor": 80.0,
        "waktu_mulai": "2025-01-20 16:00:00",
        "waktu_selesai": "2025-01-20 16:15:30"
    }
}
```

#### Get Session Results
**GET** `/sesi/{id}/hasil`

Mendapatkan hasil lengkap sesi pembelajaran.

**Headers:**
```
Authorization: Bearer <token>
```

**Response Success (200):**
```json
{
    "status": "success",
    "message": "Hasil sesi berhasil diambil",
    "data": {
        "sesi_id": 25,
        "total_soal": 10,
        "soal_benar": 8,
        "skor": 80.0,
        "waktu_mulai": "2025-01-20 16:00:00",
        "waktu_selesai": "2025-01-20 16:15:30",
        "durasi_detik": 930,
        "detail_jawaban": [
            {
                "id_detail": 123,
                "id_soal": 45,
                "pertanyaan": "أي مما يلي هو جمع من كلمة 'كتاب'؟",
                "jawaban_dipilih": "كُتُب",
                "is_benar": true,
                "urutan_soal": 1,
                "waktu_jawab": "2025-01-20 16:02:30"
            }
        ]
    }
}
```

### 4. Progress & Statistics

#### Get Overall Progress
**GET** `/progress`

Mendapatkan progress pembelajaran keseluruhan siswa.

**Headers:**
```
Authorization: Bearer <token>
```

**Response Success (200):**
```json
{
    "status": "success",
    "message": "Progress berhasil diambil",
    "data": {
        "overview": {
            "total_kaidah": 5,
            "kaidah_selesai": 2,
            "kaidah_sedang_belajar": 2,
            "kaidah_belum_dimulai": 1,
            "persentase_kelulusan": 40.0,
            "rata_rata_skor": 75.5,
            "total_sesi_selesai": 12
        },
        "recent_activity": [
            {
                "id_sesi": 25,
                "judul_kaidah": "إسم مفرد وجمع",
                "soal_benar": 8,
                "skor": 80.0,
                "waktu_selesai": "2025-01-20 16:15:30"
            }
        ],
        "learning_streak": {
            "current_streak": 3,
            "longest_streak": 7,
            "last_activity_date": "2025-01-20"
        }
    }
}
```

#### Get Detailed Progress
**GET** `/progress/detail`

Mendapatkan detail progress per kaidah.

**Headers:**
```
Authorization: Bearer <token>
```

**Query Parameters (optional):**
- `status` = Filter by status
- `tingkat_kesulitan` = Filter by difficulty
- `page` = Page number
- `limit` = Items per page

#### Get Learning History
**GET** `/progress/history`

Mendapatkan riwayat pembelajaran siswa.

**Headers:**
```
Authorization: Bearer <token>
```

**Query Parameters (optional):**
- `page` = Page number (default: 1)
- `limit` = Items per page (default: 20)

**Response Success (200):**
```json
{
    "status": "success",
    "message": "History pembelajaran berhasil diambil",
    "data": {
        "data": [
            {
                "id_sesi": 25,
                "judul_kaidah": "إسم مفرد وجمع",
                "total_soal": 10,
                "soal_benar": 8,
                "skor": 80.0,
                "waktu_mulai": "2025-01-20 16:00:00",
                "waktu_selesai": "2025-01-20 16:15:30",
                "durasi_detik": 930,
                "status": "selesai"
            }
        ],
        "pagination": {
            "current_page": 1,
            "per_page": 20,
            "total": 12,
            "total_pages": 1
        }
    }
}
```

#### Get Statistics
**GET** `/progress/statistics`

Mendapatkan statistik pembelajaran lengkap.

**Headers:**
```
Authorization: Bearer <token>
```

**Response Success (200):**
```json
{
    "status": "success",
    "message": "Statistik pembelajaran berhasil diambil",
    "data": {
        "overall_stats": {
            "total_kaidah_disimpan": 5,
            "kaidah_selesai": 2,
            "rata_rata_skor": 75.5
        },
        "monthly_progress": [
            {
                "month": "January 2025",
                "sessions_completed": 8,
                "average_score": 75.5
            }
        ],
        "difficulty_distribution": {
            "mudah": {"sessions": 6, "avg_score": 85.2},
            "sedang": {"sessions": 4, "avg_score": 72.5},
            "sulit": {"sessions": 2, "avg_score": 65.0}
        },
        "best_performing_kaidah": [
            {
                "id_materi": 1,
                "judul_kaidah": "إسم مفرد وجمع",
                "tingkat_kesulitan": "mudah",
                "average_score": 85.0,
                "total_sessions": 3
            }
        ],
        "struggling_kaidah": [
            {
                "id_materi": 3,
                "judul_kaidah": "الفعل المضارع",
                "tingkat_kesulitan": "sulit",
                "average_score": 60.0,
                "total_sessions": 2
            }
        ]
    }
}
```

#### Get Chart Data
**GET** `/progress/chart`

Mendapatkan data untuk chart progress.

**Headers:**
```
Authorization: Bearer <token>
```

**Query Parameters:**
- `type` = Chart type (`weekly`, `monthly`, `yearly`)

**Response Success (200):**
```json
{
    "status": "success",
    "message": "Data chart berhasil diambil",
    "data": [
        {
            "day": "Mon",
            "date": "2025-01-20",
            "sessions": 2
        },
        {
            "day": "Tue",
            "date": "2025-01-21",
            "sessions": 1
        }
    ]
}
```

## Error Codes

| Status Code | Description |
|-------------|-------------|
| 200 | Success |
| 201 | Created |
| 400 | Bad Request |
| 401 | Unauthorized |
| 403 | Forbidden |
| 404 | Not Found |
| 422 | Validation Error |
| 500 | Internal Server Error |

## Common Error Messages

### Authentication Errors
- `Token tidak valid` - Token invalid or expired
- `Nama pengguna atau kata sandi salah` - Invalid credentials
- `Hanya siswa yang bisa login melalui aplikasi mobile` - Only students can login via mobile

### Validation Errors
- `Validasi gagal` - Validation failed
- `Keyword pencarian harus diisi` - Search keyword required
- `Kaidah tidak ditemukan` - Kaidah not found

### Session Errors
- `Anda tidak bisa memulai sesi baru. Sesi sebelumnya masih berjalan` - Cannot start new session, previous session still active
- `Sesi tidak ditemukan atau sudah selesai` - Session not found or already finished
- `Sesi belum selesai` - Session not yet finished

## LCM Algorithm Integration

Sesi pembelajaran menggunakan algoritma Linear Congruent Method (LCM) untuk mengacak soal:

### Parameters (Hardcoded)
- `a` (multiplier) = 10
- `c` (increment) = 23
- `m` (modulus) = 29
- `seed` = generated dari `(user_id × 1000) + (timestamp % 10000) + (kaidah_id × 100)`

### Process Flow
1. Client request start session dengan `kaidah_id`
2. Server generate unique seed berdasarkan user dan timestamp
3. Server ambil semua soal untuk kaidah tersebut
4. Gunakan LCM untuk generate random indices
5. Acak soal berdasarkan indices yang dihasilkan
6. Untuk setiap soal, acak juga urutan jawaban dengan LCM
7. Return soal yang sudah diacak beserta seed yang digunakan

### Reproducibility
Dengan seed yang sama, urutan acak akan identik. Ini berguna untuk debugging dan tracking.

## Mobile App Integration Example

### Android (Java) Example

```java
// Login
public void login(String username, String password) {
    JSONObject requestBody = new JSONObject();
    try {
        requestBody.put("nama_pengguna", username);
        requestBody.put("kata_sandi", password);
    } catch (JSONException e) {
        e.printStackTrace();
    }

    apiClient.post("/auth/login", requestBody, new ApiCallback() {
        @Override
        public void onSuccess(JSONObject response) {
            String token = response.getJSONObject("data").getString("token");
            // Save token to SharedPreferences
            saveToken(token);
        }

        @Override
        public void onError(String error) {
            // Handle error
        }
    });
}

// Start Learning Session
public void startSession(int kaidahId, int jumlahSoal) {
    JSONObject requestBody = new JSONObject();
    try {
        requestBody.put("kaidah_id", kaidahId);
        requestBody.put("jumlah_soal", jumlahSoal);
    } catch (JSONException e) {
        e.printStackTrace();
    }

    apiClient.post("/sesi/start", requestBody, new ApiCallback() {
        @Override
        public void onSuccess(JSONObject response) {
            // Parse questions and display to user
            JSONArray soalArray = response.getJSONObject("data").getJSONArray("soal");
            displayQuestions(soalArray);
        }

        @Override
        public void onError(String error) {
            // Handle error
        }
    });
}

// Submit Answer
public void submitAnswer(int sesiId, int soalId, int pilihanId, int urutanSoal) {
    JSONObject requestBody = new JSONObject();
    try {
        requestBody.put("soal_id", soalId);
        requestBody.put("pilihan_id", pilihanId);
        requestBody.put("urutan_soal", urutanSoal);
    } catch (JSONException e) {
        e.printStackTrace();
    }

    apiClient.post("/sesi/" + sesiId + "/jawab", requestBody, new ApiCallback() {
        @Override
        public void onSuccess(JSONObject response) {
            boolean isCorrect = response.getJSONObject("data").getBoolean("is_correct");
            // Show feedback to user
            showAnswerFeedback(isCorrect);
        }

        @Override
        public void onError(String error) {
            // Handle error
        }
    });
}
```

## Rate Limiting

Untuk mencegah abuse, API memiliki rate limiting:
- **Auth endpoints**: 5 requests per minute
- **Other endpoints**: 60 requests per minute

Jika rate limit terlampaui, response akan return status 429:
```json
{
    "status": "error",
    "message": "Too many requests. Please try again later."
}
```

## Testing

### Demo Credentials
Untuk testing, gunakan credentials berikut:

**Siswa:**
- Username: `siswa01`
- Password: `password123`

### cURL Examples

```bash
# Login
curl -X POST http://localhost:8080/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"nama_pengguna":"siswa01","kata_sandi":"password123"}'

# Get Kaidah List
curl -X GET http://localhost:8080/api/kaidah \
  -H "Authorization: Bearer <token>"

# Start Session
curl -X POST http://localhost:8080/api/sesi/start \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer <token>" \
  -d '{"kaidah_id":1,"jumlah_soal":10}'
```

---

## Version History

| Version | Date | Changes |
|---------|------|---------|
| 1.0.0 | 2025-01-20 | Initial API release for thesis project |

---

*Documentation generated for Arabic Grammar Learning App - Thesis Project*