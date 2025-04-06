#!/bin/bash

FORCE=false
if [ "$1" == "--force" ]; then
  FORCE=true
fi

if [ -d "ManualMultiPay" ]; then
  if [ "$FORCE" == true ]; then
    rm -r ManualMultiPay
  else
    echo "Directory ManualMultiPay already exists. Use --force to overwrite."
    exit 1
  fi
fi

if [ -f "ManualMultiPay.zip" ]; then
  if [ "$FORCE" == true ]; then
    rm ManualMultiPay.zip
  else
    echo "File ManualMultiPay.zip already exists. Use --force to overwrite."
    exit 1
  fi
fi

mkdir ManualMultiPay
cp -r assets ManualMultiPay
cp -r resources ManualMultiPay
cp ManualMultiPay.php ManualMultiPay
cp routes.php ManualMultiPay
cp ManualMultiPay.png ManualMultiPay
cp README.md ManualMultiPay
cp LICENSE ManualMultiPay
zip -r ManualMultiPay.zip ManualMultiPay
rm -r ManualMultiPay
echo "Archive created successfully."
