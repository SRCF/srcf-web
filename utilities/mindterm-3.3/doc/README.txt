             README file for MindTerm binary distribution

What is MindTerm
================
  
  MindTerm is an SSH client written in 100% java which supports both
  version 1.5 and 2.0 of the SSH protocol as well as a number of
  extensions thereof. It can be used as a standalone program or be run
  as an applet, and it works with any JRE implementing Java 1.1 or
  later. For more details see the section 'MindTerm features' below.

  MindTermLite is a light-weight version of MindTerm primarily
  intended for devices with small screens. It has a much more limited
  GUI and the terminal emulator is optional.

  MindTerm does include third party software (which is available under
  a BSD-type license). For more details see the file
  THIRD_PARTY_SW.txt

  For more info on MindTerm and related issues visit
  http://www.appgate.com/mindterm


Included files
==============

  The files included in this zip-file are as follows:

  mindterm.jar          Full binary
  LICENSE.txt           Current license
  THIRD_PARTY_SW.txt    Listing of third party softwares and their licenses
  README.txt            This file...
  RELEASE_NOTES.txt     Release notes
  Applet.txt            Document describing how to run MindTerm as an applet
  Settings.txt          Description of the possible settings


How to run MindTerm
===================

  With a modern java it is possible to launch MindTerm with:
    java -jar mindterm.jar

  With older versions of java it is necessary to specify the main
  class: java -cp mindterm.jar com.mindbright.application.MindTerm

  It is also possible to launch MindTerm as an applet. It can be run
  in a separate frame or embedded in a webpage. It is worth noting
  that the MindTerm application always runs on the users client
  machine. This means that any SSH connections will originate from
  this machine. For details on how to set things up see Applet.txt


Differences vs the commercial version
=====================================

  The main difference between the free version and the commercial is
  what the licenses allows. The only difference in the source code is
  the license statements. 

  Another difference is that the commercial version includes signed
  jar and cab-files. These are needed to get full functionality when
  running MindTerm as an applet. The following functions normally
  requires that the applet be signed:
    * Connecting to a server different from the one hosting the applet
    * Reading/writing files on the local system
    * Opening local listeners

  These limitations do not apply when MindTerm is run as a local
  application. Also nothing prevents anybody from signing the MindTerm
  applet themselves, apart form the fact that it is nontrivial to
  do:-)


Support
=======

  Support for the free version of MindTerm is only available via
  mailing-lists. There are two mailing lists for MindTerm. The first
  one is mindterm-announce which is a list where we post announcements
  of new versions. There is no discussion on this list.

  The second list is mindterm-users where people may ask questions and
  discuss MindTerm. We at AppGate do follow this list but we do not
  promise to answer all questions.

  For more information about the lists see
  http://www.appgate.com/mindterm


MindTerm features
=================
  * 100% Java based
  * Support for SSHv1.5 & SSHv2 protocols
  * Support for tunnels and port forwards
  * X11 forwarding
  * Active tunnel display
  * Integrated, full-featured terminal emulator:
    - Full clipboard support (edit, copy, paste)
    - Send text file support
    - Save to text file support-
    - Terminal types: xterm, linux, scoansi, att6386,sun, aixterm,
      vt220, vt100, ansi, vt52, xtermcolor, linux-lat, at386, vt320,
      vt102 and tandem6530
    - Terminal colors
    - Fonts and font sizes
    - Copy-select via mouse selection
    - xterm mouse support
  * Ability to connect through HTTP & SOCKS proxies
  * Support for keep-alive packets
  * Integrated ftp proxy which allows the user to connect with a
    normal ftp client through the SSH tunnel to an ftp server
  * Integrated ftp to sftp proxy which allows the user to connect
    with a normal ftp client to an sftp enabled SSH-2 server
  * Zlib compression (including zlib@openssh.com)
  * Strict host-key checking
  * Supported Ciphers: AES (128, 192, 256), Blowfish, Twofish, Cast,
    3DES, Arcfour (modes cbc, ctr and, for arcfour, ecb)
  * Key exchange support: Diffie-Hellman group-exchange protocol &
    Diffie-Hellman group1-sha1
  * Ability to generate key pairs for DSA & RSA
  * Supported macs: hmac-md5, hmac-sha1, hmac-sha1-96, hmac-md5-96,
    hmac-ripemd160
  * MindTerm supports password authentication, Keyboard-interactive,
    SecurID token cards, public key authentication and certificates. 
