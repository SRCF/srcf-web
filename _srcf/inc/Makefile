#!/usr/bin/make
# -*- makefile -*-

all : header.ssi.html footer.ssi.html header-base.ssi.html footer-base.ssi.html

header.ssi.html : header.html make_ssi.py
	./make_ssi.py header > $@

header-base.ssi.html : header-base.html make_ssi.py
	./make_ssi.py header-base > $@

footer-base.ssi.html : footer-base.html make_ssi.py
	./make_ssi.py footer-base > $@

footer.ssi.html : footer.html make_ssi.py
	./make_ssi.py footer > $@

.PHONY : all
.DEFAULT_GOAL := all
