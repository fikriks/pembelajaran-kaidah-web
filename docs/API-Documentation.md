# API Documentation - Aplikasi Pembelajaran Kaidah Bahasa Arab

## Overview

Aplikasi pembelajaran kaidah bahasa Arab dengan implementasi **Linear Congruent Method (LCM)** untuk pengacakan soal. API ini dirancang untuk mobile app Android dengan fokus pada penelitian algoritma LCM untuk skripsi.

**Base URL**: `http://localhost:8080/api`

## Authentication

API menggunakan **Simple Token Authentication** untuk siswa. Token di-generate saat login dan berlaku selama 24 jam.

### Format Token
```
Token: Base64Encode(user_id:timestamp)
Contoh: "MTI6MTY2NzY0MzIwMA==" (user_id:12, timestamp:1667643200)
```

### Headers
```
Authorization: Bearer {token}
Content-Type: application/json
```

## Response Format

### Success Response
```json
{
    "status": "success",
    "message": "Success message",
    "data": {
        // Response data
    }
}
```

### Error Response
```json
{
    "status": "error",
    "message": "Error message",
    "data": null
}
```

## API Endpoints

### 1. Authentication - Siswa

#### 1.1 Login Siswa
**Endpoint**: `POST /api/siswa/login`

**Request Body**:
```json
{
    "nis": "2021001",
    "password": "student123",
    "device_info": "Android 12 - Samsung Galaxy A52"
}
```

**Response (200)**:
```json
{
    "status": "success",
    "message": "Login berhasil",
    "data": {
        "siswa": {
            "id": 1,
            "nis": "2021001",
            "nama_lengkap": "Ahmad Rizki",
            "jenis_kelamin": "L",
            "kelas": "XII IPA 1",
            "status": "AKTIF"
        },
        "token": "MTI6MTY2NzY0MzIwMA==",
        "login_time": "2025-11-04 15:30:00"
    }
}
```

#### 1.2 Get Profile
**Endpoint**: `GET /api/siswa/profile`

**Response (200)**:
```json
{
    "status": "success",
    "message": "Profile berhasil diambil",
    "data": {
        "siswa": {
            "id": 1,
            "nis": "2021001",
            "nama_lengkap": "Ahmad Rizki",
            "jenis_kelamin": "L",
            "kelas": "XII IPA 1",
            "status": "AKTIF"
        },
        "login_history": [
            {
                "login_time": "2025-11-04 15:30:00",
                "device_info": "Android 12 - Samsung Galaxy A52",
                "ip_address": "192.168.1.100"
            }
        ]
    }
}
```

#### 1.3 Update Profile
**Endpoint**: `PUT /api/siswa/profile`

**Request Body**:
```json
{
    "nama_lengkap": "Ahmad Rizki Updated",
    "jenis_kelamin": "L",
    "kelas": "XII IPA 1"
}
```

#### 1.4 Logout
**Endpoint**: `POST /api/siswa/logout`

**Response (200)**:
```json
{
    "status": "success",
    "message": "Logout berhasil",
    "data": null
}
```

### 2. Materi Kaidah

#### 2.1 Get All Kaidah
**Endpoint**: `GET /api/kaidah`

**Query Parameters**:
- `search` (optional): Search keyword
- `difficulty` (optional): mudah, sedang, sulit
- `page` (optional): Page number (default: 1)
- `limit` (optional): Items per page (default: 20)

**Response (200)**:
```json
{
    "status": "success",
    "message": "Daftar kaidah berhasil diambil",
    "data": {
        "kaidah": [
            {
                "id_materi": 1,
                "judul_kaidah": "Isim Mufrad dan Jamak",
                "deskripsi": "Pengenalan isim mufrad dan jamak dalam bahasa Arab",
                "tingkat_kesulitan": "mudah",
                "urutan": 1,
                "total_soal": 25,
                "user_progress": {
                    "status": "selesai",
                    "total_sessions": 3,
                    "average_score": 85.5,
                    "best_score": 95.0,
                    "last_attempt": "2025-11-03 14:20:00"
                }
            }
        ],
        "pagination": {
            "current_page": 1,
            "per_page": 20,
            "total": 15,
            "total_pages": 1
        }
    }
}
```

