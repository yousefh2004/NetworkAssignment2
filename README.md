# SNMP Manager — Assignment 2
**Computer Networks 2 (10636455) · An-Najah National University · Spring 2026**

---

## Part 1 — PHP SNMP Manager

### Files
| File | Description |
|------|-------------|
| `index.php` | Main landing page with navigation to all pages |
| `page1.php` | System Group — view & edit sysContact, sysName, sysLocation |
| `page2.php` | UDP Group — statistics counters + UDP listener table |
| `page3.php` | SNMP Group — dual method: snmp2_get() loop + snmp2_walk() |
| `shared_header.php` | Shared layout, navigation, CSS, helper functions |

### Requirements
- PHP 7.4+ with the `php-snmp` extension enabled
- An SNMP agent running on the target PC (default: 127.0.0.1)
- For Page 1 writes: SNMP community with **read-write** access (default community: `private`)

### Setup

**1. Enable SNMP agent on Windows:**
- Services → "SNMP Service" → Start
- Properties → Security: add community "public" (read-only) and "private" (read-write)

**2. Enable PHP SNMP extension:**
In `php.ini`, uncomment:
```
extension=snmp
```

**3. Configure IP/Community:**
Edit `shared_header.php`:
```php
$SNMP_IP           = "127.0.0.1";     // Change to your SNMP agent IP
$SNMP_COMMUNITY_RO = "public";        // Read-only community
$SNMP_COMMUNITY_RW = "private";       // Read-write community
```

**4. Run with PHP built-in server:**
```
cd snmp_manager
php -S localhost:8080
```
Then open http://localhost:8080

---

## Part 2 — OpenSSL Batch Files

### Files
| File | Description |
|------|-------------|
| `enc.bat` | Encrypts a file using AES-256-CBC + generates SHA-256 hash |
| `dec.bat` | Decrypts the file and verifies integrity |

### Prerequisites
- OpenSSL installed and added to Windows PATH
- Download from: http://slproweb.com/products/Win32OpenSSL.html

### Usage

**Encrypt:**
```
enc test.txt Ali
```
Produces:
- `Alikey.hex` — 32-byte hex shared key
- `test.txt.enc` — AES-256-CBC encrypted file
- `test.txt.sha256` — binary SHA-256 hash of original

**Decrypt:**
```
dec test.txt Ali
```
Produces:
- `test.txt.dec` — decrypted file
- `test.txt.dec.sha256` — SHA-256 hash of decrypted file
- Binary file comparison (original vs decrypted)
- Binary hash comparison (original hash vs decrypted hash)

### What enc.bat does (step by step)
1. `openssl rand -hex 32` → generates shared key, saves to `<name>key.hex`
2. `openssl enc -aes-256-cbc -pbkdf2` → encrypts file using that key
3. `openssl dgst -sha256 -binary` → computes hash of original file

### What dec.bat does (step by step)
1. `openssl enc -aes-256-cbc -pbkdf2 -d` → decrypts using saved key
2. `FC.exe /B` → binary comparison: original vs decrypted
3. `openssl dgst -sha256 -binary` → hash of decrypted file
4. `FC.exe /B` → binary comparison: original hash vs decrypted hash
