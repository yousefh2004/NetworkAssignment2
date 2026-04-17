# Network Assignment 2

This project is divided into two main parts:

1. **Part 1 – SNMP Monitoring Pages**
2. **Part 2 – Security using OpenSSL**

---

## Part 1 – SNMP Monitoring Pages

This part of the assignment uses PHP and SNMP functions to display information collected from the local SNMP agent.

### Implemented Pages

#### Page 1 – System Group
Displays the main system information using SNMP.

#### Page 2 – UDP Group
Displays:
- UDP statistics fields in a table
- UDP table entries in a table

#### Page 3 – SNMP Group
Displays SNMP group statistics using:
- `snmp2_get()`
- `snmp2_walk()`

### Technologies Used
- PHP
- SNMP extension for PHP
- HTML
- CSS

### Notes
- The IP used in the project is:
  `127.0.0.1`
- The SNMP community used is:
  `public`

---

## Part 2 – Security using OpenSSL

This part applies:

- **Confidentiality** using shared-key encryption
- **Message Integrity** using hashing

Two batch files are used:

- `enc.bat`
- `dec.bat`

---

## enc.bat

This file performs the following steps:

1. Generates a shared key of size **32 bytes**
2. Stores the key in a file named:

   `<yourname>key.hex`

3. Encrypts the input file using:
   - `aes-256-cbc`
   - `pbkdf2`

4. Produces the encrypted file:

   `<filename>.enc`

5. Computes SHA-256 hash for the original file and stores it in:

   `<filename>.sha256`

### Usage

```cmd
enc test.txt Yousef