#### 2.2 Get Detail Kaidah
**Endpoint**: `GET /api/kaidah/{id}`

**Response (200)**:
```json
{
    "status": "success",
    "message": "Detail kaidah berhasil diambil",
    "data": {
        "kaidah": {
            "id_materi": 1,
            "judul_kaidah": "Isim Mufrad dan Jamak",
            "deskripsi": "Pengenalan isim mufrad dan jamak",
            "penjelasan": "Isim mufrad adalah kata benda tunggal...",
            "contoh": "كتاب (kitab) - buku, كُتُب (kutub) - buku-buku",
            "tingkat_kesulitan": "mudah",
            "urutan": 1,
            "total_soal": 25
        },
        "user_progress": {
            "status": "selesai",
            "total_sessions": 3,
            "average_score": 85.5,
            "best_score": 95.0,
            "last_attempt": "2025-11-03 14:20:00"
        }
    }
}
```

#### 2.3 Get Progress Kaidah
**Endpoint**: `GET /api/kaidah/{id}/progress`

#### 2.4 Start Learning Kaidah
**Endpoint**: `POST /api/kaidah/{id}/start`

**Request Body**:
```json
{
    "jumlah_soal": 20
}
```

#### 2.5 Search Kaidah
**Endpoint**: `GET /api/kaidah/search?q={keyword}`

#### 2.6 Get Filters
**Endpoint**: `GET /api/kaidah/filters`

### 3. Sesi Pembelajaran

#### 3.1 Start New Session
**Endpoint**: `POST /api/sesi/start`

**Request Body**:
```json
{
    "kaidah_id": 1,
    "jumlah_soal": 20
}
```

**Response (201)**:
```json
{
    "status": "success",
    "message": "Sesi pembelajaran berhasil dimulai",
    "data": {
        "sesi": {
            "id_sesi": 123,
            "kaidah_id": 1,
            "jumlah_soal": 20,
            "seed_used": 1667643200123,
            "waktu_mulai": "2025-11-04 15:30:00"
        },
        "soal": [
            {
                "nomor": 1,
                "id_soal": 45,
                "pertanyaan": "Apa bentuk jamak dari كتاب?",
                "tipe_soal": "pilihan_ganda",
                "tingkat_kesulitan": "mudah",
                "poin": 10,
                "jawaban": [
                    {
                        "id_pilihan": 123,
                        "jawaban": "كُتُب",
                        "is_benar": true
                    },
                    {
                        "id_pilihan": 124,
                        "jawaban": "كَتَبَ",
                        "is_benar": false
                    }
                ]
            }
        ],
        "lcm_info": {
            "algorithm": "Linear Congruent Method",
            "parameters": {
                "a": 10,
                "c": 23,
                "m": 29
            },
            "seed": 1667643200123,
            "randomization_verified": true
        }
    }
}
```

#### 3.2 Get Active Session
**Endpoint**: `GET /api/sesi/active`

**Response (200)**:
```json
{
    "status": "success",
    "message": "Sesi aktif berhasil diambil",
    "data": {
        "sesi": {
            "id_sesi": 123,
            "id_siswa": 1,
            "id_materi": 1,
            "total_soal": 20,
            "soal_benar": 5,
            "skor": 50.00,
            "waktu_mulai": "2025-11-04 15:30:00"
        },
        "progress": {
            "total_soal": 20,
            "soal_dijawab": 8,
            "soal_benar": 5,
            "skor_saat_ini": 50.00,
            "persentase_selesai": 40.0
        },
        "soal": [
            {
                "nomor": 1,
                "id_soal": 45,
                "pertanyaan": "Apa bentuk jamak dari كتاب?",
                "is_answered": true,
                "user_answer": {
                    "id_pilihan": 123,
                    "is_benar": true,
                    "waktu_jawab": "2025-11-04 15:32:15"
                },
                "jawaban": [
                    {
                        "id_pilihan": 123,
                        "jawaban": "كُتُب",
                        "is_benar": true
                    }
                ]
            }
        ]
    }
}
```

