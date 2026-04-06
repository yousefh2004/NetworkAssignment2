@echo off
REM ============================================================
REM  enc.bat  --  Encryption Batch File
REM  Usage:  enc <filename> <yourname>
REM  Example: enc test.txt Ali
REM ============================================================

REM --- Validate arguments ---
IF "%~1"=="" (
    echo Usage: enc ^<filename^> ^<yourname^>
    echo Example: enc test.txt Ali
    exit /b 1
)
IF "%~2"=="" (
    echo Usage: enc ^<filename^> ^<yourname^>
    echo Example: enc test.txt Ali
    exit /b 1
)

SET FILENAME=%~1
SET YOURNAME=%~2

SET KEYFILE=%YOURNAME%key.hex
SET ENCFILE=%FILENAME%.enc
SET HASHFILE=%FILENAME%.sha256

echo ============================================================
echo  SNMP Assignment 2 -- Part 2: Encryption
echo  File    : %FILENAME%
echo  Name    : %YOURNAME%
echo ============================================================
echo.

REM --- Step 1: Check source file exists ---
IF NOT EXIST "%FILENAME%" (
    echo [ERROR] File not found: %FILENAME%
    exit /b 1
)

REM --- Step 2: Generate 32-byte shared key (hex) ---
echo [Step 1] Generating 32-byte shared key...
openssl rand -hex 32 > "%KEYFILE%"
IF ERRORLEVEL 1 (
    echo [ERROR] Key generation failed. Is OpenSSL installed and in PATH?
    exit /b 1
)
echo          Key saved to: %KEYFILE%
echo          Key value:
type "%KEYFILE%"
echo.

REM --- Step 3: Encrypt the file with AES-256-CBC using PBKDF2 ---
echo [Step 2] Encrypting %FILENAME% using AES-256-CBC + PBKDF2...
REM Read the key from the hex file and pass as password
SET /P HEXKEY=<"%KEYFILE%"
openssl enc -aes-256-cbc -pbkdf2 -in "%FILENAME%" -out "%ENCFILE%" -pass pass:%HEXKEY%
IF ERRORLEVEL 1 (
    echo [ERROR] Encryption failed.
    exit /b 1
)
echo          Encrypted file saved to: %ENCFILE%
echo.

REM --- Step 4: Compute SHA-256 hash of the ORIGINAL file ---
echo [Step 3] Computing SHA-256 hash of original file...
openssl dgst -sha256 -binary "%FILENAME%" > "%HASHFILE%"
IF ERRORLEVEL 1 (
    echo [ERROR] Hash computation failed.
    exit /b 1
)
echo          Hash saved to: %HASHFILE%
echo          Hash (hex):
openssl dgst -sha256 "%FILENAME%"
echo.

echo ============================================================
echo  Encryption Complete!
echo  Key file  : %KEYFILE%
echo  Encrypted : %ENCFILE%
echo  Hash file : %HASHFILE%
echo ============================================================
