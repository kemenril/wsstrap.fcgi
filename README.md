# wsstrap.fcgi
Bootstrap code to run WSGI apps on a FastCGI server

If you have a web server such as a shared web host, or some other similar situation where you can't change the configuration of the server, or just a system which can be made to support FastCGI but has no proper WSGI module, this may be for you.  I have a shared hosting setup with Dreamhost, and it appears to work ok there.

### What's in the repository

Just this:

   * The wrapper script, *wsstrap.fcgi*
   * A simple WSGI test app written against *Flask*

### Installation

You will need to have a Python 3 interpreter with the *flup* module installed.  You may also want other modules -- like *flask* for the example program, or whatever else you are using to write your WSGI -- but *flup* is required for the wrapper script to work.  You can either install this at the system level, if you've got the access and inclination, or create a virtual Python environment using one of the conventional means.  The latter process might be something like:

```
python3 -m virtualenv -p /usr/bin/python3 ~/somesite-python
. ~/somesite-python/bin/activate
pip install flask
pip install flup
```

Now create a WSGI library directory.  It should maybe be somewhere outside of the web root, so that the web server doesn't offer to show your code to people browsing the web, but it must be accessible to the server, so that the bootstrap script can load files out of it.  Add a WSGI app to the library directory.  You can use the simple example in this repository for testing.

Put a copy of *wsstrap.fcgi* into a directory under your web root, somewhere it can be executed by the web server.  Name it after your WSGI script.  If you've just put *test.wsgi* in the web root, call this file *test.fcgi*.  The bootstrap script will use its own name to figure out which WSGI app to run.

Edit the FCGI script.  Change the first line to run the Python interpreter from the virtual environment you just made, or the correct system interpreter for Python 3.  If you're using the virtual environment above, for example, the first line might look like *#!/home/username/somesite-python/bin/python*  You'll also need to change the value of WSGIDir to the location of your WSGI library directory.  Put a separate copy of this file in place for each WSGI app you want to use, or if your web server will follow symbolic links, you could put a single copy in the WSGI library directory with the WSGI scripts, and create symbolic links under the web root which point back.  Give the links the same names you would give the actual files.