#### 3.3 Get Session Detail
**Endpoint**: `GET /api/sesi/{id}`

#### 3.4 Continue Session
**Endpoint**: `POST /api/sesi/{id}/continue`

**Response (200)**:
```json
{
    "status": "success",
    "message": "Soal berikutnya berhasil diambil",
    "data": {
        "sesi_progress": {
            "total_soal": 20,
            "sudah_dijawab": 8,
            "sisa_soal": 12,
            "persentase": 40.0
        },
        "soal": {
            "nomor": 9,
            "id_soal": 53,
            "pertanyaan": "Apakah kata 'المَدْرَسُون' termasuk jamak mudzakkar?",
            "tipe_soal": "pilihan_ganda",
            "tingkat_kesulitan": "sedang",
            "poin": 15,
            "jawaban": [
                {
                    "id_pilihan": 201,
                    "jawaban": "iya"
                },
                {
                    "id_pilihan": 202,
                    "jawaban": "tidak"
                }
            ]
        }
    }
}
```

#### 3.5 Submit Answer
**Endpoint**: `POST /api/sesi/{id}/jawab`

**Request Body**:
```json
{
    "id_soal": 53,
    "id_pilihan": 201
}
```

**Response (200)**:
```json
{
    "status": "success",
    "message": "Jawaban berhasil disimpan",
    "data": {
        "is_benar": true,
        "poin_didapat": 15,
        "waktu_jawab": "2025-11-04 15:35:22",
        "feedback": "Jawaban benar!"
    }
}
```

#### 3.6 Finish Session
**Endpoint**: `POST /api/sesi/{id}/finish`

**Response (200)**:
```json
{
    "status": "success",
    "message": "Sesi pembelajaran selesai",
    "data": {
        "hasil": {
            "sesi_id": 123,
            "total_soal": 20,
            "soal_benar": 16,
            "soal_salah": 4,
            "skor_akhir": 85.00,
            "persentase_benar": 80.0,
            "durasi_menit": 12.5,
            "waktu_selesai": "2025-11-04 15:42:30"
        },
        "kaidah": {
            "id_materi": 1,
            "judul_kaidah": "Isim Mufrad dan Jamak"
        }
    }
}
```

#### 3.7 Get Session Results
**Endpoint**: `GET /api/sesi/{id}/hasil`

**Response (200)**:
```json
{
    "status": "success",
    "message": "Hasil sesi berhasil diambil",
    "data": {
        "sesi": {
            "id_sesi": 123,
            "id_siswa": 1,
            "id_materi": 1,
            "total_soal": 20,
            "soal_benar": 16,
            "skor": 85.00
        },
        "statistik": {
            "total_soal": 20,
            "soal_benar": 16,
            "soal_salah": 4,
            "skor_akhir": 85.00,
            "persentase_benar": 80.0,
            "durasi_menit": 12.5,
            "rata_rata_waktu_per_soal": "37.5 detik"
        },
        "detail_jawaban": [
            {
                "nomor_soal": 1,
                "pertanyaan": "Apa bentuk jamak dari كتاب?",
                "jawaban_siswa": "كُتُب",
                "is_benar": true,
                "waktu_jawab": "2025-11-04 15:32:15"
            }
        ]
    }
}
```

### 4. Progress Pembelajaran

#### 4.1 Get Overall Progress
**Endpoint**: `GET /api/progress`

