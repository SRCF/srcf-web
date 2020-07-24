#!/usr/bin/make
# -*- makefile -*-

all : header.ssi.html footer.ssi.html

header.ssi.html : header.html make_ssi.py
	./make_ssi.py header > $@

footer.ssi.html : footer.html make_ssi.py
	./make_ssi.py footer > $@

.PHONY : all
.DEFAULT_GOAL := all
