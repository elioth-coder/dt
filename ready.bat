rmdir /s /q document_tracking
git clone https://github.com/elioth-coder/document_tracking.git
cd document_tracking
del .gitignore
rmdir /s /q .git
@REM if not exist upload mkdir upload
@REM type nul > upload\filename.txt
call composer install
call composer dump-autoload -o
del document_tracking.sql
pause