**Response (200)**:
```json
{
    "status": "success",
    "message": "Progress berhasil diambil",
    "data": {
        "siswa": {
            "id": 1,
            "nama_lengkap": "Ahmad Rizki",
            "kelas": "XII IPA 1"
        },
        "overview": {
            "total_kaidah": 15,
            "kaidah_selesai": 8,
            "kaidah_sedang_belajar": 3,
            "kaidah_belum_dimulai": 4,
            "total_sesi": 25,
            "rata_rata_skor": 82.5,
            "total_soal_dijawab": 500,
            "total_jawaban_benar": 412,
            "persentase_benar_keseluruhan": 82.4,
            "persentase_kemajuan": 53.3
        },
        "kaidah_progress": [
            {
                "id_materi": 1,
                "judul_kaidah": "Isim Mufrad dan Jamak",
                "tingkat_kesulitan": "mudah",
                "status": "selesai",
                "total_attempts": 3,
                "best_score": 95.0,
                "average_score": 85.5,
                "last_attempt": "2025-11-03 14:20:00"
            }
        ],
        "weekly_activity": [
            {
                "date": "2025-11-04",
                "day_name": "Mon",
                "sessions": 2
            }
        ],
        "achievements": [
            {
                "id": "first_session",
                "title": "Pemula",
                "description": "Menyelesaikan sesi pembelajaran pertama",
                "icon": "🎯",
                "earned_at": "2025-10-28 10:15:00"
            },
            {
                "id": "high_scorer",
                "title": "Pecandu Skor Tinggi",
                "description": "Rata-rata skor 80+",
                "icon": "⭐",
                "earned_at": null
            }
        ]
    }
}
```

#### 4.2 Get Detailed Progress
**Endpoint**: `GET /api/progress/detail`

**Query Parameters**:
- `kaidah_id` (optional): Filter by kaidah ID
- `status` (optional): Filter by session status
- `date_from` (optional): Start date (YYYY-MM-DD)
- `date_to` (optional): End date (YYYY-MM-DD)
- `limit` (optional): Items per page (default: 10)
- `offset` (optional): Offset (default: 0)

#### 4.3 Get Learning History
**Endpoint**: `GET /api/progress/history`

**Query Parameters**:
- `period` (optional): week, month, year (default: month)
- `limit` (optional): Items limit (default: 30)

#### 4.4 Get Statistics
**Endpoint**: `GET /api/progress/statistics`

**Response (200)**:
```json
{
    "status": "success",
    "message": "Statistik progress berhasil diambil",
    "data": {
        "session_statistics": {
            "total_sessions": 25,
            "completed_sessions": 23,
            "cancelled_sessions": 2,
            "completion_rate": 92.0,
            "average_score": 82.5,
            "best_score": 98.0,
            "worst_score": 65.0,
            "total_questions_answered": 500,
            "total_correct_answers": 412,
            "overall_accuracy": 82.4,
            "average_duration_minutes": 8.5
        },
        "difficulty_breakdown": [
            {
                "difficulty": "mudah",
                "total_sessions": 12,
                "average_score": 88.5,
                "best_score": 98.0
            },
            {
                "difficulty": "sedang",
                "total_sessions": 10,
                "average_score": 78.5,
                "best_score": 92.0
            },
            {
                "difficulty": "sulit",
                "total_sessions": 3,
                "average_score": 72.0,
                "best_score": 85.0
            }
        ],
        "monthly_performance": [
            {
                "month": "2025-11",
                "month_name": "November 2025",
                "sessions": 8,
                "average_score": 85.2,
                "best_score": 95.0
            }
        ],
        "learning_streak": {
            "current_streak": 3,
            "longest_streak": 7
        }
    }
}
```

#### 4.5 Get Chart Data
**Endpoint**: `GET /api/progress/chart`

**Query Parameters**:
- `type` (optional): line, bar, radar (default: line)
- `period` (optional): week, month, year (default: month)

## Linear Congruent Method (LCM) Implementation

### Algorithm Parameters
Untuk penelitian skripsi, parameter LCM ditetapkan sebagai berikut:

```json
{
    "parameters": {
        "a": 10,        // Multiplier (pengali)
        "c": 23,        // Increment (penambah)
        "m": 29,        // Modulus
        "X0": "seed"   // Initial value (dari timestamp + user_id)
    }
}
```

### Formula
```
Xn+1 = (a × Xn + c) mod m
```

### Randomization Process
1. **Seed Generation**: `seed = (user_id * 1000000) + (kaidah_id * 10000) + timestamp + microtime`
2. **Question Order**: Generate random indices untuk mengacak urutan soal
3. **Answer Order**: Acak urutan pilihan jawaban untuk setiap soal
4. **Reproducible**: Dengan seed yang sama, hasil randomisasi akan sama

