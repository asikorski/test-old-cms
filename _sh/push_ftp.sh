#!/bin/bash
# Skrypt automatycznego wysyłania plików na serwer tzn. push przy pomocy protokołu ftp
# Do działania wymaga zainstalowania pakietu ncFTP
# @Author: Arnold Sikorski
# -------------------------------------------------------------------------
# -------------------------------------------------------------------------

FTP="/usr/bin/ncftpput"
CMD=""
AUTHFILE="/root/.myupload"
USER="millenium1z"
PASSWORD="12Studio1Millenium3"
HOST="ftp.millenium1.nazwa.pl"

REMOTEDIR="/janlesniak.pl/production"
LOCALDIR="/home/arnold/www/lesniak.localhost/"

  echo "Push rekursywny na serwer (ftp) $HOST (bez pakowania)"
  echo "Projektu:  $LOCALDIR"
  echo "Do katalogu zdalnego: $REMOTEDIR"
  echo "*** To terminate at any point hit [ CTRL + C ] ***"
read -p "Czy chcesz wykonać push (y - yes) : " ISYES

if [ $ISYES = 'y' ] ; then
  CMD="$FTP -m -R -u $USER -p $PASSWORD $HOST $REMOTEDIR $LOCALDIR"
  $CMD
  echo "Wykonano"
else
  echo "Anulowano"

fi

