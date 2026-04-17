set filename=%1
set username=%2

set keyfile=%username%key.hex

openssl rand -hex -out "%keyfile%" 32

openssl enc -e -aes-256-cbc -pbkdf2 -in "%filename%" -out "%filename%.enc" -pass file:"%keyfile%"

openssl dgst -sha256 -binary -out "%filename%.sha256" "%filename%"