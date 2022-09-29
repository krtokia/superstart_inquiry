import requests
import sys
import time

def pyrender(url, consoleLog):
    API_URL = "https://api.zyte.com/v1/extract"
    API_KEY = "3a42d50ce15845b78f5a8166cf10a55d"
    data = None
    html = None
    while True:
        response = requests.post(API_URL, auth=(API_KEY, ''), json={
        "url": url,
        "browserHtml": True
        })
        data = response.json()
        try:
            html = data['browserHtml']
            break
        except:
            consoleLog.error("ERROR in "+url+" | Try again")
            pass
    return html

def pyrender2(url, consoleLog):
    API_URL = "https://api.zyte.com/v1/extract"
    API_KEY = "3a42d50ce15845b78f5a8166cf10a55d"
    data = None
    html = None
    while True:
        response = requests.post(API_URL, auth=(API_KEY, ''), json={
        "url": url,
        "browserHtml": True
        })
        data = response.json()
        html = data
        break
        # try:
        #     html = data['browserHtml']
        #     break
        # except:
        #     consoleLog.error("ERROR in "+url+" | Try again")
        #     pass
    return html
