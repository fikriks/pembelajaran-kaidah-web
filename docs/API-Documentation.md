# API Documentation - Siswa Authentication

## Base URL
```
Development: http://localhost:8080/api
Production: https://yourdomain.com/api
```

## Authentication
API ini menggunakan **Simple Token Authentication** untuk kebutuhan skripsi.

## CORS
API mendukung CORS untuk cross-origin requests dari mobile app.

## Endpoints

### 1. Login Siswa
**POST** `/api/siswa/login`

Authentication endpoint untuk siswa yang login melalui mobile app.

#### Request
```json
{
  "nis": "2025001",
  "password": "123456",
  "device_info": "Android 11 - Samsung Galaxy A51"
}
```

#### Request Parameters
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| nis | string | Yes | Nomor Induk Siswa |
| password | string | Yes | Password siswa |
| device_info | string | No | Informasi device/HP (optional) |

#### Response Success (200)
```json
{
  "status": "success",
  "message": "Login berhasil",
  "data": {
    "siswa": {
      "id": 1,
      "nis": "2025001",
      "nama_lengkap": "Ahmad Fauzi",
      "jenis_kelamin": "L",
      "kelas": "XI-A",
      "status": "aktif"
    },
    "token": "MTo6MjAyNTAxMDE6MTY5OTEwMjY3Mjo0NzI4YzY5NjE1ZmI4MDU4MmY4ZTlkNjI2ZjUzNjRkMzFmNjQ0Zg==",
    "login_time": "2025-11-04 12:12:00"
  }
}
```

#### Response Error (400/401/403)
```json
{
  "status": "error",
  "message": "NIS atau password salah",
  "data": null
}
```

#### Error Codes
| Code | Description |
|------|-------------|
| 400 | Invalid JSON format atau parameter tidak lengkap |
| 401 | NIS atau password salah |
| 403 | Akun siswa tidak aktif |

### 2. Get Profile Siswa
**GET** `/api/siswa/profile`

Mendapatkan profile siswa yang sedang login.

#### Request Headers
```
Authorization: Bearer <token>
Content-Type: application/json
```

#### Response Success (200)
```json
{
  "status": "success",
  "message": "Profile berhasil diambil",
  "data": {
    "siswa": {
      "id": 1,
      "nis": "2025001",
      "nama_lengkap": "Ahmad Fauzi",
      "jenis_kelamin": "L",
      "kelas": "XI-A",
      "status": "aktif",
      "created_at": "2025-11-04 12:00:00"
    }
  }
}
```

#### Response Error (401/404)
```json
{
  "status": "error",
  "message": "Token tidak valid",
  "data": null
}
```

## Token Authentication

### Token Format
Token menggunakan **Base64 encoding** dengan format:
```
base64_encode(id:nis:timestamp:hash)
```

Contoh token:
```
MTo6MjAyNTAxMDE6MTY5OTEwMjY3Mjo0NzI4YzY5NjE1ZmI4MDU4MmY4ZTlkNjI2ZjUzNjRkMzFmNjQ0Zg==
```

### Token Usage
1. Login response mengembalikan token
2. Simpan token di mobile app (SharedPreferences)
3. Sertakan token di header untuk request yang membutuhkan authentication
4. Token berlaku selama 24 jam

### Header Format
```
Authorization: Bearer <token>
```

## Login History

Setiap kali siswa login berhasil, sistem akan mencatat:
- NIS siswa
- Login time (timestamp)
- Device info (jika dikirim)
- IP address
- Created at

Data ini bisa dilihat di web admin pada menu **Manajemen Siswa → Login History**.

## Error Handling

### Standard Error Response Format
```json
{
  "status": "error",
  "message": "Error description",
  "data": null
}
```

### Common Error Messages
- `"Invalid JSON format"` - Request body bukan JSON valid
- `"NIS dan kata sandi wajib diisi"` - Parameter kurang
- `"NIS atau kata sandi salah"` - Authentication gagal
- `"Akun siswa tidak aktif"` - Siswa nonaktif
- `"Token tidak ditemukan"` - Authorization header kosong
- `"Token tidak valid"` - Token expired atau invalid
- `"Data siswa tidak ditemukan"` - Siswa tidak ada di database

## Testing dengan Postman/curl

### Login Test
```bash
curl -X POST http://localhost:8080/api/siswa/login \
  -H "Content-Type: application/json" \
  -d '{
    "nis": "2025001",
    "password": "123456",
    "device_info": "Test Device"
  }'
```

### Profile Test (dengan token dari login)
```bash
curl -X GET http://localhost:8080/api/siswa/profile \
  -H "Authorization: Bearer MTo6MjAyNTAxMDE6MTY5OTEwMjY3Mjo0NzI4YzY5NjE1ZmI4MDU4MmY4ZTlkNjI2ZjUzNjRkMzFmNjQ0Zg=="
```

## Sample Data untuk Testing

### Dummy Siswa Accounts
| NIS | Nama Lengkap | Kata Sandi | Kelas | Status |
|-----|--------------|------------|-------|--------|
| 2025001 | Ahmad Fauzi | 123456 | XI-A | Aktif |
| 2025002 | Siti Nurhaliza | 123456 | XI-A | Aktif |
| 2025003 | Muhammad Rizki | 123456 | XI-B | Aktif |
| 2025004 | Fatimah Az Zahra | 123456 | X-A | Aktif |
| 2025005 | Abdul Rahman | 123456 | X-B | Nonaktif |

**Catatan**: Reset kata sandi hanya bisa dilakukan melalui web admin, tidak melalui mobile app.

## Implementation Notes

### Security (Basic untuk Skripsi)
1. **Password Hashing**: Menggunakan `password_hash()` dengan `PASSWORD_DEFAULT`
2. **Simple Token**: Base64 encoding dengan SHA256 hash (basic approach untuk skripsi)
3. **CORS Support**: Enabled untuk mobile app development
4. **Input Validation**: Basic validation untuk required fields

### Production Considerations
Untuk production, pertimbangkan:
- JWT tokens dengan expiry yang lebih pendek
- Rate limiting untuk login attempts
- HTTPS mandatory
- Refresh token mechanism
- More sophisticated validation

### Mobile App Integration
1. Simpan token di SharedPreferences (Android)
2. Kirim device info untuk tracking
3. Handle token expiry (re-login)
4. Parse JSON response dengan proper error handling

## Flow Diagram

```
Mobile App                 API Server                Database
    |                         |                         |
    |-- POST /api/siswa/login ---------------------->|
    |                         |                         |
    |<-- Response with token -----------------------|
    |                         |                         |
    |-- GET /api/siswa/profile (with token) -------->|
    |                         |                         |
    |<-- Profile data -----------------------------|
```

## Quick Start

1. **Login**: POST ke `/api/siswa/login` dengan NIS dan password
2. **Save Token**: Simpan token dari response login
3. **Access Profile**: GET ke `/api/siswa/profile` dengan Authorization header
4. **Use Token**: Sertakan token di semua authenticated requests

## Support

Untuk pertanyaan atau issues, hubungi development team.

---
**Last Updated**: 2025-11-04
**Version**: 1.0
**API Version**: v1