#!/usr/bin/python

"""
decrypt the backups and remove the encrypted versions
"""

import os, sys, shutil

root="/var/www"
dirs=("/chess", "/lib/php", "/lib/js")

passphrase=sys.stdin.readline().strip()

for folder in dirs:
	for path, dirs, files in os.walk(root+folder):
		for fn in files:
			fullpath=path+"/"+fn

			if fn.endswith(".gpg"):
				os.system("gpg --yes --passphrase \""+passphrase+"\" -o "+path+"/"+fn[:-len(".gpg")]+" -d "+fullpath)
				os.remove(fullpath)