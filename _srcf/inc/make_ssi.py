#!/usr/bin/python3

import sys


DOMAIN_WEB = ('<!--#if expr="${domain_web}" -->'
              '<!--#echo var="domain_web" -->'
              '<!--#endif -->')

DOMAIN_CONTROL = ('<!--#if expr="${domain_control}" -->'
                 '<!--#echo var="domain_control" -->'
                 '<!--#endif -->')

TITLE = ('<!--#if expr="${title}" -->'
         '<!--#echo var="title" -->'
         ' - '
         '<!--#endif -->'
         'Student-Run Computing Facility (SRCF)')

EXTRAHEAD = ('<!--#if expr="${extrahead}" -->'
             '<!--#echo encoding="none" var="extrahead" -->'
             '<!--#endif -->')


def main():

    if len(sys.argv) < 2 or sys.argv[1] not in ("header", "footer","header-base", "footer-base"):
        exit("Usage: {} {{header,footer}}".format(sys.argv[0]))

    sys.stdout.write('<!--#include virtual="vars.html" -->\n')
    sys.stdout.write(open("{}.html".format(sys.argv[1])).read()
            .replace("{{ DOMAIN_WEB }}",     DOMAIN_WEB)
            .replace("{{ DOMAIN_CONTROL }}", DOMAIN_CONTROL)
            .replace("{{ TITLE }}",          TITLE)
            .replace("{{ EXTRAHEAD }}",      EXTRAHEAD))


if __name__ == "__main__":
    main()
