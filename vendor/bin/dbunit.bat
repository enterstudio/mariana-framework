@ECHO OFF
SET BIN_TARGET=%~dp0/../phpunit/dbunit/dbunit
php "%BIN_TARGET%" %*
