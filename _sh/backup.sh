#!/bin/bash
# Skrypt automatecznego tworzenia kopii zapasowych w folderze backup
# @Author: Arnold Sikorski
# -------------------------------------------------------------------------
# -------------------------------------------------------------------------

DIRECTORY='lesniak.localhost'
DATE=`date +%F`
DATETIME=`date +%s`
FILE="backup_$DATETIME-$DATE.7z"

  echo "Generowanie backupow"
  echo "*** To terminate at any point hit [ CTRL + C ] ***"
  read -p "Czy chcesz wykonać push (y - yes) : " ISYES

if [ $ISYES = 'y' ] ; then

if [ -e ../../$DIRECTORY/_backup/$FILE ]
then
  echo 'plik istnieje'
echo $FILE

else
tar cpvfP ../_backup/$FILE ../../$DIRECTORY/
echo 'Backup complette'
fi
else
  echo "Anulowano"

fi

