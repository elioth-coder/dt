rmdir /s /q dms
git clone https://github.com/elioth-coder/dms.git document_tracking
cd document_tracking
del .gitignore
rmdir /s /q .git
@REM if not exist upload mkdir upload
@REM type nul > upload\filename.txt
call composer install
call composer dump-autoload -o
del document_tracking.sql
pause