#!/bin/sh
rm -f ~/.ssh/id_rsa && ssh-keygen -m PEM -t rsa -b 4096 -N '' -f ~/.ssh/id_rsa
echo "\n\n Your id_rsa.pub key is: \n"
cat ~/.ssh/id_rsa.pub
echo "\n"
