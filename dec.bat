@echo off
REM ============================================================
REM  dec.bat  --  Decryption Batch File
REM  Usage:  dec <filename> <yourname>
REM  Example: dec test.txt Ali
REM  Note:  Expects <filename>.enc and <yourname>key.hex to exist
REM ============================================================

REM --- Validate arguments ---
IF "%~1"=="" (
    echo Usage: dec ^<filename^> ^<yourname^>
    echo Example: dec test.txt Ali
    exit /b 1
)
IF "%~2"=="" (
    echo Usage: dec ^<filename^> ^<yourname^>
    echo Example: dec test.txt Ali
    exit /b 1
)

SET FILENAME=%~1
SET YOURNAME=%~2

SET KEYFILE=%YOURNAME%key.hex
SET ENCFILE=%FILENAME%.enc
SET DECFILE=%FILENAME%.dec
SET HASHFILE=%FILENAME%.sha256
SET DECHASHFILE=%FILENAME%.dec.sha256

echo ============================================================
echo  SNMP Assignment 2 -- Part 2: Decryption
echo  File    : %FILENAME%
echo  Name    : %YOURNAME%
echo ============================================================
echo.

REM --- Check required files exist ---
IF NOT EXIST "%ENCFILE%" (
    echo [ERROR] Encrypted file not found: %ENCFILE%
    echo         Run enc.bat first.
    exit /b 1
)
IF NOT EXIST "%KEYFILE%" (
    echo [ERROR] Key file not found: %KEYFILE%
    echo         Run enc.bat first to generate the key.
    exit /b 1
)

REM --- Step 1: Decrypt the file ---
echo [Step 1] Decrypting %ENCFILE% using key from %KEYFILE%...
SET /P HEXKEY=<"%KEYFILE%"
openssl enc -aes-256-cbc -pbkdf2 -d -in "%ENCFILE%" -out "%DECFILE%" -pass pass:%HEXKEY%
IF ERRORLEVEL 1 (
    echo [ERROR] Decryption failed. Key may not match.
    exit /b 1
)
echo          Decrypted file saved to: %DECFILE%
echo.

REM --- Step 2: Compare original file with decrypted file (binary compare) ---
echo [Step 2] Comparing original file with decrypted file (binary)...
IF EXIST "%FILENAME%" (
    FC.exe /B "%FILENAME%" "%DECFILE%"
    IF ERRORLEVEL 1 (
        echo [RESULT] Files are DIFFERENT! Decryption may have an issue.
    ) ELSE (
        echo [RESULT] Files are IDENTICAL. Decryption successful!
    )
) ELSE (
    echo [WARN] Original file %FILENAME% not found, skipping file comparison.
)
echo.

REM --- Step 3: Compute hash of the decrypted file ---
echo [Step 3] Computing SHA-256 hash of decrypted file...
openssl dgst -sha256 -binary "%DECFILE%" > "%DECHASHFILE%"
IF ERRORLEVEL 1 (
    echo [ERROR] Hash computation failed.
    exit /b 1
)
echo          Decrypted hash saved to: %DECHASHFILE%
echo          Decrypted hash (hex):
openssl dgst -sha256 "%DECFILE%"
echo.

REM --- Step 4: Compare original hash with decrypted hash ---
echo [Step 4] Comparing original hash vs decrypted hash...
IF EXIST "%HASHFILE%" (
    FC.exe /B "%HASHFILE%" "%DECHASHFILE%"
    IF ERRORLEVEL 1 (
        echo [RESULT] Hashes are DIFFERENT! File integrity check FAILED.
    ) ELSE (
        echo [RESULT] Hashes MATCH. File integrity VERIFIED!
    )
) ELSE (
    echo [WARN] Original hash file %HASHFILE% not found.
    echo        Run enc.bat first to generate it.
)
echo.

echo ============================================================
echo  Decryption Complete!
echo  Decrypted file  : %DECFILE%
echo  Decrypted hash  : %DECHASHFILE%
echo ============================================================
