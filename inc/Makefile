#!/usr/bin/make
# -*- makefile -*-

all : motd.txt counts.html ubuntuversion.html vms.html

motd.txt : /etc/info/news.txt make_motd_txt.py
	./make_motd_txt.py > $@

counts.html : make_counts.py .FORCE
	./make_counts.py > $@

ubuntuversion.html : /etc/lsb-release make_ubuntuversion.sh
	./make_ubuntuversion.sh > $@
	touch --reference=$< $@

vms.html : make_vm_list.py .FORCE
	./make_vm_list.py > $@

.FORCE :

.PHONY : all .FORCE
.DEFAULT_GOAL := all
