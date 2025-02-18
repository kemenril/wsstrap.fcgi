#!/home/username/somesite-python/bin/python

#This is a generic FastCGI server to WSGI app wrapper, in case you want to run 
# WSGI code on a system (like, say, Dreamhost) which doesn't have a proper 
# WSGI module loaded into the web server, but does support FCGI.
#
# Depending on your configuration and your level of access, you may need a 
# virtual environment with flup and maybe Flask or whatever other modules your
# WSGI code needs installed into it.  Make sure the first line of this script
# is then modified to execute the Python interpreter there, or the correct 
# system Python interpreter, in any other case.
#
# Create a directory in which to store WSGI apps outside of the web root, but
# accessible to the web server.  Install a copy of this in the script directory 
# for each WSGI app you want to run.  Alternately, if the server will follow 
# symlinks, store the authoritative copy in your WSGI library directory with the
# apps, and symlink it in once for each script you want to wrap. When this
# script is activated by the FCGI mechanism, it will run the WSGI script with 
# the corresponding name from your WSGI library directory.
#

import os, sys
import re
from flup.server.fcgi import WSGIServer

#We assume that the name of the FCGI script is the same as the name of the 
# WSGI script it's supposed to be running.  Just fetch the name, and use it.
script = os.path.basename(__file__)
script = re.sub('.fcgi$','',script)

#WSGI library directory, accessible to the script but ideally private with 
# respect to being browsed by the web server, containing the WSGI scripts
WSGIDir="/home/username/somesite-WSGI"

source = WSGIDir + "/" +  script + ".wsgi"

if not os.path.isfile(source):
    sys.exit("WSGI script does not exist: " + source)

#This imports the WSGI script, non-standard extension notwithstanding
import importlib.machinery, types
wsload = importlib.machinery.SourceFileLoader(script,source)
wsgimod = types.ModuleType(wsload.name)
wsload.exec_module(wsgimod)

# ... and this runs it.
application = wsgimod.app
WSGIServer(application).run()