### LCM Information in API
Setiap sesi pembelajaran menyertakan informasi LCM:
- `seed_used`: Seed yang digunakan untuk randomisasi
- `lcm_info`: Parameter dan algoritma yang digunakan
- `randomization_verified`: Boolean yang menunjukkan randomization valid

## Error Codes

| Status Code | Description |
|-------------|-------------|
| 200 | Success |
| 201 | Created |
| 400 | Bad Request |
| 401 | Unauthorized |
| 403 | Forbidden |
| 404 | Not Found |
| 500 | Internal Server Error |

## Common Error Messages

### Authentication Errors
- `"Token diperlukan"` - Authorization header missing
- `"Token tidak valid"` - Token invalid or expired
- `"NIS atau password salah"` - Invalid credentials

### Session Errors
- `"Anda masih memiliki sesi pembelajaran yang aktif"` - Active session exists
- `"Sesi tidak ditemukan"` - Session not found
- `"Sesi sudah selesai atau dibatalkan"` - Session completed/cancelled
- `"Soal ini sudah dijawab"` - Question already answered

### Learning Errors
- `"Belum ada soal untuk kaidah ini"` - No questions available
- `"Semua soal sudah dijawab"` - All questions answered

## Rate Limiting

- **No explicit rate limiting** implemented (simple authentication)
- **Token expiration**: 24 hours
- **Session cleanup**: Automatic cleanup for sessions older than 30 minutes

## Testing

### Example Login Request
```bash
curl -X POST http://localhost:8080/api/siswa/login \
  -H "Content-Type: application/json" \
  -d '{
    "nis": "2021001",
    "password": "student123"
  }'
```

### Example Get Kaidah with Token
```bash
curl -X GET http://localhost:8080/api/kaidah \
  -H "Authorization: Bearer MTI6MTY2NzY0MzIwMA=="
```

### Example Start Session
```bash
curl -X POST http://localhost:8080/api/sesi/start \
  -H "Authorization: Bearer MTI6MTY2NzY0MzIwMA==" \
  -H "Content-Type: application/json" \
  -d '{
    "kaidah_id": 1,
    "jumlah_soal": 20
  }'
```

## Mobile Integration Notes

### Android Implementation
1. **Token Storage**: Save token in SharedPreferences
2. **Session Management**: Track active session locally
3. **Offline Support**: Cache kaidah materials for offline reading
4. **Progress Sync**: Sync progress when online

### Error Handling
1. **Network Errors**: Handle connectivity issues gracefully
2. **Token Expiry**: Re-authenticate when token expires
3. **Session Conflicts**: Handle multiple session scenarios
4. **Data Validation**: Validate API responses before using

### Security Considerations
1. **Token Storage**: Store tokens securely on device
2. **HTTPS**: Use HTTPS in production
3. **Input Validation**: Validate all user inputs
4. **Data Sanitization**: Sanitize API responses

## Sample Data for Testing

### Dummy Siswa Accounts
| NIS | Nama Lengkap | Kata Sandi | Kelas | Status |
|-----|--------------|------------|-------|--------|
| 2025001 | Ahmad Fauzi | 123456 | XI-A | Aktif |
| 2025002 | Siti Nurhaliza | 123456 | XI-A | Aktif |
| 2025003 | Muhammad Rizki | 123456 | XI-B | Aktif |
| 2025004 | Fatimah Az Zahra | 123456 | X-A | Aktif |
| 2025005 | Abdul Rahman | 123456 | X-B | Nonaktif |

**Catatan**: Reset kata sandi hanya bisa dilakukan melalui web admin, tidak melalui mobile app.

## Version History

- **v1.0** (2025-11-04): Initial API release with LCM integration
- Features: Authentication, Kaidah management, Sesi management, Progress tracking
- LCM Algorithm: Fixed parameters for thesis research
- Mobile-first design for Android integration

## Support

For API support and questions:
- Developer: Khozinnatul Ulum (20210810076)
- Project: Skripsi Teknik Informatika - Universitas Kuningan
- Focus: Linear Congruent Method (LCM) for Arabic Grammar Learning

---
**Last Updated**: 2025-11-04
**Version**: 1.0
**API Version**: v1