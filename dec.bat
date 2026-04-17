set filename=%1
set username=%2

set keyfile=%username%key.hex

openssl enc -d -aes-256-cbc -pbkdf2 -in "%filename%.enc" -out "%filename%.dec" -pass file:"%keyfile%"

fc /B "%filename%" "%filename%.dec"

openssl dgst -sha256 -binary -out "%filename%.dec.sha256" "%filename%.dec"

fc /B "%filename%.sha256" "%filename%.dec.sha256"