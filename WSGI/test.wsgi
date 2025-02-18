from flask import Flask

app = Flask(__name__)

@app.route('/')
def tf():
    return 'This is a test.'

