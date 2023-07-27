#!/usr/bin/python3

import re
import sys

number = re.compile(r"^[0-9]+\)")
link = re.compile(r"\b((https?|ftp|gopher|irc):\/\/[^\s()<>{}\[\]\"']+)")

news = list(open("/etc/info/news.txt"))

for start, line in reversed(list(enumerate(news))):
    if number.match(line):
        break
else:
    raise ValueError("Couldn't find news item")

motd = news[start:]

for line in motd:
    sys.stdout.write(link.sub(r'<a href="\1">\1</a>', line))

sys.stdout.write("\n")
sys.stdout.write(open("/etc/info/donors.txt").read())
