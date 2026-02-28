@echo off
echo Starting CashFly Chat Server...
echo Current directory: %cd%
echo.
node server.js
echo.
echo Server stopped. Press any key to exit...
pause > nul